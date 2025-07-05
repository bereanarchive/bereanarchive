<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/sitecrafter/init.php';





$page_title = "Comment on:  T. Ryan Gregory and Alexander Palazzo,PLOS Genetics, 2014 | Berean Archive";
$page_theme= 'common/includes/theme-comments.php';
$page_head = '';

//$page_content
ob_start()?>
<div class="authorship">Published: Jan 2017
	<br>Updated:
	<?=date('F j, Y', filemtime(__FILE__))?>
</div>
<p>Comment on:</p>
<h3><br></h3>
<h1>The Case for Junk DNA </h1>
<p>T. Ryan Gregory and Alexander Palazzo,PLOS Genetics, 2014
	<br><a href="http://www.plosgenetics.org/article/info%3Adoi%2F10.1371%2Fjournal.pgen.1004351"
	>Source</a> | <a href="http://web.archive.org/web/20160314131246/http://journals.plos.org/plosgenetics/article?id=10.1371/journal.pgen.1004351">Archive.org</a> |
	<a
	href="http://archive.is/446GQ">Archive.is</a>
</p>
<p>T. Ryan Gregory is an evolutionary and genome biologist.&nbsp; In 2014 he and biochemist
	Alexander Palazzo, argued in favor of large amounts of junk DNA, pulling in
	nearly every argument previously used by others:</p>
<h2>Neutral Theory</h2>
<blockquote>
	<p>Several analyses of sequence conservation between humans and other mammals have
		found that about 5% of the genome is conserved. It is possible that an additional
		4% of the human genome is under lineage-specific selection pressure ... the
		idea that 9% of the human genome shows signs of functionality is actually consistent
		with the results of ENCODE and other large-scale genome analyses...</p>
	<p>Kimura, Ohta, King, and Jukes... demonstrated that alleles that were slightly beneficial
		or deleterious behaved like neutral alleles, provided that the absolute value of
		their selection coefficient was smaller than the inverse of the “effective” population
		size...For humans it has been estimated that the historical effective population
		size is approximately 10,000...Given the overall low figures for multicellular
		organisms in general, we would expect that natural selection would be powerless
		to stop the accumulation of certain genomic alterations over the entirety of metazoan
		[meaning animal] evolution.</p>
</blockquote>
<p>In other words, any mutation that has an effect on fitness less than 10<sup>-5</sup> (1/10,000)
	will become fixed in the population, regardless of whether it's beneficial or deleterious--which
	would likely be the majority of mutations. Otherwise we would see stronger effects
	from the 100 or so we get per human generation.&nbsp; And because so little DNA
	is shared with other mammals, neutral evolution could not have generated large amounts
	of functional difference.</p>
<h2>Genetic Load</h2>
<blockquote>
	<p>It has long been appreciated that there is a limit to the number of deleterious
		mutations that an organism can sustain per generation. The presence of these mutations
		is usually not harmful, because diploid organisms generally require only one functional
		copy of any given gene. However, if the rate at which these mutations are generated
		is higher than the rate at which natural selection can weed them out, then the
		collective genomes of the organisms in the species will suffer a meltdown as the
		total number of deleterious alleles increases with each generation. This rate is
		approximately one deleterious mutation per generation. In this context it becomes
		clear that the overall mutation rate would place an upper limit to the amount of
		functional DNA. Currently, the rate of mutation in humans is estimated to be anywhere
		from 70–150 mutations per generation. By this line of reasoning, we would estimate
		that, at most, only 1% of the nucleotides in the genome are essential for viability
		in a strict sequence-specific way. However, more recent computational models [Keightley's
		<a
		href="http://www.genetics.org/content/190/2/295.full">A resolution of the Mutational Load Paradox</a>, 2012] have demonstrated that genomes
			could sustain multiple slightly deleterious mutations per generation. Using statistical
			methods, it has been estimated that humans sustain 2.1–10 deleterious mutations
			per generation. These data would suggest that at most 10% of the human genome
			exhibits detectable organism-level function and conversely that<b> at least 90% of the genome consists of junk DNA</b>.
			These figures agree with measurements of genome conservation (~9%, see above)
			and are incompatible with the view that 80% of the genome is functional in the
			sense implied by ENCODE.</p>
</blockquote>
<h2>Selfish Genes</h2>
<blockquote>
	<p>Because of their capacity to increase in copy number, transposable elements have
		long been described as “parasitic” or “selfish”. However, the vast majority of
		these elements are inactive in humans, due to a very large fraction being highly
		degraded by mutation. Due to this degeneracy, estimates of the proportion of the
		human genome occupied by TEs [transposable elements] has varied widely, between
		one-half and two-thirds... there is evidence of organism-level function for only
		a tiny minority of TE sequences. It is therefore not obvious that functional explanations
		can be extrapolated from a small number of specific examples to all TEs within
		the genome.</p>
</blockquote>
<h2>C-Value Paradox</h2>
<p>Although elsewhere Gregory has preferred the term "C-value enigma"</p>
<blockquote>
	<p>genome size varies enormously among species: at least 7,000-fold among animals and
		350-fold even within vertebrates... a human genome contains eight times more DNA
		than that of a pufferfish but is 40 times smaller than that of a lungfish. Third,
		organisms that have very large genomes are not few in number or outliers—for example,
		of the &gt;200 salamander genomes analyzed thus far, all are between four and 35
		times larger than the human genome. Fourth, even closely related species with very
		similar biological properties and the same ploidy level can differ significantly
		in genome size... the notion that the majority of eukaryotic noncoding DNA
		is functional is very difficult to reconcile with the massive diversity in genome
		size observed among species, including among some closely related taxa.</p>
</blockquote>
<h2>Appears to not do anything</h2>
<blockquote>
	<p>The majority of human DNA consists of repetitive, mutationally degraded sequences.
		&nbsp;There are unambiguous examples of nonprotein-coding sequences of various
		types having been co-opted for organism-level functions in gene regulation, chromosome
		structure, and other roles, but at present evidence from the published literature
		suggests that these represent a small minority of the human genome.</p>
</blockquote>
<?php $page_content = ob_get_clean();


ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';