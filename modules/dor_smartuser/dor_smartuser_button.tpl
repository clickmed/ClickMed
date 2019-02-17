
<!-- Block user information module NAV  -->
{if $logged}
<div class="header_user_info smart-user-act">
	<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>{$cookie->customer_firstname} {$cookie->customer_lastname}</span></a>
	&nbsp;&nbsp;
</div>
{/if}

<div class="header_user_info smart-user-act">
	{if $logged}
		<a class="logout" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html'}" rel="nofollow" title="{l s='Log me out' mod='blockuserinfo'}">{l s='Sign out' mod='blockuserinfo'}</a>&nbsp;&nbsp;
	{else}
		<a href="#" onclick="return false" class="smartLogin">{l s='Sign in' mod='lab_smartuser'}</a>&nbsp;-&nbsp;Or&nbsp-&nbsp;
		<a href="#" onclick="return false" class="smartRegister">{l s='Sign up' mod='lab_smartuser'}</a>
	{/if}
	&nbsp;&nbsp;
</div>