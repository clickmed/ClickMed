<div id="dor-tab-product-category" class="col-lg-9 col-sm-9 col-xs-12">
	<div class="dor-tab-product-category-wrapper">
		<ul role="tablist" class="nav nav-tabs" id="dorTabAjax" data-ajaxurl="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory/productcategory-ajax.php">
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
		<div class="tab-content" id="dorTabProductCategoryContent">
		{if $listTabsProduct}
			{$k=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<div aria-labelledby="cate-tab-data-{$productTab.id}-tab" id="cate-tab-data-{$productTab.id}" class="tab-pane fade {if $productTab.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$productTab.id} dor-content-items">
					<div class="row-items">
					{if $productTab.id==$tabID.id} {include file="$self/product-item.tpl"} {/if}
					</div>
				</div>
				<a href="#" class="load-more-tab dor-icon-float-away" data-page="2" data-limit="{$optionsConfig.number_per_page}" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory/productcategory-ajax.php" onclick="return false">
					<span class="clt-left"></span><span><i class="fa fa-plus"></i></span><span class="clt-right"></span>
				</a>
				<div class="ctr-showmore">
					<button type="button" class="showMoreAll dorexpand" rel="1">{l s='Expand All'}</button>
					<button type="button" class="showMoreAll dorcollapse" rel="0">{l s='Collapse All'}</button>
				</div>
			</div>
			{$k= $k+1}
			{/foreach}
		{/if}
			{$key=0}
			{foreach from=$listTabs item=cate name=tabCate}
			<div aria-labelledby="cate-tab-data-{$cate.id}-tab" id="cate-tab-data-{$cate.id}" class="tab-pane fade {if $cate.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$cate.id} dor-content-items">
					<div class="row">
					{if $cate.id==$tabID.id} {include file="$productItemPath"} {/if}
					</div>
				</div>
				<a href="#" class="load-more-tab dor-icon-float-away" data-page="2" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabproductcategory/productcategory-ajax.php" data-limit="{$optionsConfig.number_per_page}" onclick="return false">
					<span class="clt-left"></span><span><i class="fa fa-plus"></i></span><span class="clt-right"></span>
				</a>

			</div>
			{$key= $key+1}
			{/foreach}	
		</div>
	</div>
</div>