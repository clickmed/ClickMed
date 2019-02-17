<!-- Footer -->
<div class="footer-container footerSkin2">
	<footer id="footer"  class="container">
		<div class="footer-middle-sku clearfix">
			<div class="row">
				{capture name='doradoFooter2'}{hook h='doradoFooter2'}{/capture}
				{if $smarty.capture.doradoFooter2}
					{$smarty.capture.doradoFooter2}
				{/if}
			</div>
		</div>
		<div class="footer-bottom-sku clearfix">
			{$HOOK_FOOTER}
			{capture name='doradoFooter10'}{hook h='doradoFooter10'}{/capture}
			{if $smarty.capture.doradoFooter10}
				{$smarty.capture.doradoFooter10}
			{/if}
		</div>
	</footer>
</div><!-- #footer -->