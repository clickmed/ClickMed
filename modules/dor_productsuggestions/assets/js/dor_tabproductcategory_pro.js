var PROCATEAJAXTABPRO = {
	init:function(){
		PROCATEAJAXTABPRO.ActTabPro();
		//PROCATEAJAXTABPRO.LoadMoreData();
	},
	ActTabPro:function(){
		jQuery("#dorTabAjaxPro > li a").click(function(){
			var tabchose = jQuery(this).closest(".dor-tab-product-category-wrapper").attr("data-tab-id");
			var idTxt = jQuery(this).attr("href");
			jQuery("#dorTabProductCategoryContentPro .tab-pane").removeClass("in active");
			jQuery(idTxt).addClass("in active");
			jQuery("#dorTabAjaxPro > li a").removeClass("active");
			idTxt = idTxt.replace("#cate-tab-data-pro-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var checkExist = jQuery("#cate-tab-data-pro-"+idObj+" .dor-content-items > div").html();
			if(jQuery.trim(checkExist).length == 0 || checkExist == undefined){
				jQuery("#dorTabProductCategoryContentPro .title, #dorTabProductCategoryContentPro .product-description, .view-all").hide();
				jQuery("#dorTabProductCategoryContentPro").append('<span class="loaddingAjax">Loadding...</span>');
				jQuery("#dorTabProductCategoryContentPro > div.tab-pane").css({
					'opacity':0,
					'min-height':350
				});
				var urlAjax = jQuery(this).closest("ul").attr("data-ajaxurl");
				var params = {}
				params.tabchose = tabchose;
				params.cateID = idObj;
				params.type = type;
				jQuery.ajax({
		            url: urlAjax,
		            data:params,
		            type:"POST",
		            success:function(data){
		            	setTimeout(function(){
		            		jQuery("#dorTabProductCategoryContentPro .title, #dorTabProductCategoryContentPro .product-description, .view-all").show();
		            		var results = JSON.parse(data);
			            	jQuery(".tab_container.dorlistproducts .sliderLoadingTab").remove();
			            	jQuery("#cate-tab-data-pro-"+idObj+" .dor-content-items > div").html(results);
			            	jQuery(".loaddingAjax").remove();
			            	jQuery("#dorTabProductCategoryContentPro > div.tab-pane").css({
								'opacity':1,
								'min-height':200
							});
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
		            	jQuery("#cate-tab-data-"+idObj+" .dor-content-items > .row-tabcontent").append(results);
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
	}
};

jQuery(document).ready(function(){
	PROCATEAJAXTABPRO.init();
});