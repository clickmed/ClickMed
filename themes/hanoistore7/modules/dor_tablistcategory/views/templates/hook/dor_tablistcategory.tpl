<div id="dor-tab-list-category" class="dor-tab-list-category clearfix">
	<div class="col-lg-12 col-sm-12-col-xs-12">
		<div class="dor-tab-list-category-wrapper">

			<div class="header-tablist">
				<div class="row">
					<div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
						<h3><i class="dor-icon-tab icon-tab-01"></i><span>{l s=$moduleTitle mod='dor_tablistcategory'}</span></h3>
					</div>
					<div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">
						<ul role="tablist" class="nav nav-tabs dorTabLists" id="dorTabLists" data-ajaxurl="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_tablistcategory/productcategory-ajax.php">
							{$j=0}
							{foreach from=$listTabsProduct item=productTab name=tabCatePro}
							<li class="{if $smarty.foreach.tabCatePro.first}first_item{elseif $smarty.foreach.tabCatePro.last}last_item{else}{/if} {if $productTab.id==$tabID.id} active {/if}" data-rel="tab_{$productTab.id}"  >
							<a aria-expanded="false" data-toggle="tab" id="cate-tab-list-{$productTab.id}-tab" href="#cate-tab-list-{$productTab.id}">{$productTab.name}</a>
							</li>
								{$j= $j+1}
							{/foreach}

							{$i=0}
							{foreach from=$listTabs item=cate name=tabCate}
							<li class="{if $smarty.foreach.tabCate.first}first_item{elseif $smarty.foreach.tabCate.last}last_item{else}{/if} {if $cate.id==$tabID.id} active {/if}"><a aria-expanded="false" data-toggle="tab" id="cate-tab-list-{$cate.id}-tab" href="#cate-tab-list-{$cate.id}">{$cate.name}</a></li>
							{$i= $i+1}
							{/foreach}	
						</ul>
					</div>
				</div>
			</div>
			<div class="protab-contents clearfix">
				<div class="row">
					<div class="slideImagesdata col-xs-12 col-sm-5 col-md-4 col-lg-3">
				    	{if $slideImages}
						<div class="ajax_block_product slideImagesList slide1">
							<div class="slideImages">
								{foreach from=$slideImages item=slide name=slideImages}
								<div class="item-slide-image-tab">
									<img src="{$slide}" alt="">
								</div>
								{/foreach}
							</div>
						</div>
					  {/if}
				    </div>
				    <div class="tab-content tablist-content-data col-xs-12 col-sm-7 col-md-8 col-lg-9" id="dorTabListCategoryContent">
					{if $listTabsProduct}
						{$k=0}
						{foreach from=$listTabsProduct item=productTab name=tabCatePro}
						<div data-aria-labelledby="cate-tab-list-{$productTab.id}-tab" id="cate-tab-list-{$productTab.id}" class="tab-pane fade {if $productTab.id==$tabID.id} active {/if} in">
							<div class="productTabContent_{$productTab.id} dor-content-items">
								<div class="row-tablist">
								{if $productTab.id==$tabID.id} {include file="$self/product-item.tpl"} {/if}
								</div>
							</div>
							<a href="#" class="load-more-tab dor-icon-float-away tablists" data-page="2" data-limit="{$optionsConfig.listLimit}" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_tablistcategory/productcategory-ajax.php" onclick="return false">
								<span class="clt-left"></span><span class="load-center"><i class="fa fa-plus"></i></span><span class="clt-right"></span>
							</a>
						</div>
						{$k= $k+1}
						{/foreach}
					{/if}
						{$key=0}
						{foreach from=$listTabs item=cate name=tabCate}
						<div data-aria-labelledby="cate-tab-list-{$cate.id}-tab" id="cate-tab-list-{$cate.id}" class="tab-pane fade {if $cate.id==$tabID.id} active {/if} in">
							<div class="productTabContent_{$cate.id} dor-content-items">
								<div class="row-tablist">
								{if $cate.id==$tabID.id} {include file="$productItemPath"} {/if}
								</div>
							</div>
							<a href="#" class="load-more-tab dor-icon-float-away tablists" data-page="2" data-ajax="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}modules/dor_tablistcategory/productcategory-ajax.php" data-limit="{$optionsConfig.listLimit}" onclick="return false">
								<span class="clt-left"></span><span class="load-center"><i class="fa fa-plus"></i></span><span class="clt-right"></span>
							</a>
						</div>
						{$key= $key+1}
						{/foreach}	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>