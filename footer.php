<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

	</div><!-- #content -->

	<?php  do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer goodville-footer" role="contentinfo">
		<div class="goodville-container">
			<div class="goodville-footer__block">
				<nav class="footer__nav">
					<a href="/" class="footer__logo">
						<svg class="image"><use xlink:href="#footer-logo"></use></svg>
					</a>
					<ul class="goodville-ul footer__navigation">
						<li class="item">
							<a class="goodville-link" href="/#goodville-categories">Categories</a>
						</li>
						<li class="item">
							<a class="goodville-link" href="/#goodville-collection">Collection</a>
						</li>
						<li class="item">
							<a class="goodville-link" href="/#goodville-features">Features</a>
						</li>
						<li class="item">
							<a class="goodville-link" href="/#goodville-faq">F.A.Q</a>
						</li>
						<li class="item">
							<a class="goodville-link" href="/#goodville-product_req">Product Registry</a>
						</li>
						<li class="item">
							<a class="goodville-link" href="/#goodville-about">About us</a>
						</li>
					</ul>
				</nav>
				<div class="goodville-footer__search">
					<svg class="magnifier"><use xlink:href="#magnifier"></use></svg>
					<?php do_action('storefront_footer_search'); ?>
				</div>
			</div>
		</div>
	</footer>

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
