<div class="grid__item item-gallery <?php echo $colIm;?>" data-size="<?php echo $gallery['imagesize'];?>">
	<div class="item-gallery-media">
		<a href="<?php echo $gallery['image'];?>" class="img-wrap"><img src="<?php echo $gallery['thumb_image'];?>" alt="<?php echo $gallery['name'];?>"></a>
		<div class="description">
			<div class="desc-gallery-item">
				<h3><?php echo $gallery['name'];?></h3>
				<span><?php echo $gallery['cate_name'];?></span>
			</div>
		</div>
	</div>
</div>