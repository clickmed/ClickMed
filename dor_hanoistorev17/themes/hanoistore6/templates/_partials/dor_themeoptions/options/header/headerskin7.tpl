<div class="header-container header-skin7">
	<header id="header">
		
		
		<div class="header-main-wrapper">
			<div class="container">
				<div class="row">
					<div class="dor-header-nav">
						
						<div class="dor-more-header-field col-md-5 col-sm-6 col-xs-12 top-links">
							<a href="{$base_dir|escape:'html':'UTF-8'}module/blockwishlist/mywishlist" class="dor-header-wishlist" title="Wishlist"><i class="fa fa-heart"></i></a>
							<a href="{$base_dir|escape:'html':'UTF-8'}products-comparison" class="dor-header-compare" title="Compare"><i class="fa fa-random"></i></a>
							{if $dorTopbarSkin != ''}
								{include file="$dorTopbarSkin"}
							{/if}
						</div>
						<div class="dor-search-header col-md-5 col-sm-6 col-xs-12 f-float"></div>
						<div id="header_logo" class="col-md-2 col-sm-12 col-xs-12">
							<a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
								<img class="logo img-responsive" src="{$tpl_uri|escape:'html':'UTF-8'}img/logo-black.png" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
							</a>
						</div>
					</div>
					<div class="dor-header-menu">
						{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
					</div>
				</div>
			</div>
		</div>
	</header>
</div>