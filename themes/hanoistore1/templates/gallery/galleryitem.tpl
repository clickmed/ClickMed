{foreach from=$galleries item=gallery key=i}
<div class="item-gallery col-lg-4 col-md-4 col-sm-6 col-xs-12">
	<div class="item-gallery-media">
		<img src="{$gallery.thumb_image}" alt="{$gallery.name}">
	</div>
</div>
{/foreach}