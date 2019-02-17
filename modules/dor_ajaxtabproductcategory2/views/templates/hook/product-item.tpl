{if isset($bigproducts) && $bigproducts}
{foreach from=$bigproducts key=i item=products name=products}

<ul class="product_list grid product-group col-xs-12 col-sm-3">
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
</ul>

{if $i == 0 && $mainProduct['product']}
    <div class="productMain product-group col-xs-12 col-sm-6">
      <div class="">
        {block name='page_content_container'}
          <section class="page-content" id="content">
            {block name='page_content'}
            <div class="images-container">
              {block name='product_cover'}
                <div class="product-cover">
                  <img class="js-qv-product-cover" src="{$mainProduct['product'].imageThumbMain}" alt="" title="" style="width:100%;" itemprop="image">
                  <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
                    <i class="material-icons zoom-in">&#xE8FF;</i>
                  </div>
                </div>
              {/block}

              {block name='product_cover_tumbnails'}
                <div class="js-qv-mask mask">
                  <ul class="product-images js-qv-product-images">
                    {foreach from=$mainProduct['images'] item=image name=thumbnails}
                    {assign var=imageIds value="`$mainProduct['product'].id`-`$image.id_image`"}
                    {if !empty($image.legend)}
                      {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                    {else}
                      {assign var=imageTitle value=$mainProduct['product'].name|escape:'html':'UTF-8'}
                    {/if}
                      <li class="thumb-container">
                        <img class="thumb js-thumb" id="thumb_{$image.id_image}" src="{$image.thumbSmall}" alt="{$imageTitle}" title="{$imageTitle}"{if isset($cartSize)} height="{$cartSize.height}" width="{$cartSize.width}"{/if} data-itemprop="image" onclick="dorChangeThumb(this,'{$image.thumbLanger}')" />
                      </li>
                    {/foreach}
                  </ul>
                </div>
              {/block}
              <h3 class="h3"><a href="{$mainProduct['product'].link|escape:'html':'UTF-8'}">{$mainProduct['product'].name|escape:'html':'UTF-8'}</a></h3>
              {capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$mainProduct['product']}{/capture}
              {if $smarty.capture.displayProductListReviews}
                <div class="hook-reviews">
                {hook h='displayProductListReviews' product=$mainProduct['product']}
                </div>
              {/if}


              {if $mainProduct['product'].show_price}
              <div class="product-prices">
                

                {block name='product_price'}
                  <div
                    class="product-price h5 {if $mainProduct['product'].has_discount}has-discount{/if}"
                    itemprop="offers"
                    itemscope
                    itemtype="https://schema.org/Offer"
                  >
                    <link itemprop="availability" href="https://schema.org/InStock"/>
                    <meta itemprop="priceCurrency" content="{$currency.iso_code}">

                    <div class="current-price">
                      <span itemprop="price" content="{$mainProduct['product'].price_amount}">{$mainProduct['product'].price}</span>

                      
                    </div>
                  </div>
                {/block}
                {block name='product_discount'}
                  {if $mainProduct['product'].has_discount}
                    <div class="product-discount">
                      {hook h='displayProductPriceBlock' product=$mainProduct['product'] type="old_price"}
                      <span class="regular-price">{$mainProduct['product'].regular_price}</span>
                    </div>
                  {/if}
                {/block}
                {if $mainProduct['product'].has_discount}
                        {if $mainProduct['product'].discount_type === 'percentage'}
                          <span class="discount discount-percentage">{l s='Save %percentage%' d='Shop.Theme.Catalog' sprintf=['%percentage%' => $mainProduct['product'].discount_percentage_absolute]}</span>
                        {else}
                          <span class="discount discount-amount">{l s='Save %amount%' d='Shop.Theme.Catalog' sprintf=['%amount%' => $mainProduct['product'].discount_amount]}</span>
                        {/if}
                      {/if}
                {hook h='displayProductPriceBlock' product=$mainProduct['product'] type="weight" hook_origin='product_sheet'}

                <div class="tax-shipping-delivery-label">
                  {hook h='displayProductPriceBlock' product=$mainProduct['product'] type="price"}
                  {hook h='displayProductPriceBlock' product=$mainProduct['product'] type="after_price"}
                </div>
              </div>
            {/if}

            <div class="product-actions">
              {block name='product_buy'}
                <form action="{$cartUrl}" method="post" id="add-to-cart-or-refresh">
                  <input type="hidden" name="token" value="{$static_token}">
                  <input type="hidden" name="id_product" value="{$mainProduct['product'].id}" id="product_page_product_id">
                  <input type="hidden" name="id_customization" value="0" id="product_customization_id">
                  <input type="hidden" name="idCombination" value="{$mainProduct['product'].id_product_attribute}" id="idCombination">
                  
                  {block name='product_add_to_cart'}
                    <div class="product-add-to-cart">
                      <span class="control-label">{l s='Quantity' d='Shop.Theme.Catalog'}</span>
                      {block name='product_quantity'}
                        <div class="product-quantity">
                          <div class="qty">
                            <input
                              type="text"
                              name="qty"
                              id="quantity_wanted"
                              value="1"
                              class="input-group quantity_wanted"
                              min="{$mainProduct['product'].minimal_quantity}"
                            />
                          </div>
                          <div class="add">
                            <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit" {if !$mainProduct['product'].add_to_cart_url}disabled{/if}>
                              <i class="material-icons shopping-cart">&#xE547;</i>
                              {l s='Add to cart' d='Shop.Theme.Actions'}
                            </button>
                            {block name='product_availability'}
                              <span id="product-availability">
                                {if $mainProduct['product'].show_availability && $mainProduct['product'].availability_message}
                                  {if $mainProduct['product'].availability == 'available'}
                                    <i class="material-icons product-available">&#xE5CA;</i>
                                  {elseif $mainProduct['product'].availability == 'last_remaining_items'}
                                    <i class="material-icons product-last-items">&#xE002;</i>
                                  {else}
                                    <i class="material-icons product-unavailable">&#xE14B;</i>
                                  {/if}
                                  {$mainProduct['product'].availability_message}
                                {/if}
                              </span>
                            {/block}
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      {/block}

                      {block name='product_minimal_quantity'}
                        <p class="product-minimal-quantity">
                          {if $mainProduct['product'].minimal_quantity > 1}
                            {l
                            s='The minimum purchase order quantity for the product is %quantity%.'
                            d='Shop.Theme.Checkout'
                            sprintf=['%quantity%' => $mainProduct['product'].minimal_quantity]
                            }
                          {/if}
                        </p>
                      {/block}
                  </div>
                  {/block}

                </form>
              {/block}

            </div>

            </div>
             
              <div class="scroll-box-arrows">
                <i class="material-icons left">&#xE314;</i>
                <i class="material-icons right">&#xE315;</i>
              </div>

            {/block}
          </section>
        {/block}
        </div>
    </div>
{/if}
{/foreach}
{/if}