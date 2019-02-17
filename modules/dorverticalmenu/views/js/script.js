jQuery(document).ready(function(){
	parentClickAbleVer();
	showMenuVer();
    $(window).resize(parentClickAbleVer);
});

function parentClickAbleVer()
{
	if($(window).width() >= 876){
        $('div.verticalmenu a.dropdown-toggle').click(function(){
            var redirect_url = $(this).attr('href');
            window.location = redirect_url;
        });
    }else{

    }
}
function showMenuVer(){
	jQuery(".dor-vertical-title .fa-icon").click(function(){
		if(jQuery(this).hasClass("open")){
			jQuery(".dor-verticalmenu").hide();
			jQuery(this).removeClass("open");
		}else{
			jQuery(".dor-verticalmenu").show();
			jQuery(this).addClass("open");
		}
	})
}