(function(){
	$.fn.dorCountDown = function( options ) {
	 	return this.each(function() { 
			// get instance of the dorCountDown.
			new  $.dorCountDown( this, options ); 
		});
 	 }
	$.dorCountDown = function( obj, options ){
		
		this.options = $.extend({
				autoStart: true,
				LeadingZero:true,
				DisplayFormat:"<div>%%D%% Days</div><div>%%H%% Hours</div><div>%%M%% Minutes</div><div>%%S%% Seconds</div>",
				FinishMessage:"Expired",
				CountActive:true,
				finishDate:null
		}, options || {} );
		if( this.options.finishDate == null || this.options.finishDate == '' ){
			return ;
		}
		this.timer  = null;
		this.element = obj;
		this.CountStepper = -1;
		this.CountStepper = Math.ceil(this.CountStepper);
		this.SetTimeOutPeriod = (Math.abs(this.CountStepper)-1)*1000 + 990;
		var dthen = new Date(this.options.finishDate);
		var dnow = new Date();
		if( this.CountStepper > 0 ) {
			ddiff = new Date(dnow-dthen);
		}
		else {
			 ddiff = new Date(dthen-dnow);
		}
		gsecs = Math.floor(ddiff.valueOf()/1000); 
		this.CountBack(gsecs, this);
	};
	 $.dorCountDown.fn =  $.dorCountDown.prototype;
     $.dorCountDown.fn.extend =  $.dorCountDown.extend = $.extend;
	 $.dorCountDown.fn.extend({
		calculateDate:function( secs, num1, num2 ){
			  var s = ((Math.floor(secs/num1))%num2).toString();
			  if ( this.options.LeadingZero && s.length < 2) {
					s = "0" + s;
			  }
			  return "<span>" + s + "</span>";
		},
		CountBack:function( secs, self ){
			 if (secs < 0) {
				self.element.innerHTML = '<div class="dor-labelexpired"> '+self.options.FinishMessage+"</div>";
				return;
			  }
			  clearInterval(self.timer);
			  DisplayStr = self.options.DisplayFormat.replace(/%%D%%/g, self.calculateDate( secs,86400,100000) );
			  DisplayStr = DisplayStr.replace(/%%H%%/g, self.calculateDate(secs,3600,24));
			  DisplayStr = DisplayStr.replace(/%%M%%/g, self.calculateDate(secs,60,60));
			  DisplayStr = DisplayStr.replace(/%%S%%/g, self.calculateDate(secs,1,60));
			  self.element.innerHTML = DisplayStr;
			  if (self.options.CountActive) {
			   	self.timer = null;
				self.timer = setTimeout( function(){
					self.CountBack((secs+self.CountStepper),self);			
				},( self.SetTimeOutPeriod ) );
			 }
		}
					
	})
})(jQuery)

jQuery(document).ready(function($){
	$(".owl-carousel-play .owl-carousel").each( function(){
        var config = {
            navigation : true, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            pagination : true,
            autoHeight: true,
            direction: ($('html').attr('dir') && $('html').attr('dir') == 'rtl' ? 'rtl' : 'ltr'),
            afterAction : SetOwlCarouselFirstLast,
            addClassActive : true
        };

        var owl = $(this);
        config.items = $(this).data( 'columns' );
        if ($(this).data('desktop')) {
            config.itemsDesktop = $(this).data('desktop');
        }
        if ($(this).data('desktopsmall')) {
            config.itemsDesktopSmall = $(this).data('desktopsmall');
        }
        if ($(this).data('desktopsmall')) {
            config.itemsTablet = $(this).data('tablet');
        }
        if ($(this).data('tabletsmall')) {
            config.itemsTabletSmall = $(this).data('tabletsmall');
        }
        if ($(this).data('mobile')) {
            config.itemsMobile = $(this).data('mobile');
        }
        $(this).owlCarousel( config );
        $('.left_carousel',$(this).parent()).click(function(){
              owl.trigger('owl.prev');
              return false; 
        });
        $('.right_carousel',$(this).parent()).click(function(){
            owl.trigger('owl.next');
            return false; 
        });
    });
	jQuery(".dataCountdow-slider .owl-pagination").addClass("col-lg-5 col-sm-5 col-xs-12");
	function SetOwlCarouselFirstLast(el){
        el.find(".owl-item").removeClass("first");
        el.find(".owl-item.active").first().addClass("first");

        el.find(".owl-item").removeClass("last");
        el.find(".owl-item.active").last().addClass("last");
    }
});