/******************************************************
 * @package Dor Megamenu module for Prestashop 1.6.x
 * @version 1.0.0
 * @author http://www.dortheme.com, http://www.dortheme.com
 * @copyright	Copyright (C) Steptember 2015 dortheme.com <@emai:dortheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

var dorMegamenuForm = {
	options:{},
	modules_dir:'modules',
	init:function(){
		dorMegamenuForm.accordionInit();
		dorMegamenuForm.addToMenu();
		dorMegamenuForm.menuPosition();
		dorMegamenuForm.submenuSetting();
		dorMegamenuForm.editMenu();
		dorMegamenuForm.deleteMenu();
		dorMegamenuForm.addWidget();
		dorMegamenuForm.editWidgetForm();
		dorMegamenuForm.deleteWidget();
		//DorMegamenuForm.ClearStorage();
	},
	accordionInit:function(){
	    $( "#list-to-choose" ).accordion({
	    	collapsible: true,
	    	heightStyle: "content"
	    });
	},
	submenuInit:function(data){
		if (typeof data != 'undefined' && data != '') {
			$(this).removeClass('btn-primary').addClass('btn-warning').attr("disabled", true);
			$.each( data.rows, function( index, row ) {
				var rowobj = $( '<div class="row dor-row"></div>' );
				$('.megamenu-setting-content').append( rowobj );
				dorMegamenuForm.changeActiveRow( rowobj );

				$.each( row.cols, function( index_col, col ) {
					var widget_text = $('#submenu-form .widget_list option[value='+ col.widget_key +']').text();

					var column = $( '<div class="col-sm-'+ col.col_width +' dor-column" data-col_width="'+ col.col_width +'" data-widget_key="'+ col.widget_key +'"></div>' );
					column.append('<div class="column-wrapper"></div>');
					column.find('.column-wrapper').html( '<span class="widget_text">' + widget_text + '</span>' );

					rowobj.append( column );
					dorMegamenuForm.changeActiveColumn( column );
				});
			});
			$('#save-button').removeClass('btn-warning').addClass('btn-primary').attr("disabled", false);
		}
	},
	submenuSetting:function(){
		$('body').on('click', '.menu-item-bar .sub-setting', function(){
			var id_menu = $(this).data('value');
			$("#menuModalLabel").text( submenu_title );
			dorMegamenuForm.addClassSaveButton('save-submenu');
			$(this).addClass('loading').attr("disabled", true);
			$.ajax({
	            url: ajaxurl,
	            type: 'POST',
	            dataType: 'html',
	            data:  'action=editSubMenu&id_menu=' + id_menu + '&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	$("#menuModal .modal-body").html( reponse );
        		$('.menu-item-bar .sub-setting').removeClass('loading').attr("disabled", false);
	        	$('#menuModal').modal();
	        });
		});

		// add row
		$('body').on('click', '#submenu-form .add-row', function(){
			var row = $( '<div class="row dor-row"></div>' );
			$('.megamenu-setting-content').append( row );
			dorMegamenuForm.changeActiveRow( row );
		});
		// change active row
		$('body').on('click', '#submenu-form .dor-row', function(){
			dorMegamenuForm.changeActiveRow( $(this) );
			return false;
		});
		// delete row
		$('body').on('click', '#submenu-form .delete-row', function(){
			var row = dorMegamenuForm.getCurrentActiveRow();
			if (row == false) {
				alert( selectrow_text );
			} else {
				row.remove();
			}
			return false;
		});
		// Add Column
		$('body').on('click', '#submenu-form .add-column', function(){
			var row = dorMegamenuForm.getCurrentActiveRow();
			if (row == false) {
				alert( selectrow_text );
			} else {
				var column = $( '<div class="col-sm-4 dor-column" data-col_width="4"></div>' );
				var column_wrapper = column.append('<div class="column-wrapper"></div>');

				row.append( column );
				dorMegamenuForm.changeActiveColumn( column );
			}
			return false;
		});
		// change active column
		$('body').on('click', '#submenu-form .dor-column', function(){
			dorMegamenuForm.changeActiveColumn( $(this) );
			
			return false;
		});
		// delete column
		$('body').on('click', '#submenu-form .delete-column', function(){
			var column = dorMegamenuForm.getCurrentActiveColumn();
			if (column == false) {
				alert( selectcolumn_text );
			} else {
				column.remove();
			}
			return false;
		});
		// change column width
		$('body').on('change', '#submenu-form .column_width', function(){
			var nclass = $(this).val();
			var column = dorMegamenuForm.getCurrentActiveColumn();
			if (column == false) {
				alert( selectcolumn_text );
			} else {
				column.removeClass (function (index, css) {
				    return (css.match (/\bcol-sm-\S+/g) || []).join(' ');
				}).addClass('col-sm-' + nclass);

				column.data( 'col_width', nclass);
			}
			return false;
		});
		// add a widget to column
		$('body').on('click', '#submenu-form .add-widget-column', function(){
			var widget_key = $('#submenu-form .widget_list').val();
			var widget_text = $('#submenu-form .widget_list :selected').text();
			var column = dorMegamenuForm.getCurrentActiveColumn();
			if (column == false) {
				alert( selectcolumn_text );
			} else {
				column.find('.column-wrapper').html( '<span class="widget_text">' + widget_text + '</span>' );
				column.data( 'widget_key', widget_key);
			}
			return false;
		});
		// save data
		$('body').on('click', '#save-button.save-submenu', function(){
			if ($('#submenu-form .megamenu-setting-content .dor-row').length > 0) {
				var data = new Object();
				data.rows = new Array();
				$('#submenu-form .megamenu-setting-content .dor-row').each(function(){
					var row =  new Object();
					row.cols = new Array();
					$(this).children(".dor-column" ).each( function(){
		 				row.cols.push( $(this).data() );
		 			} );
		 			data.rows.push(row);
				});
				var j = JSON.stringify( data ); 
	 	 	 	var params = 'params=' + j;
	 	 	 } else {
	 	 	 	var params = '';
	 	 	 }
 	 	 	var serialized = $('#submenu-form').serialize();
 	 	 	$(this).removeClass('btn-primary').addClass('btn-warning').attr("disabled", true);
 	 	 	$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data : serialized + '&' + params + '&action=updateSubMenu&secure_key=' + secure_key + '&id_shop=' + id_shop
			}).done( function ( reponse ) {
				if (reponse.status == 'ok') {
		            var type = 'success';
		        } else {
		        	var type = 'error';
		        }
		        $('#save-button').removeClass('btn-warning').addClass('btn-primary').attr("disabled", false);
		        var html = '<div class="alert msg alert-'+ type +'">' + reponse.msg + '</div>';
				$('#submenu_setting_form .tab-content').prepend(html);
				setTimeout(function(){
					$('#submenu_setting_form .tab-content .msg').hide(800);
				}, 3000);
			});

		});

	},
	getCurrentActiveRow:function(){
		var current = $('.dor-row.active');
		if (typeof current.attr('class') == 'undefined') {
			return false;
		}
		return current;
	},
	getCurrentActiveColumn:function(){
		var current = $('.dor-column.active');
		if (typeof current.attr('class') == 'undefined') {
			return false;
		}
		return current;
	},
	changeActiveRow:function(current){
		$('.dor-row').removeClass('active');
		$('.dor-row .dor-column').removeClass('active');

		current.addClass('active');
	},
	changeActiveColumn:function(current){
		$('.dor-row .dor-column').removeClass('active');
		var current_row = current.parent();
		dorMegamenuForm.changeActiveRow( current_row );
		current.addClass('active');
		$('#submenu-form .column_width').val( current.data('col_width') ).change();

		$('.megamenu-setting-header .columncls').show();
	},
	editMenu:function(){
		$('body').on('click', '#menu-form-list .menu-item-bar .quickedit', function(){
			var id_menu = $(this).data('value');
			$('#menuModalLabel').text( editmenu_title );
			dorMegamenuForm.addClassSaveButton('save-button');
			$.ajax({
	            url: adminajaxurl,
	            type: 'POST',
	            dataType: 'html',
	            data:  'action=editMenu&id_menu=' + id_menu + '&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	$("#menuModal .modal-body").html( reponse );
	        	$('#menuModal').modal();
	        });

		});

		$('body').on('click', '#save-button.save-button', function(){
			var serialized = $('form#configuration_form').serialize();
			$(this).removeClass('btn-primary').addClass('btn-warning').attr("disabled", true);
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data : serialized + '&action=updateMenu&secure_key=' + secure_key + '&id_shop=' + id_shop
			}).done( function ( reponse ) {
				if (reponse.status == 'ok') {
		            $('.menu-form-list-wrapper').html(reponse.html);
		            dorMegamenuForm.displayMsg('success', reponse.msg);
		            $('#menuModal').modal('toggle');
		            dorMegamenuForm.addClassSaveButton('');

		            dorMegamenuForm.menuPosition();
		        } else {
		        	dorMegamenuForm.displayMsg('error', reponse.msg);
		        }
		        $('#save-button').removeClass('btn-warning').addClass('btn-primary').attr("disabled", false);
			});
		});
	},
	deleteMenu:function(){
		$('body').on('click', '#menu-form-list .menu-item-bar .quickdel', function(){
			var id_menu = $(this).data('value');
			var verify = confirm( delete_text );
			if (verify == true) {
				$.ajax({
		            url: ajaxurl,
		            type: 'POST',
		            dataType: 'json',
		            data:  'action=deleteMenu&id_menu=' + id_menu + '&secure_key=' + secure_key + '&id_shop=' + id_shop
		        }).done( function ( reponse ) {
		        	if (reponse.status == 'ok') {
			            $('.menu-form-list-wrapper').html(reponse.html);
			            dorMegamenuForm.displayMsg('success', reponse.msg);
			            dorMegamenuForm.menuPosition();
			        } else {
			        	dorMegamenuForm.displayMsg('error', reponse.msg);
			        }
		        });
		    }
		});
	},
	menuPosition:function(){
		// sortable
		if($('ol').hasClass("sortable")) {
			$('ol.sortable').nestedSortable({
				forcePlaceholderSize: true,
				handle: 'div',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: 4,
				isTree: true,
				expandOnHover: 700,
				startCollapsed: true
			});
		}

		$('.save-menu-position').click(function(){
			var serialized = $('ol.sortable').nestedSortable('serialize');
		 	var text = $(this).val();
		 	var $this  = $(this);

		 	$(this).removeClass('btn-primary').addClass('btn-warning').attr("disabled", true);
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data : serialized + '&action=updatePosition&secure_key=' + secure_key + '&id_shop=' + id_shop
			}).done( function (reponse) {
				dorMegamenuForm.displayMsg(reponse.status, reponse.msg);
				$('.save-menu-position').removeClass('btn-warning').addClass('btn-primary').attr("disabled", false);
			} );
			return false;
		});
	},
	addToMenu:function(){
		$('.select-all').click(function(){
			var type = $(this).data('type');
			if ($(this).hasClass('checked')){
				$('.add-' + type).find('input[type=checkbox]').prop("checked", false);
				$(this).removeClass('checked');
			} else {
				$('.add-' + type).find('input[type=checkbox]').prop("checked", true);
				$(this).addClass('checked');
			}
			return false;
		});

		$('.add-to-menu').click(function(){
			var values = '';
			var type = $(this).data('type');
			var custom_name = '';
			var custom_link = '';
			if (type == 'custom-link') {
				custom_name = $('.add-' + type).find('input.custom_name').val();
				custom_link = $('.add-' + type).find('input.custom_link').val();
				if (custom_name == '' || custom_link == '') {
					$('.add-' + type).find('input.custom_name').addClass('error');
					$('.add-' + type).find('input.custom_link').addClass('error');
					return false;
				}
			} else if (type == 'product') {
				values = $('.add-' + type).find('input.id_product').val();
				if (values == '') {
					$('.add-' + type).find('input.id_product').addClass('error');
				}
				if (values == '' ) {
					return false;
				}
			} else {
				$('.add-' + type).find('input[type=checkbox]:checked').each(function(){
					values = values + ',' + $(this).val();
				});
				if (values == '' ) {
					return false;
				}
			}
			$(this).removeClass('btn-default').addClass('btn-warning').attr("disabled", true);

			$.ajax({
	            url: ajaxurl,
	            type: 'POST',
	            dataType: 'json',
	            data:  'action=addMenu&values=' + values + '&type=' + type + '&custom_name=' + custom_name + '&custom_link=' + custom_link + '&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	if (reponse.status == 'ok') {
		            $('.menu-form-list-wrapper').html(reponse.html);
		            dorMegamenuForm.displayMsg('success', reponse.msg);
		            dorMegamenuForm.menuPosition();
		            $('#list-to-choose input[type=checkbox]').prop( "checked", false );
		            $('#list-to-choose input[type=text]').val('');
		        } else {
		        	dorMegamenuForm.displayMsg('error', reponse.msg);
		        }
		        $('#list-to-choose button').removeClass('btn-warning').addClass('btn-default');
		        $('#list-to-choose button').removeAttr("disabled");

		        $('#list-to-choose').find('input.custom_name').removeClass('error');
				$('#list-to-choose').find('input.custom_link').removeClass('error');
				$('#list-to-choose').find('input.id_product').removeClass('error');
	        });
		});
	},
	displayMsg:function(type, msg){
		var html = '<div class="alert msg alert-'+ type +'">' + msg + '</div>';
		
		$('#menu-form-list').prepend(html);

		setTimeout(function(){
			$('#menu-form-list .msg').hide(800);
		}, 3000);
	},
	addWidget:function(){
		// widget list
		$('button.add-widget').click(function(){
			var id_menu = $(this).data('value');
			$('#menuModalLabel').text( addwidget_title );
			$.ajax({
	            url: ajaxurl,
	            type: 'POST',
	            dataType: 'html',
	            data: 'action=listWidget&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	$("#menuModal .modal-body").html( reponse );
	        	$('#menuModal').modal();
	        });
		});
		// widget form
		$('body').on('click', '#menuModal .widget-item', function(){
			var widget_type = $(this).data('widget-type');
			$('#menuModalLabel').text( formwidget_title );
			dorMegamenuForm.addClassSaveButton('save-widget');

			$.ajax({
	            url: adminajaxurl,
	            type: 'POST',
	            dataType: 'html',
	            data: 'action=widgetForm&widget_type=' + widget_type + '&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	$("#menuModal .modal-body").html( reponse );
	        });
		});
		// save widget
		$('body').on('click', '#save-button.save-widget', function(){
			tinyMCE.triggerSave();
			var serialized = $('#configuration_form').serialize();

			$.ajax({
	            url: ajaxurl,
	            type: 'POST',
	            dataType: 'json',
	            data: serialized + '&action=addWidget&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	if (reponse.status == 'ok') {
		            $('.widget-list-items').html(reponse.html);
		            dorMegamenuForm.displayWidgetMsg('success', reponse.msg);
		            $('#menuModal').modal('toggle');
		            dorMegamenuForm.addClassSaveButton('');
		        } else {
		        	dorMegamenuForm.displayWidgetMsg('error', reponse.msg);
		        }
	        });
		});
	},
	editWidgetForm:function(){
		$('body').on('click', '.widget-list-items .menu-item-bar .quickedit', function(){
			var id = $(this).data('value');
			$('#menuModalLabel').text( formwidget_title );
			dorMegamenuForm.addClassSaveButton('save-widget');

			$.ajax({
	            url: adminajaxurl,
	            type: 'POST',
	            dataType: 'html',
	            data:  'action=widgetForm&id=' + id + '&secure_key=' + secure_key + '&id_shop=' + id_shop
	        }).done(function(reponse) {
	        	$("#menuModal .modal-body").html( reponse );
	        	$('#menuModal').modal();
	        });

		});
	},
	deleteWidget:function(){
		$('body').on('click', '.widget-list-items .menu-item-bar .quickdel', function(){
			var id = $(this).data('value');
			var verify = confirm( deletewidget_text );
			if (verify == true) {
				$.ajax({
		            url: ajaxurl,
		            type: 'POST',
		            dataType: 'json',
		            data:  'action=deleteWidget&id=' + id + '&secure_key=' + secure_key + '&id_shop=' + id_shop
		        }).done( function ( reponse ) {
		        	if (reponse.status == 'ok') {
			            $('.widget-list-items').html(reponse.html);
			            dorMegamenuForm.displayWidgetMsg('success', reponse.msg);
			        } else {
			        	dorMegamenuForm.displayWidgetMsg('error', reponse.msg);
			        }
		        });
		    }
		});
	},
	displayWidgetMsg:function(type, msg){
		var html = '<div class="alert msg alert-'+ type +'">' + msg + '</div>';
		
		$('.widget-list-items').prepend(html);

		setTimeout(function(){
			$('.widget-list-items .msg').hide(800);
		}, 3000);
	},
	addClassSaveButton:function(nclass){
		$('#save-button').removeClass('save-submenu').removeClass('save-button').removeClass('save-widget').addClass(nclass);
	}
}

$(document).ready(function($){
	dorMegamenuForm.init();
});