<?php
$cachePath = 'common/temp';

$startRenderTime = microtime(true);


require_once '../php/functions.php';
require_once '../php/Markdown.php';
chdir('../../'); // one level above common folder.

// Check for the ?noCache url parameter.
global $config;

$markdownFile = trim(explode('&', $_SERVER['QUERY_STRING'])[0], '/\\');
$markdownFileModified = filemtime($markdownFile);
$cacheFile = FS::joinPaths($cachePath, preg_replace('/\\//', '_', $markdownFile . '.php'));
$variables = [
	// Get the path to the .md file from the first url parameter.
	// This is given to us by .htaccess.
	'markdownFile' => $markdownFile,
	'startRenderTime' => $startRenderTime,
	'modified' => $markdownFileModified,
	'cached'=> false
];


if (isset($_GET['noCache']))
	if (file_exists($cacheFile))
		unlink($cacheFile);


if (!file_exists($cacheFile) || $markdownFileModified > filemtime($cacheFile)) {
	$html = Markdown::markdownToHtml(file_get_contents($variables['markdownFile']));
	file_put_contents($cacheFile, $html);
} else
	$variables['cached'] = true;


extract($variables);
include $cacheFile;