
<div class="col-lg-12" id="content-themeoptions">
    <div class="row">
        <div class="boxthemeoptionTab">
        <div class="themeoptionTab col-lg-12 col-sx-12 col-xm-12">
            <div class="list-group">
                <ul id="myTab" class="nav nav-tabs">
                    <li class=""><a href="#themeSkin" data-toggle="tab"><span> Theme skin</span></a></li>
                    <li class="hidden"><a href="#fontOption" data-toggle="tab"><span> Font</span></a></li>
                    <li class="hidden-"><a href="#menuOption" data-toggle="tab"><span> Megamenu</span></a></li>
                    <li class="hidden-"><a href="#verticalMenuOption" data-toggle="tab"><span> Vertical Menu</span></a></li>
                    <li class="hidden-"><a href="#topbarOption" data-toggle="tab"><span> TopBar</span></a></li>
                    <li class=""><a href="#headercolor" data-toggle="tab"><span> Header</span></a></li>
                    <li class="hidden-"><a href="#footercolor" data-toggle="tab"><span> Footer</span></a></li>
                    <li class="active"><a href="#dorAdvance" data-toggle="tab"><span> Advance</span></a></li>
                </ul>
            </div>
        </div>

        <div class=" col-lg-12 col-sx-12 col-xm-12">
        <div id="myTabContent" class="tab-content">
        <!----  themes styles -->
            <div class="tab-pane" id="themeSkin">
                <div class="tab-content-ii">
                    <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                        <div id="styles-config-theme" class="tool-class-admin">
                           <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> {l s='Theme styles'}</span><span class="square-button"><i class="fa fa-minus-square"></i></span></h4>
                            <div class="box_dor clearfix">
                                <div class="form-group">
                                    <label class="col-lg-4 adm-label">
                                        {l s='Enable Theme Color:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" {if $dorEnableThemeColor ==1}  checked="checked" {/if} id="dorEnableThemeColor_on" name="dorEnableThemeColor">
                                                <label for="dorEnableThemeColor_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorEnableThemeColor ==0}  checked="checked" {/if} value="0" id="dorEnableThemeColor_off" name="dorEnableThemeColor">
                                                <label for="dorEnableThemeColor_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block">If you choose yes , it  will appear Theme color that you selected in the admin
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="themeColorOption">
                                    
                                    <div class="col-lg-4 adm-label"> {l s='Theme color'}</div>
                                    <div class="col-lg-8">
                                        {if $codeColor && $codeColor != ""}
                                        <div class="cl-tr cl-tr-style">
                                            {foreach from=$codeColor item=color name=codeColor}
                                            <div style="background-color: #{$color}" class="cl-td-l cl-td-layout cl-td-layoutcolor{if $dorthemecolor=={$color} } active {/if}" id="{$color}"><a href="javascript:void(0)" title="{$color}"><span class="cl2"></span><span class="cl1"></span></a></div>
                                            {/foreach}
                                        </div>
                                        <p class="dorclearfix">You must enable theme color in theme option configure.</p>
                                        {else}
                                        <p class="dorclearfix" style="color:#ff0000;">Your theme have not color option.</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 adm-label">
                                        {l s='Enable Background Image:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                         <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="dorEnableBgImage_on" {if $dorEnableBgImage ==1} checked="checked" {/if} name="dorEnableBgImage">
                                                <label for="dorEnableBgImage_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorEnableBgImage ==0} checked="checked" {/if} value="0" id="dorEnableBgImage_off" name="dorEnableBgImage">
                                                <label for="dorEnableBgImage_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block">If you choose yes , it  will appear Background Image themes that you selected in the admin
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group themeSkin" id="themeBgImageOption">
                                    <div class="col-lg-4 adm-label">
                                        <div class="cl-tr">
                                            <div class="col-lg-12">{l s='Background Image:'}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="cl-td-bg ">
                                            <div class="cl-pattern">
                                                {for $id=1 to 30}
                                                    <div class="cl-image pattern{$id} {if $dorthemebg|substr:7 == $id } active {/if}" id="pattern{$id}"  ></div>
                                                {/for}
                                            </div>
                                        </div>
                                        <p class="dorclearfix">You must enable background image in theme option configure.</p>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div id="main-admin-theme" class="tool-class-admin">
                            <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> {l s='Theme Options'}</span><span class="square-button"><i class="fa fa-minus-square"></i></span></h4>
                            <div class="box_dor clearfix">
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='On/Off Reload Processing:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                        <span class="switch prestashop-switch fixed-width-lg">
                                            <input type="radio" value="1" id="dorOptReload_on" {if $dorOptReload ==1} checked="checked" {/if} name="dorOptReload">
                                            <label for="dorOptReload_on">{l s='On'}</label>
                                                <input type="radio" {if $dorOptReload ==0} checked="checked" {/if} value="0" id="dorOptReload_off" name="dorOptReload">
                                            <label for="dorOptReload_off">{l s='Off'}</label>
                                            <a class="slide-button btn"></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Show Popup Subscribe:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="dorSubscribe_on" {if $dorSubscribe ==1} checked="checked" {/if} name="dorSubscribe">
                                                <label for="dorSubscribe_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorSubscribe ==0} checked="checked" {/if} value="0" id="dorSubscribe_off" name="dorSubscribe">
                                                <label for="dorSubscribe_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block">If you choose yes, fontend  will display box popup Subscribe
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Show Option Fontend:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="dorOptfrontend_on" {if $dorOptfrontend ==1} checked="checked" {/if} name="dorOptfrontend">
                                                <label for="dorOptfrontend_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorOptfrontend ==0} checked="checked" {/if} value="0" id="dorOptfrontend_off" name="dorOptfrontend">
                                                <label for="dorOptfrontend_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block">If you choose yes, fontend  will appear select box for you to customize themes
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Layout Mode:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                        <select id="dorlayoutmode" class=" fixed-width-xl" name="dorlayoutmode">
                                            <option {if $dorlayoutmode =='full' } selected="full" {/if} value="full">{l s='Full Width'}</option>
                                            <option {if $dorlayoutmode =='boxed' } selected="boxed" {/if} value="boxed">{l s='Boxed Large'}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer-i">
                            <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateThemeskin" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                        </div>
                    </form>
                </div>
            </div><!-- end tab pane review -->
            <!--- end themeskin -->
            <div class="tab-pane fade" id="configcolor">
                <div class="tab-content-ii">
                    <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                    <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Unlimited color</span></h4>

                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            {l s='Show fontend:'}
                        </label>
                        <div class="col-lg-9 ">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" value="1" id="show_fontend_on" name="show_fontend">
                            <label for="show_fontend_on">{l s='Yes'}</label>
                                <input type="radio" checked="checked" value="0" id="show_fontend_off" name="show_fontend">
                            <label for="show_fontend_off">{l s='No'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                        </div>
                         <div class="panel-footer-i">
                            <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdate" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                        </div>
                    </div>
                   
                    </form>
                </div>
            </div><!-- col-tab pane -->

        <!----   Font Options -->
            <div class="tab-pane fade" id="fontOption">
                <div class="tab-content-ii">
                    <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                        <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Font Options</span></h4>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Choose a font'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorFont" class=" fixed-width-xl" name="dorFont">
                                    <option value="">{l s='---Choose a font---'}</option>
                                    <option {if $dorFont =='font1' } selected="selected" {/if} value="font1">{l s='Open Sans'}</option>
                                    <option {if $dorFont =='font2' } selected="selected" {/if} value="font2">{l s='Josefin Slab'}</option>
                                    <option {if $dorFont =='font3' } selected="selected" {/if} value="font3">{l s='Arvo'}</option>
                                    <option {if $dorFont =='font4' } selected="selected" {/if} value="font4">{l s='Lato'}</option>
                                    <option {if $dorFont =='font5' } selected="selected" {/if} value="font5">{l s='Vollkorn'}</option>
                                    <option {if $dorFont =='font6' } selected="selected" {/if} value="font6">{l s='Abril Fatface'}</option>
                                    <option {if $dorFont =='font7' } selected="selected" {/if} value="font7">{l s='Ubuntu'}</option>
                                    <option {if $dorFont =='font8' } selected="selected" {/if} value="font8">{l s='PT Sans'}</option>
                                    <option {if $dorFont =='font9' } selected="selected" {/if} value="font9">{l s='Old Standard TT'}</option>
                                    <option {if $dorFont =='font10' } selected="selected" {/if} value="font10">{l s='Droid Sans'}</option>
                                    <option {if $dorFont =='font11' } selected="selected" {/if} value="font11">{l s='Economica'}</option>
                                    <option {if $dorFont =='font12' } selected="selected" {/if} value="font12">{l s='Alegreya'}</option>
                                    <option {if $dorFont =='font13' } selected="selected" {/if} value="font13">{l s='Arimo'}</option>
                                    <option {if $dorFont =='font14' } selected="selected" {/if} value="font14">{l s='Domine'}</option>
                                    <option {if $dorFont =='font15' } selected="selected" {/if} value="font15">{l s='Playfair Display'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <label class="control-label col-lg-4 adm-label">
                                {l s='Enable Font Awesome:'}
                            </label>
                            <div class="col-lg-8 ">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" value="1" {if $dorEnableAwesome ==1}  checked="checked" {/if} id="dorEnableAwesome_on" name="dorEnableAwesome">
                                    <label for="dorEnableAwesome_on">{l s='Yes'}</label>
                                        <input type="radio" {if $dorEnableAwesome ==0}  checked="checked" {/if} value="0" id="dorEnableAwesome_off" name="dorEnableAwesome">
                                    <label for="dorEnableAwesome_off">{l s='No'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        <div class="panel-footer-i">
                            <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateFont" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        
        <!----  Megamenu Options -->
            <div class="tab-pane fade" id="menuOption">
                <div class="tab-content-ii">
                    <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                        <div id="mega-change-color" class="footer-class-admin">
                            <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Unlimited color</span></h4>
                            <div class="box_dor">
                                <div class="form-group row">
                                    <label class="control-label col-lg-3">
                                        {l s='Background Outside Color:'}
                                    </label>
                                    <div class="col-lg-9 ">
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <div class="input-group">
                                                    <input type="text" value="{$dorMegamenuBgOutside}" name="dorMegamenuBgOutside" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_19" style="color: black;background-color: {$dorMegamenuBgOutside}">
                                                    <span class="mColorPickerTrigger input-group-addon" id="icp_color_19" style="cursor:pointer;" data-mcolorpicker="true">
                                                        <img align="absmiddle" src="../img/admin/color.png">
                                                    </span>
                                                    <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Background Color:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuBgColor}" name="dorMegamenuBgColor" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_18" style="color: black;background-color: {$dorMegamenuBgColor}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_18" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Primary Text:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuColorText}" name="dorMegamenuColorText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_17" style="color: black;background-color: {$dorMegamenuColorText}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_17" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Primary Link:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuColorLink}" name="dorMegamenuColorLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_16" style="color: black;background-color:{$dorMegamenuColorLink}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_16" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Primary Link Hover:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuColorLinkHover}" name="dorMegamenuColorLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_15" style="color: black;background-color:{$dorMegamenuColorLinkHover}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_15" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Sub Text:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuColorSubText}" name="dorMegamenuColorSubText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_20" style="color: black;background-color: {$dorMegamenuColorSubText}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_20" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Sub Link:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuColorSubLink}" name="dorMegamenuColorSubLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_21" style="color: black;background-color:{$dorMegamenuColorSubLink}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_21" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Sub Link Hover:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorMegamenuColorSubLinkHover}" name="dorMegamenuColorSubLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_22" style="color: black;background-color:{$dorMegamenuColorSubLinkHover}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_22" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                       </div>

                       <div class="panel-footer-i">
                                <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateMegamenu" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                            </div>
                </form>
                </div>
            </div>
        <!----  End Megamenu -->
        <!----  Vertical Menu Options -->
            <div class="tab-pane fade" id="verticalMenuOption">
                <div class="tab-content-ii">
                    <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                        <div id="vermenu-change-color" class="footer-class-admin">
                            <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Unlimited color</span></h4>
                            <div class="box_dor">
                                <div class="form-group row">
                                    <label class="control-label col-lg-3">
                                        {l s='Background Outside Color:'}
                                    </label>
                                    <div class="col-lg-9 ">
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <div class="input-group">
                                                    <input type="text" value="{$dorVermenuBgOutside}" name="dorVermenuBgOutside" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_14" style="color: black;background-color: {$dorVermenuBgOutside}">
                                                    <span class="mColorPickerTrigger input-group-addon" id="icp_color_14" style="cursor:pointer;" data-mcolorpicker="true">
                                                        <img align="absmiddle" src="../img/admin/color.png">
                                                    </span>
                                                    <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Background Color:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuBgColor}" name="dorVermenuBgColor" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_13" style="color: black;background-color: {$dorVermenuBgColor}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_13" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Primary Text:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuColorText}" name="dorVermenuColorText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_12" style="color: black;background-color: {$dorVermenuColorText}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_12" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Primary Link:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuColorLink}" name="dorVermenuColorLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_11" style="color: black;background-color:{$dorVermenuColorLink}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_11" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Primary Link Hover:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuColorLinkHover}" name="dorVermenuColorLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_10" style="color: black;background-color:{$dorVermenuColorLinkHover}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_10" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Sub Text:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuColorSubText}" name="dorVermenuColorSubText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_23" style="color: black;background-color: {$dorVermenuColorSubText}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_23" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Sub Link:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuColorSubLink}" name="dorVermenuColorSubLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_24" style="color: black;background-color:{$dorVermenuColorSubLink}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_24" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Sub Link Hover:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorVermenuColorSubLinkHover}" name="dorVermenuColorSubLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_25" style="color: black;background-color:{$dorVermenuColorSubLinkHover}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_25" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                       </div>

                       <div class="panel-footer-i">
                                <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateVermenu" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                            </div>
                </form>
                </div>
            </div>
        <!----  End Vertical Menu -->
        <!----  Top Bar Options -->
            <div class="tab-pane fade" id="topbarOption">
                <div class="tab-content-ii">
                    <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                
                        <div id="footer-chose-theme" class="footer-class-admin hidden">
                            <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> TopBar Skin</span></h4>
                            <div class="box_dor clearfix">
                                <div class="col-lg-4 adm-label">{l s='Choose a skin'}</div>
                                <div class="col-lg-8 ">
                                    <select id="dorTopbarSkin" class="fixed-width-xl" name="dorTopbarSkin">
                                        <option value="">{l s='---Choose a skin---'}</option>
                                        <option {if $dorTopbarSkin =='topbarskin1' } selected="selected" {/if} value="topbarskin1">{l s='Topbar Skin 1'}</option>
                                        <option {if $dorTopbarSkin =='topbarskin2' } selected="selected" {/if} value="topbarskin2">{l s='Topbar Skin 2'}</option>
                                        <option {if $dorTopbarSkin =='topbarskin3' } selected="selected" {/if} value="topbarskin3">{l s='Topbar Skin 3'}</option>
                                        <option {if $dorTopbarSkin =='topbarskin4' } selected="selected" {/if} value="topbarskin4">{l s='Topbar Skin 4'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="footer-change-color" class="footer-class-admin">
                            <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Unlimited color</span></h4>
                            <div class="box_dor">
                                <div class="form-group row">
                                    <label class="control-label col-lg-3">
                                        {l s='Background Outside Color:'}
                                    </label>
                                    <div class="col-lg-9 ">
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <div class="input-group">
                                                    <input type="text" value="{$dorTopbarBgOutside}" name="dorTopbarBgOutside" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_09" style="color: black;background-color: {$dorTopbarBgOutside}">
                                                    <span class="mColorPickerTrigger input-group-addon" id="icp_color_09" style="cursor:pointer;" data-mcolorpicker="true">
                                                        <img align="absmiddle" src="../img/admin/color.png">
                                                    </span>
                                                    <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Background Color:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorTopbarBgColor}" name="dorTopbarBgColor" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_08" style="color: black;background-color: {$dorTopbarBgColor}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_08" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Text:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorTopbarColorText}" name="dorTopbarColorText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_07" style="color: black;background-color: {$dorTopbarColorText}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_07" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Link:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorTopbarColorLink}" name="dorTopbarColorLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_06" style="color: black;background-color:{$dorTopbarColorLink}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_06" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                {l s='Color Link Hover:'}
                            </label>
                            <div class="col-lg-9 ">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" value="{$dorTopbarColorLinkHover}" name="dorTopbarColorLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_05" style="color: black;background-color:{$dorTopbarColorLinkHover}">
                                            <span class="mColorPickerTrigger input-group-addon" id="icp_color_05" style="cursor:pointer;" data-mcolorpicker="true">
                                                <img align="absmiddle" src="../img/admin/color.png">
                                            </span>
                                            <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                       </div>

                       <div class="panel-footer-i">
                                <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateTopbar" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                            </div>
                </form>
                </div>
            </div>
        <!----  End Topbar -->
        <!----   header color -->
            <div class="tab-pane fade" id="headercolor">
                    <div class="tab-content-ii">
                        <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">


                        <div id="header-chose-theme" class="header-class-admin hidden">
                           <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Header Skin</span></h4>
                            <div class="box_dor clearfix">
                                <div class="col-lg-4 adm-label">{l s='Choose a skin'}</div>
                                <div class="col-lg-8 ">
                                    <select id="dorHeaderSkin" class="fixed-width-xl" name="dorHeaderSkin">
                                        <option value="">{l s='---Choose a skin---'}</option>
                                        <option {if $dorHeaderSkin =='headerskin1' } selected="selected" {/if} value="headerskin1">{l s='Header Skin 1'}</option>
                                        <option {if $dorHeaderSkin =='headerskin2' } selected="selected" {/if} value="headerskin2">{l s='Header Skin 2'}</option>
                                        <option {if $dorHeaderSkin =='headerskin3' } selected="selected" {/if} value="headerskin3">{l s='Header Skin 3'}</option>
                                        <option {if $dorHeaderSkin =='headerskin4' } selected="selected" {/if} value="headerskin4">{l s='Header Skin 4'}</option>
                                        <option {if $dorHeaderSkin =='headerskin5' } selected="selected" {/if} value="headerskin5">{l s='Header Skin 5'}</option>
                                        <option {if $dorHeaderSkin =='headerskin6' } selected="selected" {/if} value="headerskin6">{l s='Header Skin 6'}</option>
                                        <option {if $dorHeaderSkin =='headerskin7' } selected="selected" {/if} value="headerskin7">{l s='Header Skin 7'}</option>
                                    </select>
                                </div>
                            </div>
                       </div>

                        <div id="header-chose-style" class="header-class-admin">
                            <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Header</span></h4>
                            <div class="box_dor">
                            <div class="form-group">
                                <label class="control-label col-lg-4 adm-label">
                                    {l s='Enable/Disable Float:'}
                                </label>
                                <div class="col-lg-8 ">
                                        <span class="switch prestashop-switch fixed-width-lg">
                                            <input type="radio" value="1" id="floatHeader_on" {if $dorFloatHeader==1} checked="checked" {/if} name="dorFloatHeader">
                                            <label for="floatHeader_on">{l s='Yes'}</label>
                                                <input type="radio" {if $dorFloatHeader==0} checked="checked" {/if} value="0" id="floatHeader_off" name="dorFloatHeader">
                                            <label for="floatHeader_off">{l s='No'}</label>
                                            <a class="slide-button btn"></a>
                                        </span>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                    <div class="hidden-">
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Background Outside Color'}
                        </label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" value="{$dorHeaderBgOutside}" name="dorHeaderBgOutside" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_0" style="background-color:{$dorHeaderBgOutside};color: black;">
                                        <span class="mColorPickerTrigger input-group-addon" id="icp_color_0" style="cursor:pointer;" data-mcolorpicker="true">
                                            <img align="absmiddle" src="../img/admin/color.png">
                                        </span>
                                        <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Background Color'}
                        </label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" value="{$dorHeaderBgColor}" name="dorHeaderBgColor" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_1" style="background-color:{$dorHeaderBgColor};color: black;">
                                        <span class="mColorPickerTrigger input-group-addon" id="icp_color_1" style="cursor:pointer;" data-mcolorpicker="true">
                                            <img align="absmiddle" src="../img/admin/color.png">
                                        </span>
                                        <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Color Icon'}
                        </label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" value="{$dorHeaderColorIcon}" name="dorHeaderColorIcon" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_26" style="background-color:{$dorHeaderColorIcon};color: black;">
                                        <span class="mColorPickerTrigger input-group-addon" id="icp_color_26" style="cursor:pointer;" data-mcolorpicker="true">
                                            <img align="absmiddle" src="../img/admin/color.png">
                                        </span>
                                        <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Color Text'}
                        </label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" value="{$dorHeaderColorText}" name="dorHeaderColorText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_2" style="background-color:{$dorHeaderColorText};color: black;">
                                        <span class="mColorPickerTrigger input-group-addon" id="icp_color_2" style="cursor:pointer;" data-mcolorpicker="true">
                                            <img align="absmiddle" src="../img/admin/color.png">
                                        </span>
                                        <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Color Link'}
                        </label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" value="{$dorHeaderColorLink}" name="dorHeaderColorLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_3" style="background-color:{$dorHeaderColorLink};color: black;">
                                        <span class="mColorPickerTrigger input-group-addon" id="icp_color_3" style="cursor:pointer;" data-mcolorpicker="true">
                                            <img align="absmiddle" src="../img/admin/color.png">
                                        </span>
                                        <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Color Link Hover'}
                        </label>
                        <div class="col-lg-9 ">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" value="{$dorHeaderColorLinkHover}" name="dorHeaderColorLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_4" style="background-color:{$dorHeaderColorLinkHover};color: black;">
                                        <span class="mColorPickerTrigger input-group-addon" id="icp_color_4" style="cursor:pointer;" data-mcolorpicker="true">
                                            <img align="absmiddle" src="../img/admin/color.png">
                                        </span>
                                        <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </div>
                    
                </div>
                </div>
                <div class="panel-footer-i">
                    <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateheader" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                </div>
                         </form>
                        </div>
                    </div>
                <!-- end header -->


        <!-- footer -->
        <div class="tab-pane fade" id="footercolor">
            <div class="tab-content-ii">
                <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                
                <div id="footer-chose-theme" class="footer-class-admin hidden">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Footer Skin</span></h4>
                    <div class="box_dor clearfix">
                        <div class="col-lg-4 adm-label">{l s='Choose a skin'}</div>
                        <div class="col-lg-8 ">
                            <select id="dorFooterSkin" class="fixed-width-xl" name="dorFooterSkin">
                                <option value="">{l s='---Choose a skin---'}</option>
                                <option {if $dorFooterSkin =='footerskin1' } selected="selected" {/if} value="footerskin1">{l s='Footer Skin 1'}</option>
                                <option {if $dorFooterSkin =='footerskin2' } selected="selected" {/if} value="footerskin2">{l s='Footer Skin 2'}</option>
                                <option {if $dorFooterSkin =='footerskin3' } selected="selected" {/if} value="footerskin3">{l s='Footer Skin 3'}</option>
                            </select>
                        </div>
                    </div>
               </div>

                <div id="footer-change-color" class="footer-class-admin">
                    <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Unlimited color</span></h4>
        <div class="box_dor">
            <div class="form-group row">
                <label class="control-label col-lg-3">
                    {l s='Background Outside Color:'}
                </label>
                <div class="col-lg-9 ">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" value="{$dorFooterBgOutside}" name="dorFooterBgOutside" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_03" style="color: black;background-color: {$dorFooterBgOutside}">
                                <span class="mColorPickerTrigger input-group-addon" id="icp_color_03" style="cursor:pointer;" data-mcolorpicker="true">
                                    <img align="absmiddle" src="../img/admin/color.png">
                                </span>
                                <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-lg-3">
                    {l s='Background Color:'}
                </label>
                <div class="col-lg-9 ">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" value="{$dorFooterBgColor}" name="dorFooterBgColor" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_04" style="color: black;background-color: {$dorFooterBgColor}">
                                <span class="mColorPickerTrigger input-group-addon" id="icp_color_04" style="cursor:pointer;" data-mcolorpicker="true">
                                    <img align="absmiddle" src="../img/admin/color.png">
                                </span>
                                <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-lg-3">
                    {l s='Color Text:'}
                </label>
                <div class="col-lg-9 ">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" value="{$dorFooterColorText}" name="dorFooterColorText" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_13" style="color: black;background-color: {$dorFooterColorText}">
                                <span class="mColorPickerTrigger input-group-addon" id="icp_color_13" style="cursor:pointer;" data-mcolorpicker="true">
                                    <img align="absmiddle" src="../img/admin/color.png">
                                </span>
                                <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-lg-3">
                    {l s='Color Link:'}
                </label>
                <div class="col-lg-9 ">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" value="{$dorFooterColorLink}" name="dorFooterColorLink" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_23" style="color: black;background-color:{$dorFooterColorLink}">
                                <span class="mColorPickerTrigger input-group-addon" id="icp_color_23" style="cursor:pointer;" data-mcolorpicker="true">
                                    <img align="absmiddle" src="../img/admin/color.png">
                                </span>
                                <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-lg-3">
                    {l s='Color Link Hover:'}
                </label>
                <div class="col-lg-9 ">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" value="{$dorFooterColorLinkHover}" name="dorFooterColorLinkHover" class="color mColorPickerInput mColorPicker" data-hex="true" id="color_33" style="color: black;background-color:{$dorFooterColorLinkHover}">
                                <span class="mColorPickerTrigger input-group-addon" id="icp_color_33" style="cursor:pointer;" data-mcolorpicker="true">
                                    <img align="absmiddle" src="../img/admin/color.png">
                                </span>
                                <a href="#" onclick="return false" class="clear-bg label label-success">Clear</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
               </div>

               <div class="panel-footer-i">
                        <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateFooter" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                    </div>
        </form>
                </div>
        </div>
        <!--end footer -->
        <!-- dorAdvance -->
        <div class="tab-pane fade active in" id="dorAdvance">
            <div class="tab-content-ii">
                <form class="form-horizontal" action = "{$action}"  enctype="multipart/form-data" method="post">
                <div id="advance-category-list-style" class="advance-class-admin">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Category list</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                    <div class="data-dor-admin">
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Choose display columns'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorCategoryCols" class="fixed-width-xl" name="dorCategoryCols">
                                    <option value="">{l s='---Choose display columns---'}</option>
                                    <option {if $dorCategoryCols =='proCateCol1' } selected="selected" {/if} value="proCateCol1">{l s='1 Column'}</option>
                                    <option {if $dorCategoryCols =='proCateCol2' } selected="selected" {/if} value="proCateCol2">{l s='Left Column'}</option>
                                    <option {if $dorCategoryCols =='proCateCol3' } selected="selected" {/if} value="proCateCol3">{l s='Right Column'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Column number'}</div>
                            <div class="col-lg-8 ">
                                <select id="proCateRowNumber" class="fixed-width-xl" name="proCateRowNumber">
                                    <option {if $proCateRowNumber == 2 } selected="selected" {/if} value="2">{l s='2 Column'}</option>
                                    <option {if $proCateRowNumber == 3 || $proCateRowNumber == "" } selected="selected" {/if} value="3">{l s='3 Column'}</option>
                                    <option {if $proCateRowNumber == 4 } selected="selected" {/if} value="4">{l s='4 Column'}</option>
                                    <option {if $proCateRowNumber == 5 } selected="selected" {/if} value="5">{l s='5 Column'}</option>
                                    <option {if $proCateRowNumber == 6 } selected="selected" {/if} value="6">{l s='6 Column'}</option>
                                </select>
                                <span class="clearfix">Default: 3</span>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Show Display'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorCategoryShow" class="fixed-width-xl" name="dorCategoryShow">
                                    <option value="">{l s='---Choose Display---'}</option>
                                    <option {if $dorCategoryShow =='grid' } selected="selected" {/if} value="grid">{l s='Grid'}</option>
                                    <option {if $dorCategoryShow =='list' } selected="selected" {/if} value="list">{l s='List'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Choose effect hover'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorCategoryEffect" class="fixed-width-xl" name="dorCategoryEffect">
                                    <option value="">{l s='---Choose effect---'}</option>
                                    <option {if $dorCategoryEffect =='1' } selected="selected" {/if} value="1">{l s='Effect 1'}</option>
                                    <option {if $dorCategoryEffect =='2' } selected="selected" {/if} value="2">{l s='Effect 2'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Thumb Images'}</div>
                            <div class="col-lg-8 ">
                                <div class="form-group">
                                    <label class="control-label col-lg-2">
                                        {l s='Enable/Disable:'}
                                    </label>
                                    <div class="col-lg-3 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="dorCategoryThumb_on" {if $dorCategoryThumb ==1} checked="checked" {/if} name="dorCategoryThumb">
                                                <label for="dorCategoryThumb_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorCategoryThumb ==0} checked="checked" {/if} value="0" id="dorCategoryThumb_off" name="dorCategoryThumb">
                                                <label for="dorCategoryThumb_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>

                                </div>
                                <div class="group-cate-thumb {if $dorCategoryThumb != 1}hidden{/if}">
                                    <div class="form-group row clearfix">
                                        <div class="col-lg-2">{l s='Quanlity Image'}:</div>
                                        <div class="col-lg-3"><input type="text" value="{$dorCatQuanlity}" name="dorCatQuanlity" class="category-thumb" data-hex="true"></div>
                                    </div>
                                    <div class="form-group row clearfix">
                                        <div class="col-lg-2">{l s='Thumb Width'}:</div>
                                        <div class="col-lg-3"><input type="text" value="{$dorCatThumbWidth}" name="dorCatThumbWidth" class="category-thumb" data-hex="true"></div>
                                    </div>
                                    <div class="form-group row clearfix">
                                        <div class="col-lg-2">{l s='Thumb Height'}:</div>
                                        <div class="col-lg-3"><input type="text" value="{$dorCatThumbHeight}" name="dorCatThumbHeight" class="category-thumb" data-hex="true"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
               <div id="advance-detail-style" class="advance-class-admin">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Product Detail</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                   <div class="data-dor-admin">
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Choose display columns'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorDetailCols" class="fixed-width-xl" name="dorDetailCols">
                                    <option value="">{l s='---Choose display columns---'}</option>
                                    <option {if $dorDetailCols =='proDetailCol1' } selected="selected" {/if} value="proDetailCol1">{l s='1 Column'}</option>
                                    <option {if $dorDetailCols =='proDetailCol2' } selected="selected" {/if} value="proDetailCol2">{l s='Right Columns'}</option>
                                    <option {if $dorDetailCols =='proDetailCol3' } selected="selected" {/if} value="proDetailCol3">{l s='Left Columns'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Position main image'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorDetailMainImage" class="fixed-width-xl" name="dorDetailMainImage">
                                    <option value="">{l s='---Choose position---'}</option>
                                    <option {if $dorDetailMainImage =='left' } selected="selected" {/if} value="left">{l s='Left'}</option>
                                    <option {if $dorDetailMainImage =='right' } selected="selected" {/if} value="right">{l s='Right'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Choose display thumb lists'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorDetailThumbList" class="fixed-width-xl" name="dorDetailThumbList">
                                    <option value="">{l s='---Choose display thumb---'}</option>
                                    <option {if $dorDetailThumbList =='right' } selected="selected" {/if} value="right">{l s='Right'}</option>
                                    <option {if $dorDetailThumbList =='left' } selected="selected" {/if} value="left">{l s='Left'}</option>
                                    <option {if $dorDetailThumbList =='bottom' } selected="selected" {/if} value="bottom">{l s='Bottom'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Choose style info'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorDetailInfoStyle" class="fixed-width-xl" name="dorDetailInfoStyle">
                                    <option value="">{l s='---Choose display style---'}</option>
                                    <option {if $dorDetailInfoStyle == 1 } selected="selected" {/if} value="1">{l s='Style 1'}</option>
                                    <option {if $dorDetailInfoStyle == 2 } selected="selected" {/if} value="2">{l s='Style 2'}</option>
                                    <option {if $dorDetailInfoStyle == 3 } selected="selected" {/if} value="3">{l s='Style 3'}</option>
                                    <option {if $dorDetailInfoStyle == 4 } selected="selected" {/if} value="4">{l s='Style 4'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix hidden">
                            <div class="col-lg-4 adm-label">{l s='Options'}</div>
                            <div class="col-lg-8 dor-sub-box">
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Review:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailReview_on" {if $dorDetailReview ==1} checked="checked" {/if} name="detailReview">
                                                <label for="detailReview_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailReview ==0} checked="checked" {/if} value="0" id="detailReview_off" name="detailReview">
                                                <label for="detailReview_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Label Icon:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailLabel_on" {if $dorDetailLabel ==1} checked="checked" {/if} name="detailLabel">
                                                <label for="detailLabel_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailLabel ==0} checked="checked" {/if} value="0" id="detailLabel_off" name="detailLabel">
                                                <label for="detailLabel_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Reduction:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailReduction_on" {if $dorDetailLabel ==1} checked="checked" {/if} name="detailReduction">
                                                <label for="detailReduction_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailReduction ==0} checked="checked" {/if} value="0" id="detailReduction_off" name="detailReduction">
                                                <label for="detailReduction_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Old Price:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailOldPrice_on" {if $dorDetailOldPrice ==1} checked="checked" {/if} name="detailOldPrice">
                                                <label for="detailOldPrice_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailOldPrice ==0} checked="checked" {/if} value="0" id="detailOldPrice_off" name="detailOldPrice">
                                                <label for="detailOldPrice_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Reference:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="dorDetailReference_on" {if $dorDetailReference ==1} checked="checked" {/if} name="dorDetailReference">
                                                <label for="dorDetailReference_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailReference ==0} checked="checked" {/if} value="0" id="dorDetailReference_off" name="dorDetailReference">
                                                <label for="dorDetailReference_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Condition:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="dorDetailCondition_on" {if $dorDetailCondition ==1} checked="checked" {/if} name="dorDetailCondition">
                                                <label for="dorDetailCondition_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailCondition ==0} checked="checked" {/if} value="0" id="dorDetailCondition_off" name="dorDetailCondition">
                                                <label for="dorDetailCondition_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Quantity Available:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailpQuantityAvailable_on" {if $dorDetailpQuantityAvailable ==1} checked="checked" {/if} name="detailpQuantityAvailable">
                                                <label for="detailpQuantityAvailable_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailpQuantityAvailable ==0} checked="checked" {/if} value="0" id="detailpQuantityAvailable_off" name="detailpQuantityAvailable">
                                                <label for="detailpQuantityAvailable_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Availability Statut:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailavailability_statut_on" {if $dorDetailavailabilityStatut==1} checked="checked" {/if} name="detailavailability_statut">
                                                <label for="detailavailability_statut_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailavailabilityStatut==0} checked="checked" {/if} value="0" id="detailavailability_statut_off" name="detailavailability_statut">
                                                <label for="detailavailability_statut_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Compare Button:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailcompare_on" {if $dorDetailcompare==1} checked="checked" {/if} name="detailcompare">
                                                <label for="detailcompare_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailcompare==0} checked="checked" {/if} value="0" id="detailcompare_off" name="detailcompare">
                                                <label for="detailcompare_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Wishlist Button:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailwishlist_on" {if $dorDetailwishlist==1} checked="checked" {/if} name="detailwishlist">
                                                <label for="detailwishlist_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailwishlist==0} checked="checked" {/if} value="0" id="detailwishlist_off" name="detailwishlist">
                                                <label for="detailwishlist_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Link Block Button:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detaillinkblock_on" {if $dorDetaillinkblock==1} checked="checked" {/if} name="detaillinkblock">
                                                <label for="detaillinkblock_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetaillinkblock==0} checked="checked" {/if} value="0" id="detaillinkblock_off" name="detaillinkblock">
                                                <label for="detaillinkblock_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4 adm-label">
                                        {l s='Enable/Disable Social Sharing:'}
                                    </label>
                                    <div class="col-lg-8 ">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="detailsocialsharing_on" {if $dorDetailsocialsharing==1} checked="checked" {/if} name="detailsocialsharing">
                                                <label for="detailsocialsharing_on">{l s='Yes'}</label>
                                                    <input type="radio" {if $dorDetailsocialsharing==0} checked="checked" {/if} value="0" id="detailsocialsharing_off" name="detailsocialsharing">
                                                <label for="detailsocialsharing_off">{l s='No'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>

               <div id="advance-blogs-style" class="advance-class-admin hidden">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Blogs</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                   <div class="data-dor-admin">
                       <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Style Blog List'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorBlogsStyle" class="fixed-width-xl" name="dorBlogsStyle">
                                    <option {if $dorBlogsStyle ==1 } selected="selected" {/if} value="1">{l s='Style List 1'}</option>
                                    <option {if $dorBlogsStyle ==2 } selected="selected" {/if} value="2">{l s='Style List 2'}</option>
                                    <option {if $dorBlogsStyle ==3 } selected="selected" {/if} value="3">{l s='Masonry 2 Columns'}</option>
                                    <option {if $dorBlogsStyle ==4 } selected="selected" {/if} value="4">{l s='Masonry 3 Columns'}</option>
                                    <option {if $dorBlogsStyle ==5 } selected="selected" {/if} value="5">{l s='Masonry 4 Columns'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Columns Blog List'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorBlogsCols" class="fixed-width-xl" name="dorBlogsCols">
                                    <option value="">{l s='---Choose columns---'}</option>
                                    <option {if $dorBlogsCols =='proBlogCol1' } selected="selected" {/if} value="proBlogCol1">{l s='1 Column'}</option>
                                    <option {if $dorBlogsCols =='proBlogCol2' } selected="selected" {/if} value="proBlogCol2">{l s='Left Column'}</option>
                                    <option {if $dorBlogsCols =='proBlogCol3' } selected="selected" {/if} value="proBlogCol3">{l s='Right Column'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Style Blog Detail'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorBlogsDetailStyle" class="fixed-width-xl" name="dorBlogsDetailStyle">
                                    <option {if $dorBlogsDetailStyle ==1 } selected="selected" {/if} value="1">{l s='Style Detail 1'}</option>
                                    <option {if $dorBlogsDetailStyle ==2 } selected="selected" {/if} value="2">{l s='Style Detail 2'}</option>
                                </select>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Columns Blog Detail'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorBlogsDetailCols" class="fixed-width-xl" name="dorBlogsDetailCols">
                                    <option value="">{l s='---Choose columns---'}</option>
                                    <option {if $dorBlogsDetailCols =='proBlogDetailCol1' } selected="selected" {/if} value="proBlogDetailCol1">{l s='1 Column'}</option>
                                    <option {if $dorBlogsDetailCols =='proBlogDetailCol2' } selected="selected" {/if} value="proBlogDetailCol2">{l s='Left Column'}</option>
                                    <option {if $dorBlogsDetailCols =='proBlogDetailCol3' } selected="selected" {/if} value="proBlogDetailCol3">{l s='Right Column'}</option>
                                </select>
                            </div>
                        </div>
                    </div>
               </div>
               <div id="advance-contact-style" class="advance-class-admin hidden">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> {l s='Contact Form'}</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                   <div class="data-dor-admin">
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Contact Style'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorContactStyle" class="fixed-width-xl" name="dorContactStyle">
                                    <option {if $dorContactStyle == 1 } selected="selected" {/if} value="1">{l s='Style 1'}</option>
                                    <option {if $dorContactStyle == 2 } selected="selected" {/if} value="2">{l s='Style 2'}</option>
                                </select>
                            </div>
                        </div>
                    </div>
               </div>
               <div id="advance-subc-style" class="advance-class-admin hidden">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Show Popup Subscribe</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                   <div class="data-dor-admin">
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Select Style'}</div>
                            <div class="col-lg-8 ">
                                <select id="dorSubsPop" class="fixed-width-xl" name="dorSubsPop">
                                    <option value="0">{l s='---Disable Popup---'}</option>
                                    <option {if $dorSubsPop == 1 } selected="selected" {/if} value="1">{l s='Style 1'}</option>
                                    <option {if $dorSubsPop == 2 } selected="selected" {/if} value="2">{l s='Style 2'}</option>
                                </select>
                            </div>
                        </div>
                    </div>
               </div>
               <div id="advance-subc-style" class="advance-class-admin hidden">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> AngularJS</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                   <div class="data-dor-admin">
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Enabled/Disabled AngularJS'}</div>
                            <div class="col-lg-8 ">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" value="1" id="enableAngularJs_on" {if $enableAngularJs==1} checked="checked" {/if} name="enableAngularJs">
                                    <label for="enableAngularJs_on">{l s='Yes'}</label>
                                        <input type="radio" {if $enableAngularJs==0} checked="checked" {/if} value="0" id="enableAngularJs_off" name="enableAngularJs">
                                    <label for="enableAngularJs_off">{l s='No'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                    </div>
               </div>
               <div id="advance-cache-style" class="advance-class-admin">
                   <h4 class="name-tab"> <span><i class="icon-dot-circle-o"></i> Dor Caches</span><span class="arow-control pull-right"><i class="fa fa-plus-square"></i></span></h4>
                   <div class="data-dor-admin">
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Enabled/Disabled Dor Caches'}</div>
                            <div class="col-lg-8 ">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" value="1" id="enableDorCache_on" {if $enableDorCache==1} checked="checked" {/if} name="enableDorCache">
                                    <label for="enableDorCache_on">{l s='Yes'}</label>
                                        <input type="radio" {if $enableDorCache==0} checked="checked" {/if} value="0" id="enableDorCache_off" name="enableDorCache">
                                    <label for="enableDorCache_off">{l s='No'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Dor Time Cache'}</div>
                            <div class="col-lg-8 ">
                                <input type="text" value="{$dorTimeCache}" name="dorTimeCache" class="dorTimeCache" style="width:35%;">
                                <span class="clearfix">Time (s): EX: 1h = 1*60*60</span>
                            </div>
                        </div>
                        <div class="box_dor clearfix">
                            <div class="col-lg-4 adm-label">{l s='Clear Dor Caches'}</div>
                            <div class="col-lg-1">
                                <button class="btn btn-default btn-dorClearCacheSubmit pull-right" name="submitDorClearCache" type="submit" title="Clear Dor Cache"><i class="process-icon-save"></i></button>
                            </div>
                        </div>
                    </div>
               </div>
               <div class="panel-advance-i clearfix">
                    <button class="btn btn-default btn-dorSubmit pull-right" name="submitUpdateDorAdvance" type="submit" title="Save Configuration"><i class="process-icon-save"></i></button>
                </div>
                </form>
            </div>
        </div>
        <!--end dorAdvance -->
            </div>
        </div><!-- end  tab content -->
        </div>
    </div>
    </div>
</div>

<style type="text/css">
    .bootstrap.panel{
        text-align: center;
        float: none;
    }
</style>