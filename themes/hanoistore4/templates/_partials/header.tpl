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


{block name='header_nav'}
  <nav class="header-nav hidden">
    <div class="container">
        <div class="row">
          <div class="hidden-md-up text-xs-center mobile">
            <div class="pull-xs-left" id="menu-icon">
              <i class="material-icons">&#xE5D2;</i>
            </div>
            <div class="pull-xs-right" id="_mobile_cart"></div>
            <div class="pull-xs-right" id="_mobile_user_info"></div>
            <div class="top-logo" id="_mobile_logo"></div>
            <div class="clearfix"></div>
          </div>
        </div>
    </div>
  </nav>
{/block}
<div id="dor-topbar">
  <div class="dor-topbar-inner">
      <div class="container">
        <div class="row">
            {capture name='topbarDorado1'}{hook h='topbarDorado1'}{/capture}
            {if $smarty.capture.topbarDorado1}
              <div class="dorTopbarContent topbar1 col-lg-12 col-sm-12">
                {$smarty.capture.topbarDorado1 nofilter}
                <div class="dor-topbar-selector pull-right"></div>
              </div>
            {/if}
        </div>
      </div>
  </div>
</div>
<div class="menu-group-show">
{block name='header_top'}
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-sm-12">
        <div class="header-top">
           <div class="row clearfix">
            <div class="header-logo col-lg-3 col-md-3 col-xs-3 col-sm-3" id="header_logo">
              <a href="{$urls.base_url}">
                <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
              </a>
            </div>
            {capture name='dorHeaderSearch'}{hook h='dorHeaderSearch'}{/capture}
            {if $smarty.capture.dorHeaderSearch}
              <div class="dorHeaderSearch-Wapper col-lg-6 col-md-6 col-xs-6 col-sm-6">
                {$smarty.capture.dorHeaderSearch nofilter}
                {capture name='headerDorado8'}{hook h='headerDorado8'}{/capture}
                {if $smarty.capture.headerDorado8}
                  {$smarty.capture.headerDorado8 nofilter}
                {/if}
              </div>
            {/if}
            <div class="header-line-wapper col-lg-3 col-md-3 col-xs-3 col-sm-3">
              <div class="search-box-mobile hidden">
                <div class="header-menu-item-icon">
                  <a href="#" class="icon-search">
                    <i class="fa animated fa-search search-icon"></i>
                  </a>
                </div>
              </div>
            {hook h='displayNav2'}
            </div>
          </div>
          <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display:none;">
            <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
            <div class="js-top-menu-bottom">
              <div id="_mobile_currency_selector"></div>
              <div id="_mobile_language_selector"></div>
              <div id="_mobile_contact_link"></div>
            </div>
          </div>
        </div>
        {hook h='displayNavFullWidth'}
      </div>
    </div>
  </div>
  <div class="header-megamenu clearfix">
    <div class="container">
      <div class="row">
        <div class="dor-header-menu col-lg-12 col-md-12 position-static">
          <div class="row">
            {capture name='dorVerticalMenu'}{hook h='dorVerticalMenu'}{/capture}
            {if $smarty.capture.dorVerticalMenu}
              <div class="col-md-3 col-sm-3 col-xs-12 dorVerticalMenu pull-left">
              {$smarty.capture.dorVerticalMenu nofilter}
              </div>
            {/if}
            {hook h='displayTop'}
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
{/block}
</div>