<div id="_desktop_cart">
  <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <div class="header-cart-txt">
      {if $cart.products_count > 0}
        <a rel="nofollow" href="{$cart_url}">
      {/if}
        <i class="material-icons shopping-cart">shopping_cart</i>
        <span class="cart-txt-inner">
          <span class="hidden-sm-down">{l s='My Cart' d='Shop.Theme.Checkout'}</span>
          <span class="cart-products-count">{$cart.products_count} {if $cart.products_count > 1}{l s='Items' d='Shop.Theme.Checkout'}{else}{l s='Item' d='Shop.Theme.Checkout'}{/if}</span>
        </span>
      {if $cart.products_count > 0}
        </a>
      {/if}
    </div>
  </div>
</div>
