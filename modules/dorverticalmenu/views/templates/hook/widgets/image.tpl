{*
* Manager and display verticalmenu use bootstrap framework
*
* @package   dorverticalmenu
* @version   1.0.0
* @author    http://www.doradothemes@gmail.com
* @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
*               <info@doradothemes@gmail.com>.All rights reserved.
* @license   GNU General Public License version 2
*}
{if isset($images)}
<div class="widget-images block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</div>
	{/if}
	<div class="widget-inner block_content clearfix">
			<div class="images-list clearfix">	
		 	{foreach from=$images item=image name=images}
		 	<div class="image-item grid-{$columns|escape:'html':'UTF-8'}"><div><img src="{$image|escape:'html':'UTF-8'}"/></div></div>
		 {/foreach}</div>
	</div>
</div>
{/if} 