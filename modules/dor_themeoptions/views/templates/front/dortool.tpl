<div class="dor-wrap">
    <div class="control inactive"><a href="javascript:void(0)"><i class="fa fa-cog fa-spin"></i></a></div>
	<h2 class="dor-title">Theme Options</h2>
	<div class="dor-option" id="dor-option-tool">

        <div class="cl-table tool-class-opt" id="dor-opt-header" style="display:none">
            <div class="cl-tr cl-tr-mode-label">
                <div class="cl-tr cl-tr-style-label"><span>{l s='Header Skin'}</span><i class="fa fa-plus-square"></i></div>
            </div>
            <div class="cl-tr cl-tr-mode tool-opt-data header-skin-tool clearfix">
                <select id="dor_header_skin" class="tool-select-opt fixed-width-xl" name="dor_header_skin">
                    <option value="">{l s='---Choose a skin---'}</option>
                    <option value="headerskin1">{l s='Header Skin 1'}</option>
                    <option value="headerskin2">{l s='Header Skin 2'}</option>
                    <option value="headerskin3">{l s='Header Skin 3'}</option>
                </select>
            </div>
        </div>

        <div class="cl-table tool-class-opt" id="dor-opt-headerayout">
            <div class="cl-tr cl-tr-mode-label">
                <div class="cl-tr cl-tr-style-label"><span>{l s='Header Float'}</span><i class="fa fa-minus-square"></i></div>
            </div>
            <div class="cl-tr cl-tr-mode tool-opt-data clearfix">
                <div class="pull-left">
                    <input class="headerfloat_theme" id="dorHeaderFloatOn" type="radio" value="1" name="headerfloat_css" checked="checked">{l s='On'}
                </div>
                <div class="pull-right">
                    <input class="headerfloat_theme" id="dorHeaderFloatOff" type="radio" value="0" name="headerfloat_css">{l s='Off'}
                </div>
            </div>
        </div>

		<div class="cl-table tool-class-opt" id="dor-opt-modelayout">
			<div class="cl-tr cl-tr-mode-label">
				<div class="cl-tr cl-tr-style-label"><span>{l s='Mode Layout'}</span><i class="fa fa-minus-square"></i></div>
			</div>
			<div class="cl-tr cl-tr-mode tool-opt-data clearfix">
                <div class="pull-left">
                    <input class="mode_theme" id="dorwideFull" type="radio" value="full" name="mode_css" checked="checked">{l s='Full'}
                </div>
				<div class="pull-right">
					<input class="mode_theme" id="dorboxBox" type="radio" value="boxed" name="mode_css">{l s='Box'}
				</div>
			</div>
		</div>
		
        <div class="cl-table tool-class-opt hidden" id="dor-opt-font">
            <div class="cl-tr cl-tr-mode-label">
                <div class="cl-tr cl-tr-style-label"><span>{l s='Font'}</span><i class="fa fa-minus-square"></i></div>
            </div>
            <div class="cl-tr cl-tr-mode tool-opt-data clearfix">
                <select id="dor_font_options" class=" fixed-width-xl tool-select-opt" name="dor_font_options">
                    <option value="">{l s='---Choose a font---'}</option>
                    <option value="font1">{l s='Open Sans'}</option>
                    <option value="font2">{l s='Josefin Slab'}</option>
                    <option value="font3">{l s='Arvo'}</option>
                    <option value="font4">{l s='Lato'}</option>
                    <option value="font5">{l s='Vollkorn'}</option>
                    <option value="font6">{l s='Abril Fatface'}</option>
                    <option value="font7">{l s='Ubuntu'}</option>
                    <option value="font8">{l s='PT Sans'}</option>
                    <option value="font9">{l s='Old Standard TT'}</option>
                    <option value="font10">{l s='Droid Sans'}</option>
                </select>
            </div>
        </div>
		{if $codeColor && $codeColor != ""}
		<div class="cl-wrapper tool-class-opt" id="dor-opt-themecolor">
			<div class="cl-container">
				<div class="cl-tr cl-tr-mode-label">
					<div class="cl-tr cl-tr-style-label"><span>{l s='Theme color'}</span><i class="fa fa-minus-square"></i></div>
				</div>
				<div class="cl-table tool-opt-data clearfix">
                    <div class="cl-tr cl-tr-style box-layout">
                        {foreach from=$codeColor item=color name=codeColor}
                        <div style="background-color: #{$color}" class="cl-td-l cl-td-layout cl-td-layoutcolor" id="{$color}"><a href="javascript:void(0)"  title="{$color}"><span class="cl2"></span><span class="cl1"></span></a></div>
                        {/foreach}
                    </div>
                </div>
            </div>
		</div>
        {/if}
        <div class="cl-table tool-class-opt clearfix" id="dor-opt-bgbody">  
            <div class="cl-tr">
                <div class="cl-tr cl-tr-style-label"><span>{l s='Background Image body:'}</span><i class="fa fa-minus-square"></i></div>
                <div class="cl-td-bg tool-opt-data clearfix">
                    <div class="cl-pattern">
                        {for $id=1 to 30}
                            <div class="cl-image pattern{$id}" id="pattern{$id}"></div>
                        {/for}
                    </div>
                </div>
            </div>
        </div>
        <div class="cl-table tool-class-opt" id="dor-opt-footer" style="display:none">
            <div class="cl-tr cl-tr-mode-label">
                <div class="cl-tr cl-tr-style-label"><span>{l s='Footer Skin'}</span><i class="fa fa-plus-square"></i></div>
            </div>
            <div class="cl-tr cl-tr-mode tool-opt-data footer-skin-tool clearfix">
                <select id="dor_footer_skin" class="tool-select-opt fixed-width-xl" name="dor_footer_skin">
                    <option value="">{l s='---Choose a skin---'}</option>
                    <option value="footerskin1">{l s='Footer Skin 1'}</option>
                    <option value="footerskin2">{l s='Footer Skin 2'}</option>
                    <option value="footerskin3">{l s='Footer Skin 3'}</option>
                </select>
            </div>
        </div>
        <div class="cl-tr cl-row-reset">
            <button class="btn btn-default cl-reset">Reset</button>
        </div>
	</div>
</div>