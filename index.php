<?php 
$title       = 'The Berean Archive';
$bodyClasses = 'noSidebars';
$headerStyle = "background-image: url('/common/img/site/berea.jpg'); background-position: 65% 100%";
$caption     = 'The Haliacmon river near the site of ancient Berea, Greece.  Copyright <a href="https://www.google.com/maps/@40.449937,22.218712,3a,75y,90t/data=!3m8!1e2!3m6!1s113074573!2e1!3e10!6s%2F%2Flh3.googleusercontent.com%2Fproxy%2FtyO4iRfN8QWlhQphql0AgZww-yi5k2G6UuFIu1xWZbDDKCHlmP-C8aXgynz33s3zb5Bb4282wr84RJJa-N8MUEsDmJHJdWA%3Dw203-h135!7i6000!8i4000">Google Maps</a>.';
$theme       = 'common/includes/theme.php';


ob_start()?>
<div style="display: flex; flex-direction: row">
	<div class="bereanLogo" style="min-width: 180px; margin: 0 45px 10px 0"></div>
	<div>
		<p><b>The Berean Archive</b> is a public domain, excessively cited library of Christian evidence.
			It will focus on data showing God exists and that Christianity is true, as well as tackling competing claims.</p>

		<p>Berea was a city in ancient Greece. In Acts chapter 17, the apostle Paul commended the Bereans for
			checking his citations of the Old Testament. Likewise this site aims to be particularly careful with
			facts and correct any errors in the face  of new information.</p>

		<p><a href="/articles/about">Read more</a> about the goals of this  website.</p>
	</div>
</div>


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

require_once 'common/includes/theme.php';