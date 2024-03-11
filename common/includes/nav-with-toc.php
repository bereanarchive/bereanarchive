<div id="navScrollWithPage">
	<div style="text-align: center">
		<a href="/" id="bereanLogo" title="Go to the home page">
			<span class="bereanLogoSmall"></span>
		</a>
	</div>
	<ul class="tabs">
		<li id="navSiteMapTab"><a class="sliding-underline">Site Map</a></li>
		<li id="navTocTab"><a class="sliding-underline selected">This Article</a></li>
	</ul>
	<div id="navSlideContainer" class="row" style="transform: translate(-50%, 0)">
		<div id="navSiteMap">
			<?=BereanArchive::getArticleListHtml()?>
		</div>
		<div id="navToc"></div>
	</div>
	<script defer src="/common/js/nav-with-toc.js"></script>
</div>
