var PROCATEADM3 = {
	init:function(){
		jQuery("#tablist_from_date3, #tablist_to_date3").datepicker({ dateFormat: 'yy-mm-dd' });
		var val = parseInt(jQuery('input[name="tablist_show_mostview3"]:checked').val());
		PROCATEADM3.showHideDate(val);
		jQuery("input[name='tablist_show_mostview3']").change(function(){
			var val = parseInt(jQuery('input[name="tablist_show_mostview3"]:checked').val());
			PROCATEADM3.showHideDate(val);
		});
	},
	showHideDate:function(val){
		if(val == 1){
			jQuery("#tablist_from_date3, #tablist_to_date3").closest(".form-group").show();
		}else{
			jQuery("#tablist_from_date3, #tablist_to_date3").closest(".form-group").hide();
		}
	}
}
jQuery(document).ready(function(){
	PROCATEADM3.init();
});