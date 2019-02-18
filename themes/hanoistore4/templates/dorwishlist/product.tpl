{**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<article class="product-miniature js-product-miniature ajax_block_product col-xs-12 col-sm-6 col-md-4" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
  <div class="dor-thumbnail-container">
    <div class="left-block">
      <div class="product-image-container">
        {block name='product_thumbnail'}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <img
              src = "{$product.cover.bySize.home_default.url}"
              alt = "{$product.cover.legend}"
              data-full-size-image-url = "{$product.cover.large.url}"
            >
          </a>
        {/block}
        {block name='product_flags'}
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
        {/block}
      </div>
      {if $product.has_discount}
        {if $product.discount_type === 'percentage'}
          <span class="discount-percentage">{$product.discount_percentage}</span>
        {/if}
      {/if}
      {if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
      {hook h="displayProductPriceBlock" product=$product type="weight"}
      <div class="category-action-buttons">
        <div class="row">
            <div class="col-xs-6"></div>
            <div class="col-xs-6">
                <div class="action-button">
                    <ul>
                        <li class="icon-line-quickview">
                          <a href="#" class="quick-view countdown-view-detail" data-link-action="quickview" data-toggle="tooltip" title="{l s='View detail'}">
                             <i class="material-icons search">&#xE8B6;</i>
                          </a>
                        </li>
                        <li class="icon-line-cart">
                          <form action="{if isset($carturl)}{$carturl}{else}{$urls.pages.cart}{/if}" method="post" id="add-to-cart-or-refresh-{$product.id_product}">
                                <div class="add">
                                  <input type="hidden" name="token" value="{$static_token}">
                                <input name="id_product" value="{$product.id_product}" type="hidden">
                                <input type="hidden" name="id_customization" value="0">
                                <a href="{if isset($carturl)}{$carturl}{else}{$urls.pages.cart}{/if}" class="cart-button button ajax_add_to_cart_button btn btn-default add-to-cart" data-button-action="add-to-cart" data-toggle="tooltip" title="{l s='Add to cart' d='Shop.Theme.Actions'}" {if !$product.add_to_cart_url}disabled{/if}>
                                <i class="material-icons shopping-cart">&#xE547;</i>
                                <span class="hidden">{l s='Add to cart' d='Shop.Theme.Actions'}</span>
                              </a>
                          </div>
                          </form>
                        </li>
                        {hook h='DorCompare' product=$product}
                    </ul>
                </div>
            </div>
        </div>
      </div>
    </div>

    <div class="right-block">
      <div class="dor-product-description">
        {block name='product_name'}
          <h5 class="h5 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h5>
        {/block}

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
            </div>
          {/if}
        {/block}
      </div>
    </div>

  </div>
</article>
