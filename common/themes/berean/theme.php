<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/sitecrafter/init.php';

$theme_name = 'Berean';
$theme_description = '';

/**
 * If true, the content area will strech so that the footers are aligned with the bottom of the page. */
$page_stretchVertical ??= false;

/**
 * @var string Title shown in the browser tab, in search engine results, and when sharing on social media.
 * It's printed inside the <code>&lt;title&gt;</code> and <code>&lt;meta&nbsp;property="og:title"&gt;</code> tags. */
$page_title = "";

/**
 * @type string|textarea
 * Page description shown in search engines and when sharing to social media.  It's printed in the
 * <code>&lt;meta&nbsp;name="description"&gt;</code> and
 * <code>&lt;meta&nbsp;property="og:description"&gt;</code> tags. */
$page_description ??= "";

/** @type string|image Page image shown when sharing to social media.
 * The image url is printed inside the <code>&lt;meta&nbsp;property="og:image"&gt;</code> tag. */
$page_image ??= "";



/** @type string|CodeEditor Optional html code injected before the closing <code>&lt;/head&gt;</code> tag. */
if (!isset($page_head1)) {
	ob_start()?><?=DarkMode::init()?><?php
	$page_head1 = ob_get_clean();
}

/** @type string|CodeEditor Optional html code injected before the closing <code>&lt;/head&gt;</code> tag. */
$page_head2 ??= '';



/** @type string|CodeEditor Optional html code shown in the top left of the title bar.  Typically a logo.  */
$page_topLeft ??= '';

/** @type string|CodeEditor Optional html code shown in the top center of the title bar, and hidden on mobile.  Typically a menu. */
if (!isset($page_topMenu)) {
	ob_start();?><ul class="row wrap">
	<li><a href="/" class="primary">Berean Archive</a></li>
	<li>|</li>
	<?php /*
		<li><a href="/articles/about">News</a></li>
		<li><a href="/articles/about">Blogs</a></li>
        */ ?>
	<li><a href="/about">About</a></li>
	<li><a href="/contribute">Contribute</a></li>
	<li><a href="/contact">Contact</a></li>
	<li><a href="https://github.com/bereanarchive/bereanarchive" target="_blank">Github</a></li>
	<?php /*
		<li><a href="/forum">Forum</a></li>
        */ ?>
</ul><?php
	$page_topMenu ??= ob_get_clean();
}

/** @type string|CodeEditor  Html code shown in the top right of the title bar.  Typically a menu, login, or social buttons. */
if (!isset($page_topRight)) {
	ob_start()?><?=DarkMode::toggleButton()?><?php
	$page_topRight = ob_get_clean();
}




/**
 * @type string|CodeEditor Menu shown on the left side of the page.  If non-empty and when viewing on mobile,
 * a button will be shown on the top right of the page to show the mobile menu. */
if (!isset($page_mobileMenu)) {
	ob_start()?><?php include 'common/includes/nav-with-toc.php'?><?php
	$page_mobileMenu = ob_get_clean();
}


$page_headerStyle??= '';


/** @type string|CodeEditor Optional html code to show a banner above the page content. */
if (!isset($page_banner1)) {
	ob_start()?><div style="<?=$page_headerStyle?>"></div><?php
	$page_banner1 = ob_get_clean();
}

/** @type string|CodeEditor Optional html code to show a second banner above the page content. */
$page_banner2 ??= '';

/** @type string|CodeEditor Optional html code to show a third banner above the page content. */
$page_banner3 ??= '';


/** @type string|CodeEditor Optional html code to show to the left of the page content. */
$page_left ??= '';


$page_right ??= '';


/** @type string|RichText The main content of the page. */
$page_content ??= '';



/** @type string|CodeEditor Optional html code to show a footer at the bottom of the page. */
$page_footer1 ??= '';

/** @type string|CodeEditor Optional html code to show a second footer at the bottom of the page. */
$page_footer2 ??= '';

/** @type string|CodeEditor Optional html code to show a third footer at the bottom of the page. */
if (!isset($page_footer3)) {
	ob_start();?><p>All code and content on The Berean Archive is
	placed under the <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank">CC0 1.0 Universal</a> "Public
	Domain" license unless originating from a third party or denoted otherwise.</p>

<p>Use of media from other websites is believed to meet the
	<a href="http://libguides.mit.edu/usingimages" target="_blank">criteria for fair use</a>.
	<?php if (isset($startRenderTime)):?>
		Rendered in <?=@number_format(microtime(true) - $startRenderTime, 5)?> seconds.
	<?php endif?>
</p><?php
	$page_footer3 = ob_get_clean();
}

// Close any open "??= ob_get_clean();" assignments that weren\'t used because the variable was already set.
while (ob_get_level())
	ob_end_clean();

if (class_exists('Stats'))
	Stats::hit();

// If ?raw url param is set, print just the page content.
// Used for loading page content via ajax.
if (isset($_GET['raw'])) {
	print $page_content;

	// Print other page variables in an html comment, so they can be read by whatever loads this page.
	$pageVars = array_filter(
		get_defined_vars(),
		fn($key) => str_starts_with($key, 'page_') && $key != 'page_content',
		ARRAY_FILTER_USE_KEY
	);
	print "\r\n<!--" . json_encode($pageVars). '-->';
	die;
}

?>
<!DOCTYPE html>
<html lang="en" class="rex-layout">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=htmlspecialchars($page_title)?></title>



	<!-- Styles -->
	<link rel="stylesheet" href="/sitecrafter/lib/css/rex.css">
	<link rel="stylesheet" href="/common/themes/berean/theme.css">
	<link rel="stylesheet" href="/common/css/footnotes2.css">
	<link rel="stylesheet" href="/common/css/magnific.css">

	<script defer src="/common/js/lib/jquery.min.js"></script>
	<script defer src="/common/js/lib/magnific.js"></script>
	<script defer src="/common/js/main.js"></script>

	<script defer src="/common/js/definitions.js"></script>
	<script type="module" src="/common/js/Footnotes2.js"></script>

	<link rel="icon" type="image/png" href="/favicon.png" crossorigin="anonymous">


	<?php /* SEO/Social media tags */ ?>
	<?php if (!empty($page_title)):?>
		<meta property="og:title" content="<?=htmlspecialchars($page_title)?>">
		<meta name="twitter:title" content="<?=htmlspecialchars($page_title)?>">
	<?php endif?>
	<?php if (!empty($page_description)):?>
		<meta name="description" content="<?=htmlspecialchars($page_description)?>" />
		<meta property="og:description" content="<?=htmlspecialchars($page_description)?>">
		<meta name="twitter:description" content="<?=htmlspecialchars($page_description)?>">
	<?php endif?>
	<?php if (!empty($page_image)):?>
		<?php $pageImageUrl = (($_SERVER['HTTPS'] ?? '') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] .
			str_replace('#', '%23', $page_image)?>
		<meta property="og:image" content="<?=htmlspecialchars($pageImageUrl)?>">
		<meta name="twitter:image" content="<?=htmlspecialchars($pageImageUrl)?>">
	<?php endif?>
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?="https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>">


	<meta property="og:type" content="website">
	<meta property="og:url" content="<?="$_SERVER[SERVER_PROTOCOL]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>">
	<style>
		/* ┌─────────────────────────────╮
		 * | Main Layout                 |
		 * └─────────────────────────────╯*/
		body { min-height: 100vh; margin: 0; display: flex }
		#main { flex: 1; display: flex; flex-direction: column; align-items: stretch; min-width: 0 } /* Prepare for SlideContainer */
		#header { position: relative }
		#header a { text-decoration: none }
		nav ul { list-style-type: none }


		/* ┌─────────────────────────────╮
		 * | Mobile Menu                 |
		 * └─────────────────────────────╯*/

		/* Slide Container */
		.slideContainer { display: flex; width: 100% }
		.slideContainer > #mobileMenu { position: fixed; top: 0; left: 0; min-width: 230px; max-width: 230px; height: 100vh }
		.slideContainer > #mobileMenu { transform: translateX(-200px) } /* Hide menu by default.*/

		/* Button */
		#mobileMenuButton { position: fixed; z-index: 1; top: 0; right: 0; padding: 8px; line-height: .8;
			border-bottom-left-radius: 10px; cursor: pointer; font-size: 30px; font-weight: bold;
			color: white; background: black;
			}
		@media (max-width: 768px) {	#mobileMenu { display: none } }
		@media (min-width: 769px) {	#mobileMenuButton { display: none } }

		/* ┌─────────────────────────────╮
		 * | Content Text                |
		 * └─────────────────────────────╯*/
		#columns { display: flex; flex: 1; min-width; 0 }
		#container, #content { min-width: 0; flex: 1 }
		#content { box-sizing: border-box; min-height: 10px }

		<?php if ($page_stretchVertical):?>
			#container, #content { flex: 1 }
		<?php endif?>
	</style>

	<?=$page_head1?>
	<?=$page_head2?>
</head>
<body>
<div class="slideContainer">
	<?php if (trim($page_mobileMenu)):?>
		<div id="mobileMenu">
			<?=$page_mobileMenu?>
		</div>
	<?php endif?>

	<div id="main">
		<?php if (trim($page_topLeft) || trim($page_topMenu) || trim($page_topRight)):?>
			<div id="header" class="row center-v space-between">
				<?php if (trim($page_topLeft)):?>
					<div id="topLeft"><?=$page_topLeft?></div>
				<?php endif?>

				<?php if (trim($page_topMenu)):?>
					<div id="topMenu"><?=$page_topMenu?></div>
				<?php endif?>

				<?php if (trim($page_topRight)):?>
					<div id="topRight"><?=$page_topRight?></div>
				<?php endif?>
			</div>
		<?php endif?>

		<?php if (trim($page_mobileMenu)):?>
			<div id="mobileMenuButton" onclick="menu.toggle()" title="Toggle Menu">☰</div>
		<?php endif?>

		<?php if (trim($page_banner1)):?>
			<div id="banner1"><?=$page_banner1?></div>
		<?php endif?>
		<?php if (trim($page_banner2)):?>
			<div id="banner2"><?=$page_banner2?></div>
		<?php endif?>

		<div id="columns">

			<?php if ($page_left !== false):?>
				<div id="left"><?=$page_left?></div>
			<?php endif?>

			<div id="container" class="col stretch">

				<?php if (trim($page_banner3)):?>
					<div id="banner3"><?=$page_banner3?></div>
				<?php endif?>


				<div id="content" class="expand"><?=$page_content?></div>

				<?php if (trim($page_footer1)):?>
					<div id="footer1"><?=$page_footer1?></div>
				<?php endif?>
			</div>

			<?php if (trim($page_right)):?>
				<div id="right"><?=$page_right?></div>
			<?php endif?>
		</div>

		<?php if (trim($page_footer2)):?>
			<div id="footer2"><?=$page_footer2?></div>
		<?php endif?>

		<?php if (trim($page_footer3)):?>
			<div id="footer3"><?=$page_footer3?></div>
		<?php endif?>

	</div>
</div>
<?php if (trim($page_mobileMenu)):?>
	<script type="module">
		let mobileMenu = document.getElementById('mobileMenu');
		let left = document.getElementById('left');
		let main = document.getElementById('main');

		function moveMenu() {
			let menu = mobileMenu.children[0] || left.children[0];
			if (window.innerWidth < 768) {
				if (menu.parentNode !== mobileMenu)
					mobileMenu.append(menu);
			}
			else {
				main.style.marginLeft = main.style.marginRight = '';
				mobileMenu.setAttribute('style', 'display: block; left: 0px; transform: translateX(-230px)');
				if (menu.parentNode !== left)
					left.append(menu);
			}
		}
		if (left) {
			window.addEventListener('resize', moveMenu);
			moveMenu()
		}


		// Activate the slide menu.
		import SlideMenu from '/sitecrafter/lib/js/util/SlideMenu.js';
		window.menu = new SlideMenu(
			document.querySelector('#mobileMenu'),
			document.querySelector('#main')
		);

		// Add expand button for footnotes.
		// Called by Footnotes.viewFootnote();

		let footnotes = document.body.querySelector('div.footnotes');
		if (footnotes) {
			let div = document.createElement('div');
			div.innerHTML = `<div class="expand row center"><a href="javascript:;">Expand</a></div>`
			let expand = div.firstChild;


			window.expandFootnotes = function() {
				footnotes.classList.add('expanded');
				expand.remove();
			};


			footnotes.parentNode.insertBefore(expand, footnotes.nextSibling);
			expand.firstChild.addEventListener('click', e => {
				window.expandFootnotes();
			});
		}
	</script>
<?php endif?>
<script type="module">
	// Highlight selected menu paths within nav elements.
	[...document.querySelectorAll('nav a')].map(a => {
		let href = a.getAttribute('href').replace(/\/$/, '')
		if (href && location.pathname.startsWith(href))
			a.classList.add('selected');
	});
</script>
</body>
</html>