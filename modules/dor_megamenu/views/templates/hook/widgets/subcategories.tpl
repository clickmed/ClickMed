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
{if isset($subcategories)}
<div class="widget-subcategories block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		<a href="{$link->getCategoryLink($ocategory->id_category, $ocategory->link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$widget_heading|escape:'html':'UTF-8'}" class="img">
			{$widget_heading|escape:'html':'UTF-8'}
		</a>
	</div>
	{/if}
	<div class="widget-inner block_content">
		<div class="widget-heading hidden">
			<a href="{$link->getCategoryLink($ocategory->id_category, $ocategory->link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$title|escape:'html':'UTF-8'}" class="img">{$title|escape:'htmlall':'UTF-8'}</a>
		</div>
		{if isset($show_image) && $show_image && $ocategory->id_image}
			<img src="{$link->getCatImageLink($ocategory->link_rewrite, $ocategory->id_image, 'medium_default')|escape:'html':'UTF-8'}" alt="{$title|escape:'htmlall':'UTF-8'}">
		{/if}
		<ul>
			{foreach from=$subcategories item=subcategory}
				<li class="clearfix">
					<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}" data-rel="sub-{$subcategory.id_category}" class="img">
						{$subcategory.name|escape:'htmlall':'UTF-8'} 
					</a>
				</li>
			{/foreach}
		</ul>
	</div>
</div>
{/if} 