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

{extends file="helpers/form/form.tpl"}


{block name="input"}
    {if $input.type == 'link_choice'}
        <script type="text/javascript">
            $(document).ready(function(){
                $('#menuOrderUp').click(function(e){
                    e.preventDefault();
                    move(true);
                });
                $('#menuOrderDown').click(function(e){
                    e.preventDefault();
                    move();
                });
                $("#items").closest('form').on('submit', function(e) {
                    $("#items option").prop('selected', true);
                });
                $("#addItem").click(add);
                $("#availableItems").dblclick(add);
                $("#removeItem").click(remove);
                $("#items").dblclick(remove);
                function add()
                {
                    $("#availableItems option:selected").each(function(i){
                        var val = $(this).val();
                        var text = $(this).text();
                        text = text.replace(/(^\s*)|(\s*$)/gi,"");
                        if (val == "PRODUCT")
                        {
                            val = prompt('{l s="Indicate the ID number for the product" mod='labvegamenu' js=1}');
                            if (val == null || val == "" || isNaN(val))
                                return;
                            text = '{l s="Product ID #" mod='labvegamenu' js=1}'+val;
                            val = "PRD"+val;
                        }

                        $("#items").append('<option value="'+val+'" selected="selected">'+text+'</option>');
                    });
                    serialize();
                    return false;
                }
                function remove()
                {
                    $("#items option:selected").each(function(i){
                        $(this).remove();
                    });
                    serialize();
                    return false;
                }
                function serialize()
                {
                    var options = "";
                    $("#items option").each(function(i){
                        options += $(this).val()+",";
                    });
                    $("#itemsInput").val(options.substr(0, options.length - 1));
                }
                function move(up)
                {
                    var tomove = $('#items option:selected');
                    if (tomove.length >1)
                    {
                        alert('{l s="Please select just one item" mod='labvegamenu'}');
                        return false;
                    }
                    if (up)
                        tomove.prev().insertAfter(tomove);
                    else
                        tomove.next().insertBefore(tomove);
                    serialize();
                    return false;
                }
            });

        </script>
	    <div class="row">
	    	<div class="col-lg-1">
	    		<h4 style="margin-top:5px;">{l s='Change position' mod='dor_tablistcategory'}</h4>
                <a href="#" id="menuOrderUp" class="btn btn-default" style="font-size:20px;display:block;"><i class="icon-chevron-up"></i></a><br/>
                <a href="#" id="menuOrderDown" class="btn btn-default" style="font-size:20px;display:block;"><i class="icon-chevron-down"></i></a><br/>
	    	</div>
	    	<div class="col-lg-4">
	    		<h4 style="margin-top:5px;">{l s='Selected items' mod='dor_tablistcategory'}</h4>
	    		{$selected_links}
	    	</div>
	    	<div class="col-lg-4">
	    		<h4 style="margin-top:5px;">{l s='Available items' mod='dor_tablistcategory'}</h4>
	    		{$choices}
	    	</div>
	    	
	    </div>
	    <br/>
	    <div class="row">
	    	<div class="col-lg-1"></div>
	    	<div class="col-lg-4"><a href="#" id="removeItem" class="btn btn-default"><i class="icon-arrow-right"></i> {l s='Remove' mod='dor_tablistcategory'}</a></div>
	    	<div class="col-lg-4"><a href="#" id="addItem" class="btn btn-default"><i class="icon-arrow-left"></i> {l s='Add' mod='dor_tablistcategory'}</a></div>
	    </div>
    {/if}
	{if $input.type =='listnew'}
        <div class="row">
            <div class="col-lg-6">
                <select id="list_cate_tab" class=" fixed-width-xl" multiple="multiple" name ="cate_data[]">
                    {$cate_data}
                </select>
            </div>
        </div>

    {/if}
		{$smarty.block.parent}
{/block}
