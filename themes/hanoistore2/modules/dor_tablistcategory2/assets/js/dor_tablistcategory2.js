var PROCATEAJAXTAB2 = {
	init:function(){
		PROCATEAJAXTAB2.ActTab();
		PROCATEAJAXTAB2.LoadMoreData();
	},
	ActTab:function(){
		jQuery("#dorTabLists2 > li a").click(function(){
			var idTxt = jQuery(this).attr("href");
			jQuery("#dor-tab-list-category2 .tab-pane").removeClass("in active");
			jQuery(idTxt).addClass("in active");
			jQuery("#dorTabLists2 > li a").removeClass("active");
			idTxt = idTxt.replace("#cate-tab-list2-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var checkExist = jQuery("#cate-tab-list2-"+idObj+" .dor-content-items > div").html();
			if(jQuery.trim(checkExist).length == 0 || checkExist == undefined){
				var limit = parseInt(jQuery(this).closest("#dor-tab-list-category2").find("#cate-tab-list2-"+idObj+" .load-more-tab.tablists2").attr("data-limit"));
				jQuery("#dorTabListCategoryContent2").css("min-height","600px");
				jQuery("#dor-tab-list-category2 .load-more-tab").hide();
				jQuery("#dorTabListCategoryContent2").append('<span class="loaddingAjaxTab">Loadding...</span>');
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
			            	jQuery("#cate-tab-list2-"+idObj+" .dor-content-items > div").html(results);
			            	jQuery("#dor-tab-list-category2 .loaddingAjaxTab").remove();
			            	jQuery("#dor-tab-list-category2 .load-more-tab").show();
			            	DORTHEME.SlideImageTabs();
			            	var lengthVal = jQuery(results).find(".product-container").length;
			            	if(lengthVal < limit){
			            		jQuery("#cate-tab-list2-"+idObj+" .load-more-tab.tablists2").remove();
			            	}
			            	jQuery("#dorTabListCategoryContent2").css("min-height","100px");
			            	$('#dorTabListCategoryContent2 .productTabContent_'+idObj+' .product_list').owlCarousel({
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
		jQuery(".load-more-tab.tablists2").click(function(){
			var _this=this;
			var limit = parseInt(jQuery(_this).attr("data-limit"))
			var urlAjax = jQuery(_this).attr("data-ajax");
			var page = jQuery(_this).attr("data-page");
			var idTxt = jQuery(_this).closest(".tab-pane").attr("id");

			jQuery("#"+idTxt).append("<span class='load-more-small'></span>");
			idTxt = idTxt.replace("cate-tab-list2-","");
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
		            	jQuery("#cate-tab-list2-"+idObj+" .row-tablist > ul").append(results);
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
	PROCATEAJAXTAB2.init();
});