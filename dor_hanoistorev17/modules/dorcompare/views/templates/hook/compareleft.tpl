<div class="dorCompareLeftSidebar">
	<div class="dorCompareLeftInner">
		<div class="section-title">
			<h2 class="title_block">Compare Products <span class="counter qty hidden">0 items</span></h2>
		</div>
		
		<div class="list-compare-left">
			<ul>
			{if $hasProduct}
			{foreach from=$compares item=product name=for_products}
				<li><a href="{$product.url}">{$product.name}</a><span class="compare_remove" href="#" title="{l s='Remove'}" data-productid="{$product.id}"><i class="material-icons">&#xE872;</i></span></li>
			{/foreach}
			{else}
				<li class="empty">You have no items to compare.</li>
			{/if}
			</ul>
		</div>
		<div class="actions-footer-sidebar {if !$hasProduct}compare-hide{/if}">
			<a href="{url entity='module' name='dorcompare' controller='compare' params=[]}" class="dor-sidebar-compare"><span>Compare</span></a>
			<a href="#" onclick="return false" class="dor-sidebar-compare-clear"><span>Clear all</span></a>
		</div>
	</div>
</div>