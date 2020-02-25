<?php

require_once 'init.php';
require_once 'common/php/functions.php';

// Get the path to the .md file from the first url parameter.
// This is given to us by .htaccess.
$mdFilePath = explode('&', $_SERVER['QUERY_STRING'])[0];
$mdFilePath = trim($mdFilePath, '/\\');

// Check for the ?noCache url parameter.
$cachePath = isset($_GET['noCache']) ? null : 'common/temp/cache/';

// Convert the markdown file to html.
$markdown = Markdown::render($mdFilePath, $cachePath);

// Send it to the browser.
print $markdown;



