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
{if isset($tabs)}
<div class="widget-tab block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</div>
	{/if}
	<div class="widget-inner block_content">	
		<div id="tabs{$id|escape:'html':'UTF-8'}" class="panel-group">
			
			<ul class="nav nav-tabs" id="menu_myTab">
			  {foreach $tabs as $key => $ac}  
			  <li class="{if $key==0}active{/if}"><a href="#tab{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}" >{$ac.header|escape:'htmlall':'UTF-8'}</a></li>
			  {/foreach}
			</ul>

			<div class="tab-content">
			 	{foreach $tabs as $key => $ac}
				  <div class="tab-pane{if $key==0} active{/if} " id="tab{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}">{$ac.content}{* HTML, can not escape *}</div>
			 	{/foreach}
	 		</div>

	</div></div>
</div>
{/if}
<script type="text/javascript">
  $('#menu_myTab a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
  });
</script>

