jQuery(document).ready(function(){
   	parentClickAble();
    $(window).resize(parentClickAble);
    DORMENU.init();
});

function parentClickAble()
{
	if($(window).width() >= 0){
        $('#dor-top-menu a.dropdown-toggle').click(function(){
            var redirect_url = $(this).attr('href');
            window.location = redirect_url;
        });
    }
}

var DORMENU = {
	init:function(){
		DORMENU.SetActiveMenu();
		DORMENU.ToggleMenuLine();
		DORMENU.OpenClose();
	},
	SetActiveMenu:function(){
		var urlPath = location.pathname;
		var urlFull = location.href;
		var check = jQuery('ul.megamenu > li > a[href="'+urlFull+'"]').html();
		if(typeof check != "undefined"){
			jQuery('ul.megamenu > li > a[href="'+urlFull+'"]').closest("li").addClass("active");
		}else{
			jQuery('a[href="'+urlPath+'"]').closest(".widget-heading").addClass("active");
			jQuery('a[href="'+urlFull+'"]').closest(".widget-heading").addClass("active");
			jQuery('a[href="'+urlPath+'"]').closest("li").addClass("active");
			jQuery('a[href="'+urlFull+'"]').closest("li").addClass("active");
			jQuery('a[href="'+urlFull+'"]').closest("li.parent.dropdown").addClass("active");
			jQuery('a[href="'+urlFull+'"]').closest("li").parents("li").addClass("active");
		}
		
		

	},
	OpenClose:function(){
		jQuery(".open_menu").click(function(){
			jQuery( "#dor-top-menu" ).animate({
			    left: 0,
			}, 500);
			jQuery("body").addClass("dor-mobile");
		});
		jQuery(".close_menu").click(function(){
			jQuery( "#dor-top-menu" ).animate({
			    left: -250,
			}, 500);
			jQuery("body").removeClass("dor-mobile");
		});
	},
	ToggleMenuLine:function(){
		jQuery(".caretmobile, .link-cate-custom").click(function(){
			var checkClick = jQuery(this).hasClass("link-cate-custom");
			if(checkClick){
				var checkSublinks = jQuery(this).parent(".widget-heading").parent(".widget-links").find(".panel-group").html();
			}
			var checkStatus = jQuery(this).parent("li").hasClass("dor-menu-open");
			jQuery(this).parent("li").parent("ul").find("li").removeClass("dor-menu-open");
			
			//jQuery(".dor-megamenu .navbar-nav > li").removeClass("dor-menu-open");
			var checkStatusWidget = jQuery(this).closest(".widget-content").hasClass("dor-menu-open");
			jQuery(this).closest("li.dor-menu-open").find(".widget-content").removeClass("dor-menu-open");

			if(!checkStatus)
				jQuery(this).closest("li").addClass("dor-menu-open");
			else
				jQuery(this).closest("li").removeClass("dor-menu-open");
			if(checkStatusWidget){
				jQuery(this).closest(".widget-content").removeClass("dor-menu-open");
			}else{
				jQuery(this).closest(".widget-content").addClass("dor-menu-open");
			}
		});
	}
};
