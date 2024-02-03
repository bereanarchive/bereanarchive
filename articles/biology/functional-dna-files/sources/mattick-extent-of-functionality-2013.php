<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/sitecrafter/init.php';





$page_title = 'Comment on: The extent of functionality in the human genome | Berean Archive';
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
<h1>The Extent of Functionality in the Human Genome</h1>
<p>John Mattick and Martin Dinger.&nbsp; HUGO.&nbsp; 2013.&nbsp;
	<br><a href="https://thehugojournal.springeropen.com/articles/10.1186/1877-6566-7-2">Source</a> |&nbsp;
	<a href="http://archive.is/ONipd">Archive.is</a>
</p>
<h3></h3>
<p>In July 2013, biochemist John Mattick and John Dinger responded to claims raised
	by several ENCODE critics. On the C-Value paradox:</p>
<blockquote>
	<p>However, where data is available, these upward exceptions appear to be due to polyploidy
		and/or varying transposon loads (of uncertain biological relevance), rather than
		an absolute increase in genetic complexity</p>
</blockquote>
<p>On level of function, they noted:</p>
<blockquote>
	<p>the vast majority of the mammalian genome is differentially transcribed in precise
		cell-specific patterns to produce large numbers of intergenic, interlacing, antisense
		and intronic non-protein-coding RNAs, which show dynamic regulation in embryonal
		development, tissue differentiation and disease, with even regions superficially
		described as ‘gene deserts’ expressing specific transcripts in particular cells.
		Moreover, there is increasing evidence of their functional relevance and that a
		major function of these noncoding RNAs is to guide chromatin-modifying complexes
		to their sites of action, to supervise the epigenetic trajectories of development... we
		would submit that differential expression (including extensive alternative splicing)
		of RNAs is a far more accurate guide to the functional content of the human genome
		than logically circular assessments of sequence conservation, or lack thereof.
		&nbsp;Assertions that the observed transcription represents random noise (tacitly
		or explicitly justified by reference to stochastic (‘noisy’) firing of known, legitimate
		promoters in bacteria and yeast), is more opinion than fact and difficult to reconcile
		with the exquisite precision of differential cell- and tissue-specific transcription
		in human cells. Moreover, where tested, these noncoding RNAs usually show evidence
		of biological function in different developmental and disease contexts, with, by
		our estimate, hundreds of validated cases already published and many more en route,
		which is a big enough subset to draw broader conclusions about the likely functionality
		of the rest.&nbsp; It is also consistent with the specific and dynamic epigenetic
		modifications across most of the genome, and concurs with the ENCODE conclusion
		that 80% of the genome shows biochemical indices of function</p>
</blockquote>
<p>They also accuses critic Dan Graur of being motivated by an anti-ID bias:</p>
<blockquote>
	<p>There may also be another factor motivating the Graur et al. and related articles
		(van Bakel et al. 2010; Scanlan 2012), which is suggested by the sources and selection
		of quotations used at the beginning of the article, as well as in the use of the
		phrase “evolution-free gospel” in its title (Graur et al. 2013): the argument of
		a largely non-functional genome is invoked by some evolutionary theorists in the
		debate against the proposition of intelligent design of life on earth, particularly
		with respect to the origin of humanity. In essence, the argument posits that the
		presence of non-protein-coding or so-called ‘junk DNA’ that comprises &gt;90% of
		the human genome is evidence for the accumulation of evolutionary debris by blind
		Darwinian evolution, and argues against intelligent design, as an intelligent designer
		would presumably not fill the human genetic instruction set with meaningless information
		(Dawkins 1986; Collins 2006). This argument is threatened in the face of growing
		functional indices of noncoding regions of the genome, with the latter reciprocally
		used in support of the notion of intelligent design and to challenge the conception
		that natural selection accounts for the existence of complex organisms (Behe 2003;
		Wells 2011).</p>
</blockquote>
<?php $page_content = ob_get_clean();


// $page['content']['']
ob_start()?>

<?php $page['content'][''] = ob_get_clean();



require_once 'common/includes/theme-comments.php';