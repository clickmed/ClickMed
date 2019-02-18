var PROCATEAJAXTAB = {
	init:function(){
		PROCATEAJAXTAB.ActTab();
		PROCATEAJAXTAB.LoadMoreData();
	},
	ActTab:function(){
		jQuery("#dorTabLists > li a").click(function(){
			var idTxt = jQuery(this).attr("href");
			jQuery("#dor-tab-list-category .tab-pane").removeClass("in active");
			jQuery(idTxt).addClass("in active");
			jQuery("#dorTabLists > li a").removeClass("active");

			
			idTxt = idTxt.replace("#cate-tab-list-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var checkExist = jQuery("#cate-tab-list-"+idObj+" .dor-content-items > div").html();
			if(jQuery.trim(checkExist).length == 0 || checkExist == undefined){
				var limit = parseInt(jQuery(this).closest("#dor-tab-list-category").find("#cate-tab-list-"+idObj+" .load-more-tab.tablists").attr("data-limit"));
				jQuery("#dorTabListCategoryContent").css("min-height","600px");
				jQuery("#dor-tab-list-category .load-more-tab").hide();
				jQuery("#dorTabListCategoryContent").append('<span class="loaddingAjaxTab">Loadding...</span>');
				var urlAjax = jQuery(this).closest("ul").attr("data-ajaxurl");
				var params = {}
				params.cateID = idObj;
				params.type = type;
				jQuery.ajax({
		            url: urlAjax,
		            data:params,
		            type:"POST",
		            success:function(data){
		            	setTimeout(function(){
		            		var results = JSON.parse(data);
			            	jQuery(".tab_container.dorlistproducts .sliderLoadingTab").remove();
			            	jQuery("#cate-tab-list-"+idObj+" .dor-content-items > div").html(results);
			            	jQuery("#dor-tab-list-category .loaddingAjaxTab").remove();
			            	jQuery("#dor-tab-list-category .load-more-tab").show();
			            	DORTHEME.SlideImageTabs();
			            	var lengthVal = jQuery(results).find(".product-container").length;
			            	if(lengthVal < limit){
			            		jQuery("#cate-tab-list-"+idObj+" .load-more-tab.tablists").remove();
			            	}
			            	jQuery("#dorTabListCategoryContent").css("min-height","100px");
			            	$('#dorTabListCategoryContent .productTabContent_'+idObj+' .product_list').owlCarousel({
						        items: 5,
						        loop: true,
						        navigation: true,
						        nav: true,
						        autoplay: false,
						        margin:10,
						        responsive: {
						            0: {items: 1},
						            1200: {items: 5},
						            992: {items: 4},
						            668: {items: 3},
						            400: {items: 2},
						            300: {items: 1}
						        },
						        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
						    });
		            	},600);
		                return false;
		            }
		        });
			}
		});
	},
	LoadMoreData:function(){
		jQuery(".load-more-tab.tablists").click(function(){
			var _this=this;
			var limit = parseInt(jQuery(_this).attr("data-limit"))
			var urlAjax = jQuery(_this).attr("data-ajax");
			var page = jQuery(_this).attr("data-page");
			var idTxt = jQuery(_this).closest(".tab-pane").attr("id");

			jQuery("#"+idTxt).append("<span class='load-more-small'></span>");
			idTxt = idTxt.replace("cate-tab-list-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var params = {}
			params.cateID = idObj;
			params.type = type;
			params.page = page;
			setTimeout(function(){
				jQuery.ajax({
		            url: urlAjax,
		            data:params,
		            type:"POST",
		            success:function(data){
		            	var results = JSON.parse(data);
		            	jQuery("#cate-tab-list-"+idObj+" .row-tablist > ul").append(results);
		            	var lengthVal = jQuery(results).find(".product-container").length;
		            	if(lengthVal >= limit){
		            		jQuery(_this).attr("data-page",parseInt(page)+1);
		            	}else{
		            		jQuery(_this).remove();
		            	}
		            	jQuery(".load-more-small").remove();
		                return false;
		            }
		        });
	        },1000);
		});
	}
};

jQuery(document).ready(function(){
	PROCATEAJAXTAB.init();
});