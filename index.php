<?php
/** The main template file.
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package storefront */

//Home page
get_header(); 


?>

	<div id="primary" class="content-area goodville-content">
		<main id="main" class="site-main goodville-main" role="main">

		<?php
			//**Обращение к главному слайдеру 
			$argsMainSliderItems = array (
				'post_type' => 'main-slider',
				'post_status' => 'publish',
				'order' 		=> 'ASC'
			);

			$mainSliderItems = new WP_Query($argsMainSliderItems);
		?>

		<section class="goodville-top">
			<div class="goodville-top__slider">
				<div class="goodville-top__arrow" id="main-slider-prev">
					<svg><use xlink:href="#slider-white-arrow"></use></svg>
				</div>
				<div class="goodville-top__arrow" id="main-slider-next">
					<svg><use xlink:href="#slider-white-arrow"></use></svg>
				</div>
				<div id="main-slider" class="owl-carousel">
					<?php 
						//**Перебор слайдов 
						while($mainSliderItems->have_posts()){
							$mainSliderItems->the_post();
						?>
							<div class="goodville-top__item" style="background-image: url(<?php echo wp_get_attachment_url(get_post_thumbnail_id())?>)">
								<div class="goodville-container goodville-container__left-padding">
									<h1 class="goodville-title top__title"><?php echo get_the_title();?></h1>
									<div class="top__subtitle"><?php echo get_the_content();?></div>
									<a href="<?php echo get_field('button-link');?>" class="goodville-link cmp-button cmp-button_red"><?php echo get_field('button-text');?></a>
								</div>
							</div>
					<?php } ?>
				</div>
			</div>
		</section>

		<?php 
			//**Получение категорий продуктов 
			$argsProductCategories = array(
				'taxonomy' => 'product_cat',
			);
			$productCategories = get_terms( $argsProductCategories );
		?>

		<a name="goodville-categories"></a>
		<section class="goodville-categories">
			<div class="goodville-container">
				<div class="goodville-categories__block">
					<h2 class="goodville-title categories__title">Categories</h2>
					<div class="goodville-categories__slider">
						<svg id="categories-slider-prev"><use xlink:href="#slider-black-arrow"></use></svg>
						<svg id="categories-slider-next"><use xlink:href="#slider-black-arrow"></use></svg>
						<div id="categories-slider" class="owl-carousel">
							<?php 
								//**Перебор категорий
								foreach($productCategories as $productCategory){
									$productCategoryId = $productCategory->term_id; 
									//$productCategoryLink = get_term_link($productCategoryId);
									$thumbnailId = get_term_meta($productCategoryId,'thumbnail_id', true);
									$thumbnailUrl = wp_get_attachment_url($thumbnailId);
							?>
								<a href="#goodville-collection" class="goodville-link goodville-categories__item" style="background-image: url(<?php echo $thumbnailUrl;?>" data-collection-toggle="<?php echo $productCategory->name;?>-category">
									<p class="category__name"><?php echo $productCategory->name;?></p>
								</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<a name="goodville-collection"></a>
		<section class="goodville-collection">
			<div class="goodville-container goodville-container__left-padding">
				<div class="goodville-collection__block">
					<?php
						//**Получение текущей коллекции
						$argCurrentCollectionName = array(
							'taxonomy' => 'product_tag',
							'hide_empty' => false,
							'slug' => 'current-collection'
						);

						$currentCollectionName = get_tags($argCurrentCollectionName);
					?>
					<h2 class="goodville-title collection__title"><span class="goodville-text__gray">Collection</span><br>
						<span class="goodville-text__bold"><?php echo $currentCollectionName[0]->name;?></span>
					</h2>

					<div class="cmp-product-cards">
						<div class="cmp-product-cards__wrap">
							<?php
								//**Получение главного товара
								$mainProductWoocommerce = get_main_product(); 
								if($mainProductWoocommerce){
							?>
								<a href="<?php echo $mainProductWoocommerce->get_permalink();?>" class="goodville-link cmp-product-cards__main" style="background-image: url(<?php echo wp_get_attachment_url($mainProductWoocommerce->get_image_id());?>)">
									<div class="info">
										<p class="title"><?php echo $mainProductWoocommerce->get_name();?></p>
										<div class="wrap">
											<span class="price">$<?php echo $mainProductWoocommerce->get_price();?></span>
										</div>
									</div>
								</a>
							<?php } ?>
							<div class="cmp-product-cards__categories">
								<ul class="goodville-ul cmp-product-cards__menu">
									<li class="category active" data-collection-toggle="all-category">All</li>
									<?php 
										//**Перебор категорий товара
										foreach($productCategories as $productCategory){ 
									?>
										<li class="category" data-collection-toggle="<?php echo $productCategory->name;?>-category"><?php echo $productCategory->name;?></li>
									<?php } ?>
								</ul>

								<?php 
									//**Вывод продуктов по категориям
									goodville_output_products_all('all',0,4); 

									foreach($productCategories as $productCategory){
										goodville_output_products_сat($productCategory->name,$productCategory->slug,0,4);
									}
								?>
							</div>
						</div>

						<div class="cmp-product-cards__accordeon">
							<button class="goodville-button cmp-button cmp-button_black-reverse">See more</button>
							<div class="cmp-product-cards__content">	
								<?php 
									//**Вывод продуктов по категориям
									goodville_output_products_all('all',2,100); 

									foreach($productCategories as $productCategory){
										if(goodville_check_products_сat($productCategory->slug,2,100)){
											goodville_output_products_сat($productCategory->name,$productCategory->slug,2,100);
										}
									}
								?>
							</div>	
							<p class="cmp-product-cards__message">
								There are no more products
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>


		<?php
			//**Обращение к слайдеру преимуществ
			$argsadvantagesSliderItems = array (
				'post_type' => 'advantages-slider',
				'post_status' => 'publish',
				'order' 		=> 'ASC'
			);

			$advantagesliderItems = new WP_Query($argsadvantagesSliderItems);
		?>

		<a name="goodville-features"></a>
		<section class="goodville-advantages">
			<div class="goodville-advantages__slider">
				<div class="goodville-advantages__arrow" id="advantages-slider-prev">
					<svg><use xlink:href="#slider-white-arrow"></use></svg>
				</div>
				<div class="goodville-advantages__arrow" id="advantages-slider-next">
					<svg><use xlink:href="#slider-white-arrow"></use></svg>
				</div>
				<div id="advantages-slider" class="owl-carousel">
					<?php 
						//**Перебор слайдов
						while($advantagesliderItems->have_posts()){
							$advantagesliderItems->the_post();
						?>
							<div class="goodville-advantages__item" style="background-image: url(<?php echo wp_get_attachment_url(get_post_thumbnail_id())?>)">
								<div class="goodville-container goodville-container__left-padding">
									<?php 
										$advantagesTitleArr = explode(' ',get_the_title());
										$advantagesTitleFirst = $advantagesTitleArr[0];
										unset($advantagesTitleArr[0]);
										$advantagesTitleSecond = implode(" ", $advantagesTitleArr);
									?>
									<h1 class="goodville-title advantages__title"><span class="goodville-text__pale"><?php echo $advantagesTitleFirst;?></span><br><?php echo $advantagesTitleSecond;?></h1>
									<div class="advantages__info">
										<div class="ico">
											<img src="<?php echo get_field('advantages-ico');?>" alt="" class="image">
										</div>
										<div class="content">
											<?php echo get_the_content();?>
										</div>
									</div>
								</div>
							</div>
					<?php } ?>
				</div>
			</div>
		</section>


		<?php 
			$argsFAQItems = array (
				'post_type' => 'goodville-faq',
				'post_status' => 'publish',
				'order' => 'DESC'
			);

			$FAQItems = new WP_Query($argsFAQItems);
		?>
		<a name="goodville-faq"></a>
		<section class="goodville-faq">
			<div class="goodville-container goodville-container__left-padding">
				<div class="goodville-faq__block">
					<h2 class="goodville-title faq__title">F.A.Q</h2>
					<div class="faq__questions">
						<?php 
							while($FAQItems->have_posts()){
								$FAQItems->the_post();
						?>
							<div class="cmp-accordeon">
								<div class="cmp-accordeon__header">
									<p class="title"><?php echo get_the_title();?></p>
									<svg class="arrow"><use xlink:href="#accordeon-arrow"></use></svg>
								</div>
								<div class="cmp-accordeon__content">
									<?php echo get_the_content();?>
								</div>
								<div class="cmp-accordeon__line"></div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>


		<section class="goodville-prod-reg">
			<div class="goodville-prod-reg__block">
				<div class="prod-reg__box">
					<div class="prod-reg__box-container">
						<h2 class="goodville-title prod-reg__title">Product Registry</h2>
						<div class="cmp-form">
							<form id="form-prod-reg">
								<p class="cmp-form__subtitle">Name</p>
								<div class="cmp-form__row">
									<div class="cmp-form__validate">
										<fieldset>
											<legend></legend>
										</fieldset>
										<input type="text" class="goodville-input cmp-form__input_gray latin-input" placeholder="First" name="cliFirst" required>
									</div>
									<div class="cmp-form__validate">
										<fieldset>
											<legend></legend>
										</fieldset>
										<input type="text" class="goodville-input cmp-form__input_gray latin-input" placeholder="Last" name="clitLast" required>
									</div>
								</div>
								<p class="cmp-form__subtitle">Address</p>
								<div class="cmp-form__row">
									<div class="cmp-form__validate">
										<fieldset>
											<legend></legend>
										</fieldset>
										<input type="text" class="goodville-input cmp-form__input_gray number-input" placeholder="ZIP Code" name="cliZip" required>
									</div>
									<div class="cmp-form__validate">
										<fieldset>
											<legend></legend>
										</fieldset>
										<input type="text" class="goodville-input cmp-form__input_gray latin-input" placeholder="State" name="cliState" required>
									</div>
									<div class="cmp-form__validate">
										<fieldset>
											<legend></legend>
										</fieldset>
										<input type="text" class="goodville-input cmp-form__input_gray latin-input" placeholder="Country" name="cliCountry" required>
									</div>
								</div>
								<p class="cmp-form__subtitle">Product Information</p>
								<div class="cmp-form__row">
									<input type="text" class="goodville-input cmp-form__input_gray" placeholder="Style Number" name="cliStyleNumb" required>
									<div class="cmp-form__validate">
										<fieldset>
											<legend></legend>
										</fieldset>
										<input type="text" class="goodville-input cmp-form__input_gray cmp-form__input_gray-long date-input" placeholder="Date of purchase" name="cliDatePurch" required autocomplete="off" maxlength="10">
									</div>
								</div>
								<p class="cmp-form__subtitle">Mail</p>
								<div class="cmp-form__row">
									<input type="email" class="goodville-input cmp-form__input_gray cmp-form__input-medium" placeholder="User@gmail.com" name="cliEmail" required>
								</div>

								<button class="goodville-button cmp-button cmp-button_red">Submit</button>
							</form>
						</div>
					</div>
				</div>
				<img src="<?php echo get_template_directory_uri(  ) . '/assets/images/goodville_images/reg_boots.jpg' ?>" alt="" class="prod-reg__background">
			</div>
		</section>


		<a name="goodville-about"></a>
		<section class="goodville-about">
				<div class="goodville-about__block">
					<div class="about__info">
						<h2 class="goodville-title about__title">About us</h2>
						<p class="about__subtitle">Quality above all</p>
						<p class="about__description">Finding that perfect fit has never been easier.</p>
						<p class="about__description">We must protect our feet using a shoe with high quality material that avoids possible friction and inflammation.</p>
						<ul class="goodville-ul about__benefit">
							<li class="item">
								<div class="ico">
									<svg class="image"><use xlink:href="#clock"></use></svg>
								</div>
								<p class="info">Quality shoes avoid discomfort and other problems</p>
							</li>
							<li class="item">
								<div class="ico">
									<svg class="image"><use xlink:href="#shild"></use></svg>
								</div>
								<p class="info">Good quality shoes last for many more years than shoes of poor quality</p>
							</li>
							<li class="item">
								<div class="ico">
									<svg class="image"><use xlink:href="#tree"></use></svg>
								</div>
								<p class="info">Comfort necessary to get you through any type of weather</p>
							</li>
						</ul>
					</div>
					<img src="<?php echo get_template_directory_uri(  ) . '/assets/images/goodville_images/crossing.jpg' ?>" alt="" class="about__background">
				</div>
		</section>


		<a name="goodville-product_req"></a>
		<section class="goodville-subscription">
			<div class="goodville-container">
				<div class="goodville-subscription__block">
					<div class="subscription__box">
						<h2 class="goodville-title subscription__title">Sign up & get discount</h2>
						<p class="subscription__subtitle">Enter your email below to get code</p>
						<div class="cmp-form">
							<form id="form-add-subscription">
								<div class="cmp-form__combined">
									<input type="email" class="goodville-input cmp-form__input" required placeholder="Enter email here" name="cliEmail">
									<button class="goodville-button cmp-button cmp-button_red">Get my code</button>
								</div>
							</form>
						</div>
						<?php //echo do_shortcode('[newsletter_form form="1"]');?>
					</div>
				</div>
			</div>	
		</section>
		</main><!-- #main -->
	</div><!-- #primary -->

	<div class="cmp-popup cmp-popup_medium" id="gdvl-subscrip-message">
		<div class="cmp-popup__block">
			<div class="cmp-popup__close">
				<svg><use xlink:href="#close"></use></svg>
			</div>
			<div class="cmp-popup__scroll">
				<svg class="cmp-success-check-mark" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" style="display:none">
					<circle class="path circle" fill="none" stroke="#32CD32" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
					<polyline class="path check" fill="none" stroke="#32CD32" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
				</svg>
				<p class="cmp-popup__message"></p>
			</div>
		</div>
	</div>
<?php
//do_action( 'storefront_sidebar' );
get_footer();
