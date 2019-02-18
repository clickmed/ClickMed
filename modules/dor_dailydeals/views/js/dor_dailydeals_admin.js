var DAILYDEAL = {
	init:function(){
		jQuery("#daily_deal_startdate, #daily_deal_enddate").datepicker({ dateFormat: 'mm/dd/yy' });
		var checkChoose = jQuery("#typeData").val();
		jQuery("#deal_content_custom").height(100).width(350);
		if(checkChoose == 0){
			jQuery(".dor-daily-custom").closest(".form-group").hide();
		}else{
			jQuery(".dor-daily-custom").closest(".form-group").show();
		}

		jQuery("#typeData").change(function(){
			var checkChoose = jQuery(this).val();
			if(checkChoose == 0){
				jQuery(".dor-daily-custom").closest(".form-group").hide();
			}else{
				jQuery(".dor-daily-custom").closest(".form-group").show();
			}
		});
	},

}
jQuery(document).ready(function(){
	DAILYDEAL.init();
});