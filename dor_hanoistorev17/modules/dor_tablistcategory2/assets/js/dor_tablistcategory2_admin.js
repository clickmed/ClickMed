var PROCATEADM2 = {
	init:function(){
		jQuery("#tablist_from_date2, #tablist_to_date2").datepicker({ dateFormat: 'yy-mm-dd' });
		var val = parseInt(jQuery('input[name="tablist_show_mostview2"]:checked').val());
		PROCATEADM2.showHideDate(val);
		jQuery("input[name='tablist_show_mostview2']").change(function(){
			var val = parseInt(jQuery('input[name="tablist_show_mostview2"]:checked').val());
			PROCATEADM2.showHideDate(val);
		});
	},
	showHideDate:function(val){
		if(val == 1){
			jQuery("#tablist_from_date2, #tablist_to_date2").closest(".form-group").show();
		}else{
			jQuery("#tablist_from_date2, #tablist_to_date2").closest(".form-group").hide();
		}
	}
}
jQuery(document).ready(function(){
	PROCATEADM2.init();
});