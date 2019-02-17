{include file='_partials/form-errors.tpl' errors=$errors['']}

{* TODO StarterTheme: HOOKS!!! *}
<div class="login-form-data-field clearfix">
  <div class="login-customer-text col-lg-6 col-sm-6 col-xs-12">
    <div class="content">
        <h2 class="auth-heading">{l s='New Customers' d='Shop.Theme.Actions'}</h2>
        <p>{l s='By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.' d='Shop.Theme.Actions'}</p>
    </div>
  </div>
  <div class="login-customer-form col-lg-6 col-sm-6 col-xs-12">
    <form id="login-form" action="{$action}" method="post">
      <h2 class="auth-heading">{l s='Sign in' d='Shop.Theme.Actions'}</h2>
      <p>{l s='Welcome back! Sign to your account' d='Shop.Theme.Actions'}</p>
      <section>
        {block name='form_fields'}
          {foreach from=$formFields item="field"}
            {block name='form_field'}
              {form_field field=$field}
            {/block}
          {/foreach}
        {/block}
        <div class="forgot-password">
          <a href="{$urls.pages.password}" rel="nofollow">
            {l s='Forgot your password?' d='Shop.Theme.CustomerAccount'}
          </a>
        </div>
      </section>
      <footer class="form-footer text-xs-center clearfix">
        <input type="hidden" name="submitLogin" value="1">
        {block name='form_buttons'}
          <button class="btn btn-primary" data-link-action="sign-in" type="submit" class="form-control-submit">
            {l s='Sign in' d='Shop.Theme.Actions'}
          </button>
        {/block}
      </footer>
    </form>
  </div>
</div>