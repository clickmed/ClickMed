{*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="dor_custom_newsletter_block" class="block col-lg-10 col-sm-10 col-sx-12">
	<label class="col-lg-2 col-sm-2 col-md-2 col-sx-12 text-right">{l s='Newsletter' mod='blocknewsletter'}:</label>
	<div class="block_content col-lg-7 col-sm-8 col-md-7 col-sx-12">
		<form action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
			<div class="form-group{if isset($msg) && $msg } {if $nw_error}form-error{else}form-ok{/if}{/if}" >
				<input class="inputNew form-control newsletter-input" id="newsletter-input" type="text" name="email" size="18" value="{if isset($msg) && $msg}{$msg}{elseif isset($value) && $value}{$value}{else}{l s='Please enter your email' mod='blocknewsletter'}{/if}" />
                <button type="submit" name="submitNewsletter" class="btn btn-default button button-small">
                    <span>{l s='Subscribe' mod='blocknewsletter'}</span>
                </button>
				<input type="hidden" name="action" value="0" />
			</div>
		</form>
	</div>
	<span class="col-lg-3 col-sm-3 col-sx-12 text-left hidden-sx hidden-sm">{l s='Register now to get updates on discount & coupons' mod='blocknewsletter'}</span>
    {hook h="displayBlockNewsletterBottom" from='blocknewsletter'}
</div>