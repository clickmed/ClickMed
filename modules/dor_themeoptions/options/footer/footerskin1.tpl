<!-- Footer -->
<div class="footer-container footerSkin1">
	{capture name='doradoFooterTop'}{hook h='doradoFooterTop'}{/capture}
	{if $smarty.capture.doradoFooterTop}
		{$smarty.capture.doradoFooterTop}
	{/if}
	<footer id="footer"  class="container">
		<div class="row">
			{capture name='blockDoradoFooter'}{hook h='blockDoradoFooter'}{/capture}
			{if $smarty.capture.blockDoradoFooter}
				{$smarty.capture.blockDoradoFooter}
			{/if}
			{capture name='doradoFooter1'}{hook h='doradoFooter1'}{/capture}
			{if $smarty.capture.doradoFooter1}
				{$smarty.capture.doradoFooter1}
			{/if}
			{$HOOK_FOOTER}
		</div>
	</footer>
	{capture name='doradoFooterAdv'}{hook h='doradoFooterAdv'}{/capture}
	{if $smarty.capture.doradoFooterAdv}
		{$smarty.capture.doradoFooterAdv}
	{/if}
</div><!-- #footer -->