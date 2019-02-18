var PROCATEAJAXTAB = {
	init:function(){
		PROCATEAJAXTAB.ActTab();
		PROCATEAJAXTAB.LoadMoreData();
	},
	ActTab:function(){
		jQuery("#dorTabAjax > li a").unbind("click");
		jQuery("#dorTabAjax > li a").click(function(){
			var idTxt = jQuery(this).attr("href");
			jQuery("#dorTabProductCategoryContent .tab-pane").removeClass("in active");
			jQuery(idTxt).addClass("in active");
			jQuery("#dorTabAjax > li a").removeClass("active");
			idTxt = idTxt.replace("#cate-tab-data-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var checkExist = jQuery("#cate-tab-data-"+idObj+" .dor-content-items > div").html();
			if(jQuery.trim(checkExist).length == 0 || checkExist == undefined){
				jQuery("#dorTabProductCategoryContent").append('<span class="loaddingAjaxTab">Loadding...</span>');
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
			            	jQuery("#cate-tab-data-"+idObj+" .dor-content-items > div").html(results);
			            	jQuery(".loaddingAjaxTab").remove();
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
	PROCATEAJAXTAB.init();
});

