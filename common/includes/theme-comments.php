<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';
?>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>
		<?=@$page_title?>
	</title>
	<style>
		.text h1 { margin-top: 0 }
	</style>
	<link rel="stylesheet" href="/common/css/theme.less">
	<script defer src="/common/js/lib/jquery.min.js"></script>
	<script defer src="/common/js/main.js"></script>
	<?=@$header?>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimum-scale=1.0,maximum-scale=1.0">
</head>

<body>
	<div id="wrapper">

		<div id="content">
			<div id="side1" class="include">
				<?php include 'common/includes/nav-logo-only.php'?>
			</div>
			<div id="main" class="placeholder text" style="">
				<?=$page_content?>
			</div>
		</div>

		<div id="footer" style="position: relative;" class="include">

		</div>
	</div>
</body>

</html>