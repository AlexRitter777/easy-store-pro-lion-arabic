
jQuery(document).ready(function($){

    // HTML content for the mobile category menu button
    let menuButtonContent = `<div style="display: flex;justify-content: space-between;">
                                 <i class="fa fa-list-ul"></i>
                                 <span>Product categories</span>
                                 <i class="fa fa-caret-down"></i>
                             </div>`;


    let categoriesWidgetTitle = $('.widget_product_categories .widget-title');
    let searchSectionOrigin = $('.es-header-logo-wrapper .easy_store_advanced_product_search');
    let cartIconOrigin = $('.es-header-area-cart-wrapper #site-header-cart');
    let wishListIconOrigin = $('.es-header-area-cart-wrapper .es-wishlist-wrap');

    let resizeTimer;

    // Toggles the visibility of product categories on mobile
    function bindClickEvent() {
        if ($(window).width() <= 768) {
            $(categoriesWidgetTitle).off('click').on('click', function() {
                changeCategorySign();
                $('.widget_product_categories ul').slideToggle();
            })
        } else {
            $('.widget_product_categories .widget-title').off('click');
            $('.widget_product_categories ul').removeAttr( 'style' );
        }
    }

    // Updates the category menu title for mobile
    function changeCategoryMenu(){
        if ($(window).width() <= 768){
            $('.widget_product_categories .widget-title').empty().append(menuButtonContent);
        }
    }

    // Moves the search section to appropriate location based on screen size
    function moveSearchSection() {
        let searchSection = $('.es-header-logo-wrapper .easy_store_advanced_product_search');
        if ($(window).width() <= 768 ) {
            if(searchSection.length > 0){
                searchSection.remove();
                $('header').append(searchSection);
            }
        } else {
            let searchSectionMobile = $('.es-main-menu-wrapper').next();
            if(searchSectionMobile.length > 0) {
                $('header .easy_store_advanced_product_search').remove();
                $('.es-header-area-cart-wrapper').prepend(searchSectionOrigin);
            }
        }
    }

    // Moves the cart icon to appropriate location based on screen size
    function moveCartIcon(){
        let cartIcon = $('.es-header-area-cart-wrapper #site-header-cart');
        if ($(window).width() <= 768){
            if(cartIcon.length > 0){
                cartIcon.remove();
                $('.es-main-menu-wrapper .mt-container').append(cartIcon);
            }
        } else {
            let cartIconMobile = $('.es-main-menu-wrapper #site-header-cart');
            if(cartIconMobile.length > 0) {
                $(cartIconMobile).remove();
                $('.es-header-area-cart-wrapper').append(cartIconOrigin);
            }
        }
    }

    // Moves the wishlist icon to appropriate location based on screen size
    function moveWishListIcon(){
        let wishListIcon = $('.es-header-area-cart-wrapper .es-wishlist-wrap');
        if ($(window).width() <= 768){
            if(wishListIcon.length > 0){
                wishListIcon.remove();
                $('.es-main-menu-wrapper .mt-container').append(wishListIcon);
            }
        } else {
            let wishListIconMobile = $('.es-main-menu-wrapper .es-wishlist-wrap');
            if(wishListIconMobile.length > 0) {
                $(wishListIconMobile).remove();
                $('.es-header-area-cart-wrapper').append(wishListIconOrigin);
            }
        }
    }


    // Toggles the category menu icon between "up" and "down" states
    function changeCategorySign(){
        let categoriesVisibility = $('.widget_product_categories .product-categories').css('display');
        console.log(categoriesVisibility);
        if (categoriesVisibility === 'none'){
            $('.widget-title .fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
        }else {
            $('.widget-title .fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
        }
    }

    // Runs all functions for screen adjustments
    function runFunctions(){
        bindClickEvent();
        changeCategoryMenu();
        moveSearchSection();
        moveCartIcon();
        moveWishListIcon();
    }

    // Initial function calls on page load
    runFunctions();

    // Handles screen resize with debounce logic
    $(window).resize(function() {
        clearTimeout(resizeTimer);
        if($(window).width() < 700  || $(window).width() > 800){
            resizeTimer = setTimeout(function (){
            runFunctions();
            }, 200)
        } else {
            runFunctions();
        }
    });


});