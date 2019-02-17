var ADM_DORTHEME = {
	init:function(){
		ADM_DORTHEME.AccTabTheme();
		ADM_DORTHEME.ClearColor();
		ADM_DORTHEME.ChoseThemeColor();
		ADM_DORTHEME.ChoseBackgroundImage();
		ADM_DORTHEME.EnableCategoryThumb();
		ADM_DORTHEME.OptionThemeSkin();
	},
	AccTabTheme:function(){
		jQuery(".square-button").click(function(){
			var _this=this;
			var objID = jQuery(this).closest("div.tool-class-admin").attr("id");
			  jQuery( "#"+objID+" .box_lab" ).slideToggle( "fast", function() {
			    if(jQuery(this).is(":visible"))
				  {
					var itext = '<i class="fa fa-minus-square"></i>';
					jQuery(_this).find('i').remove();
				  }     
				else
				  {
					var itext = '<i class="fa fa-plus-square"></i>';
					jQuery(_this).find('i').remove();
				  }
				jQuery(_this).html(itext);
			  });
		});

		
	},
	OptionThemeSkin:function(){
		var checkStatusColor = jQuery("input[name='dorEnableThemeColor'][checked='checked']").val();
		if(parseInt(checkStatusColor) == 0){
			jQuery('#themeColorOption').addClass("opt-hidden");
		}else{
			jQuery('#themeColorOption').removeClass("opt-hidden");
		}

		var checkStatusBgImage = jQuery("input[name='dorEnableBgImage'][checked='checked']").val();
		if(parseInt(checkStatusBgImage) == 0){
			jQuery('#themeBgImageOption').addClass("opt-hidden");
		}else{
			jQuery('#themeBgImageOption').removeClass("opt-hidden");
		}

		jQuery("input[name='dorEnableThemeColor']").change(function(){
			var checkStatusColor = jQuery(this).val();
			if(parseInt(checkStatusColor) == 0){
				jQuery('#themeColorOption').addClass("opt-hidden");
			}else{
				jQuery('#themeColorOption').removeClass("opt-hidden");
			}
		});
		jQuery("input[name='dorEnableBgImage']").change(function(){
			var checkStatusBgImage = jQuery(this).val();
			if(parseInt(checkStatusBgImage) == 0){
				jQuery('#themeBgImageOption').addClass("opt-hidden");
			}else{
				jQuery('#themeBgImageOption').removeClass("opt-hidden");
			}
		});
	},
	ClearColor:function(){
		jQuery(".clear-bg").click(function(){
			jQuery(this).closest('.input-group').find(".mColorPickerInput").val("").css("background-color","transparent");
		})
	},
	ChoseThemeColor:function(){
		$('.cl-td-layout').click(function(){
	        var val = $(this).attr('id');
	        if($(this).hasClass("active")) val = "";
	        $("#dorthemecolor").remove();
	        $(".cl-pattern").append('<input type=hidden id="dorthemecolor" name="dorthemecolor" value="'+val+'">');
	        if(!$(this).hasClass("active")){
            	$(".cl-td-layout").removeClass('active');
            	$(this).addClass('active');
            }else{
            	$(this).removeClass('active');
            }
	    });
	},
	ChoseBackgroundImage:function(){
		jQuery(".cl-pattern > div.cl-image").click(function(){
			var val = $(this).attr('id');
			if($(this).hasClass("active")) val = "";
			$("#bgdorthemebg").remove();
            $(".cl-pattern").append('<input type=hidden id="bgdorthemebg" name="dorthemebg" value="'+val+'">');
            if(!$(this).hasClass("active")){
            	$("div.cl-image").removeClass('active');
            	$(this).addClass('active');
            }else{
            	$(this).removeClass('active');
            }
		});
	},
	EnableCategoryThumb:function(){
		jQuery("input[name='dorCategoryThumb']").change(function(){
			var val = jQuery(this).val();
			if(parseInt(val) == 1){
				jQuery(".group-cate-thumb").removeClass("hidden");
			}else{
				jQuery(".group-cate-thumb").addClass("hidden");
			}
		});
	},
	ControlArrow:function(){
		jQuery(".arow-control").click(function(){
			jQuery(this).closest(".advance-class-admin").find(".data-dor-admin").slideToggle(100,function(){

			});
		});
	},
	MergeMenu:function(){
		jQuery("#tab-AdminDorMenu").append('<ul class="dor-submenu-lists-adm"><span class="point-start"></span></ul>');
		jQuery("#tab-AdminSmartBlog").append('<ul class="dor-submenu-lists-adm"><span class="point-start"></span></ul>');
		jQuery("#nav-sidebar > ul > li").each(function( index ) {
			var idObj = jQuery(this).attr("id");
			var checkDorTabSub2 = idObj.indexOf('subtab-Admindor');
			var checkDorTabSub = idObj.indexOf('subtab-AdminDor');
			var checkDorTabSub3 = idObj.indexOf('subtab-AdminTestimonials');
			if(checkDorTabSub2 !== -1 || checkDorTabSub3 !== -1 || checkDorTabSub !== -1){
				jQuery(this).detach().insertAfter('#tab-AdminDorMenu .dor-submenu-lists-adm .point-start');
			}

			var checkBlogTabSub2 = idObj.indexOf('subtab-AdminAboutUs');
			var checkBlogTabSub = idObj.indexOf('subtab-AdminBlog');
			var checkBlogTabSub3 = idObj.indexOf('subtab-AdminImageType');
			if(checkBlogTabSub2 !== -1 || checkBlogTabSub3 !== -1 || checkBlogTabSub !== -1){
				jQuery(this).detach().insertAfter('#tab-AdminSmartBlog .dor-submenu-lists-adm .point-start');
			}
		});
	}
}

$(document).ready(function(){
	ADM_DORTHEME.init();
	ADM_DORTHEME.ControlArrow();
	ADM_DORTHEME.MergeMenu();
});