{if $page_name =='index'}
	{if isset($homeslider_slides)}
	<div class="row">
		{if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
		<div id="Dor_Full_Slider" style="width: 1300px; height: 741px;">
		    <!-- Loading Screen -->
		    <div class="slider-loading" data-u="loading" style="position: absolute; top: 0px; left: 0px;">
		        <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
		        <div class="slider-loading-img"></div>
		    </div>
		    <div class="slider-content-wrapper" data-u="slides">
		    	{foreach from=$homeslider_slides item=slide}
					{if $slide.active}
			        <div class="slider-content" data-p="225.00" style="display:none;">
			            <img data-u="image" src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`dor_imageslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"{if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
			            <div class="dor-info-perslider">
				            <div class="dor-slider-title" data-u="caption" data-t="12">{$slide.title|escape:'html':'UTF-8'}</div>
				            <div class="dor-slider-caption" data-u="caption" data-t="9">{$slide.legend|escape:'html':'UTF-8'}</div>
				            {if $slide.description}
				            <div class="dor-slider-desc" data-u="caption" data-t="10">{$slide.description}</div>
				            {/if}
				            {if $slide.txtReadmore1 || $slide.txtReadmore2}
				            <div class="slider-read-more" data-u="caption" data-t="11">
				            	{if $slide.txtReadmore1}<a href="{$slide.UrlReadmore1}" class="dor-effect-hzt">{$slide.txtReadmore1}</a>{/if}
				            	{if $slide.txtReadmore2}<a href="{$slide.UrlReadmore2}" class="dor-effect-hzt">{$slide.txtReadmore2}</a>{/if}
				            </div>
				            {/if}
				            
			            </div>
			        </div>
			        {/if}
				{/foreach}
		    </div>
		    <!-- Bullet Navigator -->
		    <div data-u="navigator" class="dorNavSlider" style="bottom:70px;right:16px;" data-autocenter="1">
		        <!-- bullet navigator item prototype -->
		        <div data-u="prototype" style="width:16px;height:16px;"></div>
		    </div>
		    <!-- Arrow Navigator -->
		    <span data-u="arrowleft" class="dorArrowLeft" style="" data-autocenter="2"></span>
		    <span data-u="arrowright" class="dorArrowRight" style="" data-autocenter="2"></span>
		</div>
	</div>
	{/if}
{/if}