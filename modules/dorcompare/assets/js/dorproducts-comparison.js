/*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
var urlCompare = prestashop.urls.base_url+'products-compare';
var baseUrlCompare = (typeof DORCOMPARE != "undefined" && DORCOMPARE.linkModule != "")?DORCOMPARE.linkModule:urlCompare;
var comparator_max_item = (typeof DORCOMPARE != "undefined" && DORCOMPARE.maxitem > 0)?DORCOMPARE.maxitem:3;
var maxtItemTxt = "You cannot add more than "+comparator_max_item+" product(s) to the <a href='"+baseUrlCompare+"'>product comparison</a>";

$(document).ready(function(){
	$(document).on('click', '.add_to_compare, .compare_remove', function(e){
		e.preventDefault();
		if (typeof addToCompare != 'undefined'){
			var productId = parseInt($(this).attr('data-productid'));
			var productImage = jQuery(this).closest("[data-id-product='"+productId+"']").find(".product-image-container img, .product-image img").attr("src");
			var productName = jQuery(this).closest("[data-id-product='"+productId+"']").find(".product-name, .product-title").text();
			var productLink = jQuery(this).closest("[data-id-product='"+productId+"']").find(".product-name, .product-title a").attr("href");
			if(productName == "" || typeof productName == "undefined"){
				productName = jQuery(this).closest("li").find("a").text();
			}
			var product = [];
			product.image = productImage;
			product.name = productName;
			product.link = productLink;
			product.id = productId;
			addToCompare(product,productId);
		}
	});
	$(document).on('click','.dor-sidebar-compare-clear',function(e){
		jQuery(".list-compare-left ul li .compare_remove").each(function(){
			var product = [];
			var productId = parseInt($(this).attr('data-productid'));
			var productName = jQuery(this).closest("li").find("a").text();
			product.name = productName;
			if(typeof productId != "undefined"){
				addToCompare(product,productId);
			}
		});
	});
	reloadProductComparison();
	compareButtonsStatusRefresh();
	totalCompareButtons();
});

function HideShowButton(status){
	if(status == 1){
		jQuery(".actions-footer-sidebar").removeClass("compare-hide");
		jQuery(".list-compare-left ul li.empty").addClass("compare-hide");
	}else{
		jQuery(".actions-footer-sidebar").addClass("compare-hide");
		jQuery(".list-compare-left ul li.empty").removeClass("compare-hide");
	}
}


function addToCompare(product, productId)
{
	var totalValueNow = parseInt($('.bt_compare').next('.compare_product_count').val());
	var action, totalVal;
	if ($.inArray(parseInt(productId),DORCOMPARE.compared_products) === -1)
		action = 'add';
	else
		action = 'remove';

	$.ajax({
		url: prestashop.urls.base_url + 'modules/dorcompare/ajax.php?ajax=1&action=' + action + '&id_product=' + productId,
		async: true,
		cache: false,
		success: function(data) {
			if (action === 'add' && (DORCOMPARE.compared_products == null || DORCOMPARE.compared_products.length < comparator_max_item)) {
				if(DORCOMPARE.compared_products == null) DORCOMPARE.compared_products=[];
				DORCOMPARE.compared_products.push(parseInt(productId)),
				compareButtonsStatusRefresh(),
				totalVal = totalValueNow +1,
				$('.bt_compare').next('.compare_product_count').val(totalVal),
				totalValue(totalVal);
				
				setTimeout(function(){
					ShowModalCompare(product,1);
				},300);
				HideShowButton(1);
				var itemLeft = '<li><a href="'+product.link+'">'+product.name+'</a><span class="compare_remove" href="#" title="Remove" data-productid="'+product.id+'"><i class="material-icons">&#xE872;</i></span></li>';
				jQuery(".list-compare-left ul").append(itemLeft);
			}
			else if (action === 'remove') {
				DORCOMPARE.compared_products.splice($.inArray(parseInt(productId), DORCOMPARE.compared_products), 1),
				compareButtonsStatusRefresh(),
				totalVal = totalValueNow -1,
				$('.bt_compare').next('.compare_product_count').val(totalVal),
				totalValue(totalVal);
				setTimeout(function(){
					ShowModalCompare(product,-1);
				},300);
				jQuery(".list-compare-left .compare_remove[data-productid='"+productId+"']").closest("li").remove();
				var checkLeftItem = jQuery(".list-compare-left ul li").length;
				if(typeof checkLeftItem != "undefined" && checkLeftItem <= 1){
					HideShowButton(0);
				}
			}
			else
			{
				ShowModalCompare(product,0);
			}
			totalCompareButtons();
		},
		error: function(){}
	});
}

function reloadProductComparison()
{
	$(document).on('click', 'a.cmp_remove', function(e){
		e.preventDefault();
		var idProduct = parseInt($(this).data('id-product'));
		$.ajax({
			url: prestashop.urls.base_url + 'modules/dorcompare/ajax.php?ajax=1&action=remove&id_product=' + idProduct,
			async: false,
			cache: false
		});
		$('td.product-' + idProduct).fadeOut(600);

		var compare_product_list = get('compare_product_list');
		var bak = compare_product_list;
		var new_compare_product_list = [];
		compare_product_list = decodeURIComponent(compare_product_list).split('|');
		for (var i in compare_product_list)
			if (parseInt(compare_product_list[i]) != idProduct)
				new_compare_product_list.push(compare_product_list[i]);
		if (new_compare_product_list.length)
			window.location.search = window.location.search.replace(bak, new_compare_product_list.join(encodeURIComponent('|')));
	});
};

function ShowModalCompare(product,status){
	jQuery(".infoCompare").remove();
	var htmlItem = "";
	htmlItem += '<div class="infoCompare">';
		htmlItem += '<div class="infoCompareInner">';
			if(status == 1){
			htmlItem += '<h3><i class="material-icons">&#xE834;</i>Compare list updated!<span class="close-popcompare"><i class="material-icons">&#xE5C9;</i></span></h3>';
			htmlItem += '<div class="noty_text_body">';
				htmlItem += '<a class="thumbnail" href="">';
					htmlItem += '<img src="'+product.image+'">';
				htmlItem += '</a>';
				htmlItem += '<p>';
					htmlItem += 'Success: You have added product ';
					htmlItem += '<a href="'+product.link+'"><strong>'+product.name+'</strong></a>';
					htmlItem += ' to your ';
					htmlItem += '<a href="'+baseUrlCompare+'">product comparison</a>!';
				htmlItem += '</p>';
			htmlItem += '</div>';
		}else if(status == 0){
			htmlItem += '<h3><i class="material-icons">&#xE160;</i>Warning!<span class="close-popcompare"><i class="material-icons">&#xE5C9;</i></span></h3>';
			htmlItem += '<div class="noty_text_body">';
				htmlItem += maxtItemTxt;
			htmlItem += '</div>';
		}else{
			htmlItem += '<h3><i class="material-icons">&#xE834;</i>Compare list updated!<span class="close-popcompare"><i class="material-icons">&#xE5C9;</i></span></h3>';
			htmlItem += '<div class="noty_text_body red-color">';
				htmlItem += 'The product&nbsp;<strong>'+product.name+'</strong>&nbsp;has been removed from compare';
			htmlItem += '</div>';
			
		}
		htmlItem += '</div>';
	htmlItem += '</div>';
	jQuery(htmlItem).appendTo("body");
	jQuery(".close-popcompare").click(function(){
		jQuery(".infoCompare").remove();
	});
	setTimeout(function(){
		jQuery(".infoCompare").remove();
	},10000);
}

function compareButtonsStatusRefresh()
{
	$('.add_to_compare').each(function() {
		if ($.inArray(parseInt($(this).attr('data-productid')), DORCOMPARE.compared_products) !== -1)
			$(this).addClass('checked');
		else
			$(this).removeClass('checked');
	});
}

function totalCompareButtons()
{
	var totalProductsToCompare = parseInt($('.bt_compare .total-compare-val').html());
	if (typeof totalProductsToCompare !== "number" || totalProductsToCompare === 0)
		$('.bt_compare').attr("disabled",true);
	else
		$('.bt_compare').attr("disabled",false);
}

function totalValue(value)
{
	$('.bt_compare').find('.total-compare-val').html(value);
}

function get(name)
{
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.search);

	if (results == null)
		return "";
	else
		return results[1];
}
