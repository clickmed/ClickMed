{**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{foreach $stylesheets.external as $stylesheet}
  <link rel="stylesheet" href="{$stylesheet.uri}" type="text/css" media="{$stylesheet.media}">
{/foreach}
<link href="{$urls.css_url}dorado/style.css" rel="stylesheet" type="text/css"/>
<link href="{$urls.css_url}dorado/product-list.css" rel="stylesheet" type="text/css"/>
<link href="{$urls.css_url}dorado/product-detail.css" rel="stylesheet" type="text/css"/>
<link href="{$urls.css_url}dorado/cart-order.css" rel="stylesheet" type="text/css"/>
<link href="{$urls.css_url}dorado/blog.css" rel="stylesheet" type="text/css"/>
<link href="{$urls.css_url}dorado/contact-form.css" rel="stylesheet" type="text/css"/>
<link href="{$urls.css_url}dorado/responsive.css" rel="stylesheet" type="text/css"/>
{if isset($DorRtl) && $DorRtl}
<link href="{$urls.css_url}dorado/rtl.css" rel="stylesheet" type="text/css"/>
{/if}
{if isset($dorthemecolor) && $dorthemecolor != "" && isset($dorEnableThemeColor) && $dorEnableThemeColor == 1}
	{if $pathTmpColor == 1}
	<link href="{$urls.css_url}dorado/color/{$dorthemecolor}.css" rel="stylesheet" type="text/css"/>
	{else}
	<link href="{$urls.base_url}modules/dor_themeoptions/css/color/{$dorthemecolor}.css" rel="stylesheet" type="text/css"/>
	{/if}
{/if}
{foreach $stylesheets.inline as $stylesheet}
  <style>
    {$stylesheet.content}
  </style>
{/foreach}
