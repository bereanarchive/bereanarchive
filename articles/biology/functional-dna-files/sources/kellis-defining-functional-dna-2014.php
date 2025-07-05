<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/sitecrafter/init.php';





$page_title = "Comment on Kellis et al 2014 | Berean Archive";
$page_theme= 'common/includes/theme-comments.php';
$page_head = '';

//$page_content
ob_start()?>
<div class="authorship">Published: Jan 2017
	<br>Updated:
	<?=date('F j, Y', filemtime(__FILE__))?>
</div>
<p>Comment on:</p>
<h1>Defining functional DNA elements in the human genome</h1>
<p>Kellis et al.&nbsp; PNAS.&nbsp; 2014.
	<br><a href="http://www.pnas.org/content/111/17/6131.long">Source</a> | <a href="http://archive.is/hFP8U">Archive.is</a>
</p>
<h3></h3>
<p>In 2014, ENCODE director Ewan Birney and 30 other researchers published a response
	to some of the critics of ENCODE:</p>
<blockquote>
	<p>biochemically active regions cover a much larger fraction of the genome than do
		evolutionarily conserved regions, raising the question of whether nonconserved
		but biochemically active regions are truly functional. Here, we review the strengths
		and limitations of biochemical, evolutionary, and genetic approaches for defining
		functional DNA segments, potential sources for the observed differences in estimated
		genomic coverage, and the biological implications of these discrepancies... Our
		results reinforce the principle that each approach provides complementary information
		and that we need to <b>use combinations of all three</b> to elucidate genome
		function in human biology and disease...</p>
	<p>estimates that incorporate alternate references, shape-based constraint, evolutionary
		turnover, or lineage-specific constraint each suggests roughly two to three times
		more constraint than previously (<b>12 - 15%</b>), and their
		union might be even larger as they each correct different aspects of alignment-based
		excess constraint.&nbsp; Moreover, the mutation rate estimates of the human genome
		are still uncertain and surprisingly low and not inconsistent with a larger fraction
		of the genome under relatively weaker constraint.&nbsp; Although still weakly powered,
		human population studies suggest that an additional <b>4 - 11%</b> of
		the genome may be under lineage-specific constraint after specifically excluding
		protein-coding regions, and these numbers may also increase as our ability to detect
		human constraint increases with additional human genomes...</p>
	<p>At present, we cannot distinguish which low-abundance transcripts are functional,
		especially for RNAs that lack the defining characteristics of known protein coding,
		structural, or regulatory RNAs... one should have high confidence that the
		subset of the genome with large signals for RNA or chromatin signatures coupled
		with strong conservation is functional and will be supported by appropriate genetic
		tests. In contrast, the larger proportion of genome with reproducible but low biochemical
		signal strength and less evolutionary conservation is challenging to parse between
		specific functions and biological noise.</p>
</blockquote>
<p>Adding 12-15% (shared with other animals) and 4-11% (that selection is maintaining
	in humans) gives a lower bound of 16-26% that is conserved in humans.</p>
<p>They also argue that the genetic approach (observing knocked-out genes to see what
	effect they have) is limited in determining function because of widespread redundancy,
	as well as difficult-to-observe phenotypes:</p>
<blockquote>
	<p>The approach may also miss elements whose phenotypes occur only in rare cells or
		specific environmental contexts, or whose effects are too subtle to detect with
		current assays. Loss-of-function tests can also be buffered by functional redundancy,
		such that double or triple disruptions are required for a phenotypic consequence.
		Consistent with redundant, contextual, or subtle functions, the deletion of large
		and highly conserved genomic segments sometimes has no discernible organismal phenotype,
		and seemingly debilitating mutations in genes thought to be indispensable have
		been found in the human population</p>
</blockquote>
<p>They also say the evolutionary approach (finding DNA sequences the same in animals)
	isn't enough on its own:</p>
<blockquote>
	although the evolutionary approach has the advantage that it does not require a priori
	knowledge of what a DNA element does or when it is used, it is unlikely to reveal
	the molecular mechanisms under selection or the relevant cell types or physiological
	processes. Thus, comparative genomics requires complementary studies.
</blockquote>
<p>As well as the limitations of the biochemical method (looking for parts of the genome
	with activity), which is the method used by ENCODE:</p>
<blockquote>
	<p>although biochemical signatures are valuable for identifying candidate regulatory
		elements in the biological context of the cell type examined, they cannot be interpreted
		as definitive proof of function on their own.</p>
</blockquote>
<p>Near the end they say they still can't reconcile the difference between evolutionary
	conservation and biochemical activity.&nbsp; In other words, evolution shouldn't
	have been able to produce this much function:</p>
<blockquote>
	<p>Thus, unanswered questions related to biological noise, along with differences in
		the resolution, sensitivity, and activity level of the corresponding assays, help
		to explain divergent estimates of the portion of the human genome encoding functional
		elements. Nevertheless, they do not account for the entire gulf between constrained
		regions and biochemical activity. Our analysis revealed a vast portion of the genome
		that appears to be evolving neutrally according to our metrics, even though it
		shows reproducible biochemical activity, which we previously referred to as “biochemically
		active but selectively neutral"</p>
</blockquote>
<?php $page_content = ob_get_clean();


// $page['content']['']
ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';