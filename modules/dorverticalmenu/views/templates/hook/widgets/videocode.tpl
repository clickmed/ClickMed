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
{if isset($video_link)}
<div class="widget-video">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading">
		{$widget_heading|escape:'html':'UTF-8'}
	</div>
	{/if}
	<div class="widget-inner">
		<iframe src="{$video_link|escape:'htmlall':'UTF-8'}" style="width:{$width|escape:'html':'UTF-8'};height:{$height|escape:'html':'UTF-8'};" allowfullscreen></iframe>
	</div>
</div>
{/if}