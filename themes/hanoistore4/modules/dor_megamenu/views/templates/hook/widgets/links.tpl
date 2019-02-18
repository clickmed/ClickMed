{*
* Manager and display megamenu use bootstrap framework
*
* @package   dormegamenu
* @version   1.0.0
* @author    http://www.doradothemes@gmail.com
* @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
*               <info@doradothemes@gmail.com>.All rights reserved.
* @license   GNU General Public License version 2
*}
{if isset($links)}
<div class="widget-links block {$additionclss}">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		<a href="#" onclick="return false" class="img link-cate-custom">{$widget_heading|escape:'html':'UTF-8'}</a>
	</div>
	{/if}
	<div class="widget-inner block_content">	
		<div id="tabs{$id|escape:'html':'UTF-8'}" class="panel-group">
			<ul class="nav-links" data-id="myTab">
			  {foreach $links as $key => $ac}  
			  <li><a href="{$ac.link|escape:'html':'UTF-8'}" >{$ac.icon_class nofilter}{$ac.text|escape:'htmlall':'UTF-8' nofilter}</a></li>
			  {/foreach}
			</ul>
	</div></div>
</div>
{/if}


