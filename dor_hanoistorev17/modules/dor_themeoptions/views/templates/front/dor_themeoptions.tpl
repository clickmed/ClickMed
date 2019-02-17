<style type="text/css">
	{if $dorEnableBgImage == 1 && $dorthemebg != ""}
	body{
		background:url({$base_dir}modules/dor_themeoptions/img/pattern/{$dorthemebg}.png);
	}
	{/if}
	/****Top Nav***/

	{if $dorTopbarColorText !=''}
        #header > .nav div, #header > .nav span, #header > .nav strong, #header > .nav i,#currencies-block-top div.current::after, #languages-block-top div.current::after, #currencies-block-top div.current strong{
            color:{$dorTopbarColorText} !important;
        }

        #dor-topbar div, #dor-topbar div span, #dor-topbar div p{
            color:{$dorTopbarColorText} !important;
        }

    {/if}
    {if $dorTopbarBgOutside !=''}
    #dor-topbar{
    	background:{$dorTopbarBgOutside};
    }
    {/if}
    {if $dorTopbarBgColor !=''}
    .dor-topbar-inner .container{
    	background:{$dorTopbarBgColor};
    }
    {/if}
    {if $dorTopbarColorLink !=''}
    #dor-topbar a{
    	color:{$dorTopbarColorLink} !important;
    }
    {/if}
    {if $dorTopbarColorLinkHover !=''}
    #dor-topbar a:hover{
    	color:{$dorTopbarColorLinkHover} !important;
    }
    {/if}
    /***Header****/
	{if $dorHeaderBgOutside !=''}
        body header#header .menu-group-show{
            background-color:{$dorHeaderBgOutside} !important;
        }
    {/if}
	{if $dorHeaderBgColor !=''}
        body header#header .menu-group-show .container{
            background-color:{$dorHeaderBgColor} !important;
        }
    {/if}

    {if $dorHeaderColorText !=''}
        body header#header .menu-group-show div, 
        body header#header .menu-group-show div span, 
        body header#header .menu-group-show div p{
            color:{$dorHeaderColorText} !important;
        }
    {/if}
	{if $dorHeaderColorLink !=''}
        body header#header .menu-group-show a{
            color:{$dorHeaderColorLink} !important;
        }
    {/if}
    {if $dorHeaderColorLinkHover !=''}
        body header#header .menu-group-show a:hover,
        body header#header .menu-group-show a:hover span,
        body header#header .menu-group-show a:hover p{
            color:{$dorHeaderColorLinkHover} !important;
        }
    {/if}
	{if $dorHeaderColorIcon !=''}
        body header#header .header-cart-txt > i,
        body header#header .header-cart-txt > a > i,
        body header#header .search-box-mobile i,
        body header#header .user-info-inner > i{
            color:{$dorHeaderColorIcon} !important;
        }
    {/if}
	
	/****Footer****/
	{if $dorFooterBgOutside !=''}
	body #footer .footer-container,
    body #footer .footer-copyright-payment{
		background:{$dorFooterBgOutside} !important;
	}
	{/if}
	{if $dorFooterBgColor !=''}
	body #footer .footer-container .container{
		background:{$dorFooterBgColor} !important;
	}
	{/if}
	{if $dorFooterColorText !=''}
	body #footer .footer-container .container,
	body #footer .footer-container .container div,
	body #footer .footer-container .container span,
    body #footer .footer-container .container section,
    body #footer .footer-container .container h4,
    body #footer .footer-container .container h3,
    body #footer .footer-container .container h3.h3,
    body #footer .footer-container .container h5,
    body #footer .footer-container .container strong,
    body #footer .footer-container .container h3 > a.text-uppercase,
	body #footer .footer-container .container i{
		color:{$dorFooterColorText} !important;
	}
	{/if}
	{if $dorFooterColorLink !=''}
	body #footer .footer-container .container a,
    body #footer .footer-container .container a span,
    body #footer .footer-container .container a > i,
    body #footer .footer-container .container a > em,
	body #footer .footer-container .container div a{
		color:{$dorFooterColorLink} !important;
	}
	{/if}
	{if $dorFooterColorLinkHover !=''}
	body #footer .footer-container .container a:hover,
	body #footer .footer-container .container div a:hover,
    body #footer .footer-container .container a:hover span,
    body #footer .footer-container .container div a:hover span{
		color:{$dorFooterColorLinkHover} !important;
	}
	{/if}


    /*****Mega Menu*****/
    {if $dorMegamenuBgOutside !=''}
        body #header .header-top .dor-header-menu,
        body #header .menu-group-show > .header-megamenu{
            background:{$dorMegamenuBgOutside} !important;
        }
    {/if}
    {if $dorMegamenuBgColor !=''}
        body #header .header-top .dor-megamenu .navbar-nav,
        body #header .dor-megamenu .navbar-nav,
        body header#header.fixed.fixed-tran .menu-group-show .container,
        body header#header.fixed.fixed-tran .menu-group-show{
            background:{$dorMegamenuBgColor} !important;
        }
    {/if}
    {if $dorMegamenuColorLink !=''}
        body #header .dor-megamenu ul.navbar-nav > li > a span.menu-title{
            color:{$dorMegamenuColorLink} !important;
        }
    {/if}
    {if $dorMegamenuColorLinkHover !=''}
        body #header .dor-megamenu ul.navbar-nav > li.active > a span.menu-title,
        body #header .dor-megamenu ul.navbar-nav > li > a:hover span.menu-title{
            color:{$dorMegamenuColorLinkHover} !important;
        }
    {/if}
    {if $dorMegamenuColorSubText !=''}
        body .dor-megamenu #dor-top-menu .dropdown-menu,
        body .dor-megamenu #dor-top-menu .dropdown-menu div,
        body .dor-megamenu #dor-top-menu .dropdown-menu ul li{
            color:{$dorMegamenuColorSubText} !important;
        }
    {/if}
    {if $dorMegamenuColorSubLink !=''}
        body .dor-megamenu #dor-top-menu .dropdown-menu a,
        body .dor-megamenu #dor-top-menu .dropdown-menu a span{
            color:{$dorMegamenuColorSubLink} !important;
        }
    {/if}
    {if $dorMegamenuColorSubLinkHover !=''}
        body .dor-megamenu #dor-top-menu .dropdown-menu a:hover,
        body .dor-megamenu #dor-top-menu .dropdown-menu a:hover span{
            color:{$dorMegamenuColorSubLinkHover} !important;
        }
        body .dor-megamenu #dor-top-menu .dropdown-menu li > a:hover{
            border-bottom-color: {$dorMegamenuColorSubLinkHover} !important;
        }
    {/if}

    {if $dorVermenuBgOutside !=''}
        body #dor-verticalmenu .dor-vertical-title{
            background-color:{$dorVermenuBgOutside} !important;
        }
    {/if}

    {if $dorVermenuBgColor !=''}
        body #dor-verticalmenu .dor-verticalmenu{
            background-color:{$dorVermenuBgColor} !important;
        }
    {/if}

    {if $dorVermenuColorText !=''}
        body #dor-verticalmenu .dor-vertical-title h4,
        body #dor-verticalmenu .dor-vertical-title h4 span,
        body header#header #dor-verticalmenu .dor-vertical-title h4,
        body header#header #dor-verticalmenu .dor-vertical-title h4 span,
        body .fa-icon-menu > i{
            color:{$dorVermenuColorText} !important;
        }
    {/if}

    {if $dorVermenuColorLink !=''}
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu > li > a > span.menu-title,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu > li > a > span.menu-icon i{
            color:{$dorVermenuColorLink} !important;
        }
    {/if}
    {if $dorVermenuColorLinkHover !=''}
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu > li > a:hover > span.menu-title,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu > li > a:hover > span.menu-icon i{
            color:{$dorVermenuColorLinkHover} !important;
        }
    {/if}
    {if $dorVermenuColorSubText !=''}
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu div,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu .widget-content,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu .widget-content div{
            color:{$dorVermenuColorSubText} !important;
        }
    {/if}
    {if $dorVermenuColorSubLink !=''}
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu > li > a,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu ul li a,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu a > span,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu .widget-content .widget-heading a{
            color:{$dorVermenuColorSubLink} !important;
        }
    {/if}
    {if $dorVermenuColorSubLinkHover !=''}
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu > li > a:hover,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu ul li a:hover,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu a:hover > span,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu .widget-content .widget-heading a:hover,
        body #dor-verticalmenu .dor-verticalmenu .nav.navbar-nav.verticalmenu .dropdown-menu .widget-content .widget-heading a:hover span{
            color:{$dorVermenuColorSubLinkHover} !important;
        }
    {/if}
    /*****End Mega Menu*****/



</style>