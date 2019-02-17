/*
 * Custom code goes here.
 * A template should always ship with an empty custom.js
 */
jQuery(".dor-page-loading").show();
window.onload = function () { 
	setTimeout(function(){
		jQuery(".dor-page-loading").remove();
	},300);
}
var DORTHEME = {
	idShop:(typeof DOR != "undefined" && typeof DOR.id_shop != "undefined" && DOR.id_shop != null)?DOR.id_shop:1,
	init:function(){
		DORTHEME.ScrollTop();
		DORTHEME.MoveMenuLine();
		DORTHEME.DailyDeal();
		DORTHEME.Testimonials();
		DORTHEME.SortCateProduct();
		DORTHEME.RelatedProductSlide();
		DORTHEME.HeadTitle();
		DORTHEME.AboutUsFarmers();
		DORTHEME.AboutUsBrands();
		DORTHEME.SubListCategory();
		DORTHEME.ToolTipBootstrap();
		DORTHEME.ToggleSearch();
		DORTHEME.SlidePictureBlock();
		DORTHEME.ResizeCategoryWindow();
		DORTHEME.EventSite();
		DORTHEME.ViewedProductSlide();
		DORTHEME.BrandsLists();
		DORTHEME.PartnersLists();
		DORTHEME.HotDeal();
		DORTHEME.SlideImageTabs();
		DORTHEME.VerticalMenuMobile();
		//DORTHEME.SearchMobile();
		DORTHEME.HomeLastNews();
		DORTHEME.TopbarMobile();
		DORTHEME.AboutUsListUser();
		DORTHEME.ShowDisplay();
		DORTHEME.RebuildShowDisplay();
		var headerfloat = localStorage.getItem("optionHeaderFloat-Shop"+DORTHEME.idShop);
		if((typeof DOR != "undefined" && typeof DOR.dorFloatHeader != "undefined" && parseInt(DOR.dorFloatHeader) == 1 && headerfloat == null) || (headerfloat != null && parseInt(headerfloat) == 1)){
			DORTHEME.ScrollFixedMenu();
		}

        if((typeof DOR != "undefined" && typeof DOR.dorSubscribe != "undefined" && parseInt(DOR.dorSubscribe) == 1)){
			var localStorPopup = localStorage.getItem("popupScrHanoiAgain-"+DORTHEME.idShop);
	        if(localStorPopup == null && parseInt(localStorPopup) != 1 && typeof sessionStorage['popupScrHanoi-'+DORTHEME.idShop] == "undefined" && sessionStorage['popupScrHanoi-'+DORTHEME.idShop] != 1){
	        	DORTHEME.ShowPopupScre();
	        }
	        if(typeof sessionStorage['popupScrHanoi-'+DORTHEME.idShop] == "undefined" && sessionStorage['popupScrHanoi-'+DORTHEME.idShop] != 1){
	        	sessionStorage['popupScrHanoi-'+DORTHEME.idShop] = 1;
	        }
	        jQuery("input[name='notShowSubs']").change(function(){
	        	if($(this).is(':checked')){
	        		localStorage.setItem("popupScrHanoiAgain-"+DORTHEME.idShop,1);
	        	}else{
	        		localStorage.removeItem("popupScrHanoiAgain-"+DORTHEME.idShop);
	        	}
	        });
        }

        jQuery("#_mobile_user_info").click(function(){
        	var checkOpen = jQuery(this).hasClass("open");
        	if(!checkOpen){
        		jQuery(this).addClass("open");
        	}else{
        		jQuery(this).removeClass("open");
        	}
        });
        jQuery(document).click(function (event) {
            if (!jQuery(event.target).is("#_mobile_user_info, #_mobile_user_info *")) {
                jQuery("#_mobile_user_info").removeClass("open");
            }
        });
        jQuery("#searchbox .pos_search").click(function(){
        	var checkOpen = jQuery(this).hasClass("open");
        	if(!checkOpen){
        		jQuery(this).addClass("open");
        	}else{
        		jQuery(this).removeClass("open");
        	}
        });
        jQuery(document).click(function (event) {
            if (!jQuery(event.target).is("#searchbox, #searchbox *")) {
                jQuery("#searchbox .pos_search").removeClass("open");
            }
        });
	},
	ReloadPage:function(status){
		var reload = '<div class="dor-page-loading">';
			          reload += '<div id="loader"></div>';
			          reload += '<div class="loader-section section-left"></div>';
			          reload += '<div class="loader-section section-right"></div>';
			      reload += '</div>';
		jQuery(".dor-page-loading").remove();
		if(status == 0){
			jQuery(reload).appendTo("body");
			jQuery(".dor-page-loading").show().css("opacity",0.8);
		}
		
	},
	ShowDisplay:function(){
		$('body').on('click','.dor-display-cate a', function( event ) {
			event.preventDefault();
			var objId = jQuery(this).attr("data-id");
			localStorage.removeItem("dorDisplay-"+DORTHEME.idShop);
			localStorage.setItem("dorDisplay-"+DORTHEME.idShop,objId);
			DORTHEME.DisplayAct(objId);
		});
		
	},
	DisplayAct:function(objId){
		jQuery(".dor-display-cate a").removeClass("active");
		jQuery(".dor-display-cate a[data-id='"+objId+"']").addClass("active");
		if(objId == "list"){
			jQuery("#js-product-list article.product-miniature").addClass("dor-list-display");
			jQuery("#category article.product-miniature").each(function(){
				jQuery(this).find(".left-block .category-action-buttons").detach().insertAfter(jQuery(this).find('.right-block #product-description-short'));
				jQuery(this).find(".dor-wishlist").detach().insertAfter(jQuery(this).find('.right-block .category-action-buttons .action-button > ul'));
				jQuery(".addToDorWishlist").attr('data-placement',"top");
				setTimeout(function(){
					$('[data-toggle="tooltip"]').tooltip();
				},1000);
			});
			
		}else{
			jQuery("#js-product-list article.product-miniature").removeClass("dor-list-display");
			jQuery("#category article.product-miniature").each(function(){
				jQuery(this).find(".right-block .category-action-buttons .action-button > .dor-wishlist").detach().insertAfter(jQuery(this).find('.left-block'));
				jQuery(this).find(".right-block .dor-product-description .category-action-buttons").detach().insertAfter(jQuery(this).find('.left-block .product-image-container'));
				jQuery(".addToDorWishlist").attr('data-placement',"left");
				setTimeout(function(){
					$('[data-toggle="tooltip"]').tooltip();
				},1000);
			});
		}
	},
	RebuildShowDisplay:function(){
		var display = localStorage.getItem("dorDisplay-"+DORTHEME.idShop);
		if(display == null){
			if(typeof DOR != "undefined" && typeof DOR.dorCategoryShow != "undefined" && DOR.dorCategoryShow == "list"){
				display = "list";
			}else{
				display = "grid";
			}
		}
		DORTHEME.DisplayAct(display);
	},
	ShowPopupScre:function(){
		$('.subscribe-me').bPopup({
            speed: 450,
            transition: 'slideDown'
        });
	},
	EventSite:function(){
		var idObjPage = jQuery("body").attr("id");
		if(idObjPage == "category"){
			if(parseInt($( window ).width()) <= 991){
			  	jQuery("#left-column").detach().insertAfter('#content-wrapper');
			}
			DORTHEME.ResizeCategoryWindow();
		}
		
	},
	TopbarMobile:function(){
		jQuery("#_mobile_currency_selector > .currency-selector > span, #_mobile_currency_selector > .currency-selector > a").click(function(){
			var checkOpen = jQuery(this).closest(".currency-selector").hasClass("open");
			if(!checkOpen){
				jQuery(this).closest(".currency-selector").addClass("open");
			}else{
				jQuery(this).closest(".currency-selector").removeClass("open");
			}
		});
		jQuery("#_mobile_language_selector .language-selector-wrapper .language-selector > span, #_mobile_language_selector .language-selector-wrapper .language-selector > a").click(function(){
			var checkOpen = jQuery(this).closest(".language-selector-wrapper").hasClass("open");
			if(!checkOpen){
				jQuery(this).closest(".language-selector-wrapper").addClass("open");
			}else{
				jQuery(this).closest(".language-selector-wrapper").removeClass("open");
			}
		});
		jQuery(document).click(function (event) {
            if (!jQuery(event.target).is("#_mobile_currency_selector, #_mobile_currency_selector *")) {
                jQuery("#_mobile_currency_selector > .currency-selector").removeClass("open");
            }
            if (!jQuery(event.target).is("#_mobile_language_selector, #_mobile_language_selector *")) {
                jQuery("#_mobile_language_selector > .language-selector-wrapper").removeClass("open");
            }
        });
	},
	SearchMobile:function(){
		jQuery(".header-menu-item-icon > a").click(function(){
			var checkOpen = jQuery(".dorHeaderSearch-Wapper").hasClass("open");
			if(!checkOpen){
				jQuery(".dorHeaderSearch-Wapper").addClass("open");
			}else{
				jQuery(".dorHeaderSearch-Wapper").removeClass("open");
			}
		});
		if(parseInt($( window ).width()) <= 991){
			jQuery(document).click(function (event) {
	            if (!jQuery(event.target).is(".dorHeaderSearch-Wapper, .dorHeaderSearch-Wapper *, .header-menu-item-icon, .header-menu-item-icon *")) {
	                jQuery(".dorHeaderSearch-Wapper").removeClass("open");
	            }
	        });
	        jQuery(".dorHeaderSearch-Wapper").detach().insertBefore('.dorTopbarContent');
		}
	},
	VerticalMenuMobile:function(){
		jQuery(".fa-icon-menu").click(function(){
			var checkOpen = jQuery("#dor-verticalmenu").hasClass("open");
			var checkClose = jQuery("#dor-verticalmenu").hasClass("menuclose");

			if(!checkOpen){
				jQuery("#dor-verticalmenu").addClass("open");
				jQuery("#dor-verticalmenu").removeClass("menuclose");
			}else{
				jQuery("#dor-verticalmenu").removeClass("open");
				jQuery("#dor-verticalmenu").addClass("menuclose");
			}
			if(parseInt($( window ).width()) >= 992){
				if(!checkClose && !checkOpen && prestashop.page.page_name == "index"){
					jQuery("#dor-verticalmenu").removeClass("open");
					jQuery("#dor-verticalmenu").addClass("menuclose");
				}
			}
			$( window ).resize(function() {
			  var widthW = $( window ).width();
			  var checkOpen = jQuery("#dor-verticalmenu").hasClass("open");
			  var checkClose = jQuery("#dor-verticalmenu").hasClass("menuclose");
			  if(parseInt(widthW) >= 992){
			  	if(!checkClose && !checkOpen && prestashop.page.page_name == "index"){
					jQuery("#dor-verticalmenu").removeClass("open");
					jQuery("#dor-verticalmenu").addClass("menuclose");
				}else if(checkClose && prestashop.page.page_name == "index"){
					jQuery("#dor-verticalmenu").addClass("open");
					jQuery("#dor-verticalmenu").removeClass("menuclose");
				}
			  }else{
			  	if(checkOpen && prestashop.page.page_name == "index"){
					jQuery("#dor-verticalmenu").removeClass("open");
					jQuery("#dor-verticalmenu").addClass("menuclose");
				}
			  }
			});
			
		});
		jQuery(".dor-verticalmenu .navbar ul.verticalmenu.nav li.parent > span.expand").click(function(){
			var _this = this;
			var isVisible = $(this).closest("li.parent").hasClass( "open" );
			jQuery(".dor-verticalmenu .navbar ul.verticalmenu.nav > li").removeClass("open");
			if(!isVisible){
				jQuery(this).closest("li.parent").addClass("open");
			}else{
				jQuery(this).closest("li.parent").removeClass("open");
			}
		});
	},
	AboutUsListUser:function(){
		$('.about-us-lists').owlCarousel({
	        items: 4,
	        loop: true,
	        nav: true,
	        autoplay: false,
	        margin:20,
	        responsive: {
	            0: {items: 1},
	            1500: {items: 4},
	            1200: {items: 4},
	            990: {items: 3},
	            767: {items: 2},
	            551: {items: 2},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	},
	HomeLastNews:function(){
		$('.gst-post-list').owlCarousel({
	        items: 3,
	        loop: true,
	        nav: true,
	        autoplay: false,
	        margin:30,
	        responsive: {
	            0: {items: 1},
	            1500: {items: 3},
	            1200: {items: 3},
	            990: {items: 3},
	            767: {items: 2},
	            551: {items: 2},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	},
	HotDeal:function(){
		$('.dailydeal-carousel > ul').owlCarousel({
	        items: 5,
	        loop: true,
	        navigation: true,
	        nav: true,
	        autoplay: false,
	        margin:20,
	        responsive: {
	            0: {items: 1},
	            1200: {items: 5},
	            1165: {items: 4},
	            990: {items: 4},
	            650: {items: 3},
	            370: {items: 2},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	},
	BrandsLists:function(){
		$('.brands-lists > ul').owlCarousel({
	        items: 6,
	        loop: true,
	        navigation: true,
	        nav: true,
	        autoplay: false,
	        lazyLoad:true,
	        responsive: {
	            0: {items: 1},
	            1198: {items: 6},
	            1165: {items: 6},
	            990: {items: 4},
	            700: {items: 3},
	            600: {items: 3},
	            370: {items: 2},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	},
	PartnersLists:function(){
		$('.partners-lists > ul').owlCarousel({
	        items: 6,
	        loop: true,
	        navigation: true,
	        nav: true,
	        autoplay: true,
	        lazyLoad:true,
	        responsive: {
	            0: {items: 1},
	            1198: {items: 6},
	            1165: {items: 6},
	            990: {items: 4},
	            700: {items: 3},
	            600: {items: 3},
	            370: {items: 2},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	},
	SlideImageTabs:function(){
		$('.slide1 .slideImages, .slide2 .slideImages, .slide3 .slideImages').owlCarousel({
	        items: 1,
	        loop: true,
	        navigation: true,
	        nav: true,
	        autoplay: true,
	        lazyLoad:true,
	        responsive: {
	            0: {items: 1},
	            1198: {items: 1},
	            1165: {items: 1},
	            990: {items: 1},
	            700: {items: 1},
	            600: {items: 1},
	            480: {items: 1},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	},
	ToolTipBootstrap:function(){
		setTimeout(function(){
			$('[data-toggle="tooltip"]').tooltip();
		},2000);
	},
	ToggleSearch:function(){
		jQuery(".icon-search").click(function(e){
			e.preventDefault();
			jQuery("#header .dorHeaderSearch-Wapper").slideToggle(300,function(){
				if(jQuery(this).is(":hidden")){
					jQuery("#header .dorHeaderSearch-Wapper").addClass("open")
				}else{
					jQuery("#header .dorHeaderSearch-Wapper").removeClass("open")
				}
			});
		});

		if(parseInt($( window ).width()) <= 991){
			jQuery(".dorHeaderSearch-Wapper").detach().insertBefore('#dor-topbar');
		}else{
			jQuery(".dorHeaderSearch-Wapper").detach().insertAfter('#header_logo');
		}
		if(parseInt($( window ).width()) <= 991){
			jQuery(document).click(function (event) {
	            if (!jQuery(event.target).is("#header > .dorHeaderSearch-Wapper, #header > .dorHeaderSearch-Wapper *, .header-menu-item-icon, .header-menu-item-icon *")) {
	                jQuery(".dorHeaderSearch-Wapper").removeClass("open");
	            }
	        });
		}

		$( window ).resize(function() {
		  var widthW = $( window ).width();
		  if(parseInt(widthW) <= 991){
		  	jQuery(".dorHeaderSearch-Wapper").detach().insertAfter('#header > .header-nav');
		  }else{
		  	jQuery(".dorHeaderSearch-Wapper").detach().insertAfter('#header_logo');
		  }
		});
	},
	HeadTitle:function(){
		var checkTitle = jQuery("h1.h1").text();
		var checkPage = jQuery("body").attr("id");
		if(checkPage == "dorSmartBlogs"){
			checkTitle = jQuery(".info-title-blog > h1").text();
		}
		if(typeof checkTitle == "undefined" || checkTitle == null || checkTitle.length == 0){
			checkTitle = jQuery(".page-header h1").text();
		}
		if(typeof checkTitle == "undefined" || checkTitle == null || checkTitle.length == 0){
			checkTitle = jQuery(".title-head-card").text();
		}
		if(typeof checkTitle == "undefined" || checkTitle == null || checkTitle.length == 0){
			checkTitle = jQuery("#main > h2.h2").text();
		}
		if(typeof checkPage != "undefined" && checkPage != "category" && checkPage != "product" && typeof checkTitle != "undefined" && checkTitle != null && checkTitle.length > 0){
			jQuery("h1.category-name").text(checkTitle);
			jQuery("h1.h1").remove();
			jQuery(".page-header").remove();
			jQuery(".title-head-card").remove();
			jQuery("#main > h2.h2").remove();
		}
	},
	ResizeCategoryWindow:function(){
		$( window ).resize(function() {
		  var widthW = $( window ).width();
		  if(parseInt(widthW) <= 991){
		  	jQuery("#left-column").detach().insertAfter('#content-wrapper');
		  }else{
		  	jQuery("#left-column").detach().insertBefore('#content-wrapper');
		  }
		});
	},
	MoveMenuLine:function(){
		jQuery("#_desktop_language_selector").detach().appendTo('.dor-topbar-selector');
		jQuery("#_desktop_currency_selector").detach().appendTo('.dor-topbar-selector');
		if($( window ).width() <= 767){
			jQuery("#_mobile_language_selector").detach().appendTo('.dor-topbar-selector');
			jQuery("#_mobile_currency_selector").detach().appendTo('.dor-topbar-selector');
			jQuery("#_mobile_user_info").detach().appendTo('#_desktop_user_info');
			jQuery("#_mobile_cart").detach().appendTo('#_desktop_cart');
			jQuery("#_desktop_language_selector").remove();
			jQuery("#_desktop_currency_selector").remove();
		}
		$( window ).resize(function() {
		  var widthW = $( window ).width();
		  if(parseInt(widthW) <= 767){
		  	jQuery("#_mobile_language_selector").detach().appendTo('.dor-topbar-selector');
			jQuery("#_mobile_currency_selector").detach().appendTo('.dor-topbar-selector');
			jQuery("#_mobile_user_info").detach().appendTo('#_desktop_user_info');
			jQuery("#_mobile_cart").detach().appendTo('#_desktop_cart');
			jQuery("#_desktop_language_selector").remove();
			jQuery("#_desktop_currency_selector").remove();
		  }
		});
		jQuery(".widget-inner.block_content").each(function(){
			var checklevel = jQuery(this).find("ul").html();
			if(jQuery.trim(checklevel).length == 0){
				jQuery(this).addClass("finished-sub");
			}
		});
	},
	DailyDeal:function(){
		if (jQuery("#daily-countdown-time").length) {
			var endDate = jQuery("#endDateCountdown").val();
            $("#daily-countdown-time").countdown(endDate, function (event) {
                var $this = $(this).html(event.strftime(''
                        + '<div class="item-time"><span class="dw-time">%D</span> <span class="dw-txt">-days-</span></div>'
                        + '<div class="item-time"><span class="dw-time">%H</span> <span class="dw-txt">-hours-</span></div>'
                        + '<div class="item-time"><span class="dw-time">%M</span> <span class="dw-txt">-mins-</span></div>'
                        + '<div class="item-time"><span class="dw-time">%S</span> <span class="dw-txt">-secs-</span></div>'));
            });
        }
	},
	Testimonials:function(){
		$('.testimonials-slide').owlCarousel({
            items: 1,
            loop: true,
            navigation: false,
            nav: true,
            autoplay: true,
            margin:0,
            responsive: {
                0: {items: 1},
                1200: {items: 1},
                1165: {items: 1},
                992: {items: 1},
                768: {items: 1},
                600: {items: 1},
                480: {items: 1},
                320: {items: 1}
            },
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
        });
	},
	SubListCategory:function(){
		$('#subcategories > ul').owlCarousel({
            items: 3,
            loop: true,
            navigation: true,
            nav: false,
            autoplay: true,
            margin:30,
            responsive: {
                0: {items: 3},
                1200: {items: 3},
                1165: {items: 3},
                992: {items: 3},
                768: {items: 3},
                650: {items: 2},
                370: {items: 1},
                320: {items: 1}
            },
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
        });
	},
	SortCateProduct:function(){
		jQuery("body").on("click",".products-sort-order .select-title",function(){
			var checkShow = jQuery(".products-sort-order").hasClass("show-sort-order");
			if(!checkShow)
				jQuery(".products-sort-order").addClass("show-sort-order");
			else
				jQuery(".products-sort-order").removeClass("show-sort-order");
			return false;
		});
		jQuery("body").on("click",".select-list.js-search-link",function(){
			var title = jQuery(this).text();
			setTimeout(function(){
				jQuery(".products-sort-order .select-title").text(title);
			},2000);
		});
		jQuery(document).click(function (event) {
            if (!jQuery(event.target).is(".products-sort-order .select-title, .products-sort-order .select-title *, .products-sort-order .dropdown-menu, .products-sort-order .dropdown-menu *")) {
                jQuery(".products-sort-order").removeClass("show-sort-order");
            }
        });
	},
	RelatedProductSlide:function(){
		var checkColLeft = jQuery('#dor-left-column').html();
		var checkColRight = jQuery('#dor-right-column').html();
		if(typeof checkColLeft != "undefined" || typeof checkColRight != "undefined"){
			$('#productscategory_list_data .product_list_items').owlCarousel({
	            items: 3,
		        loop: true,
		        navigation: true,
		        nav: false,
		        autoplay: false,
		        margin:30,
		        responsive: {
		            0: {items: 1},
		            1200: {items: 3},
		            992: {items: 3},
		            650: {items: 3},
		            370: {items: 2},
		            300: {items: 1}
		        },
		        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	        });
		}else{
			$('#productscategory_list_data .product_list_items').owlCarousel({
	            items: 4,
		        loop: true,
		        navigation: true,
		        nav: false,
		        autoplay: false,
		        margin:30,
		        responsive: {
		            0: {items: 1},
		            1200: {items: 4},
		            992: {items: 4},
		            650: {items: 3},
		            370: {items: 2},
		            300: {items: 1}
		        },
		        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	        });
		}
	},
	ViewedProductSlide:function(){
		var checkColLeft = jQuery('#dor-left-column').html();
		var checkColRight = jQuery('#dor-right-column').html();
		if(typeof checkColLeft != "undefined" || typeof checkColRight != "undefined"){
			$('.viewed-product-lists .products').owlCarousel({
	            items: 3,
		        loop: true,
		        navigation: true,
		        nav: false,
		        autoplay: false,
		        margin:30,
		        responsive: {
		            0: {items: 1},
		            1200: {items: 3},
		            992: {items: 3},
		            650: {items: 3},
		            370: {items: 2},
		            300: {items: 1}
		        },
		        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	        });
		}else{
			$('.viewed-product-lists .products').owlCarousel({
	            items: 4,
		        loop: true,
		        navigation: true,
		        nav: false,
		        autoplay: false,
		        margin:30,
		        responsive: {
		            0: {items: 1},
		            1200: {items: 4},
		            992: {items: 4},
		            650: {items: 3},
		            370: {items: 2},
		            300: {items: 1}
		        },
		        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	        });
		}
	},
	ScrollTop:function(){
		jQuery('.to-top').click(function () {
            jQuery('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
        jQuery(window).scroll(function () {
		    if (jQuery(this).scrollTop() > 100) {
		        jQuery('.to-top').css({bottom: '20px'});
		    }
		    else {
		        jQuery('.to-top').css({bottom: '-50px'});
		    }

		});
	},
	AboutUsBrands:function(){
		$('.aboutPartners').owlCarousel({
            items: 5,
	        loop: true,
	        navigation: true,
	        nav: false,
	        autoplay: true,
	        margin:0,
	        responsive: {
	            0: {items: 1},
	            1200: {items: 5},
	            992: {items: 4},
	            500: {items: 3},
	            380: {items: 2},
	            300: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
        });
	},
	AboutUsFarmers:function(){
		$('.aboutus-ourfarmers').owlCarousel({
            items: 4,
	        loop: true,
	        navigation: true,
	        nav: false,
	        autoplay: true,
	        margin:30,
	        responsive: {
	            0: {items: 1},
	            1200: {items: 4},
	            992: {items: 4},
	            768: {items: 3},
	            400: {items: 2},
	            300: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
        });
	},
	ScrollFixedMenu:function(){
		n = 150;
		if(prestashop.page.page_name == "index")
			var n = jQuery("#dorSlideShow").height() - 30;
		$(window).bind('scroll', function() {
	     var navHeight = n;
	       if ($(window).scrollTop() > navHeight) {
	       	jQuery("#dor-verticalmenu").removeClass("open");
	       	jQuery("#dor-verticalmenu").addClass("menuclose");
	         $('#header').addClass('fixed fixed-tran');
	         var checkLogoFix = jQuery(".logoFixed").html();
	         if(parseInt($( window ).width()) > 991){
	         	jQuery(".dorHeaderSearch-Wapper").detach().insertBefore('.menu-group-show > .container');
	     	 }
	     	 jQuery(document).click(function (event) {
	            if (!jQuery(event.target).is("#header.fixed .dorHeaderSearch-Wapper, #header.fixed .dorHeaderSearch-Wapper *, #header.fixed .header-menu-item-icon, #header.fixed .header-menu-item-icon *, #header.fixed .header-menu-item-search, #header.fixed .header-menu-item-search *")) {
	                if(!jQuery("#header.fixed .dorHeaderSearch-Wapper").is(":hidden")){
	                	jQuery("#header.fixed .dorHeaderSearch-Wapper").slideToggle(300,function(){});
	                	jQuery("#header.fixed .dorHeaderSearch-Wapper").removeClass("open");
	                }
	            }
	        });
	         if(jQuery.trim(checkLogoFix).length == 0){
	          var logo = jQuery(".logo.img-responsive").attr("src");
	          var linkHomePage = jQuery("#header_logo > a").attr("href");
	        }
	       }
	       else {
	       	jQuery("#dor-verticalmenu").removeClass("menuclose");
	         $('#header').removeClass('fixed');
	         $('#header').removeClass('fixed-tran');
	         jQuery(".logoFixed").remove();
	         if(parseInt($( window ).width()) > 991){
	         	jQuery(".dorHeaderSearch-Wapper").detach().insertAfter('#header_logo');
	     	 }
	       }
	    });
	},

	ChooseAttrDetail:function(){
		jQuery('body').on('change', '.product-variants [data-product-attribute]', function () {
			
		});
	},
	SlidePictureBlock:function(){
		$('.slide-picture > ul').owlCarousel({
	        items: 1,
	        nav: false,
	        autoplay: true,
	        loop: true,
	        dots:true
	    });
	},
}
jQuery(document).ready(function(){
	DORTHEME.init();
	
});
$.widget.bridge('uibutton', $.ui.button);
$.widget.bridge('uitooltip', $.ui.tooltip);