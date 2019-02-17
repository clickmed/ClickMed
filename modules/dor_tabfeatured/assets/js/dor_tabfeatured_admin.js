var FEATUREDTABADM = {
	init:function(){
		jQuery("#from_date, #to_date").datepicker({ dateFormat: 'yy-mm-dd' });
		var val = parseInt(jQuery('input[name="show_mostview"]:checked').val());
		FEATUREDTABADM.showHideDate(val);
		jQuery("input[name='show_mostview']").change(function(){
			var val = parseInt(jQuery('input[name="show_mostview"]:checked').val());
			FEATUREDTABADM.showHideDate(val);
		});
	},
	showHideDate:function(val){
		if(val == 1){
			jQuery("#from_date, #to_date").closest(".form-group").show();
		}else{
			jQuery("#from_date, #to_date").closest(".form-group").hide();
		}
	}
}
jQuery(document).ready(function(){
	FEATUREDTABADM.init();
});