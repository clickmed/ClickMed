{if isset($products) && $products}
<ul class='filterDataSearch'>
	{foreach from=$products item=product name=products}
		<li>
		<a href="{$product.product_link}" title="{$product.pname}">
			<img alt="{$product.pname}" src="{$product.ajaxsearchimage}">
			<span class="search-name">{$product.pname|truncate:20:'...'|escape:'html':'UTF-8'}</span>
			<span itemprop="price" class="price product-price search-price">
				{hook h="displayProductPriceBlock" product=$product type="before_price"}
				{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
			</span>
			<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
			{if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
				{hook h="displayProductPriceBlock" product=$product type="old_price"}
				<span class="old-price product-price">
					{displayWtPrice p=$product.price_without_reduction}
				</span>
				{if $product.specific_prices.reduction_type == 'percentage'}
					<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
				{/if}
			{/if}
		</a>
		</li>
	{/foreach}
</ul>
{/if}