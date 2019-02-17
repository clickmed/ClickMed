{if isset($productLists) && $productLists}
<div id="dor-tabsidebar-product-category2" class="sidebar2 col-lg-3 col-sm-3 col-xs-12">
	<div class="dor-tabsidebar-product-category-wrapper">
		<ul role="tablist" class="nav nav-tabs dorTabAjaxSidebar" id="dorTabAjaxSidebar" data-ajaxurl="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_ajaxtabsidebar_product2/productcategory-ajax.php">
			{$j=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<li class="{if $smarty.foreach.tabCatePro.first}first_item{elseif $smarty.foreach.tabCatePro.last}last_item{else}{/if} {if $productTab.id==$tabID.id} active {/if}" data-rel="tab_{$productTab.id}"  >
			<a aria-expanded="false" data-toggle="tab" id="cate-tab-data-{$productTab.id}-tab" href="#cate-tab-data-{$productTab.id}"><p class="title_block">{$productTab.name}</p></a>
			</li>
				{$j= $j+1}
			{/foreach}

			{$i=0}
			{foreach from=$listTabs item=cate name=tabCate}
			<li class="{if $smarty.foreach.tabCate.first}first_item{elseif $smarty.foreach.tabCate.last}last_item{else}{/if} {if $cate.id==$tabID.id} active {/if}"><a aria-expanded="false" data-toggle="tab" id="cate-tab-data-{$cate.id}-tab" href="#cate-tab-data-{$cate.id}"><p class="title_block">{$cate.name}</p></a></li>
			{$i= $i+1}
			{/foreach}	
		</ul>
		<div class="tab-content" id="dorTabSidebarProductCategoryContent">
		{if $listTabsProduct}
			{$k=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<div aria-labelledby="cate-tab-data-{$productTab.id}-tab" id="cate-tab-data-{$productTab.id}" class="tab-pane fade {if $productTab.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$productTab.id} dor-content-items">
					<div class="row-sidebar">
					{if $productTab.id==$tabID.id} {include file="$self/product-item.tpl"} {/if}
					</div>
				</div>
				<div class="view-all">
					<a class="btn btn-scale btn-go-cate" href="{$urls.pages.index}{$productTab.url}"><span><span class="fa fa-long-arrow-right">&nbsp;</span></span>{l s='View All' mod="dor_ajaxtabsidebar_product2"}</a>
				</div>
			</div>
			{$k= $k+1}
			{/foreach}
		{/if}
			{$key=0}
			{foreach from=$listTabs item=cate name=tabCate}
			<div aria-labelledby="cate-tab-data-{$cate.id}-tab" id="cate-tab-data-{$cate.id}" class="tab-pane fade {if $cate.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$cate.id} dor-content-items">
					<div class="row-sidebar">
					{if $cate.id==$tabID.id} {include file="$productItemPath"} {/if}
					</div>
				</div>
				<div class="view-all">
					<a class="btn btn-scale btn-go-cate" href="{$urls.pages.index}"><span><span class="fa fa-long-arrow-right">&nbsp;</span></span>{l s='View All' mod="dor_ajaxtabsidebar_product2"}</a>
				</div>
			</div>
			{$key= $key+1}
			{/foreach}	
		</div>
	</div>
</div>
{/if}