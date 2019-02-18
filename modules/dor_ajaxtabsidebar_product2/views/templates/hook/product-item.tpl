{foreach from=$productLists item=products name=product}
	<ul class="product_list_sidebar">
		{include file="$self/items/default.tpl"}
	</ul>
{/foreach}