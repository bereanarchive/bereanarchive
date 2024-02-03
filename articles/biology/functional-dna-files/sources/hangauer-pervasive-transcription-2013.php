<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/sitecrafter/init.php';





$page_title = "Comment on Hangauer et al 2013 | Berean Archive";
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
<h1>Pervasive Transcription of the Human Genome Produces Thousands of Previously Unidentified Long Intergenic Noncoding RNAs</h1>
<p>Matthew Hangauer, Ian W. Vaughn, and Michael T. McManus
	<br><a href="http://journals.plos.org/plosgenetics/article?id=10.1371/journal.pgen.1003569">Source</a> |
	<a href="http://archive.is/VLHiU">Archive.is</a>
</p>
<p>In 2013, Matthew Hangauer and Ian Vaughn published a study on lincRNAs, finding an
	even higher level of transcription than reported by ENCODE:</p>
<blockquote>
	<p>Here, by analyzing a large set of RNA-seq data, we found that &gt;85% of the genome
		is transcribed,</p>
</blockquote>
<p>They looked at a specific class of RNA transcripts known as lincRNAs and found them
	to be highly functional:</p>
<blockquote>
	<p>A key question in the field is whether these intergenic transcripts are functional
		or transcriptional noise. We found that the lincRNAs we identified have many characteristics
		that are inconsistent with noise, including specific regulation of their expression,
		the presence of conserved sequence and evidence for regulated processing. Furthermore,
		these lincRNAs are strongly enriched with intergenic sequences that were previously
		known to be functional in human traits and diseases...</p>
	<p>At a threshold of one RNA-seq read, we observed reads mapping to 78.9% of the genome
		and, if additional evidence of transcription is taken into account including the
		full structures of known genes, spliced ESTs and cDNAs, we found evidence that<b> 85.2% of the genome is transcribed</b> (Figure
		1A). This result closely agrees with the recently published findings from the ENCODE
		project in which evidence for transcription of 83.7% of the genome was uncovered.
		Interestingly, even with 4.5 billion mapped reads, we observe an increase in genomic
		coverage at each lower read threshold implying that even more read depth may reveal
		yet higher genomic coverage...</p>
	<p>These observations clearly demonstrate that the human genome is pervasively transcribed,
		and that lincRNAs make up an extremely common class of intergenic transcripts...</p>
	<p>In order to minimize any potential contamination of the lincRNA catalog with protein
		coding transcripts, the filtering approach used was very aggressive. In fact, most
		previously annotated noncoding RNAs failed to pass our filters and were therefore
		excluded from the lincRNA catalog...</p>
	<p>Replicates of each tissue type strongly clustered together, indicating that lincRNA
		differential expression is indeed reproducibly tissue-specific, supporting specific
		regulation of lincRNA expression.</p>
</blockquote>
<?php $page_content = ob_get_clean();


// $page['content']['']
ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';