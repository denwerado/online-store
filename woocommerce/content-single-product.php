<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

//Product image
$post_thumbnail_id = $product->get_image_id();
//Gallery images
$attachment_ids = $product->get_gallery_image_ids();

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<section class="goodville-product">
    <!--<div class="goodville-container">-->
        <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'goodville-product__block', $product ); ?>>
            <?php
                the_title( '<h1 class="entry-title product__title">', '</h1>' );
            ?>
            <div class="product__info">
                <div class="product__slider">
                    <div id="product-slider" class="owl-carousel">
                        <div class="product__slider-item">
                            <img class="image" src="<?php echo wp_get_attachment_url($post_thumbnail_id)?>" alt="">
                        </div>
                        <?php foreach ($attachment_ids as $key => $gallery_image_id) { ?>
                            <div class="product__slider-item">
                                <img class="image" src="<?php echo wp_get_attachment_url($gallery_image_id)?>" alt="">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="product__slider-pagination">
                        <svg id="product-slider-pagination-prev"><use xlink:href="#slider-black-arrow"></use></svg>
                        <svg id="product-slider-pagination-next"><use xlink:href="#slider-black-arrow"></use></svg>
                        <div id="product-slider-pagination" class="owl-carousel">
                            <div class="product__slider-pagination-item">
                                <img class="pagination" src="<?php echo wp_get_attachment_url($post_thumbnail_id)?>" alt="">
                            </div>
                            <?php foreach ($attachment_ids as $key => $gallery_image_id) {?>
                                <div class="product__slider-pagination-item">
                                    <img class="pagination" src="<?php echo wp_get_attachment_url($gallery_image_id) ?>" alt="">
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <div class="summary entry-summary product__summary">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     * @hooked WC_Structured_Data::generate_product_data() - 60
                     */
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                </div>
            </div>
            
            <?php
            //do_action( 'woocommerce_product_thumbnails' );
            /**
             * Hook: woocommerce_after_single_product_summary.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */
            do_action( 'woocommerce_after_single_product_summary' );
            ?>
        </div>
    <!--</div>-->
</section>

<?php do_action( 'woocommerce_after_single_product' ); ?>
