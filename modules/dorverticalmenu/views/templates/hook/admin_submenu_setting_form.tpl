
<div id="submenu_setting_form">
	<form id="submenu-form" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="id_dorverticalmenu" value="{$id_dorverticalmenu}">
		<div class="row">
			<div class="col-lg-3">
				<div class="tab-left">
					<ul class="nav nav-pills nav-stacked" role="tablist">
						{if $show_menumenu}
					    <li role="presentation" class="active"><a href="#sub-tab-verticalmenu" data-toggle="tab">{l s='Mega Menu' mod='dorverticalmenu'}</a></li>
					    {/if}
					    <li role="presentation"{if !$show_menumenu} class="active"{/if}><a href="#sub-tab-general" data-toggle="tab">{l s='General Setting' mod='dorverticalmenu'}</a></li>
				  	</ul>
			  	</div>
			</div>
			<div class="col-lg-9">
				<div class="tab-right">
					<div class="tab-content">
						<!-- Verticalmenu Tab -->
						{if $show_menumenu}
						<div id="sub-tab-verticalmenu" class="tab-pane in active">
							<div class="verticalmenu-setting-header">
								<div class="verticalmenu-action">
									<button class="btn btn-primary add-row" type="button">{l s='Add Row' mod='dorverticalmenu'}</button>
									<button class="btn btn-danger delete-row" type="button">{l s='Delete Row' mod='dorverticalmenu'}</button>
									<button class="btn btn-primary add-column" type="button">{l s='Add Column' mod='dorverticalmenu'}</button>
									<button class="btn btn-danger delete-column" type="button">{l s='Delete Column' mod='dorverticalmenu'}</button>
								</div>
								<div class="columncls" style="display:none;">
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label col-sm-6">{l s='Width:' mod='dorverticalmenu'}</label>
												<div class="col-sm-6">
													<select class="column_width" name="column_width">
														<option value="12">{l s='1/1 (100%)' mod='dorverticalmenu'}</option>
														<option value="6">{l s='1/2 (50%)' mod='dorverticalmenu'}</option>
														<option value="4">{l s='1/3 (33.3%)' mod='dorverticalmenu'}</option>
														<option value="3">{l s='1/4 (25%)' mod='dorverticalmenu'}</option>
														<option value="2">{l s='1/6 (16.6%)' mod='dorverticalmenu'}</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label col-sm-6">{l s='Widget:' mod='dorverticalmenu'}</label>
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
											<button class="btn btn-primary add-widget-column" type="button">{l s='Add Widget' mod='dorverticalmenu'}</button>
										</div>
									</div>
								</div>
							</div>
							<div class="verticalmenu-setting-content">
								<!-- Content Here -->
							</div>
							<div class="after-verticalmenu-setting-content">
								<div class="alert alert-info" role="alert">{l s='If you build sub verticalmenu, all sub menu items of this menu will not show.' mod='dorverticalmenu'}</div>
							</div>
							{if isset($data.params) && $data.params}
							<script type="text/javascript">
								jQuery(document).ready(function(){
									dorVerticalmenuForm.submenuInit({$data.params nofilter});
								});
							</script>
							{/if}
					  	</div>
					  	{/if}
					  	<!-- General Tab -->
					  	<div id="sub-tab-general" class="tab-pane {if !$show_menumenu} in active{/if}">
						  	<div class="tab-general-wrapper">
								<h4>{l s='Menu Item Settings' mod='dorverticalmenu'}</h4>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Target' mod='dorverticalmenu'}</label>
									<div class="col-sm-9">
										<select name="target">
											<option value="_self" {if $data['target'] == '_self'}selected="selected"{/if}>{l s='Self window' mod='dorverticalmenu'}</option>
											<option value="_blank" {if $data['target'] == '_blank'}selected="selected"{/if}>{l s='New window' mod='dorverticalmenu'}</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Sticky Label' mod='dorverticalmenu'}</label>
									<div class="col-sm-9">
										<select name="sticky_lable">
											<option value="" {if $data['sticky_lable'] == ''}selected="selected"{/if}></option>
											<option value="hot" {if $data['sticky_lable'] == 'hot'}selected="selected"{/if}>{l s='Hot' mod='dorverticalmenu'}</option>
											<option value="new" {if $data['sticky_lable'] == 'new'}selected="selected"{/if}>{l s='New' mod='dorverticalmenu'}</option>
											<option value="featured" {if $data['sticky_lable'] == 'featured'}selected="selected"{/if}>{l s='Featured' mod='dorverticalmenu'}</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Font Awesome Icon' mod='dorverticalmenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="icon_class" name="icon_class" value="{$data['icon_class']}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Addition Class' mod='dorverticalmenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="addition_class" name="addition_class" value="{$data['addition_class']}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Background' mod='dorverticalmenu'}</label>
									<div class="col-sm-9">
										<input type="text" class="background_url" name="background_url" value="{$data['background_url']}">
									</div>
								</div>
								<h4>{l s='SubMenu Settings' mod='dorverticalmenu'}</h4>
								<div class="form-group">
									<label class="control-label col-sm-3">{l s='Sub Menu Width' mod='dorverticalmenu'}</label>
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