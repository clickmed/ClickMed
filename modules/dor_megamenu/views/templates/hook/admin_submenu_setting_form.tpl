
<div id="submenu_setting_form">
	<form id="submenu-form" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="id_dormegamenu" value="{$id_dormegamenu}">
		<div class="row">
			<div class="col-lg-3">
				<div class="tab-left">
					<ul class="nav nav-pills nav-stacked" role="tablist">
						{if $show_menumenu}
					    <li role="presentation" class="active"><a href="#sub-tab-megamenu" data-toggle="tab">{l s='Mega Menu' mod='dormegamenu'}</a></li>
					    {/if}
					    <li role="presentation"{if !$show_menumenu} class="active"{/if}><a href="#sub-tab-general" data-toggle="tab">{l s='General Setting' mod='dormegamenu'}</a></li>
				  	</ul>
			  	</div>
			</div>
			<div class="col-lg-9">
				<div class="tab-right">
					<div class="tab-content">
						<!-- Megamenu Tab -->
						{if $show_menumenu}
						<div id="sub-tab-megamenu" class="tab-pane in active">
							<div class="megamenu-setting-header">
								<div class="megamenu-action">
									<button class="btn btn-primary add-row" type="button">{l s='Add Row' mod='dormegamenu'}</button>
									<button class="btn btn-danger delete-row" type="button">{l s='Delete Row' mod='dormegamenu'}</button>
									<button class="btn btn-primary add-column" type="button">{l s='Add Column' mod='dormegamenu'}</button>
									<button class="btn btn-danger delete-column" type="button">{l s='Delete Column' mod='dormegamenu'}</button>
								</div>
								<div class="columncls" style="display:none;">
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label col-sm-6">{l s='Width:' mod='dormegamenu'}</label>
												<div class="col-sm-6">
													<select class="column_width" name="column_width">
														<option value="12">{l s='1/1 (100%)' mod='dormegamenu'}</option>
														<option value="6">{l s='1/2 (50%)' mod='dormegamenu'}</option>
														<option value="4">{l s='1/3 (33.3%)' mod='dormegamenu'}</option>
														<option value="3">{l s='1/4 (25%)' mod='dormegamenu'}</option>
														<option value="2">{l s='1/6 (16.6%)' mod='dormegamenu'}</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label col-sm-6">{l s='Widget:' mod='dormegamenu'}</label>
												<div class="col-sm-6">
													<select name="widget_list" class="widget_list">
														{foreach $listWidgets as $widget}
															<option value="{$widget.wkey}">{$widget.name}</option>
														{/foreach}
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<button class="btn btn-primary add-widget-column" type="button">{l s='Add Widget' mod='dormegamenu'}</button>
										</div>
									</div>
								</div>
							</div>
							<div class="megamenu-setting-content">
								<!-- Content Here -->
							</div>
							<div class="after-megamenu-setting-content">
								<div class="alert alert-info" role="alert">{l s='If you build sub megamenu, all sub menu items of this menu will not show.' mod='dormegamenu'}</div>
							</div>
							{if isset($data.params) && $data.params}
							<script type="text/javascript">
								jQuery(document).ready(function(){
									dorMegamenuForm.submenuInit({$data.params nofilter});
								});
							</script>
							{/if}
					  	</div>
					  	{/if}
					  	<!-- General Tab -->
					  	<div id="sub-tab-general" class="tab-pane {if !$show_menumenu} in active{/if}">
						  	<div class="tab-general-wrapper">
								<h4>{l s='Menu Item Settings' mod='dormegamenu'}</h4>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Target' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<select name="target">
											<option value="_self" {if $data['target'] == '_self'}selected="selected"{/if}>{l s='Self window' mod='dormegamenu'}</option>
											<option value="_blank" {if $data['target'] == '_blank'}selected="selected"{/if}>{l s='New window' mod='dormegamenu'}</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Sticky Label' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<select name="sticky_lable">
											<option value="" {if $data['sticky_lable'] == ''}selected="selected"{/if}></option>
											<option value="hot" {if $data['sticky_lable'] == 'hot'}selected="selected"{/if}>{l s='Hot' mod='dormegamenu'}</option>
											<option value="new" {if $data['sticky_lable'] == 'new'}selected="selected"{/if}>{l s='New' mod='dormegamenu'}</option>
											<option value="featured" {if $data['sticky_lable'] == 'featured'}selected="selected"{/if}>{l s='Featured' mod='dormegamenu'}</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Font Awesome Icon' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="icon_class" name="icon_class" value="{$data['icon_class']}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Addition Class' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="addition_class" name="addition_class" value="{$data['addition_class']}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Menu Background URL' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="menu_background" name="menu_background" value="{$data['menu_background']}">
									</div>
								</div>
								<h4>{l s='SubMenu Settings' mod='dormegamenu'}</h4>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Sub Menu Align' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<select name="submenu_align">
											<option value="left" {if $data['submenu_align'] == 'left'}selected="selected"{/if}>{l s='Left' mod='dormegamenu'}</option>
											<option value="right" {if $data['submenu_align'] == 'right'}selected="selected"{/if}>{l s='Right' mod='dormegamenu'}</option>
											<option value="center" {if $data['submenu_align'] == 'center'}selected="selected"{/if}>{l s='Center' mod='dormegamenu'}</option>
											<option value="fullwidth" {if $data['submenu_align'] == 'fullwidth'}selected="selected"{/if}>{l s='Fullwidth' mod='dormegamenu'}</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Sub Menu Width' mod='dormegamenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="submenu_width" name="submenu_width" value="{$data['submenu_width']}">
									</div>
								</div>
								<div class="dorclearfix"></div>
							</div>
					  	</div>
					</div>
			  	</div>
			</div>
		</div>
	</form>
</div>