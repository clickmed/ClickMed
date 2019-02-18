{if isset($productLists) && $productLists}
<div id="dor-tabsidebar-product-category">
	<div class="dor-tabsidebar-product-category-wrapper">
		<ul role="tablist" class="nav nav-tabs dorTabAjaxSidebar" id="dorTabAjaxSidebar" data-ajaxurl="{$urls.base_url}modules/dor_ajaxtabsidebar_product/productcategory-ajax.php">
			{$j=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<li class="{if $smarty.foreach.tabCatePro.first}first_item{elseif $smarty.foreach.tabCatePro.last}last_item{else}{/if} {if $productTab.id==$tabID.id} active {/if}" data-rel="tab_{$productTab.id}"  >
			<div class="section-title"><h2 class="title_block"><a aria-expanded="false" data-toggle="tab" id="cate-tab-data-{$productTab.id}-tab" href="#cate-tab-data-{$productTab.id}">{$productTab.name}</a></h2></div>
			</li>
				{$j= $j+1}
			{/foreach}

			{$i=0}
			{foreach from=$listTabs item=cate name=tabCate}
			<li class="{if $smarty.foreach.tabCate.first}first_item{elseif $smarty.foreach.tabCate.last}last_item{else}{/if} {if $cate.id==$tabID.id} active {/if}"><div class="section-title"><h2 class="title_block"><a aria-expanded="false" data-toggle="tab" id="cate-tab-data-{$cate.id}-tab" href="#cate-tab-data-{$cate.id}">{$cate.name}</a></h2></div></li>
			{$i= $i+1}
			{/foreach}	
		</ul>
		<div class="tab-content" id="dorTabSidebarProductCategoryContent">
		{if $listTabsProduct}
			{$k=0}
			{foreach from=$listTabsProduct item=productTab name=tabCatePro}
			<div aria-labelledby="cate-tab-data-{$productTab.id}-tab" id="cate-tab-data-{$productTab.id}" class="tab-pane fade {if $productTab.id==$tabID.id} active {/if} in">
				<div class="productTabContent_{$productTab.id} dor-content-items">
					<div class="row-sidebar nav-button-style-one">
					{if $productTab.id==$tabID.id} {include file="$selfTmp/product-item.tpl"} {/if}
					</div>
				</div>
				<div class="view-all">
					<a class="btn btn-scale btn-go-cate" href="{$urls.pages.index}{$productTab.url}"><span><span class="fa fa-long-arrow-right">&nbsp;</span></span>{l s='View All' mod="dor_ajaxtabsidebar_product"}</a>
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
				<div class="view-all">
					<a class="btn btn-scale btn-go-cate" href="{$urls.pages.index}"><span><span class="fa fa-long-arrow-right">&nbsp;</span></span>{l s='View All' mod="dor_ajaxtabsidebar_product"}</a>
				</div>
			</div>
			{$key= $key+1}
			{/foreach}	
		</div>
	</div>
</div>
{/if}