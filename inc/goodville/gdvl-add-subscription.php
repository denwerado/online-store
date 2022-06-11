<?php
    add_action( 'wp_ajax_gdv_add_subscrip', 'gdv_add_subscrip' );
    add_action( 'wp_ajax_nopriv_gdv_add_subscrip', 'gdv_add_subscrip' );

    function gdv_add_subscrip(){
        $result;

        $cliEmail = (string)$_POST["formData"]["cliEmail"];

        $checkSubscriber = gdvl_checkingExistSubscrib($cliEmail);

        if(!$checkSubscriber){
            gdvl_createSubscriberNewsLetter($cliEmail,'','');

            /**
             * Generating a coupon and sending it to the mail
             */
            $coupon_code = '';
            do{
                $coupon_code = gdvl_generateRandomString(12);
            }while(post_exists($coupon_code));

            $coupon_code_expirationDate = date('Y-m-d',strtotime(date('Y-m-d') . '+3 month'));
            gdvl_addCouponCode($coupon_code,$coupon_code_expirationDate,5);

            $messageSuccess = 'Success! You have subscribed for updates, a discount coupon code has been sent to your email.';
            $messageError = 'You have subscribed, but the coupon code has not been sent to the mail. Please contact technical support.';

            require dirname(__FILE__) . '/phpmailer/send.php';

        }else{
            $result = 'exists';
            $message = 'Error! You have already subscribed!';
            echo json_encode(["result" => $result, 'message' => $message]);
        }
        
        
        wp_die();
    }
?>