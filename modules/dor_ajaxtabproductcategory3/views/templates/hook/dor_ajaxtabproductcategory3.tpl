<div id="dor-tab-product-category3">
	<div class="dor-tab-product-category-wrapper dorButtonArrow" data-ajaxurl="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory3/productcategory-ajax.php">
		{foreach from=$listTabsProduct item=productTab name=tabCatePro}
		<div id="lip-tab-{$productTab.id}" class="lip-tab-inner col-lg-6 col-sm-6 col-xs-12" rel="{$productTab.id}">
			<div class="lip-tab-title">
				<h3><span>{$productTab.name}</span></h3>
			</div>
			<div class="lip-tab-content">
				
			</div>
		</div>
		{/foreach}
	</div>
</div>