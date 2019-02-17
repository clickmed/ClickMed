var DORFILTER = {
	init:function(){
		DORFILTER.RangePrice();
	},
	RangePrice:function(){

		var currency = (typeof prestashop.currency != undefined && prestashop.currency.sign != undefined && prestashop.currency.sign != null && prestashop.currency.sign != "")?prestashop.currency.sign:"$";

		var local = window.location;
		var search = local.search;
		var startPrice = parseInt(DORRANGE.price_min);
		var endPrice = parseInt(DORRANGE.price_max);
		var priceMin = startPrice;
		var priceMax = endPrice;
		if(search != ""){
			var searchArr = search.split('Price');
			if(jQuery(searchArr).length > 1){
				var valPrice = searchArr[1];
				if(valPrice != ""){
					var priceArr = valPrice.split('-');
					var i = jQuery(priceArr).length;
					var startPrice = priceArr[i-2];
					var endPrice = priceArr[i-1];
				}
			}
		}
		$( "#amount1" ).val(startPrice);
		$( "#amount2" ).val(endPrice);


		$( "#slider-range" ).slider({
	      range: true,
	      min: priceMin,
	      max: priceMax,
	      values: [ startPrice, endPrice ],
	      slide: function( event, ui ) {
	        $( "#amount" ).html( currency + ui.values[ 0 ] + " - "+currency + ui.values[ 1 ] );
			$( "#amount1" ).val(ui.values[ 0 ]);
			$( "#amount2" ).val(ui.values[ 1 ]);
	      }
	    });
	    $( "#amount" ).html( currency + $( "#slider-range" ).slider( "values", 0 ) +
	     " - "+currency + $( "#slider-range" ).slider( "values", 1 ) );

	    jQuery(document).on("click","input[name='submit_range']",function(){
	    	var local = window.location;
			var origin = local.origin;
			var pathname = local.pathname;
			var search = local.search;
			var reSearch = "";
			var priceStart = $( "#amount1" ).val();
	    	var priceEnd = $( "#amount2" ).val();
	    	var q = "Price-"+currency+"-"+priceStart+"-"+priceEnd;
			if(search != ""){
				var searchArr = search.split('Price');
				if(jQuery(searchArr).length > 1){
					reSearch = searchArr[0];
				}else{
					reSearch = search;
					q = "/"+q;
				}
				reSearch = reSearch+q;
			}else{
				reSearch = "?q="+q;
			}
	    	var urlAjax = origin+pathname+reSearch;
	    	window.location.href=urlAjax;
	    });
	    
	}
}

jQuery(document).ready(function(){
	DORFILTER.init();
});