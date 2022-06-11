<?php
/** WordPress shims.
 * @package storefront */

if ( ! function_exists( 'wp_body_open' ) ) {
	/** Добавлена обратная совместимость для функции wp_body_open(), представленной в WordPress 5.2
	 * @since 2.5.4
	 * @see https://developer.wordpress.org/reference/functions/wp_body_open/
	 * @return void */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
