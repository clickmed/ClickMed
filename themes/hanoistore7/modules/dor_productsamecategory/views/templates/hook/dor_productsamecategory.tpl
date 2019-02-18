{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($products) && count($products) > 0 && $products !== false}
	{*define number of products per line in other page for desktop*}
	{if $page.page_name !='index' && $page.page_name !='product'}
		{assign var='nbItemsPerLine' value=3}
		{assign var='nbItemsPerLineTablet' value=2}
		{assign var='nbItemsPerLineMobile' value=3}
	{else}
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbItemsPerLineTablet' value=3}
		{assign var='nbItemsPerLineMobile' value=2}
	{/if}
	{*define numbers of product per line in other page for tablet*}
	{assign var='nbLi' value=$products|@count}
	{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
	{math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
<div class="clearfix blockproductscategory">
	<h2 class="productscategory_h2">
		{if $products|@count == 1}
			<span>{l s='Related' sprintf=[$products|@count] mod='productscategory'}</span>
			<em>{l s='Product' sprintf=[$products|@count] mod='productscategory'}</em>
		{else}
			<span>{l s='Related' sprintf=[$products|@count] mod='productscategory'}</span>
			<em>{l s='Products' sprintf=[$products|@count] mod='productscategory'}</em>
		{/if}
	</h2>
	<div id="{if count($products) > $per_page}productscategory_same{else}productscategory_noscroll{/if}">
	<div id="productscategory_list_data" class="productscategory_list arrowStyleDot1">
	    <div class="productSameCategory-wrapper">
	    <!-- Products list -->
			<ul class="product_list_items product_list grid">
			{foreach from=$products item=product name=products}
				<li class="ajax_block_product js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
					<div class="product-container" itemscope itemtype="https://schema.org/Product">
						{capture name='dorwishlist'}{hook h='dorwishlist'}{/capture}
					    {if $smarty.capture.dorwishlist}
					    {$smarty.capture.dorwishlist nofilter}
					    {/if}
						<div class="left-block">
							<div class="product-image-container">
								<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
									<img class="replace-2x img-responsive" src="{$product.imageThumb}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"/>
								</a>
								{if isset($product.new) && $product.new == 1}
									<a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
										<span class="new-label">{l s='New'}</span>
									</a>
								{/if}
								{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price}
									<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
										<span class="sale-label">{l s='Sale!'}</span>
									</a>
								{/if}
							</div>
							{if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
							{hook h="displayProductPriceBlock" product=$product type="weight"}
							<div class="category-action-buttons">
		                        <div class="row">
		                            <div class="col-xs-6">
		                                <div class="category-item-lists">
		                                    <a href="#">{$product.category|replace:'-':' '}</a>
		                                </div>
		                            </div>
		                            <div class="col-xs-6">
		                                <div class="action-button">
					                        <ul>
					                            <li class="icon-line-quickview">
					                              <a href="#" class="quick-view countdown-view-detail" data-link-action="quickview" data-toggle="tooltip" title="{l s='View detail'}">
					                                 <i class="material-icons search">&#xE8B6;</i>
					                              </a>
					                            </li>
					                            <li class="icon-line-cart">
					                              <form action="{$cartUrl}" method="post" id="add-to-cart-or-refresh-{$product.id_product}">
					                                    <div class="add">
					                                    <input type="hidden" name="token" value="{$static_token}">
					                                    <input name="id_product" value="{$product.id_product}" type="hidden">
					                                    <input type="hidden" name="id_customization" value="0">
					                                    <a href="{$cartUrl}" class="cart-button button ajax_add_to_cart_button btn btn-default add-to-cart" data-button-action="add-to-cart" data-toggle="tooltip" title="{l s='Add to cart' d='Shop.Theme.Actions'}" {if !$product.add_to_cart_url}disabled{/if}>
					                                    <i class="material-icons shopping-cart">&#xE547;</i>
					                                    <span class="hidden">{l s='Add to cart' d='Shop.Theme.Actions'}</span>
					                                  </a>
					                              </div>
					                              </form>
					                            </li>
					                        </ul>
					                    </div>
		                            </div>
		                        </div>
		                    </div>
						</div>
						<div class="right-block">
							<h5 itemprop="name">
								{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
								<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
									{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
								</a>
							</h5>
							{capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$product}{/capture}
							{if $smarty.capture.displayProductListReviews}
								<div class="hook-reviews">
								{hook h='displayProductListReviews' product=$product}
								</div>
							{/if}
							<p class="product-desc hidden" itemprop="description">
								{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
							</p>
							{if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
							<div class="content_price">
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

					                  <span itemprop="price" class="price">{$product.price}</span>

					                  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

					                  {hook h='displayProductPriceBlock' product=$product type='weight'}
					                </div>
					              {/if}
					            {/block}
							</div>
							{/if}
							
							<div class="button-container hidden">
								{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2}
									{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
										{capture}add=1&amp;id_product={$product.id_product|intval}{if isset($product.id_product_attribute) && $product.id_product_attribute}&amp;ipa={$product.id_product_attribute|intval}{/if}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
										<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
											<span>{l s='Add to cart'}</span>
										</a>
									{else}
										<span class="button ajax_add_to_cart_button btn btn-default disabled" data-toggle="tooltip" data-placement="top" title="{l s='Out of stock'}">
											<span>{l s='Add to cart'}</span>
										</span>
									{/if}
								{/if}
								<a class="button lnk_view btn btn-default" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View'}">
									<span>{if (isset($product.customization_required) && $product.customization_required)}{l s='Customize'}{else}{l s='More'}{/if}</span>
								</a>
							</div>
							{if isset($product.color_list)}
								<div class="color-list-container">{$product.color_list}</div>
							{/if}
							<div class="product-flags">
								{if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
									{if isset($product.online_only) && $product.online_only}
										<span class="online_only">{l s='Online only'}</span>
									{/if}
								{/if}
								{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price}
									{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price}
										<span class="discount">{l s='Reduced price!'}</span>
									{/if}
							</div>
							{if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
								{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
									<span class="availability">
										{if ($product.allow_oosp || $product.quantity > 0)}
											<span class="{if $product.quantity <= 0 && isset($product.allow_oosp) && !$product.allow_oosp} label-danger{elseif $product.quantity <= 0} label-warning{else} label-success{/if}">
												{if $product.quantity <= 0}{if $product.allow_oosp}{if isset($product.available_later) && $product.available_later}{$product.available_later}{else}{l s='In Stock'}{/if}{else}{l s='Out of stock'}{/if}{else}{if isset($product.available_now) && $product.available_now}{$product.available_now}{else}{l s='In Stock'}{/if}{/if}
											</span>
										{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
											<span class="label-warning">
												{l s='Product available with different options'}
											</span>
										{else}
											<span class="label-danger">
												{l s='Out of stock'}
											</span>
										{/if}
									</span>
								{/if}
							{/if}
						</div>
						{if $page.page_name != 'index'}
							<div class="functional-buttons clearfix hidden">
								{hook h='displayProductListFunctionalButtons' product=$product}
								{if isset($comparator_max_item) && $comparator_max_item}
									<div class="compare">
										<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" data-toggle="tooltip" data-placement="top" title="{l s='Compare'}"><span class="hidden">{l s='Add to Compare'}</span></a>
									</div>
								{/if}
							</div>
						{/if}
					</div><!-- .product-container> -->
				</li>
			{/foreach}
			</ul>
		</div>
	</div>
	</div>
</div>
{/if}
