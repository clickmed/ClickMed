{if !$logged}
<div id="loginFormSmart">
	<span class="button b-close"><span>X</span></span>
	<form id="login-form" action="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}login" method="post">
		<h2 class="auth-heading">Sign in</h2>
		<p>Welcome back! Sign to your account</p>
		<section>
		<input name="back" value="my-account" type="hidden">
		<div class="form-group row ">
		<label class="col-md-3 form-control-label required">
		Email
		</label>
		<div class="col-md-6">
		<input class="form-control" name="email" value="" required="" type="email">
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label required">
		Password
		</label>
		<div class="col-md-6">
		<div class="input-group js-parent-focus">
		<input class="form-control js-child-focus js-visible-password" name="password" value="" required="" type="password">
		<span class="input-group-btn">
		<button class="btn" type="button" data-action="show-password" data-text-show="Show" data-text-hide="Hide">
		Show
		</button>
		</span>
		</div>
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="forgot-password">
		<a href="#" rel="nofollow" onclick="return false;" class="lost_password_smart">
		Forgot your password?
		</a>
		</div>
		</section>
		<footer class="form-footer text-xs-center clearfix">
			<input name="submitLogin" value="1" type="hidden">
			<button class="btn btn-primary" data-link-action="sign-in" type="submit">
			Sign in
			</button>
		</footer>
	</form>
</div>

<div id="registerFormSmart">
	<span class="button b-close"><span>X</span></span>
		<h2 class="auth-heading">Create an Account</h2>
		<form action="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}login?create_account=1" id="customer-form" class="js-customer-form" method="post">
		<section>
		<input name="id_customer" value="" type="hidden">
		<div class="form-group row ">
		<label class="col-md-3 form-control-label">
		Social title
		</label>
		<div class="col-md-6 form-control-valign">
		<label class="radio-inline">
		<span class="custom-radio">
		<input name="id_gender" value="1" type="radio">
		<span></span>
		</span>
		Mr.
		</label>
		<label class="radio-inline">
		<span class="custom-radio">
		<input name="id_gender" value="2" type="radio">
		<span></span>
		</span>
		Mrs.
		</label>
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label required">
		First name
		</label>
		<div class="col-md-8">
		<input class="form-control" name="firstname" value="" required="" type="text">
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label required">
		Last name
		</label>
		<div class="col-md-8">
		<input class="form-control" name="lastname" value="" required="" type="text">
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label required">
		Email
		</label>
		<div class="col-md-8">
		<input class="form-control" name="email" value="" required="" type="email">
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label required">
		Password
		</label>
		<div class="col-md-8">
		<div class="input-group js-parent-focus">
		<input class="form-control js-child-focus js-visible-password" name="password" value="" required="" type="password">
		<span class="input-group-btn">
		<button class="btn" type="button" data-action="show-password" data-text-show="Show" data-text-hide="Hide">
		Show
		</button>
		</span>
		</div>
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label">
		Birthdate
		</label>
		<div class="col-md-8">
		<input class="form-control" name="birthday" value="" placeholder="MM/DD/YYYY" type="text">
		<span class="form-control-comment">
		(E.g.: 05/31/1970)
		</span>
		</div>
		<div class="col-md-3 form-control-comment">
		Optional
		</div>
		</div>
		<div class="form-group row ">
		<label class="col-md-3 form-control-label">
		</label>
		<div class="col-md-6 hidden">
		<span class="custom-checkbox">
		<input name="optin" value="1" type="checkbox">
		<span><i class="material-icons checkbox-checked"></i></span>
		<label>Receive offers from our partners</label>
		</span>
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		<div class="form-group row hidden">
		<label class="col-md-3 form-control-label">
		</label>
		<div class="col-md-6">
		<span class="custom-checkbox">
		<input name="newsletter" value="1" type="checkbox">
		<span><i class="material-icons checkbox-checked"></i></span>
		<label>Sign up for our newsletter<br><em>You may unsubscribe at any moment. For that purpose, please find our contact info in the legal notice.</em></label>
		</span>
		</div>
		<div class="col-md-3 form-control-comment">
		</div>
		</div>
		</section>
		<footer class="form-footer clearfix">
		<input name="submitCreate" value="1" type="hidden">
		<label class="col-md-3 form-control-label"></label>
		<button class="btn btn-primary form-control-submit pull-xs-left" data-link-action="save-customer" type="submit">
		Save
		</button>
		</footer>
		</form>
</div>
<div id="smartForgotPass">
<span class="button b-close"><span>X</span></span>
<div class="center_column" id="center_column_smart">
<div class="box">
<h1 class="page-subheading">Forgot your password?</h1>
<form action="{if isset($urls.force_ssl) && $urls.force_ssl}{$urls.base_url_ssl}{else}{$urls.base_url}{/if}password-recovery" method="post">
    <header>
      <p>Please enter the email address you used to register. You will receive a temporary link to reset your password.</p>
    </header>
    <section class="form-fields">
      <div class="form-group row">
        <label class="col-md-3 form-control-label required">Email address</label>
        <div class="col-md-8">
          <input name="email" id="email" value="" class="form-control" required="" type="email">
        </div>
      </div>
    </section>
    <footer class="form-footer text-xs-center">
      <button class="form-control-submit btn btn-primary" name="submit" type="submit">
        Send reset link
      </button>
    </footer>
  </form>
</div>
</div>
</div>


{/if}