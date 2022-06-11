jQuery(document).ready(function($){
    //#####Header burger#####
    $('.goodville-header__burger').on('click', ()=>{
        $('.header__nav').toggleClass('header__nav_active');
        $('.goodville-header__burger').toggleClass('goodville-header__burger_active');
    });

    $('.header__nav .item a').on('click',()=>{
        $('.header__nav').removeClass('header__nav_active');
        $('.goodville-header__burger').removeClass('goodville-header__burger_active');
    });

    $(window).on('click',function(e){
        let element = $(e.target);
        if(!element.closest('.header__nav').length && !element.closest('.goodville-header__burger').length){
            $('.header__nav_active').removeClass('header__nav_active');
            $('.goodville-header__burger_active').removeClass('goodville-header__burger_active');
        }
    });
    //#####-----#####



    //######Smoothness of anchors on the document######
    $('a[href*="#"]').on("click", function(e){
        //e.preventDefault();
        let anchor = $(this);
        let href = anchor.attr('href').split('#')[1];

        let anchorEl = $('[name="' + href + '"]');

        $('html, body').stop().animate({
            scrollTop: anchorEl.offset().top - 60
        }, 1000);
    });
    //#####-----#####



    //######Pop-ups######
    $('[data-modal*="#"]').on("click", function(event){
        let button = event.target;
        let dataModal = button.dataset.modal;

        while(!dataModal){
            button = button.parentElement
            dataModal = button.dataset.modal;
        }

        let modal = $(dataModal);
        modal.addClass('cmp-popup_active');

        $('html, body').css('overflow', 'hidden');
    });

    $(document).on('click','.cmp-popup__close',function(e){
        let modal = $(e.target).closest('.cmp-popup_active');

        modal.removeClass('cmp-popup_active');
        $('html, body').css('overflow', 'visible');
    });

    $(document).on('click','.cmp-popup_active',function(e){
        if($(e.target).hasClass("cmp-popup_active")){

            $(e.target).removeClass('cmp-popup_active');
            $('html, body').css('overflow', 'visible');
        }
    });
    //#####-----#####



    //#####Main slider#####
    let mainSlider = $('#main-slider');

    if(mainSlider){
        mainSlider.owlCarousel({
            loop:true,
            dots: true,
            smartSpeed: 1000,
            items: 1
        });

        $('#main-slider-prev').on('click', ()=>{
            mainSlider.trigger('prev.owl.carousel');
        });

        $('#main-slider-next').on('click', ()=>{
            mainSlider.trigger('next.owl.carousel');
        });
    }
    //#####-----#####



    //#####Category slider#####
    let categoriesSlider = $('#categories-slider');

    if(categoriesSlider){
        categoriesSlider.owlCarousel({
            loop:true,
            nav: false,
            smartSpeed: 1000,
            responsive:{
                0:{
                    items: 1,
                    dots: true
                },
                576:{
                    items: 2,
                },
                768:{
                    items: 2,
                    dots: false
                }
            }
        });

        $('#categories-slider-prev').on('click', ()=>{
            categoriesSlider.trigger('prev.owl.carousel');
        });

        $('#categories-slider-next').on('click', ()=>{
            categoriesSlider.trigger('next.owl.carousel');
        });
    }
    //#####-----#####



    //#####Advantages slider#####
    let advantagesSlider = $('#advantages-slider');

    if(advantagesSlider){
        advantagesSlider.owlCarousel({
            loop:true,
            smartSpeed: 1000,
            dots: true,
            responsive:{
                0:{
                    items: 1,
                    dots: true
                },
                768:{
                    items: 1,
                    dots: false
                }
            }
        });

        $('#advantages-slider-prev').on('click', ()=>{
            advantagesSlider.trigger('prev.owl.carousel');
        });

        $('#advantages-slider-next').on('click', ()=>{
            advantagesSlider.trigger('next.owl.carousel');
        });
    }
    //#####-----#####



    //#####Product slider#####
    let productSlider = $('#product-slider');

    if(productSlider){
        let productSliderDots = $('#product-slider-pagination');

        productSliderDots.owlCarousel({
            loop:false,
            smartSpeed: 1000,
            nav: false,
            responsive:{
                0:{
                    items:3
                }
            }
        });

        productSlider.owlCarousel({
            loop:true,
            smartSpeed: 1000,
            nav: false,
            dots: true,
            dotsContainer: productSliderDots,
            onInitialized: carouselInitialized,
            responsive:{
                0:{
                    items:1
                }
            }
        });

        function carouselInitialized (){
            productSliderDots.find('.owl-item').click(function () {
                productSlider.trigger('to.owl.carousel', [$(this).index(), 300]);
            });
        }
    }
    //#####-----#####



    //######Переключение коллекций товаров по категориям#####
    $('[data-collection-toggle]').on('click', (event)=>{
        let categoryName = event.target.getAttribute("data-collection-toggle");

        $('.cmp-product-cards__menu .active').removeClass('active');
        $('.cmp-product-cards__menu [data-collection-toggle="'+categoryName+'"]').addClass('active');

        $('[data-collection-category]').css({'display':'none'});
        $('.cmp-product-cards__accordeon .cmp-product-cards__message').css({'display':'none'});
        $('.cmp-product-cards__content').slideUp();

        $('.cmp-product-cards__categories [data-collection-category="'+categoryName+'"]').css({'display':'flex'});
        $('.cmp-product-cards__accordeon button').css({'display':'block'});
    });
    //######-----#####



    //#####Открытие аккордеона c товарами#####
    $('.cmp-product-cards__accordeon button').on('click', (event)=>{
        let accordeon = $(event.target).closest('.cmp-product-cards__accordeon');
        let categoryName = $('.cmp-product-cards__menu .active')[0].getAttribute("data-collection-toggle");


        $(event.target).css({'display':'none'});1

        let category = accordeon.find('[data-collection-category="'+categoryName+'"]');
        let cardsItem = category.find('.cmp-product-cards__item');

        let displayCardsItem = false;
        cardsItem.each(function(index, element){
            if ($(element).css('display') != 'none'){
                displayCardsItem = true;
            }
        });

        if(category.length && displayCardsItem){
            category.css({'display':'flex'});
        }else{
            accordeon.find('.cmp-product-cards__message').css({'display':'block'});
        }

        $('.cmp-product-cards__content').slideDown();
        
    });
    //#####------######



    //#####Переключение аккордеона#####
    $('.cmp-accordeon').on('click', function(e){
        let cmpAccordeonActive = $('.cmp-accordeon_active');
        cmpAccordeonActive.removeClass('cmp-accordeon_active');
        cmpAccordeonActive.find('.cmp-accordeon__content').slideUp();

        let cmpAccordeon = $(e.target).closest('.cmp-accordeon');

        if(cmpAccordeonActive[0] != cmpAccordeon[0]){
            cmpAccordeon.addClass('cmp-accordeon_active');
            cmpAccordeon.find('.cmp-accordeon__content').slideDown();
        }
    })
    //#####-----######



    //#####Увеличение количества товаров#####
    $('.cmp-form__count .change__count-increase').on('click',function(event){
        let changeCount = $(event.target);
        let inputNumber = changeCount.closest('.cmp-form__count').find('input');
        inputNumber[0].value++;

        let myEvent = new Event("change",{bubbles: true});
        inputNumber[0].dispatchEvent(myEvent);
    });

    $('.cmp-form__count .change__count-decrease').on('click',function(event){
        let changeCount = $(event.target);
        let inputNumber = changeCount.closest('.cmp-form__count').find('input');
        if(inputNumber[0].value > 1){
            inputNumber[0].value--;
        }

        let myEvent = new Event("change",{bubbles: true});
        inputNumber[0].dispatchEvent(myEvent);
    });
    //#####-----#####


    //#####Product Registry#####
    let prodRegForm = $('#form-prod-reg');

    if(prodRegForm.length){
        prodRegForm.on('submit', (event)=>{
            event.preventDefault();

            $('.cmp-loader').css('display','block');
            $('html, body').css('overflow', 'hidden');

            let formData = new Object;

            $.each($(event.target).serializeArray(),function(){
                formData[this.name] = this.value;
            });

            let data = {
                action: 'gdv_prod_reg',
                formData: formData
            };

            jQuery.post('/wp-admin/admin-ajax.php', data, function( response ){
                let answerData = JSON.parse(response);
                messageOutputSubscrip(answerData);
            } );

        });
    };
    //#####-----#####


    //#####Add subscription#####
    let addSubscriptionForm = $('#form-add-subscription');

    if(addSubscriptionForm.length){
        addSubscriptionForm.on('submit', (event)=>{
            event.preventDefault();

            $('.cmp-loader').css('display','block');
            $('html, body').css('overflow', 'hidden');

            let formData = new Object;

            $.each($(event.target).serializeArray(),function(){
                formData[this.name] = this.value;
            });

            let data = {
                action: 'gdv_add_subscrip',
                formData: formData
            };

            jQuery.post('/wp-admin/admin-ajax.php', data, function( response ){
                let answerData = JSON.parse(response);

                messageOutputSubscrip(answerData);
            } );

        });
    }
    //#####-----#####


    function messageOutputSubscrip(answerData){
        let message = answerData['message'];
        let result = answerData['result'];

        $('.cmp-loader').css('display','none');

        let gdvlSubscripMessage = $('#gdvl-subscrip-message');
        gdvlSubscripMessage.addClass('cmp-popup_active');

        gdvlSubscripMessage.find('.cmp-popup__message').text(message);

        if(result == 'success'){
            gdvlSubscripMessage.find('.cmp-success-check-mark').css('display','block');
            gdvlSubscripMessage.find('.cmp-popup__message').addClass('success');
        }else{
            gdvlSubscripMessage.find('.cmp-success-check-mark').css('display','none');
            gdvlSubscripMessage.find('.cmp-popup__message').addClass('error');
        }
    }



    //#####Latin input#####
    $('.latin-input').bind('keydown paste', function(event) {
        var that = this;

        setTimeout(function() {
            let inputLetter = /[^a-zA-Z]/g.exec(that.value);

            let inputValidate = $(event.target).closest('.cmp-form__validate');
            let inputLegend = inputValidate.find('legend');
            $('.cmp-form__validate').removeClass('error');

            if (inputLetter){
                that.value = that.value.replace(inputLetter, '');
                inputValidate.addClass('error');
                inputLegend.text('Error! You can only enter Latin letters');
            }else{
                inputLegend.text('');
            }

            do{
                inputLetter = /[^a-zA-Z]/g.exec(that.value);

                if (inputLetter){
                    that.value = that.value.replace(inputLetter, '');
                }
            }while( inputLetter );

        }, 0);
    });

    $('.latin-input').on( "blur", function(event) {
        let inputValidate = $(event.target).closest('.cmp-form__validate');
        inputValidate.removeClass('error');
    });
    //#####-----#####



    //#####Number input#####
    $('.number-input').bind('keydown paste', function(event) {
        let that = this;

        setTimeout(function() {
            let inputLetter = /[^0-9]/g.exec(that.value);
    
            let inputValidate = $(event.target).closest('.cmp-form__validate');
            let inputLegend = inputValidate.find('legend');
            $('.cmp-form__validate').removeClass('error');

            if (inputLetter){
                that.value = that.value.replace(inputLetter, '');
                inputValidate.addClass('error');
                inputLegend.text('Error! You can only enter numbers');
            }else{
                inputLegend.text('');
            }

            do{
                inputLetter = /[^0-9]/g.exec(that.value);

                if (inputLetter){
                    that.value = that.value.replace(inputLetter, '');
                }
            }while( inputLetter );
        }, 0);
    });

    $('.number-input').on("blur", function(event) {
        let inputValidate = $(event.target).closest('.cmp-form__validate');
        inputValidate.removeClass('error');
    });
    //#####-----#####


    
    //#####Date input#####
    $(".date-input").datepicker({
        dateFormat: "dd.mm.yyyy",
        maxDate: new Date(),
        onSelect: function(dateText) {
            $('.date-input').trigger('paste');
        }
    });

    $('.date-input').bind('keydown paste', function(event) {
        $(event.target).closest('.cmp-form__validate').removeClass('error');

        let that = this;

        if(that.oldLenght <= that.value.length){
            switch (that.value.length) {
                case 2:
                    that.value += '.'
                    break;
                case 5:
                    that.value += '.'
                    break;
                default:
                    break;
            }
        }
    

        setTimeout(function() {
            let inputLetter = /[^0-9.]/g.exec(that.value);

            if (inputLetter){
                that.value = that.value.replace(inputLetter, '');
            }

            do{
                inputLetter = /[^0-9.]/g.exec(that.value);

                if (inputLetter){
                    that.value = that.value.replace(inputLetter, '');
                }
            }while( inputLetter );


            if(that.value.toString().slice(-1) == '.' && that.value.length!=3 && that.value.length!=6){
                that.value = that.value.replace(/.$/,"");
            }

            if(that.value.length > 10){
                that.value = that.value.replace(/.$/,"");
            }
        },0);

        that.oldLenght = that.value.length;
    });

    $('.date-input').on('blur', function(event) {
        let that = this;

        let inputValidate = $(event.target).closest('.cmp-form__validate');
        let inputLegend = inputValidate.find('legend');
        $('.cmp-form__validate').removeClass('error');

        if(!Date.parse(that.value)){
            inputValidate.addClass('error');
            inputLegend.text('Error! Enter a valid date');
            that.value = '';
        }
    });
    //#####-----#####
});