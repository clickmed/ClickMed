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
<!doctype html>
<html lang="{$language.iso_code}">

  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames} dor-list-effect-pizza2 {if isset($dorDetailCols) && $dorDetailCols != ''}{$dorDetailCols}{/if} {if isset($dorCategoryEffect) && $dorCategoryEffect != ''}dorHoverEffect{$dorCategoryEffect}{/if}">

    {hook h='displayAfterBodyOpeningTag'}

    <main{if isset($dorlayoutmode) && $dorlayoutmode != ""} class="{$dorlayoutmode}"{/if}>
      {block name='product_activation'}
        {include file='catalog/_partials/product-activation.tpl'}
      {/block}
      <header id="header">
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>


      {if $page.page_name =='index' && $page.page_name !='pagenotfound'}
      <div class="homeslider-container dor-bg-white">
        <div class="container">
          {capture name='dorHomeSlider'}{hook h='dorHomeSlider'}{/capture}
          {if $smarty.capture.dorHomeSlider}
            {$smarty.capture.dorHomeSlider nofilter}
          {/if}
        </div>
      </div>
      {/if}


      {block name='notifications'}
        {include file='_partials/notifications.tpl'}
      {/block}
      {if $page.page_name == 'index'}
        {capture name='blockDorado1'}{hook h='blockDorado1'}{/capture}
        {if $smarty.capture.blockDorado1}
          <div class="blockDorado1 blockPosition dor-bg-white">
            <div class="container">
              <div class="row">
              {$smarty.capture.blockDorado1 nofilter}
              </div>
            </div>
          </div>
        {/if}
      {/if}
      {if $page.page_name == 'index'}

      <div id="dor-homepage-sidebar">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 home-sidebar">
              {capture name='dorDailyDeal'}{hook h='dorDailyDeal'}{/capture}
              {if $smarty.capture.dorDailyDeal}
                <div class="dorDailyDeal blockPosition dor-bg-white">
                    {$smarty.capture.dorDailyDeal nofilter}
                </div>
              {/if}
              {capture name='dorSidebar2'}{hook h='dorSidebar2'}{/capture}
              {if $smarty.capture.dorSidebar2}
                <div class="dorSidebar2 blockPosition dor-bg-white">
                    {$smarty.capture.dorSidebar2 nofilter}
                </div>
              {/if}
              {capture name='DorTestimonial'}{hook h='DorTestimonial'}{/capture}
              {if $smarty.capture.DorTestimonial}
                <div class="DorTestimonial blockPosition dor-bg-white">
                    {$smarty.capture.DorTestimonial nofilter}
                </div>
              {/if}
              {capture name='dorSidebar3'}{hook h='dorSidebar3'}{/capture}
              {if $smarty.capture.dorSidebar3}
                <div class="dorSidebar3 blockPosition dor-bg-white">
                    {$smarty.capture.dorSidebar3 nofilter}
                </div>
              {/if}
              
            </div>
            <div class="col-xs-12 col-sm-12 col-md-9 home-content">
                {capture name='dorTabProductCate'}{hook h='dorTabProductCate'}{/capture}
                {if $smarty.capture.dorTabProductCate}
                  <div class="dorTabProductCate blockPosition dor-bg-white">
                      {$smarty.capture.dorTabProductCate nofilter}
                  </div>
                {/if}
                {capture name='blockDorado2'}{hook h='blockDorado2'}{/capture}
                {if $smarty.capture.blockDorado2}
                  <div class="blockDorado2 blockPosition dor-bg-white">
                      {$smarty.capture.blockDorado2 nofilter}
                  </div>
                {/if}
                {capture name='dorTabFeatured'}{hook h='dorTabFeatured'}{/capture}
                {if $smarty.capture.dorTabFeatured}
                  <div class="dorTabFeatured blockPosition dor-bg-white">
                      {$smarty.capture.dorTabFeatured nofilter}
                  </div>
                {/if}
                {capture name='blockDorado3'}{hook h='blockDorado3'}{/capture}
                {if $smarty.capture.blockDorado3}
                  <div class="blockDorado3 blockPosition dor-bg-white">
                      {$smarty.capture.blockDorado3 nofilter}
                  </div>
                {/if}
                {capture name='DorSuggestions'}{hook h='DorSuggestions'}{/capture}
                {if $smarty.capture.DorSuggestions}
                  <div class="DorSuggestions blockPosition dor-bg-white">
                      {$smarty.capture.DorSuggestions nofilter}
                  </div>
                {/if}
                {capture name='DorHomeLatestNews'}{hook h='DorHomeLatestNews'}{/capture}
                {if $smarty.capture.DorHomeLatestNews}
                  <div class="DorHomeLatestNews blockPosition dor-bg-white">
                      {$smarty.capture.DorHomeLatestNews nofilter}
                  </div>
                {/if}
                {capture name='blockDorado9'}{hook h='blockDorado9'}{/capture}
                {if $smarty.capture.blockDorado9}
                  <div class="blockDorado9 blockPosition dor-bg-white">
                      {$smarty.capture.blockDorado9 nofilter}
                  </div>
                {/if}
            </div>
          </div>
        </div>
      </div>
      {/if}
      
      {if $page.page_name == 'index'}
        {capture name='DorTabPro'}{hook h='DorTabPro'}{/capture}
        {if $smarty.capture.DorTabPro}
          <div class="DorTabPro blockPosition dor-bg-gray">
            <div class="container">
              <div class="row">
              {$smarty.capture.DorTabPro nofilter}
              </div>
            </div>
          </div>
        {/if}
      {/if}
      {if $page.page_name != 'index'}
      {block name='breadcrumb'}
        {include file='_partials/breadcrumb.tpl'}
      {/block}
      {/if}
      <section id="wrapper">
        <div class="container">
          <div class="row">
          {block name="left_column"}
            <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
              {if $page.page_name == 'product'}
                {hook h='displayLeftColumnProduct'}
              {else}
                {hook h="displayLeftColumn"}
              {/if}
            </div>
          {/block}

          {block name="content_wrapper"}
            <div id="content-wrapper" class="left-column right-column col-sm-4 col-md-6">
              {block name="content"}
                <p>Hello world! This is HTML5 Boilerplate.</p>
              {/block}
            </div>
          {/block}

          {block name="right_column"}
            <div id="right-column" class="col-xs-12 col-sm-4 col-md-3">
              {if $page.page_name == 'product'}
                {hook h='displayRightColumnProduct'}
              {else}
                {hook h="displayRightColumn"}
              {/if}
            </div>
          {/block}
          </div>
        </div>
      </section>
      {if $page.page_name == 'index'}
          {capture name='blockDorado5'}{hook h='blockDorado5'}{/capture}
          {if $smarty.capture.blockDorado5}
            <div class="blockDorado5 blockPosition dor-bg-white">
              <div class="container">
                <div class="row">
                {$smarty.capture.blockDorado5 nofilter}
                </div>
              </div>
            </div>
          {/if}
        {/if}
      {capture name='dorBlockCustom1'}{hook h='dorBlockCustom1'}{/capture}
        {if $smarty.capture.dorBlockCustom1}
          <div class="dorBlockCustom1 blockPosition dor-bg-white">
            <div class="container">
              <div class="row">
              {$smarty.capture.dorBlockCustom1 nofilter}
              </div>
            </div>
          </div>
        {/if}
      {capture name='blockDorado10'}{hook h='blockDorado10'}{/capture}
        {if $smarty.capture.blockDorado10}
          <div class="blockDorado10 blockPosition dor-bg-white">
            <div class="container">
              <div class="row">
              {$smarty.capture.blockDorado10 nofilter}
              </div>
            </div>
          </div>
        {/if}
      <footer id="footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>

    </main>
    {if isset($dorOptReload) && $dorOptReload == 1}
      <div class="dor-page-loading">
          <div id="loader"></div>
          <div class="loader-section section-left"></div>
          <div class="loader-section section-right"></div>
      </div>
    {/if}
    {include file="_partials/dor-subscribe-popup.tpl"}
    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}
    {capture name='dorthemeoptions'}{hook h='dorthemeoptions'}{/capture}
    {if $smarty.capture.dorthemeoptions}
      {$smarty.capture.dorthemeoptions nofilter}
    {/if}
    {hook h='displayBeforeBodyClosingTag'}
    <div id="to-top" class="to-top"> <i class="fa fa-angle-up"></i> </div>
    {if $page.page_name|escape:'html':'UTF-8' == 'contact'}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMH_Sh8EdCWkG1OFhAih3FFhbkRYuo-0U"></script>
    <script src="{$urls.js_url}jquery.googlemap.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $("#mapContact").googleMap();
        $("#mapContact").addMarker({
            coords: [48.895651, 2.290569],
            icon: prestashop.urls.base_url+'img/cms/dorado/icon/market-map.png',
            url: 'http://www.doradothemes.com'
          });
      });
    </script>
    {/if}
  </body>

</html>
