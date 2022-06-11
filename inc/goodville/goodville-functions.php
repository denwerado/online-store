<?php
    //**Custom Theme features */


    /**
     * Checking the existence of products in the category 
     * $slug - slug метки таксономии
     * $offset - отсуп от первого элемента
     * $postsPerPage - кол-во показываемых постов
    */
    function goodville_check_products_сat($slug, $offset, $postsPerPage){
        $argsProducts = array(
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $slug,
                ),
                array(
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => 'main-product',
                    'operator' => 'NOT IN'
                )
            ),
            'post_type' => 'product',
            'offset' => $offset,
            'posts_per_page' => $postsPerPage
        );

        $products = new WP_Query($argsProducts);

        if($products->posts){
            return true;
        }else{
            return false;
        }
    }
    


    /**
     * Output of products by category 
     * $catName - имя категории
     * $slug - slug метки таксономии
     * $offset - отсуп от первого элемента
     * $postsPerPage - кол-во показываемых постов
     */
    function goodville_output_products_сat($catName, $slug, $offset, $postsPerPage){
        $argsProducts = array(
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $slug,
                ),
                array(
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => 'main-product',
                    'operator' => 'NOT IN'
                )
            ),
            'post_type' => 'product',
            'offset' => $offset,
            'posts_per_page' => $postsPerPage
        );

        $products = new WP_Query($argsProducts);

    ?>
        <div class="cmp-product-cards__category" data-collection-category="<?php echo $catName;?>-category">
            <?php
                foreach ($products->posts as $product){
                    $productWoocommerce = new WC_Product($product->ID);
            ?>
                    <a href="<?php echo $product->guid;?>" class="goodville-link cmp-product-cards__item">
                        <div class="wrap">
                            <img src="<?php echo wp_get_attachment_url($productWoocommerce->get_image_id());?>" alt="" class="image">
                            <div class="info">
                                <p class="title"><?php echo $product->post_title;?></p>
                                <span class="price">$ <?php echo $productWoocommerce->get_price();?></span>
                            </div>
                        </div>
                    </a>
            <?php
                }
            ?>
        </div>
    <?php
    }


    /**
     * Output of all products in the all category
     * $allName - имя для обертки всех товаров
     * $offset - отсуп от первого элемента
     * $postsPerPage - кол-во показываемых постов
     */
    function goodville_output_products_all($allName, $offset, $postsPerPage){
        $argsProducts = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => 'main-product',
                    'operator' => 'NOT IN'
                ),
            ),
            'post_type' => 'product',
            'offset' => $offset,
            'posts_per_page' => $postsPerPage
        );

        $products = new WP_Query($argsProducts);

    ?>
        <div class="cmp-product-cards__category" data-collection-category="all-category" style="display: flex;">
            <?php
                foreach ($products->posts as $product){
                    $productWoocommerce = new WC_Product($product->ID);
            ?>
                    <a href="<?php echo $product->guid;?>" class="goodville-link cmp-product-cards__item">
                        <div class="wrap">
                            <img src="<?php echo wp_get_attachment_url($productWoocommerce->get_image_id());?>" alt="" class="image">
                            <div class="info">
                                <p class="title"><?php echo $product->post_title;?></p>
                                <span class="price">$ <?php echo $productWoocommerce->get_price();?></span>
                            </div>
                        </div>
                    </a>
            <?php
                }
            ?>
        </div>
    <?php
    }



    /**
     * Getting the Main product object
     * If there is none, then the most popular one is selected
     */
    function get_main_product(){
        $argsMainProduct = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'product_tag' => 'main-product',
        );

        $mainProduct = new WP_Query($argsMainProduct);

        if (!$mainProduct) {
            $argsMainProduct = array(
                'post_type' => 'product',
                'posts_per_page' => 1,
                "orderby" => "popularity",
                "order" => 'ASC',
            );
        }
        $mainProduct = new WP_Query($argsMainProduct);

        if($mainProduct->posts){
            $mainProduct = $mainProduct->posts[0];
            $mainProductWoocommerce = new WC_Product($mainProduct->ID);
            return $mainProductWoocommerce;
        }else{
            return false;
        }
    }



    /**
     * Request to create a subscriber
     */
    function gdvl_createSubscriberNewsLetter($subscribEmail,$subscribFirst,$subscribLast){
        $serverUrL = 'http://' . $_SERVER['SERVER_NAME'] . '/wp-json/newsletter/v2/subscriptions';
                
        $addSubscribCurl = curl_init();
        curl_setopt_array($addSubscribCurl, array(
            CURLOPT_URL => $serverUrL,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array('accept: */*','Content-Type: application/json'),
            CURLOPT_POSTFIELDS => json_encode (
                array(         
                    'email'=> $subscribEmail,
                    'first_name'=>$subscribFirst,
                    'last_name'=>$subscribLast
                ),
                JSON_UNESCAPED_UNICODE
            ),
            CURLOPT_RETURNTRANSFER => 1
        ));

        $responseCurl = curl_exec($addSubscribCurl);
        curl_close($addSubscribCurl);
    }


    /**
     * Random string generation
     */
    function gdvl_generateRandomString($randLenght){
        $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random ='';
        for ($i=0; $i < $randLenght; $i++) { 
            //Обращается к char и с первого по последний значение рандомно выбираем символ
            $random .= $char[rand(0,(strlen($char)-1))];
        }
        return $random;
    }


    
    /**
     * Adding a coupon
     */
    function gdvl_addCouponCode($generatedСoupon,$expirationDate, $discountAmount ){
        //$amount = '5'; // Amount
        $discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product

        $coupon = array(
        'post_title' => (string)$generatedСoupon,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'shop_coupon');

        $new_coupon_id = wp_insert_post( $coupon );

        // Add meta
        update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
        update_post_meta( $new_coupon_id, 'coupon_amount', $discountAmount );
        update_post_meta( $new_coupon_id, 'individual_use', 'no' );
        update_post_meta( $new_coupon_id, 'product_ids', '' );
        update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
        update_post_meta( $new_coupon_id, 'usage_limit', '1' );
        update_post_meta( $new_coupon_id, 'expiry_date', (string)$expirationDate );
        update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
        update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
    }


    /**
     * Checking the existence of a subscriber
     */
    function gdvl_checkingExistSubscrib($checkEmail){
        global $wpdb;
        
        $requestExistSubscriber = $wpdb->get_results (
            $wpdb->prepare(
                "SELECT * FROM `wp_newsletter` WHERE `email` = (%s)",
                $checkEmail
            )
        );

        if($requestExistSubscriber){return true;}
        else{return false;}
    }
?>