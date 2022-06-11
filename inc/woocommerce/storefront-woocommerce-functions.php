<?php
/**Storefront WooCommerce functions.
 * @package storefront */

/** Проверяет, является ли текущая страница архивом продукта
 * @return boolean */
function storefront_is_product_archive() {
	if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
		return true;
	} else {
		return false;
	}
}

/** Извлекает предыдущий продукт.
 * @since 2.4.3
 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string       $taxonomy       Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found. */
function storefront_get_previous_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' ) {
	$product = new Storefront_WooCommerce_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy, true );
	return $product->get_product();
}

/** Извлекает следующий продукт.
 * @since 2.4.3
 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string       $taxonomy       Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found. */
function storefront_get_next_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' ) {
	$product = new Storefront_WooCommerce_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy );
	return $product->get_product();
}
