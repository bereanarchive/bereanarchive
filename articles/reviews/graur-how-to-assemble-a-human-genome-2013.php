<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/sitecrafter/init.php';





$page_title = "Comment on:  Graur 2013 | Berean Archive";
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
<h1>How to Assemble a Human Genome<br></h1>
<p>Dan Graur, 2013
	<br>No longer online | <a href="http://web.archive.org/web/20130719031933/http://twileshare.com/askq">Archive.org</a> |
	<a
	href="graur-how-to-assemble-a-human-genome-2013-slide5.png">Local screenshot of slide 5</a>
</p>
<p>In July 2013, Dan Graur gave a presentation at the Society for Molecular Biology
	and Evolution, arguing against the ENCODE 2012 results.&nbsp; From slide 5:</p>
<blockquote>
	<p>If the human genome is indeed devoid of junk DNA as implied by the ENCODE project,
		then a long, undirected evolutionary process cannot explain the human genome.&nbsp; If
		on the other hand organisms are designed, then all DNA, or as much as possible,
		is expected to exhibit function.&nbsp; If ENCODE is right, then evolution is wrong.</p>
</blockquote>
<p>He goes on to define what he calls the ENCODE incongruity on slides 113 and 114:</p>
<blockquote>
	<p>The difference between the fraction of the genome claimed by ENCODE to be functional
		(&gt;80%) and the fraction of the genome under selection (&lt;8%)... The ENCODE
		Incongruity implies that a biological function can be maintained without selection,
		which in turn implies that no deleterious mutations can occur in those genomic
		sequences described by ENCODE as functional.&nbsp; This is akin to claiming that
		a television set left on and unattended will still be in working condition after
		a million years."</p>
</blockquote>
<p>In other words, since less than 8% is shared with other animals, evolution could
	not have produced the rest in the available time--or even preserved it from deterioration.</p>
<p
>On slide 46, Graur cited <a href="http://download.cell.com/trends/genetics/pdf/PIIS0168952505002684.pdf?intermediate=true">Genome size is negativey correlated with effective population size in ray-finned fish</a>,
	from Trends in Genetics, 2005.&nbsp; The idea is that smaller populations have weaker
	selective forces against the slightly deleterious accumulation of extra junk, and
	will therefore have larger genomes.&nbsp; But this argument was rebutted in a
	<a
	href="https://www.ncbi.nlm.nih.gov/pubmed/18356967">2008 paper</a>by T. Ryan Gregory.</p>
<?php $page_content = ob_get_clean();


ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';