var PROCATEAJAXTABS3 = {
	init:function(){
		PROCATEAJAXTABS3.ActTab();
	},
	ActTab:function(){
		jQuery(".lip-tab-inner").each(function(key,item){
			var idObj = jQuery(this).attr("rel");
			var urlAjax = jQuery(this).closest(".dor-tab-product-category-wrapper").attr("data-ajaxurl");
			var params = {}
			params.cateID = idObj;
			params.type = 0;
			jQuery.ajax({
	            url: urlAjax,
	            data:params,
	            type:"POST",
	            success:function(data){
	            	setTimeout(function(){
	            		var results = JSON.parse(data);
		            	jQuery(".tab_container.dorlistproducts .sliderLoadingTab").remove();
		            	jQuery("#lip-tab-"+idObj+" .lip-tab-content").html(results);
		            	jQuery(".loaddingAjaxTab").remove();
		            	jQuery(".load-more-tab").show();
		            	jQuery("#lip-tab-"+idObj+" .lip-products").owlCarousel({
					        items: 2,
					        loop: true,
					        navigation: true,
					        nav: true,
					        autoplay: false,
					        responsive: {
					            0: {items: 1},
					            1200: {items: 2},
					            992: {items: 2},
					            768: {items: 1},
					            610: {items: 2},
					            200: {items: 1}
					        },
					        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
					    });
	            	},600);
	                return false;
	            }
	        });
		});
	}
};

jQuery(document).ready(function(){
	PROCATEAJAXTABS3.init();
});