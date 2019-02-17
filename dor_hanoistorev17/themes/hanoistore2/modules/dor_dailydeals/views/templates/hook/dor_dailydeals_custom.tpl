{if isset($products) && $products != null}
{assign var=product value=$products[0]}
<div class="dorDailyDeal">
	<div class="dataDailyDeal">
		<div class="media-dordeal col-lg-6 col-sm-6 col-xs-12">
			<div class="media-deal-inner">
				<a class="product-image product_img_link"   href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
					<img class="replace-2x img-responsive" src="{$product.thumb_image}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)}{/if} />
				</a>
			</div>
			{if ( ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                <div class="content_price price">
                    {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                    	<div class="deail-price-main button--sacnite button--round-l">
	                    	<span class="deail-price-txt">-{l s="Only" mod="dor_dailydeals"}-</span>
	                        <span class="product-price {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}new-price{/if}">
	                        	{if isset($product.price_custom) && $product.price_custom != ""}
	                        		{$product.price_custom}
	                        	{else}
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
		                        {/if}
	                        </span>
	                        {hook h="displayProductPriceBlock" product=$product type="price"}
	                        {hook h="displayProductPriceBlock" product=$product type="unit_price"}
	                        {hook h="displayProductPriceBlock" product=$product type='after_price'}
                        </div>
                    {/if}
                </div>
            {/if}
		</div>
		<div class="info-dordeal col-lg-6 col-sm-6 col-xs-12">
			<div class="info-deal-inner">
				<h2>{l s="Deal Of The Day"}</h2>
				<h3><a class="product-image product_img_link"   href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">{$product.name}</a></h3>
				<div class="deal-desc">
					{$product.description_short nofilter}
				</div>
				<div class="countdown-time-data">
                <!-- <div id="daily-countdown-time">
                    <div class="item-time">
                        <span class="dw-time">142</span> 
                        <span class="dw-txt">-days-</span>
                    </div>
                    <div class="item-time">
                        <span class="dw-time">15</span> 
                        <span class="dw-txt">-hours-</span>
                    </div>
                    <div class="item-time">
                        <span class="dw-time">42</span> 
                        <span class="dw-txt">-mins-</span>
                    </div>
                    <div class="item-time">
                        <span class="dw-time">36</span> 
                        <span class="dw-txt">-secs-</span>
                    </div>
                </div> -->
                <div id="daily-countdown-time"></div>
                {if $endDate|escape:'html':'UTF-8' != ""}
                <input type="hidden" value="{$endDate|escape:'html':'UTF-8'}" id="endDateCountdown">
                {/if}
            	</div>
            	<div class="action-btn product-action-deals">
	                {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2}
	                    {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
	                        {capture}add=1&amp;id_product={$product.id_product|intval}{if isset($product.id_product_attribute) && $product.id_product_attribute}&amp;ipa={$product.id_product_attribute|intval}{/if}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
	                        <div class="add-to-cart cart pull-left"><a class="btn btn-shopping-cart btn-outline ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
	                            <span><span><i class="fa fa-shopping-cart"></i></span></span>
	                            {l s='Add to cart'}
	                        </a></div>
	                    {else}
	                        <div class="add-to-cart cart pull-left"><span class="btn btn-shopping-cart btn-outline ajax_add_to_cart_button disabled">
	                            <span><span><i class="fa fa-shopping-cart"></i></span></span>
	                            {l s='Add to cart'}
	                        </span></div>
	                    {/if}
	                {/if}
	                
	                <div class="hidden">
	                    {if ( ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
	                        {if isset($product.online_only) && $product.online_only}
	                            <span class="online_only">{l s='Online only'}</span>
	                        {/if}
	                    {/if}
	                    {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price}
	                    {elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price}
	                        <span class="discount">{l s='Reduced price!'}</span>
	                    {/if}
	                </div>
	                <div class="add-to-line pull-left js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
	                    <div class="dor-cdw-detail">
	                    	<form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh-{$product.id_product}">
		                    	<div class="add-to-cart cart pull-left">
			                    	<input type="hidden" name="token" value="{$static_token}">
					                <input name="id_product" value="{$product.id_product}" type="hidden">
					                <input type="hidden" name="id_customization" value="0">
			                    	<a class="btn btn-shopping-cart btn-outline ajax_add_to_cart_button add-to-cart" data-button-action="add-to-cart" {if !$product.add_to_cart_url}disabled{/if}>
						            <i class="material-icons shopping-cart hidden">&#xE547;</i>
						            {l s='Add to cart' d='Shop.Theme.Actions'}
						          </a>
					          </div>
				          	</form>
	                    	<!-- <a href="{$product.link|escape:'html':'UTF-8'}" class="countdown-view-detail" data-toggle="tooltip" title="{l s='View detail'}"><i class="fa fa-search"></i></a> -->
	                    	<a href="#" class="quick-view countdown-view-detail" data-link-action="quickview" data-toggle="tooltip" title="{l s='View detail'}">
						       <i class="material-icons search">&#xE8B6;</i>
						    </a>
	                    </div>
	                    {if isset($comparator_max_item) && $comparator_max_item}
	                        <div class="compare"><a class="btn-highlighted add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" data-toggle="tooltip" title="{l s='Compare'}">
	                            <i class="fa fa-random"></i></a>
	                        </div>
	                    {/if}
	                    {hook h='displayProductListFunctionalButtons' product=$product}
	                </div>

	                



	            </div>
			</div>
		</div>
	</div>
</div>
{/if}