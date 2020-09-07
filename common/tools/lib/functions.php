<?php

/**
 * FileSystem */
class FS {

	/**
	 * Recursively create a directory with the same permissions as its parent folders.
	 * @param $path */
	public static function makeDir($path) {
		$permissions = 0777;
		$path2 = $path;
		do {
			if (file_exists($path2)) {
				$permissions = fileperms($path2);
				break;
			}
		} while (strlen($path2 = dirname($path2)) > 1);

		if (!is_dir($path))
			mkdir($path, $permissions, true);
	}
}


/**
 * Print caching headers to tell the client if it should use it's cached copy of a file or redownload it.
 * If $serverModifiedTime is before any modified since headers, then add the 304 response status code.
 * @param int $serverModifiedTime Unix timestamp.
 * @param string $serverETag  A hash string of the content.  Optional.
 * @return bool True if the server's content does not match the client's.*/
function modifiedSince($serverModifiedTime, $serverETag=null) {
	// Calculate response headers
	$clientModified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : 0;
	$clientETag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : '';

	// Tell proxy servers, etc. not to cache our response, so we can handle caching ourselves.
	header('Cache-Control: private');

	header('Last-Modified: '.gmdate("D, d M Y H:i:s", $serverModifiedTime).' GMT');
	if ($serverETag !== null)
		header("Etag: $serverETag");

	// Send 304 not modified if nothing has changed.
	$same = ($clientModified >= $serverModifiedTime) || ($serverETag !== null && ($clientETag == $serverETag));
	if ($same)
		http_response_code(304);
	return !$same;
}

