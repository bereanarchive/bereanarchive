<div id="nav">
	<div id="navScrollWithPage">
		<p style="margin: 0px; text-align: center;">
			<a href="/" id="bereanLogo" title="Go to the home page">
				<span class="logo"></span>
			</a>
		</p>
		<ul class="tabs">
			<li id="navSiteMapTab"><a class="sliding-underline selected">Site Map</a></li>
		</ul>
		<div id="navSlideContainer">
			<div id="navSiteMap" class="include">
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
		</div>
	</div>
	<script defer src="/common/js/nav.js"></script>
</div>
