{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- pos search module TOP -->
<div id="dor_search_top" class="center_column col-lg-4 col-md-4 col-xs-12 col-sm-12 clearfix" >
    <form method="get" action="{$link->getPageLink('search')|escape:'html'}" id="searchbox" class="form-inline">
        <div class="pos_search form-group no-uniform col-lg-4 col-md-4 col-xs-4 col-sm-4">
            <i class="fa fa-th"></i>
            <button type="button" class="dropdown-toggle form-control" data-toggle="dropdown">
               <span data-bind="label">{l s='All Category' mod='dor_searchcategories'}</span>&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" data-value="0">{l s='All Category' mod='dor_searchcategories'}</a></li>
               <!-- {$categories_option nofilter} -->
            </ul>
        </div>
        <div class="dor_search form-group col-lg-8 col-md-8 col-xs-8 col-sm-8">
			<input class="search_query form-control" type="text" id="dor_query_top" name="search_query" value="{$search_query|escape:'html':'UTF-8'|stripslashes}" placeholder="{l s='Type your search here...' mod='dor_searchcategories'}" autocomplete="off" />
			<button type="submit" name="submit_search" class="btn btn-default"><i class="fa fa-search"></i></button>
        </div>
        <label for="dor_query_top"></label>
        <input type="hidden" name="controller" value="search" />
        <input type="hidden" name="orderby" value="position" />
        <input type="hidden" name="orderby" value="categories" />
        <input type="hidden" name="orderway" value="desc" />
        {if $smarty.get.valSelected == ""}
        <input type="hidden" name="valSelected" value="0" />
        {else}
        <input type="hidden" name="valSelected" value="{$smarty.get.valSelected}" />
        {/if}
    </form>
</div>
<!-- /pos search module TOP -->
