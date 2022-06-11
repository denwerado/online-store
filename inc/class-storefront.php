<?php
	/** Storefront Class
	 * @since    2.0.0
	 * @package  storefront */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'Storefront' ) ) :

		//The main Storefront class
		class Storefront {

			/** Setup class.
			 * @since 1.0 */
			public function __construct() {
				add_action( 'after_setup_theme', array( $this, 'setup' ) );
				add_action( 'widgets_init', array( $this, 'widgets_init' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
				add_action( 'wp_enqueue_scripts', array( $this, 'child_scripts' ), 30 ); // After WooCommerce.
				add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
				add_filter( 'body_class', array( $this, 'body_classes' ) );
				add_filter( 'wp_page_menu_args', array( $this, 'page_menu_args' ) );
				add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ) );
				add_action( 'enqueue_embed_scripts', array( $this, 'print_embed_styles' ) );
			}

			/** Настраивает темы по умолчанию и регистрирует поддержку различных функций WordPress. */
			public function setup() {
				/* Загрузка файлов локализации.
				* Примечание: первый загруженный файл перевода переопределяет все последующие, если присутствует один и тот же перевод. */

				// Loads wp-content/languages/themes/storefront-it_IT.mo.
				load_theme_textdomain( 'storefront', trailingslashit( WP_LANG_DIR ) . 'themes' );

				// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
				load_theme_textdomain( 'storefront', get_stylesheet_directory() . '/languages' );

				// Loads wp-content/themes/storefront/languages/it_IT.mo.
				load_theme_textdomain( 'storefront', get_template_directory() . '/languages' );

				/** Add default posts and comments RSS feed links to head. */
				add_theme_support( 'automatic-feed-links' );

				/* Включите поддержку миниатюр сообщений на публикациях и страницах.
				* @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails */
				add_theme_support( 'post-thumbnails' );

				/** Включите поддержку логотипа сайта. */
				add_theme_support(
					'custom-logo',
					apply_filters(
						'storefront_custom_logo_args',
						array(
							'height'      => 110,
							'width'       => 470,
							'flex-width'  => true,
							'flex-height' => true,
						)
					)
				);

				/** Зарегистрируйте расположение меню. */
				register_nav_menus(
					apply_filters(
						'storefront_register_nav_menus',
						array(
							'primary'   => __( 'Primary Menu', 'storefront' ),
							'secondary' => __( 'Secondary Menu', 'storefront' ),
							'handheld'  => __( 'Handheld Menu', 'storefront' ),
						)
					)
				);

				/* Переключите базовую разметку по умолчанию для формы поиска, формы комментариев, комментариев, галерей, подписей и виджетов */
				add_theme_support(
					'html5',
					apply_filters(
						'storefront_html5_args',
						array(
							'search-form',
							'comment-form',
							'comment-list',
							'gallery',
							'caption',
							'widgets',
							'style',
							'script',
						)
					)
				);

				/* Настройте базовую функцию пользовательского фона WordPress.*/
				/*add_theme_support(
					'custom-background',
					apply_filters(
						'storefront_custom_background_args',
						array(
							'default-color' => apply_filters( 'storefront_default_background_color', 'ffffff' ),
							'default-image' => '',
						)
					)
				);*/

				/** Настройте основную функцию пользовательского заголовка WordPress. */
				add_theme_support(
					'custom-header',
					apply_filters(
						'storefront_custom_header_args',
						array(
							'default-image' => '',
							'header-text'   => false,
							'width'         => 1950,
							'height'        => 500,
							'flex-width'    => true,
							'flex-height'   => true,
						)
					)
				);

				/** Добавьте поддержку плагина логотипа сайта и функциональности логотипа сайта в JetPack
				 *  https://github.com/automattic/site-logo
				 *  http://jetpack.me/ */
				add_theme_support(
					'site-logo',
					apply_filters(
						'storefront_site_logo_args',
						array(
							'size' => 'full',
						)
					)
				);

				/** Объявите о поддержке функции темы заголовка. */
				add_theme_support( 'title-tag' );

				/** Объявите о поддержке выборочного обновления виджетов. */
				add_theme_support( 'customize-selective-refresh-widgets' );

				/** Добавьте поддержку стилей блоков. */
				add_theme_support( 'wp-block-styles' );

				/** Добавьте поддержку изображений с полным и широким выравниванием. */
				add_theme_support( 'align-wide' );

				/** Добавьте поддержку стилей редактора. */
				add_theme_support( 'editor-styles' );

				/** Добавьте поддержку размеров шрифтов редактора. */
				add_theme_support(
					'editor-font-sizes',
					array(
						array(
							'name' => __( 'Small', 'storefront' ),
							'size' => 14,
							'slug' => 'small',
						),
						array(
							'name' => __( 'Normal', 'storefront' ),
							'size' => 16,
							'slug' => 'normal',
						),
						array(
							'name' => __( 'Medium', 'storefront' ),
							'size' => 23,
							'slug' => 'medium',
						),
						array(
							'name' => __( 'Large', 'storefront' ),
							'size' => 26,
							'slug' => 'large',
						),
						array(
							'name' => __( 'Huge', 'storefront' ),
							'size' => 37,
							'slug' => 'huge',
						),
					)
				);

				/** Enqueue editor styles. */
				add_editor_style( array( 'assets/css/base/gutenberg-editor.css', $this->google_fonts() ) );

				/** Добавьте поддержку адаптивного встроенного контента. */
				add_theme_support( 'responsive-embeds' );
			}

			/** Область виджета регистрации.
			 * @link https://codex.wordpress.org/Function_Reference/register_sidebar */
			public function widgets_init() {
				$sidebar_args['sidebar'] = array(
					'name'        => __( 'Sidebar', 'storefront' ),
					'id'          => 'sidebar-1',
					'description' => '',
				);

				$sidebar_args['header'] = array(
					'name'        => __( 'Below Header', 'storefront' ),
					'id'          => 'header-1',
					'description' => __( 'Widgets added to this region will appear beneath the header and above the main content.', 'storefront' ),
				);

				$rows    = intval( apply_filters( 'storefront_footer_widget_rows', 1 ) );
				$regions = intval( apply_filters( 'storefront_footer_widget_columns', 4 ) );

				for ( $row = 1; $row <= $rows; $row++ ) {
					for ( $region = 1; $region <= $regions; $region++ ) {
						$footer_n = $region + $regions * ( $row - 1 ); // Defines footer sidebar ID.
						$footer   = sprintf( 'footer_%d', $footer_n );

						if ( 1 === $rows ) {
							/* translators: 1: column number */
							$footer_region_name = sprintf( __( 'Footer Column %1$d', 'storefront' ), $region );

							/* translators: 1: column number */
							$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of the footer.', 'storefront' ), $region );
						} else {
							/* translators: 1: row number, 2: column number */
							$footer_region_name = sprintf( __( 'Footer Row %1$d - Column %2$d', 'storefront' ), $row, $region );

							/* translators: 1: column number, 2: row number */
							$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of footer row %2$d.', 'storefront' ), $region, $row );
						}

						$sidebar_args[ $footer ] = array(
							'name'        => $footer_region_name,
							'id'          => sprintf( 'footer-%d', $footer_n ),
							'description' => $footer_region_description,
						);
					}
				}

				$sidebar_args = apply_filters( 'storefront_sidebar_args', $sidebar_args );

				foreach ( $sidebar_args as $sidebar => $args ) {
					$widget_tags = array(
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<span class="gamma widget-title">',
						'after_title'   => '</span>',
					);

					/** Динамически генерируемые крючки фильтров. Разрешить изменение тегов оболочки виджета и заголовка. Смотрите список ниже. */
					$filter_hook = sprintf( 'storefront_%s_widget_tags', $sidebar );
					$widget_tags = apply_filters( $filter_hook, $widget_tags );

					if ( is_array( $widget_tags ) ) {
						register_sidebar( $args + $widget_tags );
					}
				}
			}

			/** Сценарии и стили для постановки в очередь.
			 * @since  1.0.0 */
			public function scripts() {
				global $storefront_version;

				/** Styles */
				wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', '', $storefront_version );
				wp_style_add_data( 'storefront-style', 'rtl', 'replace' );

				wp_enqueue_style( 'storefront-icons', get_template_directory_uri() . '/assets/css/base/icons.css', '', $storefront_version );
				wp_style_add_data( 'storefront-icons', 'rtl', 'replace' );

				//Goddvilee Styles
				if(!is_admin()){
					wp_enqueue_style('owl-carousel-style', get_template_directory_uri() . '/assets/plugins/css/owl.carousel.min.css');
					wp_enqueue_style('datepicker-style', get_template_directory_uri() . '/assets/plugins/css/datepicker.css');
					wp_enqueue_style('goodvilee-style', get_template_directory_uri() . '/assets/css/goodville_css/style.css',array('storefront-style'));
					wp_style_add_data( 'goodvilee-style', 'rtl', 'replace' );
				}

				/** Fonts */
				//Отключили шрифты
				//wp_enqueue_style( 'storefront-fonts', $this->google_fonts(), array(), $storefront_version );

				/** Scripts */
				$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

				wp_enqueue_script( 'storefront-navigation', get_template_directory_uri() . '/assets/js/navigation' . $suffix . '.js', array(), $storefront_version, true );

				if ( has_nav_menu( 'handheld' ) ) {
					$storefront_l10n = array(
						'expand'   => __( 'Expand child menu', 'storefront' ),
						'collapse' => __( 'Collapse child menu', 'storefront' ),
					);

					wp_localize_script( 'storefront-navigation', 'storefrontScreenReaderText', $storefront_l10n );
				}

				if ( is_page_template( 'template-homepage.php' ) && has_post_thumbnail() ) {
					wp_enqueue_script( 'storefront-homepage', get_template_directory_uri() . '/assets/js/homepage' . $suffix . '.js', array(), $storefront_version, true );
				}

				if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
					wp_enqueue_script( 'comment-reply' );
				}

				//Goodville scripts
				if(!is_admin()){
					wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/assets/plugins/js/owl.carousel.min.js', array('jquery'), null, true);
					wp_enqueue_script('datepicker-js', get_template_directory_uri() . '/assets/js/datepicker.min.js', array('jquery'), null, true);
					wp_enqueue_script('goodville-js', get_template_directory_uri() . '/assets/js/goodville.min.js', array('jquery'), null, true);
				}
			}

			/** Зарегистрируйте шрифты Google.
			 * @since 2.4.0
			 * @return string Google fonts URL for the theme. */
			public function google_fonts() {
				$google_fonts = apply_filters(
					'storefront_google_font_families',
					array(
						'source-sans-pro' => 'Source+Sans+Pro:400,300,300italic,400italic,600,700,900',
					)
				);

				$query_args = array(
					'family' => implode( '|', $google_fonts ),
					'subset' => rawurlencode( 'latin,latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

				return $fonts_url;
			}

			/** Поставьте в очередь активы блока.
			 * @since 2.5.0 */
			public function block_assets() {
				global $storefront_version;

				// Styles.
				wp_enqueue_style( 'storefront-gutenberg-blocks', get_template_directory_uri() . '/assets/css/base/gutenberg-blocks.css', '', $storefront_version );
				wp_style_add_data( 'storefront-gutenberg-blocks', 'rtl', 'replace' );
			}

			/** Поставьте в очередь таблицу стилей дочерней темы.
			 * Требуется отдельная функция, так как css дочерней темы необходимо поставить в очередь _после_ родительской темы
			 * основной css и отдельный css WooCommerce.
			 * @since  1.5.3 */

			public function child_scripts() {
				if ( is_child_theme() ) {
					$child_theme = wp_get_theme( get_stylesheet() );
					wp_enqueue_style( 'storefront-child-style', get_stylesheet_uri(), array(), $child_theme->get( 'Version' ) );
				}
			}

			/** Получите запасной вариант wp_nav_menu(), wp_page_menu(), чтобы показать домашнюю ссылку.
			 * @param array $args Configuration arguments.
			 * @return array */

			public function page_menu_args( $args ) {
				$args['show_home'] = true;
				return $args;
			}

			/** Добавляет пользовательские классы в массив классов тела.
			 * @param array $classes Classes for the body element.
			 * @return array */

			public function body_classes( $classes ) {
				//Добавляет класс в блоги с более чем 1 опубликованным автором.
				if ( is_multi_author() ) {
					$classes[] = 'group-blog';
				}

				/** Добавляет класс, когда WooCommerce не активен.
				 * @todo Refactor child themes to remove dependency on this class. */

				$classes[] = 'no-wc-breadcrumb';

				/**
				 * What is this?!
				 * Take the blue pill, close this file and forget you saw the following code.
				 * Or take the red pill, filter storefront_make_me_cute and see how deep the rabbit hole goes...
				 */
				$cute = apply_filters( 'storefront_make_me_cute', false );

				if ( true === $cute ) {
					$classes[] = 'storefront-cute';
				}

				//Если наша главная боковая панель не содержит виджетов, настройте макет так, чтобы он был во всю ширину.
				if ( ! is_active_sidebar( 'sidebar-1' ) ) {
					$classes[] = 'storefront-full-width-content';
				}

				//Добавьте класс при использовании шаблона домашней страницы + рекомендуемое изображение.
				if ( is_page_template( 'template-homepage.php' ) && has_post_thumbnail() ) {
					$classes[] = 'has-post-thumbnail';
				}

				//Добавьте класс, когда используется дополнительная навигация.
				if ( has_nav_menu( 'secondary' ) ) {
					$classes[] = 'storefront-secondary-navigation';
				}

				//Добавьте класс, если поддерживается выравнивание по ширине.
				if ( current_theme_supports( 'align-wide' ) ) {
					$classes[] = 'storefront-align-wide';
				}

				return $classes;
			}

			/** Custom navigation markup template hooked into `navigation_markup_template` filter hook. */
			public function navigation_markup_template() {
				$template  = '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="' . esc_html__( 'Post Navigation', 'storefront' ) . '">';
				$template .= '<h2 class="screen-reader-text">%2$s</h2>';
				$template .= '<div class="nav-links">%3$s</div>';
				$template .= '</nav>';

				return apply_filters( 'storefront_navigation_markup_template', $template );
			}

			/** Добавление стилей для встраиваний */
			public function print_embed_styles() {
				global $storefront_version;

				wp_enqueue_style( 'source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,700,900', array(), $storefront_version );
				$accent_color     = get_theme_mod( 'storefront_accent_color' );
				$background_color = storefront_get_content_background_color();
				?>
				<style type="text/css">
					.wp-embed {
						padding: 2.618em !important;
						border: 0 !important;
						border-radius: 3px !important;
						font-family: "Source Sans Pro", "Open Sans", sans-serif !important;
						background-color: <?php echo esc_html( storefront_adjust_color_brightness( $background_color, -7 ) ); ?> !important;
					}

					.wp-embed .wp-embed-featured-image {
						margin-bottom: 2.618em;
					}

					.wp-embed .wp-embed-featured-image img,
					.wp-embed .wp-embed-featured-image.square {
						min-width: 100%;
						margin-bottom: .618em;
					}

					a.wc-embed-button {
						padding: .857em 1.387em !important;
						font-weight: 600;
						background-color: <?php echo esc_attr( $accent_color ); ?>;
						color: #fff !important;
						border: 0 !important;
						line-height: 1;
						border-radius: 0 !important;
						box-shadow:
							inset 0 -1px 0 rgba(#000,.3);
					}

					a.wc-embed-button + a.wc-embed-button {
						background-color: #60646c;
					}
				</style>
				<?php
			}
		}
	endif;

	return new Storefront();

?>