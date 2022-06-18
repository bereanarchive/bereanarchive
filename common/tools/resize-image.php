<?php
/**
 * @param string $_GEt['url']
 * @param ?int   $_GET['w']
 * @param int    $_GET['h']
 * @param string $_GET['size']    Optionally combine w and h into one parameter instead.  e.g. ?s=320x240
 * @param bool   $_GET['upscale'] Allow increasing the resolution.
 * @param string $_GET['mode']    stretch, cover, or contain.  Default is contain.
 * @param string $_GET['format']  jpg or png
 * @param string $_GET['fail']    Can be 'original', 'placeholder', 'error', or an http status code.  Defaults to fail.
 * @param string $_GET['allowCache']  Can be 1 or 0, defaults to 1.
 * The resulting image will not be upscaled unless the mode is explicitly set to stretch.
 *
 * If the resize script runs out of memory, the original image will be printed, and the HTTP status code 203 will be used (instead of 200).
 *
 * TODO: A lot of the resize code works for common use cases but calculates incorrect sizes otherwise.
 * Probably needs to be rewritten from scratch.
 *
 * TODO: Despite what articles online say, two scripts both resizing at the same time seem to use the same php memory limit.
 * This can make a small image fail to resize if requested at the same time as a large image.
 *
 * @example # Add something like this to .htaccess to map all images with url parameters to this file to be resized:
 * RewriteCond %{QUERY_STRING} (^|&)w=|(^|&)h=|(^|&)s=|(^|&)format=
 * ReWriteRule ^(.*)\.(jpg|jpeg|png|gif|webp)$ /common/tools/resize-image.php?url=$1.$2 [NC,QSA]
 */

class Options {
	/**
	 * Cached versions of the resized images will be stored in this folder.  Relative to $_SERVER['DOCUMENT_ROOT']
	 * Set to empty string to disable caching. */
	static string $cacheFolder = 'common/temp';
	//static string $cacheFolder = '';

	/** @var int Delete images from the $cacheFolder if they haven't been read in this amount of time: */
	static int $cacheExpire = 3600 * 24 * 30;

	/** @var int Run the cache cleanup once out of this many requests. */
	static int $cacheCleanupOdds = 3;

	static int $cacheTouch = 3600;

	/** Don't rezie images with a dimension larger than this.  Slightly reduce potential damage from DDOS.  */
	static int $maxSize = 16384;
}

class FailMode {
	const Original = 'Original';
	const Placeholder = 'Placeholder';
	const Error = 'Error';
}

class Params {

	public string $url;
	public string $mode;
	public string $format;
	public ?int $width;
	public ?int $height;
	public bool $upscale = false;
	public bool $allowCache = true;
	public string $fail = FailMode::Error;

	// Not params but come from params:
	public string $path;
	public string $ext;
	public string $outputType; // will be jpeg or png

	function __construct($maxSize=16384) {
		$get = $_GET;

		// If called directly, e.g. resize-image.php?url=...
		if (str_contains($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF']))
			$this->url = $get['url'];
		else { // If called from htaccess redirect.
			// This is necessary b/c htaccess screws up the encoding of some chars, like turning %23 into #, which breaks the whole url.
			$this->url = $_SERVER['REQUEST_URI'];
			parse_str(parse_url($this->url)['query'], $get);
		}

		// Path and url
		$this->url = self::securePath($this->url);
		$q = strpos($this->url, '?');
		if ($q === false)
			$q = strlen($this->url);
		$this->path = urldecode(substr($this->url, 0, $q));

		// Output type
		$this->ext = pathinfo($this->path, PATHINFO_EXTENSION);
		$this->format = $get['format'] ?? 'jpeg';
		if (isset($get['format']))
			$this->outputType = $get['format']=='png' ? 'png' : 'jpeg';
		else
			$this->outputType = ($this->ext=='jpg' || $this->ext=='jpeg' || $this->ext=='webp') ? 'jpeg' : 'png'; // convert webp to jpeg because most browsers don't support webp.


		// Dimensions and mode
		$this->mode = isset($get['mode']) ? strtolower($get['mode']) : 'contain';
		if (isset($get['s']))
			[$this->width, $this->height] = explode('x', $get['s']);
		else if (isset($get['w']) || isset($get['h'])) {
			$this->width = isset($get['w']) ? min(intval($get['w']), $maxSize) : null;
			$this->height = isset($get['h']) ? min(intval($get['h']), $maxSize) : null;
		} else
			$this->width = $this->height = null; // width and height will be determined from the image.

		$this->upscale = in_array(strtolower($get['upscale'] ?? 'false'), ['', '1', 'true']);

		// Fail mode
		$this->fail = ucfirst(strtolower($_GET['fail'] ?? $this->fail));

		$this->allowCache = !in_array(strtolower($_GET['allowCache'] ?? ''), ['0', 'false', 'no']);
	}

	function validate() {
		if (!in_array($this->mode, ['stretch', 'cover', 'contain']))
			die('mode not supported'); // reduce the chance of infinite unique queries as a DOS attack.
		if (!file_exists($this->path)) {
			http_response_code(404);
			die('File does not exist: ' . $this->path);
		}

		if (!in_array($this->fail, [FailMode::Original, FailMode::Placeholder, FailMode::Error]))
			die('fail mode ' . $this->fail . ' not supported.');
	}


	function getHash($modified) {
		$filename = preg_replace('#[^A-Z0-9_-]#i', '_', pathinfo($this->path)['filename']);
		$size = $this->width && $this->height ? $this->width.'x'.$this->height : ($this->width ?: ($this->height ?: ''));

		// md5 gives us a hex value.  base64 lets us encode the same hash with less characters.
		$hash = base64_encode(hex2bin(substr(md5($this->path.$modified.$this->width.$this->height.$this->mode.$this->outputType), 0, 6)));

		return $filename.'_' . $size . '_' . $hash;
	}

	function getContentTypeHeader() {
		return  'Content-type: image/' . $this->outputType;
	}

	// copied from Path.php
	private static function securePath(string $path) : string {
		$path2 = str_replace(['../', '..\\', '\\',  ':', ';'], '/', $path);
		$path2 = trim(trim($path2, '\\'), '/');
		$path2 = preg_replace('#/+#', '/', $path2);  // reduce multiple consecutive / characters.
		if (str_ends_with($path, '/'))
			$path2 .= '/';

		return $path2;
	}
}

class ImageUtil {

	/**
	 * Given dimensions originalWidth and originalHeight, this function adjusts them to fit
	 * inside a box of size targetWidth by targetHeight, preserving aspect ratio.
	 * @param $originalWidth int
	 * @param $originalHeight int
	 * @param $targetWidth int
	 * @param $targetHeight int
	 * @param $crop boolean, resize to crop or to fit.
	 * @param $allowUpscale boolean If false and both the target width and height are greater, then don't upscale more than one dimension.
	 * @return array($width, $height) */
	static function calcSize(int $originalWidth, int $originalHeight, int $targetWidth, int $targetHeight, $crop=false, $allowUpscale=true): array {

		if (!$allowUpscale && $targetWidth > $originalWidth)
			$targetWidth = $originalWidth;
		if (!$allowUpscale && $targetHeight > $originalHeight)
			$targetHeight = $originalHeight;


		$aspect = $originalWidth/$originalHeight;
		$targetAspect = $targetWidth/$targetHeight;

		if (($crop && $targetAspect !== $aspect)) {

			$newWidth = $targetHeight*$aspect;
			$newHeight = $targetHeight;
		}
		else {
			if ($aspect > $targetAspect) {
				$newWidth = $targetWidth;
				$newHeight = $targetWidth/$aspect;
			} else {
				$newWidth = $targetHeight*$aspect;
				$newHeight = $targetHeight;
			}
		}

		return [$newWidth, $newHeight];
	}

	/**
	 * Dragons and bugs be here.
	 * @param ?int $targetHeight
	 * @param ?int $targetWidth
	 * @param int $originalWidth
	 * @param int $originalHeight
	 * @param string $mode
	 * @return array */
	static function calculateSize2(int $originalWidth, int $originalHeight, ?int $targetHeight, ?int $targetWidth, string $mode, bool $allowUpscale): array {
		// Calculate size
		if ($targetHeight === null) // determine height from width
			$targetHeight = round(($targetWidth / $originalWidth) * $originalHeight);
		elseif ($targetWidth === null) // or vice versa
			$targetWidth = round(($targetHeight / $originalHeight) * $originalWidth);

		$targetWidth = max($targetWidth, 1);
		$targetHeight = max($targetHeight, 1);

		if (!$allowUpscale) {
			if ($targetWidth > $originalWidth)
				$targetWidth = $originalWidth;
			if ($targetHeight > $originalHeight)
				$targetHeight = $originalHeight;
		}

		if ($mode == 'cover')
			[$newWidth, $newHeight] = ImageUtil::calcSize($originalWidth, $originalHeight, $targetWidth, $targetHeight, true, $allowUpscale);
		elseif ($mode == 'contain')
			[$newWidth, $newHeight] = ImageUtil::calcSize($originalWidth, $originalHeight, $targetWidth, $targetHeight, false, $allowUpscale);
		else { // stretch
			$newWidth = $targetWidth;
			$newHeight = $targetHeight;
		}
		return [$targetHeight, $targetWidth, round($newWidth), round($newHeight)];
	}

	static function cleanup() {
		$now = time();
		foreach (scandir(Options::$cacheFolder) as $file) {
			if ($file === '.' || $file==='..')
				continue;

			$path = Options::$cacheFolder . '/' . $file;
			if (filemtime($path) < $now - Options::$cacheExpire) {
				//var_dump('unlinking ' . $path);
				unlink($path);

			}
		}
	}

	/**
	 * Print caching headers to tell the client if it should use it's cached copy of a file or redownload it.
	 * If $serverModifiedTime is before any modified since headers, then add the 304 response status code.
	 * @param int $serverModifiedTime Unix timestamp.
	 * @param string $serverETag  A hash string of the content.  Optional.
	 * @return bool True if the server's content does not match the client's.*/
	static function modifiedSince($serverModifiedTime, $serverETag=null) {
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

	static function open(string $url): ?GdImage {

		// I used to use createimagefromstring(get_file_contents($url))
		// But testing shows that this path can resize larger images before reaching the memory limit.
		$ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
		if ($ext === 'jpg' || $ext === 'jpeg')
			return imagecreatefromjpeg($url);
		elseif ($ext === 'png')
			return imagecreatefrompng($url);
		elseif ($ext === 'gif')
			return imagecreatefromgif($url);
		elseif ($ext === 'webp')
			return imagecreatefromwebp($url);
		elseif ($ext === 'avif' && function_exists('imagecreatefromavif')) // php 8.1
			return imagecreatefromavif($url);
		elseif ($ext === 'bmp')
			return imagecreatefrombmp($url);
		return null;
	}


	static function resize(GdImage $imageSrc, mixed $reqWidth, mixed $reqHeight, mixed $newWidth, mixed $newHeight): false|GdImage {
		$imageDest = imageCreateTrueColor($reqWidth, $reqHeight);

		imagealphablending($imageDest, false);
		imagesavealpha($imageDest, true);
		imagefilledrectangle($imageDest, 0, 0, $reqWidth, $reqHeight, imageColorAllocateAlpha($imageDest, 255, 255, 255, 127)); //127 is complete transparency

		imageCopyResampled($imageDest, $imageSrc, ($reqWidth - $newWidth) / 2, ($reqHeight - $newHeight) / 2, 0, 0, $newWidth, $newHeight, imagesx($imageSrc), imagesy($imageSrc));
		return $imageDest;
	}

}


// Debugging:
//ini_set('memory_limit', '-1'); // TODO: Will this allow us to resize any large image?
//set_time_limit(360000); // temporary for debugging.
//ini_set('display_errors', '1');

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('UTC');
chdir($_SERVER['DOCUMENT_ROOT']);
Options::$cacheFolder = trim(Options::$cacheFolder, '/\\');

$params = new Params(Options::$maxSize);
$params->validate();
$modified = filemtime($params->path);
$hash = $params->getHash($modified);

if (ImageUtil::modifiedSince($modified, $hash)) { // If server's copy is newer than client's

	$cachePath = Options::$cacheFolder . '/' . $hash.'.'.$params->ext;



	// If a cached version already exists
	if ($params->allowCache && Options::$cacheFolder && file_exists($cachePath)) {
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($cachePath)) . " GMT");

		// If it was generated more than a day ago, set the modified time to the current time.
		// This way any cleanup script knows not to delete it.
		$mtime = filemtime($cachePath);
		if ($mtime < time() - Options::$cacheTouch)
			touch($cachePath);

		// Return the file
		if (!headers_sent())
			header($params->getContentTypeHeader());
		readfile($cachePath);
	}
	else { // Generate a new version

		if (Options::$cacheFolder && !file_exists(Options::$cacheFolder)) {
			mkdir(Options::$cacheFolder, true);
			chmod(Options::$cacheFolder, 0775); // required on some hosts.
		}

		// Handle out of memory, return original image.
		$displayErrors = ini_get('display_errors');
		if ($params->fail !== FailMode::Error)
			register_shutdown_function(function() use ($cachePath, $params, $displayErrors) {

				ini_set('display_errors', $displayErrors);

				$error = error_get_last();
				if ($error && $error['type'] === E_ERROR) {
					// The error handler switches the directory back to apache's root directory.
					// So we have to switch it back again.
					chdir($_SERVER['DOCUMENT_ROOT']);

					http_response_code(203); // Mark that we weren't able to resize.

					if ($params->fail === FailMode::Placeholder) {

						$compressedImage = base64_decode( // Generic, 64x64 broken image icon:
							"iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmU".
							"AQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAAPUExURf///5nM/5mZZmZmZv///4IKedkAAAAFdFJOU/////8A+7YOUwAAAH9J".
							"REFUeNrs10EOgDAIRFHE3v/MtolNzASEumPsX5rwVpWqtN6ZrBkJEXAEeQgrID0LGM8RYQTkkQUgwgYIZB0kfLnYgJU2s".
							"IHR8lYmAz7dTAUBvXsbZgYU8oZZAXXCQXehFAeyw+5SLQ5kh+fB+gtgDc8YgegnM/zQrAtcAgwAwMYlQV3WplcAAAAASU".
							"VORK5CYII=");

						if (Options::$cacheFolder)
							file_put_contents($cachePath, $compressedImage);

						if (!headers_sent())
							header("Content-type: image/png");
						print $compressedImage;
					}
					else { // Send the original image, unresized
						if (!headers_sent())
							header($params->getContentTypeHeader());

						// Sometimes the file will fail to open at this point.
						// This can happen if ImageUtil::open() runs out of memory.
						// It's like it ruins anything else from opening the same path.
						readfile($params->path);
					}
				}
			});


		// TODO:  getimagesize() will download the entire image before it checks for the requested information.
		[$imgWidth, $imgHeight] = getimagesize($params->path);
		if ($params->width === null && $params->height === null) {
			$reqWidth = $imgWidth;
			$reqHeight = $imgHeight;
		}
		else {
			$reqWidth = $params->width;
			$reqHeight = $params->height;
		}

		[$reqHeight, $reqWidth, $newWidth, $newHeight] = ImageUtil::calculateSize2($imgWidth, $imgHeight, $reqHeight, $reqWidth, $params->mode, $params->upscale);

		// If no modification necesary, just return the same image.
		if ($imgWidth==$newWidth && $imgHeight==$newHeight && ($params->format === $params->ext)) {
			if (!headers_sent())
				header($params->getContentTypeHeader());
			readfile($params->path);
			die;
		}

		// Memory errors may occur below.  We hide them so that we can properly print image data in the fallback path.
		if ($params->fail !== FailMode::Error)
			ini_set('display_errors', false);

		// Open and resize image.
		$imageSrc = ImageUtil::open($params->path);
		$imageDest = ImageUtil::resize($imageSrc, $reqWidth, $reqHeight, $newWidth, $newHeight);
		$imageSrc = null;


		// Encode image
		ob_start();
		if ($params->outputType === 'jpeg')
			imagejpeg($imageDest, null, 90);
		else // png always compressed better than gif
			imagepng($imageDest);
		$imageDest = null;
		$compressedImage = ob_get_clean();
		if ($params->fail !== FailMode::Error)
			ini_set('display_errors', $displayErrors);

		// Output
		if (!headers_sent())
			header($params->getContentTypeHeader());
		print $compressedImage;


		if (Options::$cacheFolder)
			file_put_contents($cachePath, $compressedImage); // cached resized image file for next time
	}

	// Cleanup after the image is returned, so we don't leave the user waiting.
	if (random_int(1, Options::$cacheCleanupOdds) === 1)
		ImageUtil::cleanup();
}
