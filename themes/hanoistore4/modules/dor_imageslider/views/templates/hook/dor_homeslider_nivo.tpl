{if $page_name =='index'}
    {if isset($homeslider_slides)}
    <div class="slider-area">
        <!-- direction 1 -->
        <div id="ensign-nivoslider-1" class="ensign-nivoslider">   
            <img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`dor_homeslider/images/sliderbg.png")}" alt="" title="#slider-direction-0"  />
            <img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`dor_homeslider/images/sliderbg.png")}" alt="" title="#slider-direction-1"  />
        </div>
        {foreach from=$homeslider_slides key=i item=slides}
            <div id="slider-direction-{$i}" class="slider-direction">
                <div class="slider-content">
                    {foreach from=$slides key=j item=slide}
                    {if $slide.active}
                    <div class="sliderimg">
                        <img class="{if $j==0 && $i==0}fadeInRight{else if $j==1 && $i==0}fadeInLeft{else if $j==0 && $i==1}fadeInUp{else if $j==1 && $i==1}fadeInDown{/if}" src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`dor_homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
                        <div class="slider-text">
                            {if $slide.description}
                                {$slide.description}
                            {/if}
                        </div>
                    </div>
                    {/if}
                    {/foreach}
                </div>
            </div>
        {/foreach}
    </div>
    {/if}
{/if}