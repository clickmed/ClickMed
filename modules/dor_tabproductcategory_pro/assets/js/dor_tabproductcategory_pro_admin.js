var DORPROTABADM = {
	init:function(){
		DORPROTABADM.OptionAdmin();
		DORPROTABADM.BuildTab();
	},
	OptionAdmin:function(){
		jQuery("#from_date_pro, #to_date_pro").datepicker({ dateFormat: 'yy-mm-dd' });
		var val = parseInt(jQuery('input[name="pro_show_mostview"]:checked').val());
		var valText = parseInt(jQuery('input[name="pro_enabled_custom"]:checked').val());
		DORPROTABADM.showHideDate(val);
		DORPROTABADM.showHideCustomText(valText);
		jQuery("input[name='pro_show_mostview']").change(function(){
			var val = parseInt(jQuery('input[name="pro_show_mostview"]:checked').val());
			DORPROTABADM.showHideDate(val);
		});
		jQuery("input[name='pro_enabled_custom']").change(function(){
			var valText = parseInt(jQuery('input[name="pro_enabled_custom"]:checked').val());
			DORPROTABADM.showHideCustomText(valText);
		});

		var valAll = parseInt(jQuery('input[name="pro_show_all"]:checked').val());
		DORPROTABADM.showHideTitleSpecial(valAll,"#all_title_pro");
		jQuery("input[name='pro_show_all']").change(function(){
			var valAll = parseInt(jQuery('input[name="pro_show_all"]:checked').val());
			DORPROTABADM.showHideTitleSpecial(valAll,"#all_title_pro");
		});

		var valNew = parseInt(jQuery('input[name="pro_show_new"]:checked').val());
		DORPROTABADM.showHideTitleSpecial(valNew,"#new_title_pro");
		jQuery("input[name='pro_show_new']").change(function(){
			var valNew = parseInt(jQuery('input[name="pro_show_new"]:checked').val());
			DORPROTABADM.showHideTitleSpecial(valNew,"#new_title_pro");
		});

		var valSale = parseInt(jQuery('input[name="pro_show_sale"]:checked').val());
		DORPROTABADM.showHideTitleSpecial(valSale,"#special_title_pro");
		jQuery("input[name='pro_show_sale']").change(function(){
			var valSale = parseInt(jQuery('input[name="pro_show_sale"]:checked').val());
			DORPROTABADM.showHideTitleSpecial(valSale,"#special_title_pro");
		});

		var valBest = parseInt(jQuery('input[name="pro_show_best"]:checked').val());
		DORPROTABADM.showHideTitleSpecial(valBest,"#best_title_pro");
		jQuery("input[name='pro_show_best']").change(function(){
			var valBest = parseInt(jQuery('input[name="pro_show_best"]:checked').val());
			DORPROTABADM.showHideTitleSpecial(valBest,"#best_title_pro");
		});

		var valFeature = parseInt(jQuery('input[name="pro_show_feature"]:checked').val());
		DORPROTABADM.showHideTitleSpecial(valFeature,"#feature_title_pro");
		jQuery("input[name='pro_show_feature']").change(function(){
			var valFeature = parseInt(jQuery('input[name="pro_show_feature"]:checked').val());
			DORPROTABADM.showHideTitleSpecial(valFeature,"#feature_title_pro");
		});

		var valMost = parseInt(jQuery('input[name="pro_show_mostview"]:checked').val());
		DORPROTABADM.showHideTitleSpecial(valMost,"#mostview_title_pro");
		jQuery("input[name='pro_show_mostview']").change(function(){
			var valMost = parseInt(jQuery('input[name="pro_show_mostview"]:checked').val());
			DORPROTABADM.showHideTitleSpecial(valMost,"#mostview_title_pro");
		});
	},
	showHideDate:function(val){
		if(val == 1){
			jQuery("#from_date_pro, #to_date_pro").closest(".form-group").show();
		}else{
			jQuery("#from_date_pro, #to_date_pro").closest(".form-group").hide();
		}
	},
	showHideCustomText:function(val){
		if(val == 1){
			jQuery("#pro_content_custom").closest(".form-group").show();
		}else{
			jQuery("#pro_content_custom").closest(".form-group").hide();
		}
	},
	showHideTitleSpecial:function(val,objOption){
		if(val == 1){
			jQuery(objOption).closest(".form-group").show();
		}else{
			jQuery(objOption).closest(".form-group").hide();
		}
	},
	BuildTab:function(){
		var html="";
	}
}
jQuery(document).ready(function(){
	DORPROTABADM.init();
});