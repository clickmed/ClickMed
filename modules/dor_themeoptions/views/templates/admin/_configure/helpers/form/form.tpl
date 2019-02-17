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
    {if $input.type == 'background_image'}
        <script type="text/javascript">
            $(document).ready(function(){
            {for $id=1 to 30}
                $('#pattern{$id}').click(function(){
                    var val = $(this).attr('id');
                    $(".cl-pattern").append('<input type=hidden  name="backgroundimg" value="'+val+'">');
                    $('.cl-image').removeClass('active');
                    $(this).addClass('active');
                });
            {/for}
            });
        </script>
        <div class="col-lg-3">
        <div class="cl-tr">
           {* <div class="cl-td-l">Background Image:</div>*}
            <div class="cl-td-bg">
                <div class="cl-pattern">
                    {for $id=1 to 30}
                        <div class="cl-image pattern{$id}" id="pattern{$id}"></div>
                    {/for}
                    <input type=hidden id='bg_img' name="backgroundimg" value="pattern1">
                </div>
            </div>
        </div>
            </div>

	{else}
		{$smarty.block.parent}
    {/if}
{/block}
