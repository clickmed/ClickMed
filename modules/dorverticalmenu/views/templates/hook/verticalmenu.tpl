<div id="dor-verticalmenu" class="block block-info nopadding">
    <div class="dor-vertical-title"><h4 class="dor_title_block"><span>{l s='Shop by Categories' mod='dorverticalmenu'}</span></h4>
    <div class="fa-icon-menu"><i aria-hidden="true" class="fa fa-bars"></i></div>
    </div>
    <div class="dor-verticalmenu block_content" {if $page.page_name != 'index'}style="display:none;"{/if}>
        <div class="navbar navbar-default">
            <div class="verticalmenu" role="navigation">
                <div class="navbar-header">
                    <div class="navbar-collapse navbar-ex1-collapse">
                        {$output nofilter}{* HTML, can not escape *}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
