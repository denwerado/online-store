<?php
    /**
     * Resizing thumbnails in the gallery on the product page
     */
    add_filter('woocommerce_get_image_size_gallery_thumbnail','add_gallery_thumbnail_size',1,10);
    function add_gallery_thumbnail_size($size){
        $size['width'] = 300;
        $size['height'] = 300;
        $size['crop']   = 0;
        return $size;
    }
?>