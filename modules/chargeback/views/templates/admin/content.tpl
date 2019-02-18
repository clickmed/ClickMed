<?php
/*
 * 2016 Chargeback
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 *  @author Chargeback - it@chargeback.com
 *  @copyright  2016 Chargeback
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
?>
<style type="text/css">
  /*.nobootstrap {
    min-height: 300px;
  }*/
  body {
    background-color: #FFF;
  }
  #chargeback-plugin .logo {
    cell-padding: 50px;
}
  #chargeback-plugin p.redirect {
    clear: both;
    width: 580px;
    text-align: center;
  }
  #chargeback-plugin .icons{

    height: 60px;
}


  #chargeback-plugin a.btn.btn-warning {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    color: #fff;
    background-color: #DD5849;
    border-color: #DD5849
  }
  #chargeback-plugin .links{
    font-size: 15px;
    color: blue;
    font-weight: bold;
    color: #2BAADF;
    font-style: normal;
}

  #chargeback-plugin .profile-card {
    position: relative;
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
    padding: 1rem;
    margin: 2rem 0;
    background-color: #fff;
    min-height: 350px;
    max-width: 500px;
    padding-right: 15px;
    padding-left: 15px;

}

  #chargeback-plugin .profile-card-short {
    position: relative;
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
    padding: 1rem;
    margin: 2rem 0;
    background-color: #fff;
    min-height: 250px;
    max-width: 500px;
    padding-right: 15px;
    padding-left: 15px;

}

  #chargeback-plugin a {
    color: #2BAADF;
    text-decoration: none;
}

  #chargeback-plugin .greyText{
    color: #8a8a8a;

}

  #chargeback-plugin .greyTextBold{
    color: #8a8a8a;
    font-weight: bold;
}

  #chargeback-plugin .greenText{
    color: #29B362;
}

  #chargeback-plugin .button {
    display: inline-block;
    text-align: center;
    line-height: 1;
    cursor: pointer;
    -webkit-appearance: none;
    transition: background-color 0.25s ease-out, color 0.25s ease-out;
    vertical-align: middle;
    border: 1px solid transparent;
    border-radius: 5px;
    padding: 0.85em 1em;
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    background-color: #2199e8;
    color: #fff;
}
</style>

<div id='chargeback-plugin'>
    {if $stage == 'nothing'}
      <div class='row'>
        <p>
          <img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/chargeback-horizontal.png" style="width: 200px;margin-left: 24px;" />
          <span style="font-size: 20px; line-height: 40px;vertical-align: middle;">Automated Chargeback Management & Reporting</span>
          <a href="{$cbUrl|escape:'htmlall':'UTF-8'}/auth_tokens/new?location=login" target="_blank" style="margin-left: 40px;" class="btn btn-warning" onclick="cbDelayRedirectToModule();">Connect Chargeback</a>
        </p>
      </div>
      <div class='row'>
        <div style="float: left; margin: 20px; width: 600px; text-align: center;" class="profile-card">
          <div style="padding-bottom: 30px;">
            <img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/chargeback-software-demo-tour_SMALL.gif" style="height: 300px;" />
          </div>
          <div class="greyText" style="text-align: center; padding-bottom: 30px;">
            Connect your account with Chargeback to link data
          </div>
          <hr />
          <div style="text-align: center; padding-top: 10px; padding-right: 10px; float: right;">
            <a href="{$cbUrl|escape:'htmlall':'UTF-8'}/auth_tokens/new?location=login" target="_blank" class="links" onclick="cbDelayRedirectToModule();">CONNECT CHARGEBACK</a>
          </div>
        </div>
        <div style="float: left; margin: 20px; width: 500px;">
          <span style="font-size: 22px">Why Chargeback?</span>
          <p style="font-size: 15px; color: #ccc;">Understand your situation, recover lost revenue, and protect your merchant account now.</p>
          <table style='width: 500px; text-align: center;'>
            <tr>
              <td><img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/0-free.png" style="width: 70px; float: right;" /></td>
              <td><span style="float: left; text-align: left;">Free To Get Started</span></td>
            </tr>
            <tr>
              <td><img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/0-recovered.png" style="width: 70px; float: right;" /></td>
              <td><span style="float: left; text-align: left;">Recovered Revenue Billing Model</span></td>
            </tr>
            <tr>
              <td><img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/0-secure.png" style="width: 70px; float: right;" /></td>
              <td><span style="float: left; text-align: left;">Secure Data Encryption</span></td>
            </tr>
            <tr>
              <td><img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/0-quality.png" style="width: 70px; float: right;" /></td>
              <td><span style="float: left; text-align: left;">Quality Assurance Reviews</span></td>
            </tr>
          </table>
        </div>
      </div>
      <script>
        function cbDelayRedirectToModule(){
          window.setInterval(
            function(){ window.location = "{$base_url|escape:'htmlall':'UTF-8'}&token={$token|escape:'htmlall':'UTF-8'}&configure=chargeback&tab_module=payments_gateways&module_name=chargeback"}, 15000);
        }
      </script>
    {elseif $stage == 'connected'}
      {if $cbStatus == 'success'}
        <div style="float: left; margin: 20px; width: 600px; text-align: center;" class="profile-card">
          <div class="greenText" style="text-align: center; font-size: 25px; padding-top: 30px; padding-bottom: 30px;">
            Success
          </div>
          <div style="padding-bottom: 60px;">
            <img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/connected.png" style="height: 115px;" />
          </div>
          <div class="greyText">
            You will be redirected to Chargeback to connect
            <br />
            your payment service providers shortly.
            <br />
          </div>
        </div>
        <p class='redirect'>
          If you are not automatically redirected, <a href="{$cbUrl|escape:'htmlall':'UTF-8'}/connections?partner=true">click here.</a>
        </p>
        <div class="greyText" style="position: absolute; top: 500px; left: 120px;">
        </div>
        <script>
          window.setTimeout(function(){ window.location = "{$cbUrl|escape:'htmlall':'UTF-8'}/connections?partner=true"; }, 2000)
        </script>
      {else}
        <div style="float: left; margin: 20px; width: 600px; text-align: center;" class="profile-card-short">
          <div style="padding-bottom: 15px; padding-top: 15px;">
            <img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/connected.png" style="height: 115px;" />
          </div>
          <div class="greenText" style="padding-bottom: 50px; padding-top: 15px;">
            Your account is currently connected to Chargeback.
          </div>
          <hr />
          <div style="text-align: center; padding-top: 10px; float: right; padding-right: 20px;">
            <a href="{$base_url|escape:'htmlall':'UTF-8'}&token={$token|escape:'htmlall':'UTF-8'}&configure=chargeback&tab_module=payments_gateways&module_name=chargeback&disconnect=true" class="links" style="padding-right: 50px;">DISCONNECT</a>
            <a href="{$cbUrl|escape:'htmlall':'UTF-8'}" class="links" target="_blank">VIEW DASHBOARD</a>
          </div>
        </div>
      {/if}
    {elseif $stage == 'authed'}
      {if $cbStatus == 'failure'}
      <div style="float: left; margin: 20px; width: 600px; text-align: center;" class="profile-card">
        <div style="padding-bottom: 30px;">
          <img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/chargeback-software-demo-tour_SMALL.gif" style="height: 300px;" />
        </div>
        <div class="greyText" style="text-align: center; padding-bottom: 30px;">
          {$cbError|escape:'htmlall':'UTF-8'}
        </div>
        <hr />
        <div style="text-align: center; padding-top: 10px; padding-right: 10px; float: right;">
          <a href="{$cbUrl|escape:'htmlall':'UTF-8'}/auth_tokens/new" class="links">CONNECT</a>
        </div>
      </div>
      {else}
        <div style="float: left; margin: 20px; width: 600px; text-align: center;" class="profile-card">
          <div class="greyText" style="text-align: center; font-size: 25px; padding-top: 30px; padding-bottom: 30px;">
            Linking your accounts...
          </div>
          <div style="padding-bottom: 60px;">
            <img src="{$cbUrl|escape:'htmlall':'UTF-8'}/assets/prestashop/PrestaShop_Connecting.gif" style="height: 115px;" />
          </div>
          <div class="greyText">
            If you are not redirected in about 10 seconds, <a href="#">click here.</a>
          </div>
          <script>
            window.setTimeout(function(){ window.location = "{$cbUrl|escape:'htmlall':'UTF-8'}/auth_tokens/connect?_cb_auth_token={$cbToken|escape:'htmlall':'UTF-8'}&username={$apiKey|escape:'htmlall':'UTF-8'}&name=PrestaShop&url={$apiPath|escape:'htmlall':'UTF-8'}"; }, 2000);
          </script>
        </div>
      {/if}
    {/if}
</div>
