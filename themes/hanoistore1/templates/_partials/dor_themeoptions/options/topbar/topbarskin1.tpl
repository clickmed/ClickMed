<div class="nav topbarDorado" id="topbarDorado1">
	<div class="container">
		<div class="row">
		{capture name='topbarDorado1'}{hook h='topbarDorado1'}{/capture}
		{if $smarty.capture.topbarDorado1}
			{$smarty.capture.topbarDorado1}
		{/if}
		{capture name='topbarDorado2'}{hook h='topbarDorado2'}{/capture}
		{if $smarty.capture.topbarDorado2}
		<div class="pull-right list-unstyled list-inline">{$smarty.capture.topbarDorado2}</div>
		{/if}
		</div>
	</div>
</div>
