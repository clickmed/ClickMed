<!-- Footer -->
<div class="footer-container footerSkin3">
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
</div><!-- #footer -->