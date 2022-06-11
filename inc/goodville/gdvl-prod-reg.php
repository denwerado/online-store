<?php
    add_action( 'wp_ajax_gdv_prod_reg', 'gdv_prod_reg' );
    add_action( 'wp_ajax_nopriv_gdv_prod_reg', 'gdv_prod_reg' );
    

    /**
     * Subscription and product registration function
     */
    function gdv_prod_reg(){
        global $wpdb;

        $result;

        /**
         * Get formData
         */
        $cliFirst = (string)$_POST["formData"]["cliFirst"];
        $clitLast = (string)$_POST["formData"]["clitLast"];
        $cliZip = (string)$_POST["formData"]["cliZip"];
        $cliState = (string)$_POST["formData"]["cliState"];
        $cliCountry = (string)$_POST["formData"]["cliCountry"];
        $cliStyleNumb = (string)$_POST["formData"]["cliStyleNumb"];
        $cliDatePurch = date('Y-m-d',strtotime((string)$_POST["formData"]["cliDatePurch"]));
        $cliEmail = (string)$_POST["formData"]["cliEmail"];

        /**
         * Check style Number
         */
        $requestExistBoots = $wpdb->get_results (
            $wpdb->prepare(
                "SELECT * FROM `gdvl_prod_reg` WHERE `styleNumber` = (%s)",
                $cliStyleNumb
            )
        );


        if(!$requestExistBoots){
            /**
             * Check subscriber
             */

            $checkSubscriber = gdvl_checkingExistSubscrib($cliEmail);

            if(!$checkSubscriber){gdvl_createSubscriberNewsLetter($cliEmail,$cliFirst,$clitLast);}


            /**
             * Get id email newsletter
             */
            $requestGetIdNewsLetter = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT `id` FROM `wp_newsletter` WHERE `email` = (%s)",
                    $cliEmail
                )
            );
            $newsLetEmailId = $requestGetIdNewsLetter;


            /**
             * Insert data into a table
             */
            $wpdb->query (
                $wpdb->prepare(
                    "INSERT INTO `gdvl_prod_reg` (`newsLetEmailId`,`zipCode`,`state`,`country`,`styleNumber`,`dateOfPurchase`) VALUES (%s,%s,%s,%s,%s,%s)",
                    array(
                        $newsLetEmailId,
                        $cliZip,
                        $cliState,
                        $cliCountry,
                        $cliStyleNumb,
                        $cliDatePurch
                    )
                )
            );

            
            /**
             * Generating a coupon and sending it to the mail
             */
            $coupon_code = '';
            do{
                $coupon_code = gdvl_generateRandomString(12);
            }while(post_exists($coupon_code));

            $coupon_code_expirationDate = date('Y-m-d',strtotime(date('Y-m-d') . '+3 month'));
            gdvl_addCouponCode($coupon_code,$coupon_code_expirationDate,10);

            $messageSuccess = 'Success! The product is registered, you have subscribed for updates, a discount coupon code has been sent to your email.';
            $messageError = 'The product is registered, but the coupon code has not been sent to the mail. Please contact technical support.';

            require dirname(__FILE__) . '/phpmailer/send.php';
        }else{
            
            $result = 'exists';
            $message = 'Error! Products with this number already exist. If this is really your product, then contact technical support.';
            echo json_encode(["result" => $result,'message'=>$message]);
        }

        wp_die();
    }

?> 