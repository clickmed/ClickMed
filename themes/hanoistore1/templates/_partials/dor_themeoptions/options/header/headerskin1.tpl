<div class="header-container header-skin2">
	<header id="header">
		{if $dorTopbarSkin != ''}
			{include file="$dorTopbarSkin"}
		{/if}
		<div class="header-main-wrapper">
			<div id="header_logo" class="col-lg-2 col-md-2 col-xs-3 col-sm-3">
				<div class="logo-wapper">
					<a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
						<img class="logo img-responsive" src="{$tpl_uri|escape:'html':'UTF-8'}img/logo.png" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
					</a>
				</div>
			</div>
			<div class="dor-header-menu col-lg-7 col-md-7">
			{capture name='dormegamenu'}{hook h='dormegamenu'}{/capture}
			{if $smarty.capture.dormegamenu}
				<nav class="dorMegaMenu-Wapper">{$smarty.capture.dormegamenu}</nav>
			{/if}
			</div>
			{capture name='displayNav'}{hook h='displayNav'}{/capture}
			{if $smarty.capture.displayNav}
			<div class="header-line-wapper col-lg-3 col-md-3 col-sm-6 col-xs-6">
				<nav class="header-nav pull-right">
				<div class="search-box-area">
                    <div class="header-menu-item-icon">
					    <a href="#" class="icon-search">
							<i class="fa animated fa-search search-icon"></i>
					    </a>
                    </div>
                </div>
				<div class="user-menu-area">
                    <div class="header-menu-item-icon">
                        <a class="user-icon">
                            <i class="fa animated fa-gear"></i>
                        </a>
                        <div class="user-menu active">
                            {capture name='headerDorado1'}{hook h='headerDorado1'}{/capture}
							{if $smarty.capture.headerDorado1}
								{$smarty.capture.headerDorado1}
							{/if}
                        </div>
                    </div>
                </div>
                <div class="mini-cart-area">
                    <div class="header-menu-item-icon">
                        {if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
                    </div>
                </div>
				</nav>
			</div>
			{/if}
		</div>
	</header>
</div>