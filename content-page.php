<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package storefront
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<section class="goodville-page">
		<div class="goodville-container">
			<div class="goodville-page__block">
				<?php
				/**
				 * Functions hooked in to storefront_page add_action
				 *
				 * @hooked storefront_page_header          - 10
				 * @hooked storefront_page_content         - 20
				 */
				do_action( 'storefront_page' );
				?>
			</div>
		</div>
	</section>
</article><!-- #post-## -->
