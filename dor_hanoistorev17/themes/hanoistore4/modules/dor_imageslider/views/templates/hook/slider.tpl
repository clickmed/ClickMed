{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $dorslider.slides}
  <div id="dorSlideShow" class="homeslider-container" data-interval="{$dorslider.speed}" data-wrap="{$dorslider.wrap}" data-pause="{$dorslider.pause}" data-arrow={$dorslider.arrow} data-nav={$dorslider.nav}>
    <div id="top_column" class="center_column col-xs-12 col-sm-12">
      <div class="row">
      <div id="Dor_Full_Slider" style="width: 1300px; height: 460px;">
          <!-- Loading Screen -->
          <div class="slider-loading" data-u="loading" style="position: absolute; top: 0px; left: 0px;">
              <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
              <div class="slider-loading-img"></div>
          </div>
          <div class="slider-content-wrapper" data-u="slides">
          {foreach from=$dorslider.slides item=slide key=i}
            {if $slide.active}
               <div class="slider-content effectSlider{$slide.effect} item-slide-data-{$i+1}" data-p="225.00" style="display:none;">
                    <img data-u="image" src="{$slide.image_url}" alt="{$slide.legend|escape}" />
                    <div class="product-item-image">
                          {if isset($slide.imageproduct) && $slide.imageproduct != ""}
                          <img data-u="caption" data-t="2" src="{$slide.imageproduct}" alt=""/>
                          {/if}
                          {if isset($slide.price) && $slide.price != ""}
                          <div data-u="caption" data-t="16" class="dor-slider-price">
                            <div class="price-slider button--sacnite button--round-l">
                              <span>-{l s="Only" mod="dor_homeslider"}-</span>
                              <span>{$slide.price}</span>
                            </div>
                          </div>
                          {/if}
                      </div>
                    <div class="dor-info-perslider">
                      <div class="container">
                        <div class="dor-slider-title" data-u="caption" data-t="{if $i==0}88{else}88{/if}">{$slide.title|escape:'html':'UTF-8'}</div>
                        <div class="dor-slider-caption" data-u="caption" data-t="{if $i==0}87{else}87{/if}">{$slide.legend|escape:'html':'UTF-8'}</div>
                        {if $slide.description}
                        <div class="dor-slider-desc" data-u="caption" data-t="83">{$slide.description nofilter}</div>
                        {/if}
                        {if $slide.txtReadmore1 || $slide.txtReadmore2}
                        <div class="slider-read-more" data-u="caption" data-t="{if $i==0}89{else}89{/if}">
                          {if $slide.txtReadmore1}<a href="{$slide.UrlReadmore1}" class="dor-effect-hzt button--winona" data-text="{$slide.txtReadmore1}"><span>{$slide.txtReadmore1}</span></a>{/if}
                          {if $slide.txtReadmore2}<a href="{$slide.UrlReadmore2}" class="dor-effect-hzt button--winona" data-text="{$slide.txtReadmore2}"><span>{$slide.txtReadmore2}</span></a>{/if}
                        </div>
                        {/if}
                      </div>
                    </div>
                </div>
              {/if}
          {/foreach}
          </div>
          <!-- Bullet Navigator -->
          <div data-u="navigator" class="dorNavSlider" style="bottom:70px;right:16px;" data-autocenter="1">
              <!-- bullet navigator item prototype -->
              <div data-u="prototype"></div>
          </div>
          <!-- Arrow Navigator -->
          <span data-u="arrowleft" class="dorArrowLeft" style="" data-autocenter="2"></span>
          <span data-u="arrowright" class="dorArrowRight" style="" data-autocenter="2"></span>
      </div>
    </div>
    </div>
  </div>
{/if}
