<!-- Footer -->
111111111
<div class="footer-container footerSkin1">
	{capture name='doradoFooterTop'}{hook h='doradoFooterTop'}{/capture}
	{if $smarty.capture.doradoFooterTop}
		{$smarty.capture.doradoFooterTop nofilter}
	{/if}
	<footer id="footer"  class="container">
		<div class="row">
			{capture name='blockDoradoFooter'}{hook h='blockDoradoFooter'}{/capture}
			{if $smarty.capture.blockDoradoFooter}
				{$smarty.capture.blockDoradoFooter nofilter}
			{/if}
			{capture name='doradoFooter1'}{hook h='doradoFooter1'}{/capture}
			{if $smarty.capture.doradoFooter1}
				{$smarty.capture.doradoFooter1 nofilter}
			{/if}
		</div>
	</footer>
	{capture name='doradoFooterAdv'}{hook h='doradoFooterAdv'}{/capture}
	{if $smarty.capture.doradoFooterAdv}
		{$smarty.capture.doradoFooterAdv nofilter}
	{/if}
</div><!-- #footer -->