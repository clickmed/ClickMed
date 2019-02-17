<div class="header-container header-skin2">
	<header id="header">
		{if $dorTopbarSkin != ''}
			{include file="$dorTopbarSkin"}
		{/if}
		<div class="header-main-wrapper">
			<div class="container">
				<div class="row">
					<div id="header_logo" class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
						<a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
							<img class="logo img-responsive" src="{$tpl_uri|escape:'html':'UTF-8'}img/logo.png" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
						</a>
					</div>
					{capture name='displayNav'}{hook h='displayNav'}{/capture}
					{if $smarty.capture.displayNav}
						<nav class="header-nav pull-right">
							{$smarty.capture.displayNav}
							<div class="option-oct">
								<a href="#" class="icon-oct"></a>
								<div class="main-oct"></div>
							</div>
						</nav>
					{/if}
					{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
				</div>
			</div>
		</div>
	</header>
</div>