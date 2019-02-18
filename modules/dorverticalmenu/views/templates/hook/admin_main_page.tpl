{*
* Manager and display verticalmenu use bootstrap framework
*
* @package   dorverticalmenu
* @version   1.0.0
* @author    http://www.doradothemes@gmail.com
* @copyright Copyright (C) December 2015 doradothemes@gmail.com <@emai:doradothemes@gmail.com>
*               <info@doradothemes@gmail.com>.All rights reserved.
* @license   GNU General Public License version 2
*}

<div id="page-content">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#verticalmenu-manager">{l s='VerticalMenu Manager' mod='dorverticalmenu'}</a></li>
        <li><a data-toggle="tab" href="#widgets-manager">{l s='Widgets Manager' mod='dorverticalmenu'}</a></li>
    </ul>
    <br>
    <div class="tab-content">
        <div id="verticalmenu-manager" class="tab-pane fade in active">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <form id="choice-menu-form" method="post" action="" enctype="multipart/form-data">
                        {$html_choices_select}
                    </form>
                </div>
                <div class="col-md-8 col-sm-12">

                    <form id="menu-form" method="post" action="" enctype="multipart/form-data">
                        <h4>{l s='Menu Structure' mod='dorverticalmenu'}</h4>
                        <p>{l s='Drag each item into the order you prefer. Click the action buttion on the right of the item to edit or delete menu. Click submenu settings to setting sub verticalmenu.' mod='dorverticalmenu'}</p>
                        <div id="menu-form-list">
                            <div class="menu-form-list-wrapper">
                                {$list_menu}
                            </div>
                            <div class="clearfix"></div>
                            <button class="btn btn-primary save-menu-position" type="button">{l s='Update Position' mod='dorverticalmenu'}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div id="widgets-manager" class="tab-pane fade in">
            <form id="main-widget-form" method="post" action="" enctype="multipart/form-data">
                <button class="btn btn-primary add-widget" type="button">{l s='Add Widget' mod='dorverticalmenu'}</button>
                <div class="widget-list-items">
                    {$list_widgets}
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="menuModalLabel">{l s='Setting Sub Verticalmenu' mod='dorverticalmenu'}</h4>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='dorverticalmenu'}</button>
                    <button type="button" class="btn btn-primary" id="save-button">{l s='Save' mod='dorverticalmenu'}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var id_shop = {$id_shop};
    var ajaxurl = '{$ajaxurl}';
    var submenu_title = "{l s='Setting Sub Verticalmenu' mod='dorverticalmenu'}";
    var editmenu_title = "{l s='Edit Menu' mod='dorverticalmenu'}";
    var adminajaxurl = "{$adminajaxurl}";
    var secure_key = "{$secure_key}";
    var delete_text = "{l s='Do you want to delete this menu and all Sub menus?' mod='dorverticalmenu'}";
    var addwidget_title = "{l s='List Widgets' mod='dorverticalmenu'}";
    var formwidget_title = "{l s='Widget Form' mod='dorverticalmenu'}";
    var deletewidget_text = "{l s='Do you want to delete this widget?' mod='dorverticalmenu'}";
    var selectrow_text = "{l s='Please select a row' mod='dorverticalmenu'}";
    var selectcolumn_text = "{l s='Please select a column' mod='dorverticalmenu'}";
    var addingwidget_text = "{l s='Loading ...' mod='dorverticalmenu'}";
</script>