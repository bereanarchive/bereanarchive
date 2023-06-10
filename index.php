<?php 
$title       = 'The Berean Archive';
$headerStyle = "background: url('/common/img/site/berean-archive-small-logo-white2.svg') no-repeat 20px 10%, url('/common/img/site/berea2.jpg') no-repeat 25% 50% / cover; filter: drop-shadow( -5px -5px 5px #000 );";
$caption     = 'The Haliacmon river near the site of ancient Berea, Greece.  Copyright <a href="https://www.google.com/maps/@40.449937,22.218712,3a,75y,90t/data=!3m8!1e2!3m6!1s113074573!2e1!3e10!6s%2F%2Flh3.googleusercontent.com%2Fproxy%2FtyO4iRfN8QWlhQphql0AgZww-yi5k2G6UuFIu1xWZbDDKCHlmP-C8aXgynz33s3zb5Bb4282wr84RJJa-N8MUEsDmJHJdWA%3Dw203-h135!7i6000!8i4000">Google Maps</a>.';
$image       = '/common/img/site/berea-fb.jpg';
$sideBars    = false;
$theme       = 'common/includes/theme.php';

ob_start()?>
<p><b>The Berean Archive</b> is a public domain, excessively cited library of Christian evidence.
It will focus on data showing God exists and that Christianity is true, as well as tackling competing claims.</p>

<p>Berea was a city in ancient Greece. In Acts chapter 17, the apostle Paul commended the Bereans for
checking his citations of the Old Testament.&nbsp; In that same spirit our articles target a zero-trust audience, providing numerous citations to back up even mildly controversial claims.</p>

<p><a href="/about">Read more</a> about the goals of this  website.</p>

<h2>Articles</h2>

<p>In these early stages, articles are biased toward topics not covered in sufficient depth elsewhere online.</p>

<?php include 'common/includes/articles-list.php'?>
<?php foreach ($articleCategories as $categoryName=>$articles):?>
<h3><?=htmlspecialchars($categoryName)?></h3>
<?php foreach ($articles as $article):?>
<div class="item">
<a href="<?=htmlspecialchars($article->url)?>">
	<img src="<?=htmlspecialchars($article->thumb)?>?w=90">
</a>
<div>
	<h4><a href="<?=htmlspecialchars($article->url)?>"><?=$article->name?></a></h4>
	<p><?=$article->description?></p>
</div>
</div>
<?php endforeach?>
<?php endforeach?>


<?php
$content = ob_get_clean();

require_once $theme;