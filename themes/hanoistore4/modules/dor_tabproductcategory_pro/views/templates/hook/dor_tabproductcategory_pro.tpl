<div id="dor-tab-product-category" class="clearfix col-md-9 col-sm-9 col-xs-12">
	<div class="dor-pro-tabcontent col-md-12 col-sm-12 col-xs-12 clearfix">
		<div class="dor-tab-product-category-wrapper" data-tab-id="{$tabChose}">
			<div class="row-item-protab">
				<div class="protab-lists">
					<div>
						<h2><span class="tab-title-pro"><i class="fa fa-heart-o" aria-hidden="true"></i><span>{$tabChoseName}</span></span></h2>
						<ul role="tablist" class="nav nav-tabs" id="dorTabAjaxPro" data-ajaxurl="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_tabproductcategory_pro/productcategory-ajax.php">
							{$i=0}
							{foreach from=$listTabs item=cate name=tabCate}
							<li class="{if $smarty.foreach.tabCate.first}first_item{elseif $smarty.foreach.tabCate.last}last_item{else}{/if} {if $cate.id==$tabID.id} active {/if}"><a aria-expanded="false" data-toggle="tab" id="cate-tab-data-pro-{$cate.id}-tab" href="#cate-tab-data-pro-{$cate.id}">{if $cate.icontab != ""}<i class="{$cate.icontab}"></i>{/if}{$cate.name}</a></li>
							{$i= $i+1}
							{/foreach}	
						</ul>
					</div>
				</div>
				<div class="protab-contents">
					<div class="tab-content dorTabProductCategoryContentPro" id="dorTabProductCategoryContentPro">
					{if $listTabsProduct}
						{$k=0}
						{foreach from=$listTabsProduct item=productTab name=tabCatePro}
						<div aria-labelledby="cate-tab-data-pro-{$productTab.id}-tab" id="cate-tab-data-pro-{$productTab.id}" class="tab-pane fade {if $productTab.id==$tabID.id} active {/if} in">
							<div class="productTabContentPro_{$productTab.id} dor-content-items">
								<div class="row-items">
								{if $productTab.id==$tabID.id} {include file="$self/product-item.tpl"} {/if}
								</div>
							</div>
							<a href="#" class="load-more-tab dor-icon-float-away" data-page="2" data-limit="" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_tabproductcategory_pro/productcategory-ajax.php" onclick="return false">
								<span class="clt-left"></span><span><i class="fa fa-plus"></i></span><span class="clt-right"></span>
							</a>
						</div>
						{$k= $k+1}
						{/foreach}
					{/if}
						{$key=0}
						{foreach from=$listTabs item=cate name=tabCate}
						<div aria-labelledby="cate-tab-data-pro-{$cate.id}-tab" id="cate-tab-data-pro-{$cate.id}" class="tab-pane fade {if $cate.id==$tabID.id} active {/if} in">
							<div class="productTabContentPro_{$cate.id} dor-content-items">
								<div class="row-items">
								{if $cate.id==$tabID.id} {include file="$productItemPath"} {/if}
								</div>
							</div>
							<div class="view-all">
								<a class="btn btn-scale btn-go-cate" href="#"><span><span class="fa fa-long-arrow-right">&nbsp;</span></span>{l s='View all'}</a>
							</div>
						</div>
						{$key= $key+1}
						{/foreach}	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>