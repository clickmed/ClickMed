var DORTHEMETOOL = {
	idShop: (typeof DOR != undefined && typeof DOR.id_shop != undefined)?DOR.id_shop:1,
	PathFont:"",
	PathColor:"",
	init:function(){
		DORTHEMETOOL.ControlOption();
		DORTHEMETOOL.ChooseColorOption();
		//DORTHEMETOOL.ChooseFontOption();
		DORTHEMETOOL.ChooseBackgroundOption();
		DORTHEMETOOL.ChooseModeOption();
		DORTHEMETOOL.ChooseHeaderFloatOption();
		DORTHEMETOOL.ResetOptions();
		DORTHEMETOOL.RebuiltColor();
		//DORTHEMETOOL.RebuiltFont();
		DORTHEMETOOL.RebuiltBackground();
		DORTHEMETOOL.RebuiltMode();
		DORTHEMETOOL.RebuiltHeaderFloat();
	},
	ControlOption:function(){
		jQuery(".dor-wrap > .control").unbind("click");
		jQuery(".dor-wrap > .control").click(function(){
			var pos = jQuery(".dor-wrap").position();
			if(pos.left == 0){
				$( ".dor-wrap" ).animate({
					left: "-228"
				}, 600, function() {});
			}else{
				$( ".dor-wrap" ).animate({
					left: "0"
				}, 600, function() {});
			}
		});
	},
	ChooseFontOption:function(){
		var DOR_FONT = "";
		jQuery("#dor_font_options").change(function(){
			var font = jQuery(this).val();
			if(font == "") font = "font";
			localStorage.setItem("optionFont-Shop"+DORTHEMETOOL.idShop, font);
			var linkFont = DORTHEMETOOL.PathFont+"/"+font+".css";
			$('head link[href="'+DOR_FONT+'"]').attr('href',linkFont);
			DOR_FONT = linkFont;
		});
	},
	ChooseColorOption:function(){
		jQuery(".cl-td-layoutcolor a").click(function(){
			var color = jQuery(this).closest(".cl-td-layoutcolor").attr("id");
			localStorage.setItem("optionColor-Shop"+DORTHEMETOOL.idShop, color);
			DORTHEMETOOL.OptionFindCss(color);
			jQuery(".cl-td-layout").removeClass("selected");
			jQuery("#"+color).addClass("selected");
		});
	},
	ChooseModeOption:function(){
		jQuery(".mode_theme").change(function(){
			var mode = jQuery(this).val();
			jQuery("main").removeClass("full").removeClass("boxed");
			jQuery("main").addClass(mode);
			localStorage.setItem("optionMode-Shop"+DORTHEMETOOL.idShop, mode);
			window.location.reload();
		});
	},
	ChooseHeaderFloatOption:function(){
		jQuery(".headerfloat_theme").change(function(){
			var headerfloat = jQuery(this).val();
			localStorage.setItem("optionHeaderFloat-Shop"+DORTHEMETOOL.idShop, headerfloat);
			window.location.reload();
		});
	},
	ChooseBackgroundOption:function(){
		jQuery(".cl-pattern .cl-image").click(function(){
			for(var i=1;i<=30;i++){
				jQuery("body").removeClass("pattern"+i);
			}
			var bg = jQuery(this).attr("id");
			localStorage.setItem("optionBg-Shop"+DORTHEMETOOL.idShop, bg);
			$('body').addClass(bg);
			jQuery(".cl-image").removeClass("selected");
			jQuery("#"+bg).addClass("selected");
		});
	},
	ResetOptions:function(){
		jQuery(".cl-reset").click(function(){
			localStorage.removeItem("optionColor-Shop"+DORTHEMETOOL.idShop);
			localStorage.removeItem("optionFont-Shop"+DORTHEMETOOL.idShop);
			localStorage.removeItem("optionBg-Shop"+DORTHEMETOOL.idShop);
			localStorage.removeItem("optionMode-Shop"+DORTHEMETOOL.idShop);
			localStorage.removeItem("optionHeaderFloat-Shop"+DORTHEMETOOL.idShop);
			window.location.reload();
		});
	},
	OptionFindCss:function(color){
		$("head").children("link[rel='stylesheet']").each(function(index, ele) {
			var eleTxt = jQuery(ele).attr("href");
			var eleArr = eleTxt.split('/');
			var newLink = eleArr.slice(0, -1).join("/")+"/"+color+".css";
			 var res = eleTxt.match(/assets\/css\/dorado\/color\//g); 
			 if(res == null){
			 	res = eleTxt.match(/css\/color\//g); 
			 }
			if(res != null){
				if(color != null){
					jQuery(ele).attr("href",newLink);
				}
			}
		});
	},
	RebuiltColor:function(){
		DORTHEMETOOL.OptionFindCss(color);
		var color = localStorage.getItem("optionColor-Shop"+DORTHEMETOOL.idShop);
		if(color != null){
			DORTHEMETOOL.OptionFindCss(color);
		}
	},
	RebuiltFont:function(){
		var font = localStorage.getItem("optionFont-Shop"+DORTHEMETOOL.idShop);
		var linkUrl = DOR_FONT;
		if(typeof linkUrl != "undefined"){
			var linkUrls = linkUrl.split("/");
			var newArr = linkUrls.slice(0, -1);
			var newLink = newArr.join("/");
			DORTHEMETOOL.PathFont = newLink;
			if(font != null){
				var linkFont = newLink+"/"+font+".css";
				$('head link[href="'+DOR_FONT+'"]').attr('href',linkFont);
				jQuery("#dor_font_options option[value='"+font+"']").attr("selected","selected");
				DOR_FONT = linkFont;
			}
		}
	},
	RebuiltBackground:function(){
		var bgs = localStorage.getItem("optionBg-Shop"+DORTHEMETOOL.idShop);
		if(bgs != null){
			for(var i=1;i<=30;i++){
				jQuery("body").removeClass("pattern"+i);
			}
			jQuery("body").addClass(bgs);
			jQuery("#"+bgs).addClass("selected");
		}
	},
	RebuiltMode:function(){
		var mode = localStorage.getItem("optionMode-Shop"+DORTHEMETOOL.idShop);
		if(mode != null){
			jQuery("main").removeClass("full").removeClass("boxed");
			jQuery("main").addClass(mode);
			jQuery(".mode_theme").removeAttr("checked");
			jQuery("input[value='"+mode+"']").prop('checked', true);
		}
	},
	RebuiltHeaderFloat:function(){
		var headerfloat = localStorage.getItem("optionHeaderFloat-Shop"+DORTHEMETOOL.idShop);
		if(headerfloat != null){
			jQuery(".headerfloat_theme").removeAttr("checked");
			jQuery("input[value='"+headerfloat+"']").prop('checked', true);
		}
	}
}

$(document).ready(function(){
	DORTHEMETOOL.init();
});