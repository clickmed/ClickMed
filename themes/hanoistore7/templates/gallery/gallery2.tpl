{extends file='page.tpl'}
{block name='page_header_container'}{/block}
{block name='page_content'}
{capture name=path}{l s='Gallery'}{/capture}
<h1 class="h1 hidden">{l s="Gallery"}</h1>
<div id="dor-gallery-base" class="dorgallery-base-2">
	<div class="dor-gallery-wrapper">
		<div class="dor-gallery-content">
			<div class="dor-gallery-inner">
				<ul class="header-tab-gallery nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#gallery-0" aria-controls="gallery-0" role="tab" data-toggle="tab">{l s="All"}</a></li>
				    {foreach from=$categories item=category key=i}
				    <li role="presentation"><a href="#gallery-{$category.id_dorgallery_category}" aria-controls="gallery-{$category.id_dorgallery_category}" role="tab" data-toggle="tab">{$category.name}</a></li>
				    {/foreach}
				</ul>
				<div id="list-gallery-show-items" class="tab-content clearfix">
				    <div role="tabpanel" class="tab-pane active" id="gallery-0">
				    	<div class="data-gallery">
					    	<div class="gallery-content-detail grid">
					    		{if $tabdefaultID == 0}
					    			{include file='gallery/_item/v2/lists.tpl'}
					    		{/if}
					    	</div>
					    	<div class="preview">
								<button class="action action--close"><i class="fa fa-times"></i><span class="text-hidden">Close</span></button>
								<div class="description description--preview"></div>
							</div>
						</div>
						{if $totalGalleryPerPage >= $limit}
						<div class="dor-gallery-page"><a href="#" rel="{$current}">{l s="Load more" mod="dorgallery"}</a></div>
				    	{/if}
				    </div>
				    {foreach from=$categories item=category key=j}
				    <div role="tabpanel" class="tab-pane" id="gallery-{$category.id_dorgallery_category}">
				    	<div class="data-gallery">
					    	<div class="gallery-content-detail grid">
					    		{if $tabdefaultID == $category.id_dorgallery_category}
						    		{include file='gallery/_item/v2/lists.tpl'}
					    		{/if}
					    	</div>
					    	<div class="preview">
								<button class="action action--close"><i class="fa fa-times"></i><span class="text-hidden">Close</span></button>
								<div class="description description--preview"></div>
							</div>
						</div>
						{if $tabdefaultID == $category.id_dorgallery_category}
							{if $totalGalleryPerPage >= $limit}
							<div class="dor-gallery-page"><a href="#" rel="{$current}">{l s="Load more" mod="dorgallery"}</a></div>
					    	{/if}
				    	{/if}
				    </div>
				    {/foreach}
				</div>
				<input type="hidden" id="versionGallery" value="gallery-v2">
			</div>
		</div>
	</div>
</div>
{/block}