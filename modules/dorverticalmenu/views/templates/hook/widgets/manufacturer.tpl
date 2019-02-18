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
<div class="widget-manufacture block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</div>
	{/if}
	<div class="widget-inner block_content">
		<div class="manu-logo">
			{foreach from=$manufacturers item=manufacturer name=manufacturers}
			<a  href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html':'UTF-8'}"  title="{l s='view products' mod='dorverticalmenu'}">
			<img src="{$img_manu_dir|escape:'html':'UTF-8'}{$manufacturer.image|escape:'htmlall':'UTF-8'}-medium_default.jpg" alt=""/> </a>
			{/foreach}
		</div>
	</div>
</div>
 