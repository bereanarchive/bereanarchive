<?php
/**
 * @param url string
 * @param w int
 * @param h int
 * @param s string.  Optionally combine w and h into one parameter instead.  e.g. ?s=320x240
 * @param mode string stretch, cover, or contain.  Default is contain.
 * @param format string jpg or png
 * The resulting image will not be upscaled unless the mode is explicitly set to stretch.
 *
 * TODO: resizing a 6000x4000 images runs out of memory on shared servers that only give php 100MB
 * So far I haven't found a good way around this, or via profiling, reduce the memory usage of this script.
 */

$cacheFolder = realpath('../') . '/temp/cache';

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('UTC');
require_once 'lib/functions.php'; // for modifiedSince()
chdir('../../');




/**
 * Given dimensions originalX and originalY, this function adjusts them to fit
 * inside a box of size targetX by targetY, preserving aspect ratio.
 * @param $originalWidth float
 * @param $originalHeight float
 * @param $targetWidth float
 * @param $targetHeight float
 * @param $crop boolean, resize to crop or to fit.
 * @param $allowUpscale boolean If false and both the target width and height are greater, then don't upscale more than one dimension.
 * @return array($width, $height) */
function resize($originalWidth, $originalHeight, $targetWidth, $targetHeight, $crop=false, $allowUpscale=true)
{
	$aspect = $originalWidth/$originalHeight;
	$targetAspect = $targetWidth/$targetHeight;
	if (($crop && $targetAspect < $aspect) || (!$crop && $targetAspect > $aspect)) {
		if (!$allowUpscale && $targetHeight > $originalHeight)
			$targetHeight = $originalHeight;
		$newWidth = $targetHeight*$aspect;
		$newHeight = $targetHeight;
	}
	else {
		if (!$allowUpscale && $targetWidth > $originalWidth)
			$targetWidth = $originalWidth;
		if ($aspect > $targetAspect) {
			$newWidth = $targetWidth;
			$newHeight = $targetWidth/$aspect;
		} else {
			$newWidth = $targetHeight*$aspect;
			$newHeight = $targetHeight;
		}
	}
	return array($newWidth, $newHeight);
}


// Hash parameters
$maxSize = 8192; // Slightly reduce risk of DDOS
$url = trim($_GET['url'], '/');
if (!file_exists($url)) {
	http_response_code(404);
	die('File does not exist: ' . $url);
}
$mode = isset($_GET['mode']) ? strtolower($_GET['mode']) : 'contain';
if (isset($_GET['s']))
	list($reqWidth, $reqHeight) = explode('x', $_GET['s']);
else if (isset($_GET['w']) || isset($_GET['h']))
{	$reqWidth = min(isset($_GET['w']) ? intval($_GET['w']) : false, $maxSize);
	$reqHeight = min(isset($_GET['h']) ? intval($_GET['h']) : false, $maxSize);
} else
	$reqWidth = $reqHeight = -1; // width and height will be determined from the image.


if (!in_array($mode, array('stretch', 'cover', 'contain')))
	die('mode not supported'); // reduce the chance of infinite unique queries as a DOS attack.

$ext = pathinfo($url, PATHINFO_EXTENSION);
if (!file_exists($cacheFolder))
	FS::makeDir($cacheFolder);
$modified = filemtime($url);
$hash = md5('resizeImage'.$url.$modified.$reqWidth.$reqHeight.$mode);
$hashPath = $cacheFolder . '/' . substr($hash, 0, 10).'.'.$ext;
if (isset($_GET['format']))
	$outputType = $_GET['format']=='png' ? 'png' : 'jpeg';
else
	$outputType = ($ext=='jpg' || $ext=='jpeg' || $ext=='webp') ? 'jpeg' : 'png'; // convert webp to jpeg because most browsers don't support webp.

$header = 'Content-type: image/' . $outputType;

if (modifiedSince($modified, $hash)) { // If server's copy is newer than client's


	// If a cached version already exists
	if (file_exists($hashPath)) {

		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($hashPath)) . " GMT");

		// If it was generated more than a day ago, set the modified time to the current time.
		// This way Files::cleanup() knows not to clean it up.
		$mtime = filemtime($hashPath);
		if ($mtime < time() - 24*3600)
			touch($hashPath);

		// Return the file
		header($header);
		print file_get_contents($hashPath);
	}
	else // Generate a new version
	{	// Load
		//$image = imagecreatefromstring(file_get_contents($url));
		list($imgWidth, $imgHeight) = getimagesize($url);
		if ($reqWidth==-1) {  // determine width/height from image.
			$reqWidth = $imgWidth;
			$reqHeight = $imgHeight;
		}

		// Calculate size
		if ($reqHeight===false) // determine height from width
			$reqHeight = round(($reqWidth /$imgWidth) * $imgHeight);
		elseif ($reqWidth===false) // or vice versa
			$reqWidth = round(($reqHeight / $imgHeight) * $imgWidth);

		if ($mode=='cover') {
			list($newWidth, $newHeight) = resize($imgWidth, $imgHeight, $reqWidth, $reqHeight, true, true);

			$newWidth = round($newWidth);
			$newHeight = round($newHeight);
		}
		else {
			if ($mode=='contain') {
				list($reqWidth, $reqHeight) = resize($imgWidth, $imgHeight, $reqWidth, $reqHeight, false, false);
				$reqWidth = max($reqWidth, 1);
				$reqHeight = max($reqHeight, 1);
			}
			$newWidth = round($reqWidth);
			$newHeight = round($reqHeight);
		}

		// If no modification necesary, just return the same image.
		if ($imgWidth==$newWidth && $imgHeight==$newHeight && (!isset($_GET['format']) || $_GET['format']===$ext)) {
			header($header);
			print file_get_contents($url);
			die;
		}

		// Resize
		$image = imagecreatefromstring(file_get_contents($url));
		$resizedImage = imageCreateTrueColor($reqWidth, $reqHeight);
		imagealphablending($resizedImage, false);
		imagesavealpha($resizedImage, true);
		imagefilledrectangle($resizedImage, 0, 0, $reqWidth, $reqHeight, imageColorAllocateAlpha($resizedImage, 255, 255, 255, 127)); //127 is complete transparency
		imageCopyResampled($resizedImage, $image, ($reqWidth-$newWidth)/2, ($reqHeight-$newHeight)/2, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
		$image = null;

		// Output
		ob_start();
		if ($outputType === 'jpeg')
			imagejpeg($resizedImage, null, 90);
		else // png always compressed better than gif
			imagepng($resizedImage);
		$resizedImage = null;

		$compressedImage = ob_get_clean();

		header($header);
		print $compressedImage;
		file_put_contents($hashPath, $compressedImage); // cached resized image file for next time
	}
}