{*
* Manager and display megamenu use bootstrap framework
*
* @package   dormegamenu
* @version   1.0.0
* @author    http://www.doradothemes@gmail.com
* @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
*               <info@doradothemes@gmail.com>.All rights reserved.
* @license   GNU General Public License version 2
*}
{if isset($products) && !empty($products)}
<div class="widget-products block {$additionclss}">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</div>
	{/if}
	<div class="widget-inner block_content">
		
		{if isset($products) AND $products}
		<div class="product-block">
			{assign var='liHeight' value=140}
			{assign var='nbItemsPerLine' value=3}
			{assign var='nbLi' value=$limit}
			{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
			{math equation="nbLines*liHeight" nbLines=$nbLines|ceil liHeight=$liHeight assign=ulHeight}
			{$mproducts=array_chunk($products,$limit)}
			{foreach from=$products item=product name=homeFeaturedProducts}
				{math equation="(total%perLine)" total=$smarty.foreach.homeFeaturedProducts.total perLine=$nbItemsPerLine assign=totModulo}
				{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
			 <div class="product-container clearfix">	
					<div class="image ">
						<!-- <a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:html:'UTF-8'}" class="product_image">
							<img class="img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'htmlall':'UTF-8'}"  alt="{$product.name|escape:html:'UTF-8'}" />
							{if isset($product.new) && $product.new == 1}<span class="new">{l s='New' mod='dormegamenu'}</span>{/if}
						</a> -->
					</div>
					<div class="product-meta">
						<a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a>
						{block name='product_price_and_shipping'}
			              {if $product.show_price}
			                <div class="product-price-and-shipping">
			                  {if $product.has_discount}
			                    {hook h='displayProductPriceBlock' product=$product type="old_price"}

			                    <span class="regular-price">{$product.regular_price}</span>
			                    {if $product.discount_type === 'percentage'}
			                      <span class="discount-percentage">{$product.discount_percentage}</span>
			                    {/if}
			                  {/if}

			                  {hook h='displayProductPriceBlock' product=$product type="before_price"}

			                  <span class="price">{$product.price}</span>

			                  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

			                  {hook h='displayProductPriceBlock' product=$product type='weight'}
			                </div>
			              {/if}
			            {/block}
					</div>
				</div>					
			{/foreach}
		</div>
		{/if}
	</div>
</div>
{/if}