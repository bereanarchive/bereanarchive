<div id="nav">
	<div id="navScrollWithPage">
		<p style="margin: 0; text-align: center;">
			<a href="/" id="bereanLogo" title="Go to the home page">
				<span class="bereanLogoSmall"></span>
			</a>
		</p>
		<ul class="tabs">
			<li id="navSiteMapTab"><a class="sliding-underline">Site Map</a></li>
			<li id="navTocTab"><a class="sliding-underline selected">This Article</a></li>
		</ul>
		<div id="navSlideContainer" style="transform: translate(-50%, 0);">
			<div id="navSiteMap">
				<?php include 'common/includes/articles-list.php'?>
				<?php foreach ($articleCategories as $categoryName=>$articles):?>
					<h3><?=$categoryName?></h3>
					<ul>
						<?php foreach ($articles as $article):?>						
							<li><a href="<?=htmlspecialchars($article->url)?>"
									title="<?=$article->description?>">
									<div>
										<img src="<?=htmlspecialchars($article->thumb)?>?w=32"/>
									</div>
									<div>								
										<?=$article->name?>
									</div>	
								</a>
							</li>
								
						<?php endforeach?>
					</ul>
				<?php endforeach?>
			</div>
			<div id="navToc"></div>
		</div>
	</div>
	<script defer src="/common/js/nav-with-toc.js"></script>
</div>
