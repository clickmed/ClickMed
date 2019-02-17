{if isset($lists) && $lists}
<div class="lip-products">
	{foreach from=$lists item=products name=lists}
		<div class="lip-group-product">
			{foreach from=$products item=product name=products}
			<div class="lip_ajax_block_product">
				<div class="product-container" itemscope itemtype="https://schema.org/Product">
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
					</div>
					<div class="right-block">
						<h5 itemprop="name">
							{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
							<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" data-itemprop="url" >
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
					</div>
					
				</div><!-- .product-container> -->
			</div>
		{/foreach}
	</div>
	{/foreach}
</div>
{/if}