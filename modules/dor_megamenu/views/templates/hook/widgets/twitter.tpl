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
{if isset($username)}
<div class="widget-twitter block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</div>
	{/if}
	<div class="widget-inner block_content">
		<a class="twitter-timeline" data-dnt="true" data-theme="{$theme|escape:'htmlall':'UTF-8'}" data-link-color="#FFFFFF" width="{$width|escape:'html':'UTF-8'}" height="{$height|escape:'html':'UTF-8'}" data-chrome="{$chrome|escape:'htmlall':'UTF-8'}" data-border-color="{$border_color|escape:'htmlall':'UTF-8'}" lang="EN" data-tweet-limit="{$count|escape:'htmlall':'UTF-8'}" data-show-replies="{$show_replies|escape:'htmlall':'UTF-8'}" href="https://twitter.com/{$username|escape:'htmlall':'UTF-8'}"  data-widget-id="{$twidget_id|escape:'htmlall':'UTF-8'}">Tweets by @{$username|escape:'htmlall':'UTF-8'}</a>
		{$js}{* HTML, javascript can not escape *}
	</div>
</div>
{/if} 