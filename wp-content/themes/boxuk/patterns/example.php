<?php
/**
 * This is an example pattern.
 *
 * Using the `Block Types` keyword, you can specify which blocks can accept this pattern. For example,
 * this example has a `core/post-content` block type, which means it is presented as a pattern when
 * adding a new post or page.
 *
 * Title: Example
 * Slug: boxuk/example
 * Categories: example
 * Keywords: example
 * Block Types: core/post-content
 * Post Types: post, page
 *
 * @package boxuk/patterns
 */

declare(strict_types=1);

?>
<!-- wp:cover {"useFeaturedImage":true,"isUserOverlayColor":true,"align":"full"} -->
<div class="wp-block-cover alignfull"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim"></span>
	<div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
		<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-link-color" id="h-page-heading">Page Heading</h1>
		<!-- /wp:heading -->
	</div>
</div>
<!-- /wp:cover -->

<!-- wp:group {"metadata":{"name":"Text"},"style":{"spacing":{"blockGap":"var:preset|spacing|50","margin":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--80);margin-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"textAlign":"left","fontSize":"large"} -->
	<h2 class="wp-block-heading has-text-align-left has-large-font-size" id="h-headings-and-paragraphs-large">Headings and Paragraphs (Large)</h2>
	<!-- /wp:heading -->

	<!-- wp:heading {"textAlign":"left","level":3,"fontSize":"default"} -->
	<h3 class="wp-block-heading has-text-align-left has-default-font-size" id="h-a-quick-guide-to-formatting-text-medium">A quick guide to formatting text (Medium)</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"left","fontSize":"default"} -->
	<p class="has-text-align-left has-default-font-size">When creating text content use <strong>H1</strong> for the main title (for example the cover block on top of this page), <strong>H2</strong> for major section titles, and <strong>H3</strong>, <strong>H4</strong> etc. for subsections in order to organise information logically, enhance accessibility for all users, and improve search engine visibility.<br><br>Use <strong>paragraph</strong> for your main copy. This paragraph uses default paragraph text.</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Images and Galleries"},"style":{"spacing":{"blockGap":"var:preset|spacing|50","margin":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--80);margin-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"textAlign":"left","fontSize":"large"} -->
	<h2 class="wp-block-heading has-text-align-left has-large-font-size" id="h-images-and-galleries">Images and Galleries</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph -->
	<p>You can add images throughout your page using ‘<strong>image</strong>’ or ‘<strong>gallery</strong>’ for a collection of images. Don’t forget to add your alt text in block settings for accessibility.</p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":3,"fontSize":"default"} -->
	<h3 class="wp-block-heading has-default-font-size" id="h-image">Image</h3>
	<!-- /wp:heading -->

	<!-- wp:image {"id":109829,"sizeSlug":"large","linkDestination":"none"} -->
	<figure class="wp-block-image size-large"><img src="" alt="" class="wp-image-109829" /></figure>
	<!-- /wp:image -->

	<!-- wp:heading {"level":3,"fontSize":"default"} -->
	<h3 class="wp-block-heading has-default-font-size" id="h-gallery">Gallery</h3>
	<!-- /wp:heading -->

	<!-- wp:gallery {"linkTo":"none"} -->
	<figure class="wp-block-gallery has-nested-images columns-default is-cropped"><!-- wp:image {"id":84411,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="" alt="" class="wp-image-84411" /></figure>
		<!-- /wp:image -->

		<!-- wp:image {"id":84412,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="" alt="" class="wp-image-84412" /></figure>
		<!-- /wp:image -->

		<!-- wp:image {"id":84413,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="" alt="" class="wp-image-84413" /></figure>
		<!-- /wp:image -->

		<!-- wp:image {"id":84414,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="" alt="" class="wp-image-84414" /></figure>
		<!-- /wp:image -->

		<!-- wp:image {"id":84415,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="" alt="" class="wp-image-84415" /></figure>
		<!-- /wp:image -->
	</figure>
	<!-- /wp:gallery -->
</div>
<!-- /wp:group -->
