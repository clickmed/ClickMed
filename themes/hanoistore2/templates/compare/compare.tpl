{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file='page.tpl'}
{block name='page_header_container'}{/block}
{block name='page_content'}
<div id="content-wrapper" class="right-column col-xs-12 col-sm-12 col-md-12">
	<div id="dorcompare">
		<section id="products">
		<h1 class="h1 hidden">{l s='Product Comparison' mod='dorcompare'}</h1>
		<h2 class="hidden">{l s='Product Comparison' mod='dorcompare'}</h2>
		{if $hasProduct}
			<div class="products_block table-responsive">
				<table id="product_comparison" class="table table-bordered">
					<tr>
						<td class="td_empty compare_extra_information td-compare-header">
							{$HOOK_COMPARE_EXTRA_INFORMATION}
							<span>{l s='Product:'}</span>
						</td>
						{foreach from=$products item=product name=for_products}
							{assign var='replace_id' value=$product.id_product|cat:'|'}
							<td class="ajax_block_product comparison_infos product-block product-{$product.id_product}">
								<div class="remove">
									<a class="cmp_remove" href="#" title="{l s='Remove'}" data-id-product="{$product.id_product}">
										<i class="material-icons">&#xE872;</i>
									</a>
								</div>
								<div class="product-image-block">
									<a
									class="product_image"
									href="{$product.url|escape:'html':'UTF-8'}"
									title="{$product.name|escape:'html':'UTF-8'}">
										<img
										class="img-responsive"
										src="{$product.imageThumb}"
										alt="{$product.name|escape:'html':'UTF-8'}" />
									</a>
									{if isset($product.new) && $product.new == 1}
										<a class="new-box" href="{$product.url|escape:'html':'UTF-8'}">
											<span class="new-label">{l s='New'}</span>
										</a>
									{/if}
									{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
										{if $product.on_sale}
											<a class="sale-box" href="{$product.url|escape:'html':'UTF-8'}">
												<span class="sale-label">{l s='Sale!'}</span>
											</a>
										{/if}
									{/if}
								</div> <!-- end product-image-block -->
								<h5 class="h5-name-product">
									<a class="product-name" href="{$product.url|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
						              {$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
						            </a>
								</h5>
								<div class="prices-container">
									{if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
										<div class="content_price">
											{block name='product_price_and_shipping'}
								              {if $product.show_price}
								                <div class="product-price-and-shipping">
								                  {if $product.has_discount}
								                    {hook h='displayProductPriceBlock' product=$product type="old_price"}
								                    <span class="regular-price">{$product.regular_price}</span>
								                  {/if}

								                  {hook h='displayProductPriceBlock' product=$product type="before_price"}

								                  <span itemprop="price" class="price">{$product.price}</span>

								                  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

								                  {hook h='displayProductPriceBlock' product=$product type='weight'}

								                  {if $product.has_discount}
								                    {if $product.discount_type === 'percentage'}
								                      <span class="discount-percentage">{$product.discount_percentage}</span>
								                    {/if}
								                  {/if}

								                </div>
								              {/if}
								            {/block}
										</div>
										{/if}
								</div> <!-- end prices-container -->
								<div class="product_desc">
									{$product.description_short|strip_tags|truncate:60:'...'}
								</div>

								<div class="comparison_product_infos">
									<p class="comparison_availability_statut">
										{if !(($product.quantity <= 0 && !$product.available_later) OR ($product.quantity != 0 && !$product.available_now) OR !$product.available_for_order)}
											<span class="availability_label">{l s='Availability:'}</span>
											<span class="availability_value"{if $product.quantity <= 0} class="warning-inline"{/if}>
												{if $product.quantity <= 0}
													{if $product.allow_oosp}
														{$product.available_later|escape:'html':'UTF-8'}
													{else}
														{l s='This product is no longer in stock.'}
													{/if}
												{else}
													{$product.available_now|escape:'html':'UTF-8'}
												{/if}
											</span>
										{/if}
									</p>
									{if !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
									{hook h="displayProductPriceBlock" product=$product type="weight"}
									
								</div> <!-- end comparison_product_infos -->
								{capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$product}{/capture}
						          {if $smarty.capture.displayProductListReviews}
						            <div class="hook-reviews">
						            {hook h='displayProductListReviews' product=$product}
						            </div>
						          {/if}
								<div class="comparison-info-footer">
									<div class="comparison-addcart">
										<form action="{$cartUrl}" method="post" id="add-to-cart-or-refresh-{$product.id_product}">
		                                    <div class="add">
			                                    <input type="hidden" name="token" value="{$static_token}">
			                                    <input name="id_product" value="{$product.id_product}" type="hidden">
			                                    <input type="hidden" name="id_customization" value="0">
			                                    <a href="{$cartUrl}" class="cart-button button ajax_add_to_cart_button btn btn-default add-to-cart" data-button-action="add-to-cart" data-toggle="tooltip" title="{l s='Add to cart' d='Shop.Theme.Actions'}" {if !$product.add_to_cart_url}disabled{/if}>
			                                    <i class="material-icons shopping-cart">&#xE547;</i>
			                                    <span class="hidden-">{l s='Add to cart' d='Shop.Theme.Actions'}</span>
			                                  	</a>
		                              		</div>
		                              	</form>
	                              	</div>
								</div>
							</td>
						{/foreach}
					</tr>
					{if $ordered_features}
						{foreach from=$ordered_features item=feature}
							<tr>
								{cycle values='comparison_feature_odd,comparison_feature_even' assign='classname'}
								<td class="{$classname} feature-name td-compare-header" >
									<strong>{$feature.name|escape:'html':'UTF-8'}</strong>
								</td>
								{foreach from=$products item=product name=for_products}
									{assign var='product_id' value=$product.id_product}
									{assign var='feature_id' value=$feature.id_feature}
									{if isset($product_features[$product_id])}
										{assign var='tab' value=$product_features[$product_id]}
										<td class="{$classname} comparison_infos product-{$product.id_product}">{if (isset($tab[$feature_id]))}{$tab[$feature_id]|escape:'html':'UTF-8'}{/if}</td>
									{else}
										<td class="{$classname} comparison_infos product-{$product.id_product}"></td>
									{/if}
								{/foreach}
							</tr>
						{/foreach}
					{else}
						<tr>
							<td></td>
							<td colspan="{$products|@count}" class="text-center">{l s='No features to compare'}</td>
						</tr>
					{/if}
					{$HOOK_EXTRA_PRODUCT_COMPARISON}
				</table>
			</div> <!-- end products_block -->
		{else}
			<p class="alert alert-warning">{l s='There are no products selected for comparison.'}</p>
		{/if}
		<ul class="compare_footer_link">
			<li>
				<a class="button lnk_view btn btn-default" href="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}">
					<span><i class="icon-chevron-left left"></i>Continue Shopping</span>
				</a>
			</li>
		</ul>
		</section>
	</div>
</div>
{/block}
{/block}