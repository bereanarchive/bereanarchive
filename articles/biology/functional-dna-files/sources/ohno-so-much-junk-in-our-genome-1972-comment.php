<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/sitecrafter/init.php';





$page_title = 'Comment on:  Ohno, So Much Junk in our Genome, 1972 | Berean Archive';
$page_theme= 'common/includes/theme-comments.php';
$page_head = '';

//$page_content
ob_start()?>
<div class="authorship">by John Berea
	<br>Published: Jan 2017
	<br>Updated:
	<?=date('F j, Y', filemtime(__FILE__))?>
</div>
<p>Comment on:</p>
<h1>So much "Junk" DNA in our Genome</h1>
<p>Susumu Ohno, Brookhaven Symposia in Biology, 1972
	<br><a href="http://www.junkdna.com/ohno.html">Source</a> |
	<a href="https://web.archive.org/web/20160203012605/http://www.junkdna.com/ohno.html"
	>Archive.org</a> | <a href="http://archive.is/j22Qx#selection-79.366-79.535">Archive.is</a>
</p>
<p>Susumu Ohno was an evolutionary biologist famous for the idea of gene duplication
	and neofunctionalization.&nbsp; In 1972 he published a paper predicting that
	at most only 6% of our genome could functional.&nbsp; Ohno wrote:</p>
<blockquote>
	<p>[page 366] The falseness of such an assumption [that gene count is proportional
		to genome size] becomes clear when we realize that the genome of the lowly lungfish
		and salamanders can be 36 times greater than our own...</p>
	<p>The observations on a number of structural gene loci of man, mice, and other organisms
		that each locus has a 10<sup>-5</sup> per generation probability of obtaining a
		deleterious mutation.&nbsp; It then follows that the moment we acquire 10<sup>5</sup> gene
		loci, the overall deleterious mutation rate per generation becomes 1.0 which appears
		to represent an unbearably heavy genetic load.&nbsp; Taking into consideration
		the fact that deleterious mutations can be dominant or recessive,the total number
		of gene loci of man has been estimated to be about 3 x 10<sup>4</sup> (Muller 1967,
		Crow and Kimura 1970).&nbsp; Even if an allowance is made for the existence in
		multiplicates of certain genes, it is still concluded that <b>at the most, only 6% of our DNA base sequences is utilized as genes</b> (Kimura
		and Ohta, 1971) ...</p>
	<p>[page 368] Inasmuch as the only requirement to be qualified as partitioning sequences
		[spacers between genes, so that one frameshift doesn't affect multiple genes] is
		to be untranscribable and/or untranslatable, it is not likely that these sequences
		came into being as a result of positive selection.&nbsp; Our view is that they
		are the remains of nature's experiments which failed.&nbsp; <b>The earth is strewn with fossil remains of extinct species; is it a wonder that our genome too is filled with the remains of extinct genes?</b>...</p>
	<p>[page 369] The creation of every new gene must have been accompanied by many other
		redundant copies joining the ranks of silent DNA base sequences, and these silent
		DNA base sequences may now be serving the useful but negative function of spacing
		those which have succeeded.&nbsp; Triumphs as well as failures of nature's past
		experiments appear to be contained in our genome.</p>
</blockquote>
<p>Above, Ohno says 6% of "genes" which at the time meant a "region of DNA."</p>
<?php $page_content = ob_get_clean();


// $page['content']['']
ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';