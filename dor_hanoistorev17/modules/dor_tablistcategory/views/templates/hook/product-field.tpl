{foreach from=$products item=product name=products}
  <li class="ajax_block_product js-product-miniature col-xs-12 col-sm-4 col-dor-2" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
      <div class="product-container" itemscope itemtype="https://schema.org/Product">
        {capture name='dorwishlist'}{hook h='dorwishlist'}{/capture}
        {if $smarty.capture.dorwishlist}
        {$smarty.capture.dorwishlist nofilter}
        {/if}
        <div class="left-block">
          <div class="product-image-container">
            <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
              <img class="replace-2x img-responsive" src="{$product.imageThumb}" alt="" />
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
                            {hook h='DorCompare' product=$product}
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

                  <span class="price">{$product.price}</span>

                  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

                  {hook h='displayProductPriceBlock' product=$product type='weight'}
                </div>
              {/if}
            {/block}
          </div>
          {/if}
          
          <div class="button-container hidden">
            
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
      </div><!-- .product-container> -->
    </li>
  {/foreach}