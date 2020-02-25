<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';





$page['title'] = 'Comment on:  Ohno, So Much Junk in our Genome, 1972 | Berean Archive';
$page['theme'] = 'common/includes/theme-comments.php';
$page['head'] = '';

//$content
ob_start()?>
<div class="authorship">by John Berea
	<br>Published: Jan 2017
	<br>Updated:
	<?=date('F j, Y', filemtime(__FILE__))?>
</div>
<p>Comment on:</p>
<h1>Selfish genes, the phenotype, paradigm and genome evolution</h1>
<p>Ford Doolittle and Carmen Sapienza, Nature, 1980
	<br><a href="http://www.junkdna.com/ohno.html">Source</a> |
	<a href="https://web.archive.org/web/20160203012605/http://www.junkdna.com/ohno.html"
	>Archive.org</a> | <a href="http://archive.is/j22Qx#selection-79.366-79.535">Archive.is</a>
</p>
<h3></h3>
<p>Ford Doolittle is a biochemist and member of the National Academy of Sciences. In
	1980 he and Sapienza argued for large amounts of junk DNA must exist in higher
	organisms because selfish genes would duplicate much faster than selection could
	remove them.&nbsp; He and Sapienza write:</p>
<blockquote>
	<p>Natural selection operating within genomes will inevitably result in the appearance
		of DNAs with no phenotypic expression whose only 'function' is survival within
		genomes.&nbsp; Prokaryotic transposable elements and eukaryotic middle-repetitive
		sequences [repeats in the middle of a chromosome] can be seen as such DNAs, and
		thus no phenotypic or evolutionary function need be assigned to them.&nbsp; The
		assertion that organisms are simply DNA's way of producing more DNA has been made
		so often that it is hard to remember who made it first... The DNA is there because
		it facilitates genetic rearrangements which increase evolutionary versatility (and
		hence long-term phenotypic benefit), or because it is a repository from which new
		functional sequences can be recruited or, at worst, because it is the yet-to-be-eliminated
		by-product of past chromosomal rearrangements of evolutionary significance.</p>
	<p>[page 601, Non-phenotypic selection]</p>
	<p>if it can be shown that a given gene (region of DNA) or class of genes (regions)
		has evolved a strategy which increases its probability of survival within cells,
		then no additional (phenotypic) explanation for its origin or continued existence
		is required.&nbsp; This proposal is not altogether new; Dawkins, Crick, and Bodmer
		have briefly alluded to it...</p>
	<p>[page 602, left column]</p>
	<p>A single copy of a DNA sequence of no phenotypic benefit to the host risks deletion,
		but a sequence which spawns copies of itself elsewhere in the genome can only be
		eradicated by simultaneous multiple deletions.</p>
	<p>[page 603, The rest of the eukaryotic genome]</p>
	<p>Such [transposlabe] elements, once inserted, are relatively immune to deletion (Since
		only very precise deletion can be non-lethal)</p>
	<p>[page 603, Necessary and unnecessary explanations]</p>
	<p>It is inevitable that natural selection of the special sort we call non-phenotypic
		will favor the development within genomes of DNAs whose only 'function' is survival
		within genomes [selfish genes].&nbsp; When a given DNA, or class of DNAs, of unproven
		phenotpyic function can be shown to have evolved a strategy (such as transposition)
		which ensures its genomic survival, then no other explanation for its existence
		is necessary. The search for other explanations may prove, if not intellectually
		sterile, ultimately futile.</p>
</blockquote>
<p>Like Ohno, Doolittle and Sapienza suggest that a large genome couldn't
	be maintianed by selection, claiming duplicating selfish genes as a plausible alternative:</p>
<blockquote>
	<p>if the calculations of Kimura and Salser and Isaacson are correct, middle-repetitive
		DNAs together comprise too large a fraction of most eukaryotic genomes to be kept
		accurate by Darwinian selection operating on organismal phenotype.&nbsp; The most
		plausible form of "remultiplication of the 'correct' surviving sequences" is transposition.</p>
</blockquote>
<?php $content = ob_get_clean();





require_once 'common/includes/theme-comments.php';