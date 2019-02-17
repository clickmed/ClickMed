var SMARTUSER = {
	init:function(){
		SMARTUSER.navControlAct();
		SMARTUSER.smartRegister();
		SMARTUSER.smartLogin();
	},
	navControlAct:function(){
		jQuery(".smartLogin").click(function(){
			jQuery("#registerFormSmart .button.b-close").click();
			jQuery("#smartForgotPass .button.b-close").click();
			$('#loginFormSmart').bPopup();
		});
		jQuery(".smartRegister").click(function(){
			jQuery("#loginFormSmart .button.b-close").click();
			jQuery("#smartForgotPass .button.b-close").click();
			$('#registerFormSmart').bPopup();
		});
		jQuery(".lost_password_smart, .lost_password_smart > a").click(function(){
			jQuery("#loginFormSmart .button.b-close").click();
			jQuery("#registerFormSmart .button.b-close").click();
			$('#smartForgotPass').bPopup();
		});
	},
	smartRegister:function(){
		jQuery("#registerFormSmart #submitAccount").click(function(){
			var validate = true, _this=jQuery(this), email, firstname, lastname, password, fieldLists = ["customer_firstname","customer_lastname","email","passwd"];
			for(var i=0 in fieldLists){
				var field = fieldLists[i];
				var fieldCheck = jQuery("#registerFormSmart input[name='"+field+"']").val();
				if(jQuery.trim(fieldCheck).length == 0 && fieldCheck != undefined){
					jQuery("#registerFormSmart input[name='"+field+"']").addClass("hight-light");
					validate = false;
				}else{
					jQuery("#registerFormSmart input[name='"+field+"']").removeClass("hight-light");
				}
			}
			return validate;
		});
	},
	smartLogin:function(){
		jQuery("#loginFormSmart #SubmitLoginCus").click(function(){
			var validate = true, _this=jQuery(this), email, password, fieldLists = ["email","passwd"];
			for(var i=0 in fieldLists){
				var field = fieldLists[i];
				var fieldCheck = jQuery("#loginFormSmart input[name='"+field+"']").val();
				if(jQuery.trim(fieldCheck).length == 0 && fieldCheck != undefined){
					jQuery("#loginFormSmart input[name='"+field+"']").addClass("hight-light");
					validate = false;
				}else{
					jQuery("#loginFormSmart input[name='"+field+"']").removeClass("hight-light");
				}
			}
			return validate;
		});
	}
}
jQuery(document).ready(function(){
	SMARTUSER.init();
})