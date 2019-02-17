<div class="nav topbarDorado" id="topbarDorado2">
	<div class="container">
		{capture name='topbarDorado1'}{hook h='topbarDorado1'}{/capture}
		{if $smarty.capture.topbarDorado1}
		<div class="list-unstyled list-inline">{$smarty.capture.topbarDorado1}</div>
		{/if}
		{capture name='topbarDorado2'}{hook h='topbarDorado2'}{/capture}
		{if $smarty.capture.topbarDorado2}
		<div class="list-unstyled list-inline">{$smarty.capture.topbarDorado2}</div>
		{/if}
	</div>
</div>
