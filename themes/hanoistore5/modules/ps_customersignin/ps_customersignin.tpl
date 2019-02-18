<div id="_desktop_user_info">
  <div class="user-info">
    <div class="user-info-inner">
      <i class="material-icons">&#xE7FF;</i>
      <div class="useInfoCurrText">
        <span class="useLine1">{l s='Hello' d='Shop.Theme.Actions'}.
          {if $logged}
          <span>{$customerName}</span>
          {else}
          <span>{l s='Sign in' d='Shop.Theme.Actions'}</span>
          {/if}
        </span>
        <span class="useLine2">{l s='Your Account' d='Shop.Theme.Actions'} <i class="fa fa-angle-down"></i></span>
      </div>
    </div>
    <ul class="toogle_content" style="display: none;">
      <li class="best-userinfo-head">
        <div class="main-userinfo-head">
          <h3>{l s='Welcome to' d='Shop.Theme.Actions'} HanoiStore</h3>
            <a href="{$my_account_url}" class="btn btn-default" title="{l s='Log in to your customer account' d='Shop.Theme.CustomerAccount'}"><span>{if !$logged}{l s='Sign in' d='Shop.Theme.CustomerAccount'}{else}{$customerName}{/if}</span></a>
          {if !$logged}
          <div class="more-userinfo-link">
            <span>{l s='New Customer?' d='Shop.Theme.CustomerAccount'}</span>
            <a href="{$urls.pages.register}" title="Sign up now">{l s='Sign up now' d='Shop.Theme.CustomerAccount'}</a>
          </div>
          {/if}
        </div>
      </li>
      <li><a class="link-myaccount" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.CustomerAccount'}">{l s='My account' d='Shop.Theme.CustomerAccount'}</a></li>
      <li><a class="link-wishlist wishlist_block" href="{$link->getModuleLink('dorblockwishlist', 'dorwishlist', array(), true)|escape:'html':'UTF-8'}" title="{l s='My wishlist' d='Shop.Theme.CustomerAccount'}">{l s='My wishlist' d='Shop.Theme.CustomerAccount'}</a></li>
      <li><a class="link-mycart" href="{$urls.pages.cart}" title="{l s='My cart' d='Shop.Theme.CustomerAccount'}">
      {l s='My cart' d='Shop.Theme.CustomerAccount'}</a></li>
      {if $logged}
      <a href="{$logout_url}" class="btn btn-default signout-button" title="{l s='Log out to your customer account' d='Shop.Theme.CustomerAccount'}"><span>{l s='Sign out' d='Shop.Theme.CustomerAccount'}</span></a>
      {else}
      <li><a href="#" onclick="return false" class="smartLogin">{l s='Sign in popup' d='Shop.Theme.CustomerAccount'}</a></li>
      <li><a href="#" onclick="return false" class="smartRegister">{l s='Sign up popup' d='Shop.Theme.CustomerAccount'}</a></li>
      {/if}
    </ul>
  </div>
</div>