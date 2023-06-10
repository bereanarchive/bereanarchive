<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';

$page_title = 'The C-Value Paradox | Berean Archive';
$image = '/articles/biology/c-value-paradox-files/header-square.jpg';
$headerStyle = "background-image: url('c-value-paradox-files/header-wide.jpg')";
$caption = '"The Onion Test" has become a common argument of junk DNA proponents.';



// $page_content
ob_start()?>
<aside>Published: Spring 2017
	<br>Updated:
	<?=date('F j, Y', filemtime(__FILE__))?>
</aside>

<h1>The C-Value Paradox</h1>

<h2>Overview</h2>
<p>Some species have dozens or hundreds of times more DNA than other species of similar  complexity.&nbsp; Because of this, some have argued that most DNA in complex  organisms must be unused junk, and therefore they are not designed.&nbsp; There  are several good reasons to think otherwise:</p>
<aside class="companionArticle">
	<a href="/articles/biology/functional-dna-predictions">
<img src="/articles/biology/functional-dna-files/header-square.jpg?w=230"></a>
	<div>
		<a href="/articles/biology/functional-dna"><b>Abundant
		Functional DNA is Evidence of Design</b><br></a>This companion
		article presents data indicating humans have large amounts of functional
		DNA and that evolution cannot account for it.
	</div>
</aside>
<ol>
	<li>Some of the largest reported genome sizes don't report haploid genome size, or contain contamination.
	</li>
	<li>Genome size is roughly correlated with number of cell types
	</li>
	<li>Genome size correlates with cell size.
	</li>
	<li>Genome size differences may represent tradeoffs between different forms of data storage.
	</li>
	<li>In some cases, some organisms with excessively large genomes may actually have large   amounts junk DNA, created by runaway transposon duplication.</li>
	<li>But in humans (the most-studied complex animal) there is good evidence that most   DNA is functional. Therefore most mammal DNA is likely functional since mammals   are similar in complexity and mammals all have about the same amount of DNA.[^gregory-2001]
	</li>
</ol>

<h2>The Problem</h2>

<p>A C-value is the weight (in picograms) of all DNA in a haploid genome.&nbsp; That  means all the DNA among unique chromosomes, since many organisms have two or  more copies of each chromosome.&nbsp; Some organisms of similar complexity have  very different C-values.&nbsp; Therefore it was reasoned that most DNA in those  with very large genomes must be junk, since other organisms of similar complexity  could get by on so much less. Francis Crick and Leslie Orgel, 1980 were among the  first to make this argument:</p>
<blockquote>
	<p>We also have to account for the vast amount of DNA found in certain species, such   as lilies and salamanders, which may amount to as much as 20 times that found in   the human genome.&nbsp; It seems totally implausible that the number of radically   different genes needed in a salamander is 20 times that in a man.[^crick-1980]
	</p>
</blockquote>
<p>Or more recently by T. Ryan Gregory in 2014:</p>
<blockquote>
	<p>Genome size varies enormously among species: at least 7,000-fold among animals and   350-fold even within vertebrates... a human genome contains eight times more DNA   than that of a pufferfish but is 40 times smaller than that of a lungfish.&nbsp; Third,   organisms that have very large genomes are not few in number or outliers—for example,   of the &gt;200 salamander genomes analyzed thus far, all are between four and 35   times larger than the human genome.&nbsp; Fourth, even closely related species   with very similar biological properties and the same ploidy level can differ significantly   in genome size... the notion that the majority of eukaryotic noncoding DNA is functional   is very difficult to reconcile with the massive diversity in genome size observed   among species, including among some closely related taxa.[^gregory-2014]</p>
</blockquote>
<p>Because onions can have six times more DNA than humans, the C-value paradox has colloquially  been referred to as the "onion test" (a term coined by Gregory in 2007).&nbsp; However,  there are five reasons I don't think this can be used to argue that most  DNA in most eukaryotes is junk:</p>
<h2>The Solutions</h2>
<h3>1. Polyploidy and/or contamination</h3>
<p>Some large reported genome sizes don't report haploid genome size, or contain contamination.  Researchers report in 2007:</p>
<blockquote>
	<p>...the C-value for the salamander genus Ambystoma has been taken to indicate a genome   size 10-25 times larger than other vertebrates although polyploidy is known in   Ambystomatidae, and a recent genetic map suggests the salamander genome may not   be greatly dissimilar in size to other vertebrate genomes.&nbsp; The lungfish also   has a high C-value, superficially suggesting that its genome is over an order of   magnitude larger than primates, but is again known to be polyploid.&nbsp; Other   groups of organisms that exhibit a wide range of C-values, such as crustaceans   and insects, are also frequently polyploid.&nbsp; There may also be significant   measurement errors stemming from different experimental methodologies, interfering   compounds and physiological states.&nbsp; For example, there are widely differing   estimates of the DNA content of the lungfish Proteopterus aethiopicus, with measurements   ranging from 40 to 130pg...
		<br>
		<br>...amoebae are often cited as the most dramatic example of the lack of correlation   between genome size and biological complexity.&nbsp; There are may problems with   this conclusion, including a likely variation in ploidy...&nbsp; as well as   the presence of significant amounts of contaminating DNA from their prey.&nbsp; The   amoeba genome is probably smaller than 20 pg, far less than the 700 pg commonly   cited.[^taft-2007]
	</p>
</blockquote>
<p>Likewise from Lui et al, 2013:</p>
<blockquote>
	<p>...our analysis is focused on haploid genome composition, thus removing the confounding   factor of ploidy or the contaminating DNA of prey, which are likely to be the primary   cause of the large genome sizes attributed to lungfish and amoeba, respectively.[^lui-2013]</p>
</blockquote>
<p>But what good is having higher levels polyploidy?&nbsp; Luca Comai, 2005 offers  three advantages:</p>
<blockquote>
	<p>the advantages of polyploidy are caused by the ability to make better use of heterozygosity,   the buffering effect of gene redundancy on mutations and, in certain cases the   facilitation of reproduction through self-fertilization or asexual means.[^comai-2005]</p>
</blockquote>
<p>Likewise, a study in 2004 observed that plant "tetraploids generally grew at higher altitudes  than the diploids."[^suda-2004]&nbsp; Perhaps due to a shorter growing season these  plants need to get all their transcription done a little faster than their diploid  cousins.&nbsp; Although higher levels of ploidy also come with disadvantages--thus  a tradeoff:</p>
<blockquote>
	<p>the disrupting effects of nuclear and cell enlargement, the propensity   of polyploid mitosis and meiosis to produce aneuploid cells and the epigenetic   instability that results in transgressive (non-additive) gene regulation.[^comai-2005]
	</p>
</blockquote>
<p>However, misreporting and polyploidy certainly don't explain all variations  in C-value among organisms.</p>
<h3>2. Genome size roughly correlates with number of cell types</h3>
<p>This figure from Lui et al, 2013[^lui-2013] shows the ratio of noncoding  (NC) DNA to total genome size (TG) in various organisms (A) and the percentage of  non-coding DNA versus the number of cell types (B).
	<img src="c-value-paradox-files/non-coding-vs-cell-types.png" class="wide rounded inverted">
</p>
<p>In the figures above, viridiplantae are algae and plants, while metazoa are animals,  with vertebrates being within the deuterostomia. It makes sense that more complex  organisms (measured by number of cell types) would require larger genomes.</p>
<p>This figure from the same paper[^lui-2013] also shows the distribution,  although note that the top axis is not linear:</p>
<p>
	<img src="c-value-paradox-files/lui-2013-genome-size-vs-cell-types.png" class="inverted" style="width: 100%;">
</p>
<p>However, there are some notable outliers, as can be seen in this image.&nbsp; Note  that the bottom axis is logarithmic.&nbsp; 1 pg (picogram - one trillionth of a  gram) is about 1 billion letters of DNA:[^memim-2017]</p>
<p>
	<img src="c-value-paradox-files/c-value-ranges.jpg" class="inverted" style="width: 100%;">
</p>

<p>Genome researcher John Mattick explains that rather in protein coding genes, it seems  that larger genomes have more complexity in their non-coding DNA:</p>
<blockquote>
	<p>It was originally assumed that as complexity increased there would be more and more   such genes - before the genome was sequenced there was speculation that humans   might have a hundred thousand or more, and it was a huge shock that it's much less,   and doesn't scale with complexity. But there are very large numbers of long non-coding   RNAs, so this is where the real genetic scaling has occurred.[^mattick-2010]</p>
</blockquote>
<h3>3. Genome size correlates with cell size</h3>
<p>The figure below shows a correlation between genome size and cell size in various  taxa, composited from Beaulieau et al 2008 figure 3[^beaulieu-2008] and Gregory  2001 figures 1 and 3.[^gregory-2001]&nbsp; "Angiosperms" iincludes all flowering  plants, including flowering trees.&nbsp; Reptiles, birds, and mammals show a weaker  correlation because their cell sizes vary less than the other groups.
</p>
<p>
	<img src="c-value-paradox-files/dna-cell-size.png" class="wide inverted">
</p>
<p>Cavalier-Smith and Beaton, 1999 explain that more DNA is needed in larger cells  to account for greater rates of production:</p>
<blockquote>
	<p>The situation is like that of a car factory aiming for a steady output of cars:&nbsp;   engines, wheels and doors must be made at the same rate; if overall output   is to be increased the number of each must be increased by the same proportion.&nbsp;   Moreover, if each robot, machine tool, and operative is already working at   maximal rates, one can increase output only by increasing the number of assembly   lines.&nbsp; As these take up space the factory also has to be larger.&nbsp; In   a cell the nucleus is the production line for RNA molecules.&nbsp; To produce more   per cell cycle one must have more copies of RNA polymerases and more copies of   spliceosomes and other processing machinery, e.g. mRNA capping machinery; both   of these take up space, as do nascent RNAs as well as those being processed and   in transit towards the nuclear pores.&nbsp; Thus nuclei have to be larger in larger   cells.[^cavalier-smith-1999]
	</p>
</blockquote>
<p>However, production rate is only one factor and overall the explanation  is not that simple, since cells with larger genomes don't always have  more copies of protein-coding genes.[^gregory-2001]
</p>
<h3>4. Genome size versus efficiency tradeoffs</h3>
<p>Among our own designs it's common to see tradeoffs between size, speed, and other  limitingn factors.&nbsp; A familiar example is in image compression where decoding  and encoding speed also come into play.&nbsp; The png image format offers a small  size without loss of image quality, but most computers are not fast enough to record  a video where every image is saved as a png file in real time.&nbsp; </p>
<p>The following table shows file sizes for an 8 megapixel photo of diced onion, using  different image encoding formats:</p>
	<aside>
		<img src="c-value-paradox-files/onion-small.jpg" alt="" class="rounded" id="img1" style="width: 100%;">
	</aside>
	<table class="style1" style="width: 100%;">
		<tbody>
			<tr>
				<td>File</td>
				<td>Size </td>
				<td>Quality </td>
				<td>CPU Time</td>
			</tr>
			<tr>
				<td><a href="c-value-paradox-files/onion.jpg">onion.jpg</a>
				</td>
				<td>398,672 bytes</td>
				<td>Lossy (poor)</td>
				<td>Medium</td>
			</tr>
			<tr>
				<td><a href="c-value-paradox-files/onion.png">onion.png</a>
				</td>
				<td>11,196,084 bytes</td>
				<td>Lossless (best)</td>
				<td>Slow&nbsp; &nbsp; </td>
			</tr>
			<tr>
				<td><a href="c-value-paradox-files/onion.bmp">onion.bmp</a>
				</td>
				<td>23,780,576 bytes</td>
				<td>Lossless (best)</td>
				<td>Fast</td>
			</tr>
		</tbody>
	</table>
	<p>
		<br>
	</p>
	<aside>
		<img src="c-value-paradox-files/titanfall.jpg" class="rounded" style="width: 100%;">
	</aside>
	<p>The PC and Xbox One game Titanfall provides another size versus speed tradeoff.&nbsp;   The PC enthusiast website Tom's Hardware explains:</p>
	<blockquote>
		<p>Want to know why the just-released Titanfall shooter is such a hefty install on    the PC? Blame it on the lower-end machines. The Xbox One version of Titanfall    is a mere 17 GB, but the PC version eats up around 48 GB of hard drive space,    35 GB of which is all uncompressed audio so that lower-end machines aren't bogged    down with decompressing audio.[^parish-2014]
		</p>
	</blockquote>
	<p>An opposite extreme can be seen in the PC game .kkrieger,[^kkrieger] a first person   shooter with detailed graphics and sounds that requires only 96KB of disk space--500,000   times smaller than TitanFall, and a shocking three times smaller than the onion.jpg   above.&nbsp; The .kkreiger developers created all sound effects, music, textures,   and 3D models through fractals--the tradeoff being a lack of nuanced control   over artistic assets.</p>
	
	<p>
		<iframe width="100%" height="450" src="https://www.youtube.com/embed/Gf8Zcz5c7t4?rel=0&amp;t=19s" frameborder="0" allowfullscreen=""></iframe>
	</p>
	<p>We may see a similar size versus fidelity tradeoff in genomes via alternate   splicing.&nbsp; One ENCODE researcher explained:</p>
	<blockquote>
		<p>Organism introduce genetic variation in different ways. For instance, in Drosophila,    a 100 kilobase gene (DSCAM) encode thousands of different proteins through a complex    alternate splicing mechanism. One could envision copying each of these transcripts    - without the alternate splicing - into the genome thus increasing the size of    the genome by 10 million bases, or roughly 10%, but not changing the complexity    at all.[^everywhere-2013]
		</p>
	</blockquote>
	<p>This is similar to how our own compression algorithms operate--frequently used sequences   are stored only once and re-referenced, as opposed to the same information being   stored multiple times.&nbsp; We can imagine some cases where there are many copies   of a sequence in a genome, each slightly different and optimal for its use case.   While in other genomes those copies may be condensed into a smaller number of sequences   assembled through alternate splicing.&nbsp; Many specialized sequences become a   few common sequences--like the fidelity vs size tradeoff as we see in jpeg   images. In plants we see that smaller genome sizes can offer the benefit of faster   replication speed: "there is a striking connection between DNA content per   cell and the minimum generation time of the plant."[^crick-1980]
	</p>
	<h3>5.&nbsp; Runaway Transposon Duplication</h3>
	<p>Transposable elements (also known as transposons or TE's) are stretches of   DNA that can copy and move (transpose) themselves to new locations in a genome.&nbsp;   They increase their number in doing so and can even copy themselves relatively   quickly.&nbsp; In some taxonomically restricted cases of large genomes, the excess   may actually be due to runaway transposon duplication and would therefore be true   junk DNA.&nbsp; This may be the case with onions of the genus allium, which range   in genome size from "7 pg to 31.5 pg"[^gregory-2007]&nbsp; It may also be   the case that organisms with similar genomes (such as the various species of salamanders)   are prone to the same factors leading to runaway transposon duplication.</p>
	<p>However, the cases like onions cannot be used to argue that most DNA in most   eukaryotes is junk.&nbsp; Mammals for example all have close to the same genome   size.</p>
		
		
	<h2>Sources</h2>
	<div class="footnotes">
		<ol>
			<li>[^crick-1980]:Crick, Francis and Leslie Orgel.&nbsp; "<a href="http://profiles.nlm.nih.gov/ps/access/SCBCDG.pdf">Selfish DNA: The Ultimate Parasite</a>."&nbsp;       Nature.&nbsp; 1980.</li>
			<li>Mirrors:&nbsp; <a href="https://web.archive.org/web/20150919160849/http://profiles.nlm.nih.gov/ps/access/SCBCDG.pdf">Archive.org</a> | <a href="/articles/reviews/crick-orgel-selfish-dna-1980">Local excerpt with comment</a>
			</li>
			<li>[^gregory-2014]:Gregory, T. Ryan and Alexander Palazzo.&nbsp; "<a href="http://www.plosgenetics.org/article/info%3Adoi%2F10.1371%2Fjournal.pgen.1004351">The Case for Junk DNA</a>."&nbsp; PLOS Genetics.&nbsp;      2014.
				<span class="comment">Mirrors:&nbsp; <a href="https://web.archive.org/web/20160514080645/http://journals.plos.org/plosgenetics/article?id=10.1371/journal.pgen.1004351">Archive.org</a> | <a href="/articles/reviews/gregory-the-case-for-junk-dna-2014.php">Local excerpts with notes</a></span>
			</li>
			<li>[^taft-2007]:Taft, Ryan J. et al.&nbsp; "<a href="https://www.ncbi.nlm.nih.gov/pubmed/17295292">The relationship between non-protein-coding DNA and eukaryotic complexity</a>."&nbsp; BioEssays.&nbsp; 2007.      Mirrors:&nbsp; <a href="http://salamander.uky.edu/srvoss/425SP08/Taftetal.pdf">University of Kentucky</a>
			</li>
			<li>[^lui-2013]:Lui, Guosheng et al.&nbsp; "<a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC3737309/">A meta-analysis of the genomic and transcriptomic composition of complex life</a>."&nbsp;      Cell Cycle.&nbsp; 2013.</li>
			<li>[^comai-2005]:Comai, Luca.&nbsp; "The advantages and disadvantages of being polyploid." Nature.&nbsp; 2005.
			</li>
			<li>[^suda-2004]:Suda, Jan et al.&nbsp; "<a href="https://link.springer.com/article/10.1007/BF02805244">Cytotype Distribution in Empetrum (ericaceae) at Various Spatial Scales in the Czech Republic</a>."&nbsp;      Folia Geobotanica.&nbsp; 2004.<span class="comment">Mirrors:&nbsp; <a href="http://www.marekbanas.com/soubory/soubor9.pdf">marekbanas.com</a></span>
			</li>
			<li>[^memim-2017]:Memim Encyclopedia.&nbsp; "<a href="https://memim.com/c-value.html">C-Value</a>."&nbsp;      2017.
			</li>
			<li>[^cavalier-smith-1999]:Cavalier-Smith, T. et al.&nbsp; "<a href="http://books.google.com/books?id=tVnxXiIB4CAC&amp;pg=PA8&amp;dq=%22The+situation+is+like+that+of+a+car+factory+aiming+for+a+steady+output+of+cars%22&amp;source=bl&amp;ots=nOZHxZYquW&amp;sig=ESPrrrlYyjM6yIzuBuMa0oURo1A&amp;hl=en&amp;sa=X&amp;ei=cmaiU4_VKcqgyATHnIHIAw&amp;ved=0CB8Q6AEwAA#v=onepage&amp;q=%22The%20situation%20is%20like%20that%20of%20a%20car%20factory%20aiming%20for%20a%20steady%20output%20of%20cars%22&amp;f=false">The skeletal function of non-genic nuclear DNA: new evidence from ancient cell chimeras</a>."&nbsp;      In "Structural Biology and Functional Genomics."&nbsp; Springer.&nbsp; 1999.</li>
			<li>[^beaulieu-2008]:Beaulieu, Jeremy M. et al.&nbsp; "<a href="http://onlinelibrary.wiley.com/doi/10.1111/j.1469-8137.2008.02528.x/full">Genome size is a strong predictor of cell size and stomatal density in angiosperms</a>."&nbsp;      New Phytologist.&nbsp; 2008.</li>
			<li>[^gregory-2001]:Gretory, T. Ryan.&nbsp; "<a href="https://www.ncbi.nlm.nih.gov/labs/articles/11783946/">The Bigger the C-Value, the Larger the Cell</a>."&nbsp;      Blood Cells, Molecules, and Diseases.&nbsp; 2001.</li>
			<li>[^toms-hardware-2014]: "<a href="http://www.tomshardware.com/news/respawn-titanfall-pc-gaming-install-electronic-arts,26275.html">Why Titanfall's Install Requires 48 GB: Uncompressed Audio</a>."&nbsp;      Tom's Hardware.&nbsp; 2014.</li>
			<li>[^everywhere-2013]:Reddit user west_of_everywhere.&nbsp; "<a href="https://www.reddit.com/r/askscience/comments/1l3zfx/how_come_theres_a_amoeba_with_200_times_larger/cbvsn0n/">Comment on How come there's a Amoeba with 200 times larger gene set than humans?</a>"&nbsp; Reddit.&nbsp; 2013.
			</li>
			<li>[^gregory-2007]:Gregory, T. Ryan.&nbsp; "<a href="http://www.genomicron.evolverzone.com/2007/04/onion-test/">The onion test</a>."&nbsp;      Genomicon Blog.&nbsp; 2007.</li>
			<li>[^mattick-2010]:Mattick, John.&nbsp; "<a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC2905358/">Video Q&amp;A: Non-coding RNAs and eukaryotic evolution - a personal view</a>."&nbsp; BMC Biol.&nbsp; 2010.
			</li>
			<li>[^parish-2014]:Parrish, Kevin.&nbsp; "<a href="http://www.tomshardware.com/news/respawn-titanfall-pc-gaming-install-electronic-arts,26275.html">Why Titanfall's Install Requires 48 GB: Uncompressed Audio</a>."&nbsp;      Tom's Hardware.&nbsp; 2014.</li>
			<li>[^kkrieger]:<a href="https://web.archive.org/web/20120204065621/http://www.theprodukkt.com/kkrieger">.kkrieger official website</a>.      2012.</li>
		</ol>
	</div>
<?php $page_content = ob_get_clean();


require_once 'common/includes/theme.php';
