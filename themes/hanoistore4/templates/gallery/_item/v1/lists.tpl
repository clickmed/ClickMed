{foreach from=$galleries item=gallery key=i}
<div class="grid__item item-gallery col-lg-4 col-md-4 col-sm-6 col-xs-12" data-size="{$gallery.imagesize}">
	<div class="item-gallery-media">
		<a href="{$gallery.image}" class="img-wrap"><img src="{$gallery.thumb_image}" alt="{$gallery.name}"></a>
		<div class="description">
			<div class="desc-gallery-item">
				<h3>{$gallery.name}</h3>
				<span>{$gallery.cate_name}</span>
			</div>
		</div>
	</div>
</div>
{/foreach}
