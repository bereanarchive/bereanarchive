<?php
/**
 * @param string $page_title
 * @param string $image
 * @param string $headerStyle
 * @param string $caption
 * @param string $bodyClasses
 * @param string $page_content
 * @param bool $sideBars
 */

/*
$pdo = new PDO('sqlite:test.sqlite3');
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query("PRAGMA synchronous = OFF");
$pdo->query("PRAGMA journal_mode = OFF"); no rollbacks, db corrupt on app or system crash.
$pdo->query("INSERT INTO logs (message, created) VALUES('hi', '2008-12-22 12:33:22')");
*/
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimum-scale=1.0,maximum-scale=1.0">

		<?php if (isset($image)): // Image preview used by facebook when sharing hte article. ?>
			<meta property="og:image" content="http://<?=$_SERVER['SERVER_NAME']?><?=htmlspecialchars($image)?>">
		<?php else:?>
			<meta property="og:image" content="http://<?=$_SERVER['SERVER_NAME']?>/common/img/site/berea-fb.jpg">
		<?php endif?>
		<?php if (isset($description)): // Image preview used by facebook when sharing hte article. ?>
			<meta property="og:description" content="<?=htmlspecialchars($description)?>">
		<?php else:?>
			<meta property="og:description" content="An open source, excessively cited library of Christian evidence.">
		<?php endif?>
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?=@htmlspecialchars($page_title)?>" />
		
		<title><?=@$page_title?></title>

		<?php /*
		<base href="/<?=dirname(trim(strtok($_SERVER["REQUEST_URI"], '?'), '/'))?>/" />
        */?>

		<link rel="stylesheet" href="/common/css/theme.less">
		<link rel="stylesheet" href="/common/css/theme.css">
		<link rel="stylesheet" href="/common/css/theme-dark.less">
		<link rel="stylesheet" href="/common/css/footnotes.css">
		<link rel="stylesheet" href="/common/css/magnific.css">
		
		<script defer src="/common/js/lib/jquery.min.js"></script>
		<script defer src="/common/js/lib/hammer.min.js"></script>
		<script defer src="/common/js/lib/tether.min.js"></script>
		<script defer src="/common/js/lib/magnific.js"></script>
		<script defer src="/common/js/main.js"></script>

		<script defer src="/common/js/definitions.js"></script>
		<script defer src="/common/js/footnotes.js"></script>
		
		<link rel="icon" type="image/png" href="/favicon.png" crossorigin="anonymous">
		<?=@$header?>
	</head>

	<body class="<?=$_COOKIE['mode']??''?> <?=@$bodyClasses?> <?=isset($markdownFile) ? 'markdown' : ''?>">
		<div id="wrapper">

			<div id="topBar">
				<ul>
					<li id="topNavMobile"><a><img src="/common/img/site/nav.png"></a></li>

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
				</ul>


				<div id="navMobile"><br></div>

				<div id="darkSlider" title="Toggle dark mode">
					<label class="sliderButton" for="darkMode">
						<input type="checkbox" value="None" id="darkMode" name="check"
							<?=($_COOKIE['mode']??'')==='dark' ? 'checked' : ''?>/>
							<span></span>
					</label>
				</div>
				<script>
					<?php /* Activate dark mode slider */ ?>
					var checkbox = document.getElementById('darkMode');
					checkbox.addEventListener('change', function() {
						document.body.classList.toggle('dark', checkbox.checked);
						document.cookie = "mode=" + (checkbox.checked ? 'dark' : '') + '; path=/; expires=Tue, 19 Jan 2038 0:00:00 UTC ';
					});
				</script>
			</div>


			<div id="imageHeader" style="<?=@$headerStyle?>">
				<div class="caption">
					<?=@$caption?>
				</div>
			</div>

			<div id="topBar2"></div>


			<div id="content">
				<?php if ($sideBars ?? true):?>
					<div id="side1"><?php include 'common/includes/nav-with-toc.php'?></div>
				<?php endif?>
				<div id="main" class="text"><?=@$page_content?></div>
				<?php if ($sideBars ?? true):?>
					<div id="side2"></div>
				<?php endif?>
			</div>
			<div id="footer" style="position: relative" class="include">
				<p>All code and content on The Berean Archive is
					placed under the <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank">CC0 1.0 Universal</a> "Public
					Domain" license unless originating from a third party or denoted otherwise.</p>

				<p>Use of media from other websites is believed to meet the
					<a href="http://libguides.mit.edu/usingimages" target="_blank">criteria for fair use</a>.
					<?php if (isset($startRenderTime)):?>
						Rendered in <?=@number_format(microtime(true) - $startRenderTime, 5)?> seconds.
					<?php endif?>
				</p>
			</div>
		</div>
	</body>
</html>