<div id="dor-tab-product-category2">
	<div class="dor-tab-product-category-wrapper">
		<ul role="tablist" class="nav nav-tabs" id="dorTabAjax2" data-ajaxurl="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory2/productcategory-ajax.php">
			{$j=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<li class="{if $smarty.foreach.tabCatePro.first}first_item{elseif $smarty.foreach.tabCatePro.last}last_item{else}{/if} {if $productTab.id==$tabID.id} active {/if}" data-rel="tab_{$productTab.id}"  >
			<a aria-expanded="false" data-toggle="tab" id="cate-tab-data-{$productTab.id}-tab" href="#cate-tab-data-{$productTab.id}"><span>{$productTab.name}</span></a>
			</li>
				{$j= $j+1}
			{/foreach}

			{$i=0}
			{foreach from=$listTabs item=cate name=tabCate}
			<li class="{if $smarty.foreach.tabCate.first}first_item{elseif $smarty.foreach.tabCate.last}last_item{else}{/if} {if $cate.id==$tabID.id} active {/if}"><a aria-expanded="false" data-toggle="tab" id="cate-tab-data-{$cate.id}-tab" href="#cate-tab-data-{$cate.id}"><span>{$cate.name}</span></a></li>
			{$i= $i+1}
			{/foreach}	
		</ul>
		<div class="tab-content" id="dorTabProductCategory2Content">
		{if $listTabsProduct}
			{$k=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<div data-aria-labelledby="cate-tab-data-{$productTab.id}-tab" id="cate-tab-data-{$productTab.id}" class="tab-pane fade {if $productTab.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$productTab.id} dor-content-items">
					<div class="row">
					{if $productTab.id==$tabID.id} {include file="$self/product-item.tpl"} {/if}
					</div>
				</div>
				<a href="#" class="load-more-tab dor-icon-float-away" data-page="2" data-limit="" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory2/productcategory-ajax.php" onclick="return false">
					<span class="clt-left"></span><span><i class="fa fa-plus"></i></span><span class="clt-right"></span>
				</a>
			</div>
			{$k= $k+1}
			{/foreach}
		{/if}
			{$key=0}
			{foreach from=$listTabs item=cate name=tabCate}
			<div data-aria-labelledby="cate-tab-data-{$cate.id}-tab" id="cate-tab-data-{$cate.id}" class="tab-pane fade {if $cate.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$cate.id} dor-content-items">
					<div class="row">
					{if $cate.id==$tabID.id} {include file="$productItemPath"} {/if}
					</div>
				</div>
				<a href="#" class="load-more-tab dor-icon-float-away" data-page="2" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory2/productcategory-ajax.php" data-limit="" onclick="return false">
					<span class="clt-left"></span><span><i class="fa fa-plus"></i></span><span class="clt-right"></span>
				</a>
			</div>
			{$key= $key+1}
			{/foreach}	
		</div>
	</div>
</div>