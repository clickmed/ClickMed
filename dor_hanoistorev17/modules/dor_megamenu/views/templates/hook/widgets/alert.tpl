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
{if isset($html)&& !empty($html)}
<div class="alert {$alert_type|escape:'htmlall':'UTF-8'}">
	{$html nofilter}{* HTML, can not escape *}
</div>
{/if}