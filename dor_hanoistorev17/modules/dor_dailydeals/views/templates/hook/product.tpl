<div class="product-block clearfix" itemscope="" itemtype="https://schema.org/Product">
    <div class="product-container">
        <div class="col-md-6 col-sm-12 col-xs-12 deals-images product-image-container">
            <a class="product-image product_img_link"   href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" data-itemprop="url">
                <img class="replace-2x img-responsive flip-image-1" src="{$product.thumb_image}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)}{/if} itemprop="image" />
                {if isset($product.thumb_image_rotator) && $product.thumb_image_rotator != ""}
                <img class="replace-2x img-responsive flip-image-2" src="{$product.thumb_image_rotator}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)}{/if} itemprop="image" />
                {/if}
            </a>
                        
            {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price}
                <a class="product-label sale-box hidden" href="{$product.link|escape:'html':'UTF-8'}">
                    <span class="sale-label">{l s='Sale!'}</span>
                </a>
            {elseif isset($product.new) && $product.new == 1}
                <a class="product-label new-box hidden" href="{$product.link|escape:'html':'UTF-8'}">
                    <span class="new-label">{l s='New'}</span>
                </a>
            {/if}
        
            {if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
            {hook h="displayProductPriceBlock" product=$product type="weight"}

            {if isset($product.special_finish_time)}
                <div class="count-down-box">
                    <div class="dor-countdown-{$product.id_product|escape:'html':'UTF-8'} daycounter-container"></div>
                    <div class="save-count">
                        <ul>
                            <li class="percent">
                                {if $product.specific_prices.reduction_type == 'percentage'}
                                <p class="text">OFF</p>
                                <p class="number">{$product.specific_prices.reduction * 100}%</p>
                                {/if}
                            </li>
                        </ul>
                    </div>
                </div>
                <script type="text/javascript">
                    {literal}
                    jQuery(document).ready(function($) {{/literal}
                        $(".dor-countdown-{$product.id_product|escape:'html':'UTF-8'}").dorCountDown({literal}{{/literal}
                            finishDate:"{$product.special_finish_time|escape:'html':'UTF-8'}",
                            DisplayFormat:"<ul><li><div class=\"countdown_num\">%%D%% </div><div>{l s='Day' mod='dorproductscountdown'}</div></li><li><div class=\"countdown_num\">%%H%% </div><div>{l s='Hrs' mod='dorproductscountdown'}</div></li><li><div class=\"countdown_num\">%%M%%</div> <div>{l s='Mins' mod='dorproductscountdown'}</div></li><li><div class=\"countdown_num\">%%S%%</div><div> {l s='Secs' mod='dorproductscountdown'}</div></li></ul>",
                            FinishMessage: ""
                        {literal}
                        });
                    });
                    {/literal}
                </script>
            {/if}

        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 list-desc">        
            
            <div class="title">
                <h2>Daily Deals</h2>
            </div>
            <div class="product-desc">
                <h3 class="name" itemprop="name">
                    {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
                    <a href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" data-itemprop="url" >
                        {$product.name|truncate:35:'...'|escape:'html':'UTF-8'}
                    </a>
                </h3>
                {if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                    <div itemprop="offers" itemscope="" itemtype="https://schema.org/Offer" class="content_price price">

                        {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                            <span itemprop="price" class="product-price {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}new-price{/if}">
                                {hook h="displayProductPriceBlock" product=$product type="before_price"}
                                {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                            </span>
                            <meta itemprop="priceCurrency" content="{$priceDisplay}" />
                            {if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                                <span class="old-price">
                                    {displayWtPrice p=$product.price_without_reduction}
                                </span>
                                
                            {/if}
                            {hook h="displayProductPriceBlock" product=$product type="price"}
                            {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                            {hook h="displayProductPriceBlock" product=$product type='after_price'}
                        {/if}
                    </div>
                {/if}
                {$product.description_short}
                {if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                    {if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
                    <span class="availability">
                        {if ($product.allow_oosp || $product.quantity > 0)}
                            <span class="{if $product.quantity <= 0 && !$product.allow_oosp}out-of-stock text-danger{else}available-now text-success{/if}">
                                {if $product.quantity <= 0}{if $product.allow_oosp}{if isset($product.available_later) && $product.available_later}{$product.available_later}{else}{l s='In Stock'}{/if}{else}{l s='Out of stock'}{/if}{else}{if isset($product.available_now) && $product.available_now}{$product.available_now}{else}{l s='In Stock'}{/if}{/if}
                            </span>
                        {elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
                            <span class="text-warning">
                            {l s='Product available with different options'}
                            </span>
                        {else}
                            <span class="text-danger">
                                {l s='Out of stock'}
                            </span>
                        {/if}
                    </span>
                    {/if}
                {/if}   

                {if isset($product.color_list)}
                    <div class="color-list-container product-colors hidden">{$product.color_list} </div>
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
                <div class="add-to pull-right">
                    <div class="dor-cdw-detail"><a href="{$product.link|escape:'html':'UTF-8'}" class="countdown-view-detail"><i class="fa fa-search"></i></a></div>
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