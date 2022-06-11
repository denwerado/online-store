<?php
//Установка названия темы
$theme = wp_get_theme( 'storefront' );

//Установка версии
$storefront_version = $theme['Version'];

//Установите ширину содержимого на основе дизайна темы и таблицы стилей.
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}


//Инициализируйте все эти вещи.
$storefront = (object) array(
	'version'    => $storefront_version,

	//Создает Storefront Class
	'main'       => require 'inc/class-storefront.php',

	//Работа со стилями
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

//Предварит функционал проверки wooCommerce
require 'inc/storefront-functions.php';

//Подключение хуков темы
require 'inc/storefront-template-hooks.php';

//Подключение полного функционала темы
require 'inc/storefront-template-functions.php';

//совместимость для функции wp_body_open() в старых версиях wp
require 'inc/wordpress-shims.php';

//Если подключен Jetpack
if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

//Проверка на активность wooCommerce
if ( storefront_is_woocommerce_activated() ) {
	//Подключение интеграции магазина с WooCommerce
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';

	//Кастомизация wooCommerce
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	//Функционал товаров
	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	//Подключение хуков wooComerce
	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';

	//Функции шаблона WooCommerce
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';

	//Функции WooCommerce 
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

//Если страница администратора
if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}



/**
 * Enabling functionality from the Daring&Young
 */
require 'inc/goodville/goodville-woocommerce.php';
require 'inc/goodville/goodville-functions.php';
require	'inc/goodville/gdvl-prod-reg.php';
require	'inc/goodville/gdvl-add-subscription.php';



/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Примечание: Не добавляйте здесь никакого пользовательского кода. Пожалуйста, используйте пользовательский плагин, чтобы ваши настройки не были потеряны во время обновлений.
 * https://github.com/woocommerce/theme-customisations
 */
