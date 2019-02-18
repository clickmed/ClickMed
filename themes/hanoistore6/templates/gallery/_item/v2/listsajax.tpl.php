<div class="gallery-content-detail grid">
	<div class="row">
		<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php foreach ($galleries1 as $i => $gallery):?>
				<?php
					if($i <= 2){
						if ($i == 0 || $i == 1){
							$colIm ="col-lg-6 col-md-6 col-sm-6 col-xs-12";
						}elseif ($i == 2){
			    			$colIm ="col-lg-12 col-md-12 col-sm-12 col-xs-12";
						}
						require $pathRootView.'/front/_item/v2/itemajax.tpl.php';
					}
				?>
			<?php endforeach;?>
		</div>
		<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php foreach ($galleries1 as $i => $gallery):?>
				<?php
					if($i == 3){
						$colIm ="col-lg-12 col-md-12 col-sm-12 col-xs-12";
						require $pathRootView.'/front/_item/v2/itemajax.tpl.php';
					}
				?>
			<?php endforeach;?>
		</div>
		<div class="grid-group-col col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
			<?php foreach ($galleries2 as $i => $gallery):?>
				<?php
					if ($i == 0 || $i == 2){
						$colIm ="col-lg-3 col-md-3 col-sm-3 col-xs-12";
					}elseif ($i == 1){
		    			$colIm ="col-lg-6 col-md-6 col-sm-6 col-xs-12";
					}
					require $pathRootView.'/front/_item/v2/itemajax.tpl.php';
				?>
			<?php endforeach;?>
		</div>
		<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php foreach ($galleries3 as $i => $gallery):?>
				<?php
					if($i == 0){
						$colIm ="col-lg-12 col-md-12 col-sm-12 col-xs-12";
						require $pathRootView.'/front/_item/v2/itemajax.tpl.php';
					}
				?>
			<?php endforeach;?>
		</div>
		<div class="grid-group-col col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<?php foreach ($galleries3 as $i => $gallery):?>
				<?php
					if($i > 0 && $i <= 3){
						if ($i == 2 || $i == 3){
							$colIm ="col-lg-6 col-md-6 col-sm-6 col-xs-12";
						}elseif ($i == 1){
			    			$colIm ="col-lg-12 col-md-12 col-sm-12 col-xs-12";
						}
						require $pathRootView.'/front/_item/v2/itemajax.tpl.php';
					}
				?>
			<?php endforeach;?>
		</div>
	</div>
</div>
<div class="preview">
	<button class="action action--close"><i class="fa fa-times"></i><span class="text-hidden">Close</span></button>
	<div class="description description--preview"></div>
</div>