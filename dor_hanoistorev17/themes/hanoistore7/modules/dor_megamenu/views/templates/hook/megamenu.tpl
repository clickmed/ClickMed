
<nav class="dor-megamenu col-lg-9 col-sx-9 col-sm-9 pull-right">
    <div class="navbar navbar-default " role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle open_menu">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="dor-top-menu" class="collapse navbar-collapse navbar-ex1-collapse">
            <div class="close_menu" style="display:none;">
                <span class="btn-close"><i class="fa fa-angle-left"></i></span>
            </div>
            <div class="mobile-logo-menu hidden-lg hidden-md">
                <a href="{if isset($force_ssl) && $force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}" title="{$shop.name}">
                    <img class="logo img-responsive" src="{$urls.img_url|escape:'html':'UTF-8'}dorado/logo-menu.png" alt="{$shop.name}"/>
                </a>
            </div>
            {$output nofilter}{* HTML, can not escape *}
        </div>
    </div>  
</nav>