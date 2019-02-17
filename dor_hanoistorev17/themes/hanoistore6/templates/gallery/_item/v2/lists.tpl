<div class="row">
	<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
		{foreach from=$galleries1 item=gallery key=i}
			{if $i <= 2}
				{if $i == 0 || $i == 1}
					{assign var='colIm' value="col-lg-6 col-md-6 col-sm-6 col-xs-12"}
	    		{elseif $i == 2}
	    			{assign var='colIm' value="col-lg-12 col-md-12 col-sm-12 col-xs-12"}
	    		{/if}
	    		{include file='gallery/_item/v2/item.tpl'}
	    	{/if}
		{/foreach}
	</div>
	<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
		{foreach from=$galleries1 item=gallery key=i}
			{if $i == 3}
			{assign var='colIm' value="col-lg-12 col-md-12 col-sm-12 col-xs-12"}
    			{include file='gallery/_item/v2/item.tpl'}
    		{/if}
		{/foreach}
	</div>
	<div class="grid-group-col col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
		{foreach from=$galleries2 item=gallery key=i}
			{if $i == 0 || $i == 2}
				{assign var='colIm' value="col-lg-3 col-md-3 col-sm-3 col-xs-12"}
    		{elseif $i == 1}
    			{assign var='colIm' value="col-lg-6 col-md-6 col-sm-6 col-xs-12"}
    		{/if}
    			{include file='gallery/_item/v2/item.tpl'}
		{/foreach}
	</div>
	<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
		{foreach from=$galleries3 item=gallery key=i}
			{if $i == 0}
			{assign var='colIm' value="col-lg-12 col-md-12 col-sm-12 col-xs-12"}
    			{include file='gallery/_item/v2/item.tpl'}
    		{/if}
		{/foreach}
	</div>
	<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
		{foreach from=$galleries3 item=gallery key=i}
			{if $i > 0 && $i <= 3}
				{if $i == 2 || $i == 3}
					{assign var='colIm' value="col-lg-6 col-md-6 col-sm-6 col-xs-12"}
	    		{elseif $i == 1}
	    			{assign var='colIm' value="col-lg-12 col-md-12 col-sm-12 col-xs-12"}
	    		{/if}
	    			{include file='gallery/_item/v2/item.tpl'}
	    	{/if}
		{/foreach}
	</div>
</div>