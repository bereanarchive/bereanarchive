<?php

class BereanArchive {

	static function getArticleList(): object {

		// Names and descriptions should be entered as html-encoded.
		// TODO:  This could be generated automatically from FileSearch.php and a $page_isPublic variable.
		return (object)[
			'Biology' => (object)[
				(object)[
					'name'=>'Organisms Appear Designed',
					'url'=>'/articles/biology/organisms-appear-designed',
					'thumb'=>'/articles/biology/organisms-appear-designed-files/header-square.jpg',
					'description'=>'Biologists often agree organisms appear designed, even when they prefer evolution as the explanation.'
				],/*
				(object)[
					'name'=>'Perspectives on Evolution and Design',
					'url'=>'',
					'thumb'=>'',
					'description'=>'TODO'
				],*/
				(object)[
					'name'=>'Abundant Functional DNA is Evidence of Design',
					'url'=>'/articles/biology/functional-dna',
					'thumb'=>'/articles/biology/functional-dna-files/header-square.jpg',
					'description'=>'Evidence suggests most mammal DNA is functional, and this is much more function than evolution can create.'
				],
				(object)[
					'name'=>'Junk Versus Functional DNA Predictions',
					'url'=>'/articles/biology/functional-dna-predictions',
					'thumb'=>'/articles/biology/functional-dna-predictions-files/header-square-small.jpg',
					'description'=>'Evolution proponents prediced most DNA in complex organisms would be junk, while intelligent design proponents predicted the opposite.'
				],
				(object)[
					'name'=>'The C-Value Paradox',
					'url'=>'/articles/biology/c-value-paradox',
					'thumb'=>'/articles/biology/c-value-paradox-files/header-square.jpg',
					'description'=>'Because some organisms have much more DNA than others of similar complexity, it is often argued that most DNA in complex organisms must be junk, and thus they are not designed.  This article argues otherwise.'
				],
				(object)[
					'name'=>'HIV Evolution',
					'url'=>'/articles/biology/hiv-evolution',
					'thumb'=>'/articles/biology/hiv-evolution-files/header-square.jpg',
					'description'=>'With huge populations and strong natural selection, HIV evolves faster than any other organism, but has produced very little in terms of new functions.  This suggests evolution is not a powerful, creative force.'
				],
				(object)[
					'name'=>'Low-Effort Arguments in the Evolution Debate',
					'url'=>'/articles/biology/low-effort-arguments',
					'thumb'=>'/articles/biology/low-effort-arguments-files/header-square.jpg',
					'description'=>'An overview of simpler but commonly used arguments in evolution debates, often involving misuses of terminology or logical falacies.'
				]
			],
			'History' => (object)[
				(object)[
					'name'=>'Ancient Israel:  Slavery, Servanthood, and Social Welfare',
					'url'=>'/articles/history/ancient-israel-slavery-servanthood-and-social-welfare',
					'thumb'=>'/articles/history/ancient-israel-slavery-servanthood-and-social-welfare-files/header-square.jpg',
					'description'=>'Hebrew uses the same word for "servant" and "slave" and even high-ranking officials were "slaves" to the king.  Unlike other ancient cultures, Israel\'s strict regulations made it impossible to treat a servant/slave inhumanely.'
				]/*,
				(object)[
					'name'=>'Ancient Israel:  Morality of the Conquest of Canaan',
					'url'=>'/articles/history/ancient-israel-morality-of-the-conquest-of-canaan',
					'thumb'=>'/articles/history/ancient-israel-morality-of-the-conquest-of-canaan-files/header-square.jpg',
					'description'=>'Are the Old Testament military campaigns of Israel inconsistent with being led by a just and
						loving God?&nbsp; This article makes the case for consistency and justness.'
				],
				(object)[
					'name'=>'Shroud of Turin',
					'url'=>'/articles/history/shroud-of-turin',
					'thumb'=>'/articles/history/shroud-of-turin-files/header-square.jpg',
					'description'=>''
				]*/,
				(object)[
					'name'=>'Shroud of Turin:  1988 Carbon Dating',
					'url'=>'/articles/history/shroud-of-turin-carbon-dating',
					'thumb'=>'/articles/history/shroud-of-turin-carbon-dating-files/header-square.jpg',
					'description'=>"The Shroud of Turin is a linen burial cloth with the image and blood of a man having wounds matching the crucifixion of Christ, as if the imprint of his body was flash-burned into the shroud's fibers.  However, carbon dating in 1988 puts the shroud in the middle ages, not the time of Christ.  This article disproves that carbon date."
				]

			],
			'Sociology' => (object)[
				(object)[
					'name'=>'Problems in LGBT Studies',
					'url'=>'/articles/sociology/problems-in-lgbt-studies',
					'thumb'=>'/articles/sociology/problems-in-lgbt-studies-files/header-square.jpg',
					'description'=>'The field has abandoned good science to pursue a political agenda.  Intense pressure to conform to LGBT narratives leads to poor methodology, fabricated stories, and discrimination against those who don\'t comply.'
				]
			]
		];
	}

	static function getArticleListHtml(): string {
		ob_start()?>
			<?php foreach (BereanArchive::getArticleList() as $categoryName=>$articles):?>
				<h3><?=$categoryName?></h3>
				<ul>
					<?php foreach ($articles as $article):?>
						<li><a href="<?=htmlspecialchars($article->url)?>"
							title="<?=htmlspecialchars($article->description)?>">
							<div>
								<img src="<?=htmlspecialchars($article->thumb)?>?w=32"/>
							</div>
							<div>
								<?=htmlspecialchars($article->name)?>
							</div>
						</a>
						</li>

					<?php endforeach?>
				</ul>
			<?php endforeach?>
		<?php
		return ob_get_clean();
	}

}