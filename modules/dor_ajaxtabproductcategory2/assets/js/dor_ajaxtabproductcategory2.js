var PROCATEAJAXTAB2 = {
	init:function(){
		PROCATEAJAXTAB2.ActTab();
		PROCATEAJAXTAB2.LoadMoreData();
		PROCATEAJAXTAB2.ChangeThumb();
		setTimeout(function(){
			jQuery(".dor-content-items").css("opacity",1);
		},1000)
	},
	ChangeThumb:function(){
		jQuery(".dortab_thumbs_list ul li a").unbind("click");
		jQuery(".dortab_thumbs_list ul li a").click(function(e){
			e.preventDefault();
			var image = jQuery(this).attr("data-big-img");
			jQuery(".dortab_thumbs_list ul li a").removeClass("shown");
			jQuery(this).addClass("shown");
			jQuery(this).closest(".dormainTabProducts").find("#bigpic").attr("src",image)
		})
	},
	ActTab:function(){
		jQuery("#dorTabAjax2 > li a").click(function(){
			var idTxt = jQuery(this).attr("href");
			jQuery("#dorTabProductCategory2Content .tab-pane").removeClass("in active");
			jQuery(idTxt).addClass("in active");
			jQuery("#dorTabAjax2 > li a").removeClass("active");

			
			idTxt = idTxt.replace("#cate-tab-data-","");
			type = 1;
			var idObj = parseInt(idTxt);
			if(isNaN(idObj)){
				idObj = idTxt;
				type = 0;
			}
			var checkExist = jQuery("#cate-tab-data-"+idObj+" .dor-content-items > div").html();
			if(jQuery.trim(checkExist).length == 0 || checkExist == undefined){
				jQuery("#dorTabProductCategory2Content").append('<span class="loaddingAjaxTab">Loadding...</span>');
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
			            	var quantityInput = $('.quantity_wanted');
						    quantityInput.TouchSpin({
						      verticalbuttons: true,
						      verticalupclass: 'material-icons touchspin-up',
						      verticaldownclass: 'material-icons touchspin-down',
						      buttondown_class: 'btn btn-touchspin js-touchspin',
						      buttonup_class: 'btn btn-touchspin js-touchspin',
						      min: parseInt(quantityInput.attr('min'), 10),
						      max: 1000000
						    });
		            	},600);
		                return false;
		            }
		        });
			}
		});
	},
	LoadMoreData:function(){
		jQuery(".load-more-tab.data2").unbind("click");
		jQuery(".load-more-tab.data2").click(function(){
			var _this=this;
			var limit = parseInt(jQuery(_this).attr("data-limit"))
			var urlAjax = jQuery(_this).attr("data-ajax");
			var page = jQuery(_this).attr("data-page");
			var idTxt = jQuery(_this).closest(".tab-pane").attr("id");
			jQuery("#"+idTxt).append("<span class='load-more-small'></span>");
			idTxt = idTxt.replace("cate-tab-data2-","");
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

		            	jQuery("#cate-tab-data2-"+idObj+" .dor-content-items > div.row").append(results);
		            	var lengthVal = jQuery(results).find(".ajax_block_product").length;
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

function dorChangeThumb(item,image){
	jQuery(".productMain .js-qv-product-cover").attr("src",image);
	jQuery(".productMain .product-images .thumb-container img").removeClass("selected");
	jQuery(item).addClass("selected");
}