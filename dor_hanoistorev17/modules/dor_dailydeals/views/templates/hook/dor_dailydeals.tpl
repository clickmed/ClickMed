<div class="block products_block owl-carousel-play dataCountdow-slider wow fadeInUp" data-wow-delay="200ms">
	<div class="block_content">
		
		<div class="owl-carousel" data-columns="{$columns|escape:'html':'UTF-8'}" data-pagination="true" data-navigation="true"
			data-desktop="[1200,{$columns|escape:'html':'UTF-8'}]"
			data-desktopsmall="[992,{$columns|escape:'html':'UTF-8'}]"
			data-tablet="[768,{$columns|escape:'html':'UTF-8'}]"
			data-mobile="[480,1]">
			{foreach from=$products item=product name=name_product}
				<div class="item ajax_block_product">
					{include file="{$product_path}"}
				</div>
			{/foreach}
		</div>
		{if count($products) > $columns}
	 	<div class="carousel-controls hidden">
		 	<a class="left carousel-control left_carousel" href="#"><i class="fa fa-angle-left"></i></a>
			<a class="right carousel-control right_carousel" href="#"><i class="fa fa-angle-right"></i></a>
		</div>
		{/if}
	</div>
</div>
