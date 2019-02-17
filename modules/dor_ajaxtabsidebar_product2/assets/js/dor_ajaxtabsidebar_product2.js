
var SIDEBAR_PROCATEAJAXTAB2 = {
	init:function(){
		SIDEBAR_PROCATEAJAXTAB2.ActTab();
		//SIDEBAR_PROCATEAJAXTAB2.LoadMoreData();
	},
	ActTab:function(){
		jQuery(".sidebar2 #dorTabAjaxSidebar > li a").click(function(){
			var idTxt = jQuery(this).attr("href");
			jQuery("#dor-tabsidebar-product-category2 .tab-pane").removeClass("in active");
			jQuery(idTxt).addClass("in active");
			jQuery(".sidebar2 #dorTabAjaxSidebar > li a").removeClass("active");
			var objIdTxt = idTxt;
			idTxt = idTxt.replace("#cate-tab-data-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var checkExist = jQuery("#cate-tab-data-"+idObj+" .dor-content-items > .row-sidebar").html();
			if(jQuery.trim(checkExist).length == 0 || checkExist == undefined){
				jQuery(".viewall-sidebar, #dorTabSidebarProductCategoryContent .view-all").hide();
				jQuery("#dorTabSidebarProductCategoryContent").append('<span class="loaddingAjaxTab">Loadding...</span>');
				var urlAjax = jQuery(this).closest("ul").attr("data-ajaxurl");
				jQuery("#dorTabSidebarProductCategoryContent > div").css({
					'opacity':0,
					'min-height':400
				});
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
			            	jQuery("#cate-tab-data-"+idObj+" .dor-content-items > .row-sidebar").html(results);
			            	jQuery(".loaddingAjaxTab").remove();
			            	jQuery("#dorTabSidebarProductCategoryContent > div").css({
								'opacity':1,
								'min-height':1
							});
			            	jQuery(".viewall-sidebar, #dorTabSidebarProductCategoryContent .view-all").show();
		            		SIDEBAR_PROCATEAJAXTAB2.SlideCarousel(".sidebar2 "+objIdTxt);
		            	},600);
		                return false;
		            }
		        });
			}
		});
	},
	LoadMoreData:function(){
		jQuery(".load-more-tab").click(function(){
			var _this=this;
			var limit = parseInt(jQuery(_this).attr("data-limit"))
			var urlAjax = jQuery(_this).attr("data-ajax");
			var page = jQuery(_this).attr("data-page");
			var idTxt = jQuery(_this).closest(".tab-pane").attr("id");
			jQuery("#"+idTxt).append("<span class='load-more-small'></span>");
			idTxt = idTxt.replace("cate-tab-data-","");
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
		            	jQuery("#cate-tab-data-"+idObj+" .dor-content-items > .row").append(results);
		            	var lengthVal = jQuery(results).find(".dor-product-lists").length;
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
	},
	SlideCarousel:function(idTxt){
		jQuery(idTxt+' .row-sidebar').owlCarousel({
	        items: 1,
	        nav: true,
	        autoplay: false,
	        margin:0,
	        loop:false,
	        responsive: {
	            0: {items: 1},
	            1200: {items: 1},
	            992: {items: 1},
	            768: {items: 1},
	            450: {items: 1},
	            320: {items: 1}
	        },
	        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
	    });
	}
};

jQuery(document).ready(function(){
	SIDEBAR_PROCATEAJAXTAB2.init();
	var idTxt = jQuery(".sidebar2 #dorTabAjaxSidebar li.active a").attr("href");
	SIDEBAR_PROCATEAJAXTAB2.SlideCarousel(idTxt);
});