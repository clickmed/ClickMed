<div class="header-container header-skin5">
	<header id="header">
		<div class="header-main-wrapper">
			<div class="container">
				<div class="row">
					<div id="header_logo">
						<a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
							<img class="logo img-responsive" src="{$tpl_uri|escape:'html':'UTF-8'}img/logo.png" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
						</a>
					</div>
					<div class="dor-info-header">
						{capture name='displayNav'}{hook h='displayNav'}{/capture}
						{if $smarty.capture.displayNav}
							<div class="nav-skin5">
								{$smarty.capture.displayNav}
							</div>
						{/if}
						{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
					</div>
				</div>
			</div>
		</div>
	</header>
</div>