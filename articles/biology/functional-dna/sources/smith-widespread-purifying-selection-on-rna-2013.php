<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';





$page['title'] = 'Comment on: Widespread purifying selection on RNA structure in mammals | Berean Archive';
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
<h1>Widespread purifying selection on RNA structure in mammals</h1>
<p>Martin Smith, Tanja Gesell, Peter F. Stadler, and John Mattick.&nbsp; Nucleic Acid
	Research.&nbsp; 2013.&nbsp;
	<br><a href="https://academic.oup.com/nar/article/41/17/8220/2411364/Widespread-purifying-selection-on-RNA-structure-in">Source</a> |&nbsp;
	<a href="http://archive.is/ZuR7p">Archive.is</a>
</p>
<div class="source" id="smith-2013">
	<p>In 2013, John Mattick and three other biologists published a study comparing
		RNA structure across mammals, finding that 13.6% to 30% of it was conserved independent
		of DNA sequence:</p>
	<blockquote>
		<p>When applied to consistency-based multiple genome alignments of 35 [placental and
			marsupial, including including bats, mice, pigs, cows, dolphins and human] mammals,
			our approach confidently identifies &gt;4 million evolutionarily constrained RNA
			structures using a conservative sensitivity threshold that entails historically
			low false discovery rates for such analyses (5–22%). These predictions comprise
			13.6% of the human genome, 88% of which fall outside any known sequence-constrained
			element, suggesting that a large proportion of the mammalian genome is functional.</p>
		<p
		>...</p>
			<p>Our findings provide an additional layer of support for previous reports advancing
				that &gt;20% of the human genome is subjected to evolutionary selection while
				suggesting that additional evidence for function can be uncovered through careful
				investigation of analytically involute higher-order RNA structures. Furthermore,
				our approach entails an empirically determined false discovery rate well below
				that reported in previous endeavors (i.e. 5–22% versus 50–70%), supporting the
				widespread involvement of RNA secondary structure in mammalian evolution.</p>
			<p>...</p>
			<p>the RNA structure predictions we report using conservative thresholds are likely
				to span<b> &gt;13.6%</b> of the human genome we report.&nbsp; This number
				is probably a substantial underestimate of the true proportion given the conservative
				scoring thresholds employed, the neglect of pseudoknots, the liberal distance
				between overlapping windows and the incapacity of the sliding-window approach
				to detect base-pair interactions outside the fixed window length. A less conservative
				estimate would place this ratio somewhere<b> above 20%</b> from the
				reported sensitivities measured from native RFAM alignments and <b>over 30%</b> from
				the observed sensitivities derived from sequence-based realignment of RFAM data.
				&nbsp;Our data complement recent findings from the ENCODE consortium, which report
				that 74.7% of the human genome is transcribed in multiple cell lines and that
				many novel unannotated genes are detected when sequencing RNA from subcellular
				compartments.
			</p>
			<p>...</p>
			<p>The resulting ECS [evolutionarily conserved structure] predictions encompass 18.5%
				of the surveyed alignments that, in turn, span across 84.1% of the human genome.</p>
			<p>...</p>
			<p>With regards to the genomic distribution of hits, the majority of predictions
				lie within intronic and intergenic regions [see figure 4B]... Predictions
				are roughly 2-fold enriched (odds ratio) in annotated exons, with the highest
				enrichment observed in protein-coding regions... about half of the ECS predictions
				we report overlap repeat elements... The prevalence of ECS structures is
				strongly enriched in Alu elements, which are known to form conserved RNA secondary
				structures.
			</p>
			<p>...</p>
			<p>the majority (87.8%) of the ECS predictions reported herein lie outside annotated
				sequence-constrained elements </p>
	</blockquote>
	<p>The paper was long and highly technical, but thankfully it was summarized in <a href="http://www.garvan.org.au/news-events/news/new-insight-into-the-human-genome-through-the-lens-of-evolution">a press release</a>:</p>
	<blockquote>
		<p>While other studies have shown that around 5-8% of the genome is conserved at the
			level of DNA sequence, indicating that it is functional, the new study shows that
			in addition much more, possibly up to 30%, is also conserved at the level of RNA
			structure.
		</p>
	</blockquote>
	<p>Conserved sequences (those shared with other mammals) only provide a lower estimate
		to how much is functional.&nbsp; They also comment that RNA's require a specific
		structure to be functional:</p>
	<blockquote>
		<p>the nucleic acids that make up RNA connect to each other in very specific ways,
			which force RNA molecules to twist and loop into a variety of complicated 3D structures.</p>
	</blockquote>
	<p>But how could RNA secondary structure be conserved if the DNA encoding for it is
		not?&nbsp; <a href="http://www.ncbi.nlm.nih.gov/pmc/articles/PMC2689613/">Analysis of the fitness effect of compensatory mutations</a> (HSFP
		J, 2009) may explain:</p>
	<p></p>
	<blockquote>
		<p>It is well known that the folding of RNA molecules into the stem-loop structure
			requires base pair matching in the stem part of the molecule, and mutations occurring
			to one segment of the stem part will disrupt the matching, and therefore, have
			a deleterious effect on the folding and stability of the molecule. It has been
			observed that mutations in the complementary segment can rescind the deleterious
			effect by mutating into a base pair that matches the already mutated base, thus
			recovering the fitness of the original molecule (Kelley et al., 2000; Wilke et
			al., 2003).</p>
	</blockquote>
	<p>
		<img src="smith-2013-rna-stem-loop.png" width="257" style="float: right;">
	</p>
	<p>This diagram from Wikipedia (CC BY-SA 3.0 license) helps illustrate.&nbsp; If
		a base on the top mutates, a base on the bottom can also mutate to match it again,
		which maintains the same secondary structure.</p>
</div>
<?php $content = ob_get_clean();


// $page['content']['']
ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';