<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';


$page_title       = 'Contributing Guidelines | Berean Archive';
$headerStyle = "background-image: linear-gradient(transparent, transparent, rgba(0, 0, 0, .5)), url('/contribute-files/header-wide.jpg'); background-position: 75% 50%";
$sideBars    = false;


// $page['content']['main']
ob_start()?>
<h1>Contributing Guidelines</h1>

<p>These are <b>incomplete</b> guidelines for contributing or improving articles on The Berean Archive.</p>

<h2>Source Code</h2>

<p>The entire site's source code, including all articles, is <a href="https://github.com/bereanarchive/bereanarchive">published on GitHub</a>.
	If you'd like to correct, improve, or add new content, <a href="/contact">contact us</a> and send a pull request.
</p>

<p>Articles can be written in either html (with a little bit of php to help) or in Markdown, with the latter being the preferred format for new articles.  Markdown files are
	automatically converted to html on page load, via the <code>.htaccess</code> and <code>md2html.php</code> code
	files in the root of the site.&nbsp; <a href="https://www.typora.io/">Typora</a> is a decent and free editor for Markdown Files and supports most features used.&nbsp;
	HTML tags can be embedded in the .md files to go beyond features supported by Markdown.</p>

<h3>Examples</h3>
<p>The HIV Evolution  article is the most up-to-date example that
	conforms to this specification.&nbsp; Some other articles don't yet conform because they
	were written prior to this specification.
</p>

<ul>
	<li><a href="/articles/biology/hiv-evolution.md">hiv-evolution.md source code</a></li>
	<li><a href="/articles/biology/hiv-evolution">HIV Evolution article</a></li>
</ul>


<h2>Article Format</h2>
<p>Except where it otherwise doesn't make sense to do so, most articles should use the
	following layout:</p>


<h3>Layout</h3>
<ol class="compact">
	<li>Large image header</li>
	<li>Meta info:&nbsp; authors, created, modified, history</li>
	<li>Summary with short list of key points.
	<li>Detailed Points 1..N</li>
	<li>Common Objections</li>
	<li>Issues and Unanswered Questions (if applicable)</li>
	<li>Further Reading</li>
	<li>Related Articles</li>
	<li>Footnotes</li>
</ol>
<p>More detailed sub-points can be summarized in one article with a link to a more detailed
	article.&nbsp; Likewise with large blocks of supplemental information.</p>


<h3>Writing Guidelines:</h3>
<ol class="compact">
	<li>Make ample use of tables, lists, charts, and images to summarize information and
		to make article more readable.</li>
	<li>If possible, embed javascript tools to try out calculations or show adjustable graphs.</li>
	<li>Within articles, use either citations inline or shortened versions of quotes.&nbsp; Include or
		link to full quotes in sources.</li>
	<li>NRSV should be the first choice for bible citations, since it is most commonly cited
		in academia.</li>
</ol>


<h2>Citations</h2>
<h3>Berean Modified MLA Rules</h3>
<p>The main goal of citations is to make it easy to find and very sources.&nbsp; The
	Berean Modified MLA citation format is based on <a href="https://www.mla.org/MLA-Style/What-s-New-in-the-Eighth-Edition">MLA version 8</a>,
	with modifications are made to replace less helpful information with the more helpful:</p>

<h4>Omissions:</h4>
<ol class="compact">
	<li>Website name and Publisher may be omitted if they are not significant.</li>
	<li>Access dates should be omitted for brevity.</li>
	<li>Since all journal articles names should be a link to their source, volume and issue numbers
		may be omitted.</li>
	<li>Words like Web and Print should be omitted--including page numbers indicates the
		source was originally in print.</li>
	<li>Long article names may be abbreviated, or subtitles omitted.</li>
</ol>
<h4>Dates:</h4>
<ol class="compact">
	<li>Format dates as Feb 7, 2005 since it's more conventional.
	</li>
</ol>
<h4>Links and Pages:</h4>
<ol class="compact">
	<li>Any online content should have its title link to it source.&nbsp;  If page numbers,
		or audio/video seek time can be linked, they should be linked instead.</li>
	<li>All longer audio and video sources should include the minutes and seconds, linked
		if possible.&nbsp; E.g. "<a href="#">At 2:23</a>
		" or "<a href="#">2:34 to 4:36</a> and <a href="#">8:15 to 8:39</a>"
	</li>
	<li>Page numbers should be preceded by the word "Page" or "Pages".</li>
	<li>Page numbers can be suffxed with a parenthetical note to find the specific place
		on the page.&nbsp; E.g. (2nd column, top left).</li>
	<li>Closed-source journal article titles should link to the most official source available,
		such as the journal's website or ncbi.nlm.nih.gov, even if the source only includes
		the abstract.&nbsp; If arXiv.org or someone else has a full source PDF published
		on their blog, it can be listed in the mirrors.</li>
</ol>
<h4>Abbreviations:</h4>
<ol class="compact">
	<li>"Ibid." should be replaced with list sub-items with a page number and at least some of the relevant text quoted.</li>
	<li>Journal names may use their approved abbreviations.&nbsp; <a href="http://journalseek.net/">This site</a> can
		convert journal names to abbreviations.</li>
</ol>
<h4>Miscellaneous:</h4>
<ol class="compact">
	<li>Citation notes are surrounded with &lt;small&gt;, which makes the text
		smaller and lighter.</li>
	<li>Since sites sometimes shut down, include mirror links in the comments, such as archive.org, archive.is, a screenshot of
		the source, or a local copy of the source in its entirity, if fair use allows it.</li>
	<li>Use two spaces after each period to improve readability.</li>
</ol>
<img src="/about-files/credible-hulk.jpg" id="img1" style="width: 80%;">


<h3>Berean Modified MLA Examples</h3>
<p>Here are several examples of what article citations should look like:</p>
<h4>Website Examples:</h4>
<div class="footnotes">
	<ol class="compact">
		<li>Last, First.&nbsp; "<a href="#">Article Title</a>".&nbsp; <i>Webiste Name</i>.
			&nbsp;Article Date.
			<br>
			<small>Comment about the source.&nbsp; Mirrors:&nbsp; <a href="#">Mirror 1</a> | <a href="#">Mirror 2 </a>| <a href="#">Local copy with notes</a></small>
		</li>
		<li>Behe, Michael J.&nbsp; "<a href="http://www.nytimes.com/2005/02/07/opinion/design-for-living.html">Design for Living</a>."
			&nbsp;
			<i>New York Times</i>.&nbsp; Feb 7, 2005.&nbsp;
			<small>Behe is a biochemist and a leading proponent of Intelligent Design.
				&nbsp;Mirrors:&nbsp; <a href="http://web.archive.org/web/20150415000000*/http://www.nytimes.com/2005/02/07/opinion/design-for-living.html">Archive.org</a> | <a href="#">Local copy</a></small>
							
		</li>
	</ol>
</div>
<h4>Book Examples:</h4>
<div class="footnotes">
	<ol class="compact">
		<li>Last, First.
			<em>Book Name.</em> Publisher.&nbsp;
			Year.&nbsp; Page 1.
			<small>Mirrors:&nbsp; <a href="#">Local copy</a></small>
		</li>
		<li>Behe, Michael
			J.&nbsp; <i>The Edge of Evolution.&nbsp; </i>2007.
			&nbsp;Pages 62-63.
			<small>Mirrors:&nbsp; <a href="#">Local copy</a></small>
		</li>
		<li>Behe, Michael J.&nbsp; 
			<i>The Edge of Evolution.&nbsp; </i>2007.&nbsp; Pages 37, 49, and 113.
			<small>Mirrors:&nbsp; <a href="#">Local copy</a></small>
		</li>
	</ol>
</div>
<h4>Journal Examples:</h4>
<div class="footnotes">
	<ol class="compact">
		<li>Last, First.
			&nbsp;"Paper Title."&nbsp; <em>Journal Name, Year. </em> Pages.</li>
		<li>Behe,
			Micahel J.&nbsp; "<a href="http://www.journals.uchicago.edu/doi/full/10.1086/656902"
			>Experimental Evolution, Loss-of-function Mutations, and 'the First Rule of Adaptive Evolution'</a>".
			&nbsp;
			<i>Q Rev Biol</i>
			2010.&nbsp; Page 419 (top left).
			<small>Mirrors: <a href="http://www.lehigh.edu/bio/Faculty/Behe/PDF/QRB_paper.pdf">PDF on Behe's website</a> |
				<a href="https://www.researchgate.net/publication/49764025_Experimental_evolution_loss-of-function_mutations_and_the_first_rule_of_adaptive_evolution">ResearchGate</a> |
				<a href="http://fliphtml5.com/dkuy/srog/basic">FlipHtml5</a></small>
		</li>
		<li id="fn:sharp-2010">
			<p>Sharp, Paul M et al.&nbsp; "<a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC2935100/">The evolution of HIV-1 and the origin of AIDS</a>."&nbsp; Philos Trans R Soc Lond B.&nbsp; 2010.<small>Mirrors:&nbsp;&nbsp;<a href="https://web.archive.org/web/20171229140219/https://www.ncbi.nlm.nih.gov/pmc/articles/PMC2935100/">Archive.org</a></small> <a href="#fnref1:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref2:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref3:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref4:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref5:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref6:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref7:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref8:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref9:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref10:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref11:sharp-2010" rev="footnote" class="footnote-backref">↩</a> <a href="#fnref12:sharp-2010" rev="footnote" class="footnote-backref">↩</a></p>
			<ol><li id="fn:sharp-2010:a">
					<p><small>"More than 40 species of African monkeys are infected with their own, species-specific, SIV and in at least some host species, the infection seems non-pathogenic."</small> <a href="#fnref1:sharp-2010:a" rev="footnote" class="footnote-backref">↩</a></p>
				</li><li id="fn:sharp-2010:b">
					<p><small>"Chimpanzees acquired from monkeys two distinct forms of SIVs that recombined to produce a virus with a unique genome structure."</small> <a href="#fnref1:sharp-2010:b" rev="footnote" class="footnote-backref">↩</a></p>
				</li><li id="fn:sharp-2010:c">
					<p><small>"We have found that SIV infection causes CD4+ T-cell depletion and increases mortality in wild chimpanzees, and so the origin of AIDS is more ancient than the origin of HIV-1."</small> <a href="#fnref1:sharp-2010:c" rev="footnote" class="footnote-backref">↩</a></p>
				</li></ol>
		</li>
	</ol>
</div>
<h4>Video Examples:</h4>
<div class="footnotes">
	<ol class="compact">
		<li>Last, First.&nbsp; "
			<a href="#">Video Title</a>."&nbsp; Video Date.&nbsp; Seek to video time
		</li>
		<li>Behe, Michael
			J.&nbsp; "<a href="https://www.youtube.com/watch?v=V_XN8s-zXx4">What Are the Limits of Darwinism?</a>"
			&nbsp;Jan 31, 2013.&nbsp; Seek to <a href="#">2:23.</a>&nbsp;
				<small>Behe is a biochemist and a leading proponent of Intelligent Design.&nbsp; His talk was presented at the University of Toronto.&nbsp; </small>
		</li>
	</ol>
</div>
<h4>Forum, blog, or newsgroup comment:</h4>
<p>Based on the guidelines <a href="https://www.reddit.com/r/AskReddit/comments/punqw/citations_from_an_iama_for_thesis_papers_mla_style/">here</a>.</p>
<div class="footnotes">
	<ol class="compact">
		<li>west_of_everywhere.&nbsp;
			"<a href="https://www.reddit.com/r/askscience/comments/1l3zfx/how_come_theres_a_amoeba_with_200_times_larger/cbvsn0n"
			>Re: How come there's a Amoeba with 200 times larger gene set than humans?</a>"&nbsp; Aug 27, 2016.<br>
			<small>west_of_everywhere <a href="https://www.reddit.com/r/askscience/comments/znlk6/askscience_special_ama_we_are_the_encyclopedia_of/">describes his/herself as</a>
				"a grad student in Statistics in the Bickel group at UC Berkeley" who was part of the ENCODE Analysis Working Group.&nbsp; Mirrors: <a href="#">Local copy</a></small>
		</li>
	</ol>
</div>

<h4>Additional calculations without an external source:</h4>
<p>TODO</p>



<h2>File organization</h2>

<p>Articles live in the <code>articles</code> folder, which is organized by scholarly disciplein (biology, history, etc).</p>




Naming conventions for locally mirrored sources (pdfs / images / html pages)
<ol class="compact">
	<li>lastname-article-title-year-pages23-25.png</li>
	<li>username-forum-thread-title-website-year.html
		<br>
	</li>
</ol>

<?php $page_content = ob_get_clean();



require_once 'common/includes/theme.php';
