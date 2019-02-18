<div class="lablistproducts  labnewsmartblog col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class='title_block'>
		<h4>
			<span>{l s='Latest News' mod='smartbloghomelatestnews'}</span>
		</h4>
	</div>
	<a class="blogviewMore" href="{smartblog::GetSmartBlogLink('smartblog')}">
		<span>{l s='View More Blogs >>' mod='smartbloghomelatestnews'}</span>
	</a>
    <div class="row">
    <div class="sdsblog-box-content">
        {if isset($view_data) AND !empty($view_data)}
            {assign var='i' value=1}
            {foreach from=$view_data item=post}
                    {assign var="options" value=null}
                    {$options.id_post = $post.id}
                    {$options.slug = $post.link_rewrite}
                    <div class="item-inner">
						<div class="item-i">
							<span class="news_module_image_holder">
								 <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"><img alt="{$post.title}" class="feat_img_small" src="{$modules_dir}smartblog/images/{$post.post_img}-home-default.jpg"></a>
								
							</span>
							
							<h2 class="labname"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title}</a></h2>
							<div class="post-info">
								<i class="fa fa-calendar-o"></i>
								<span class="date_added">{$post.date_added|truncate:10:''}</span>
								
								 {assign var="catOptions" value=null}
												{$catOptions.id_category = $id_category}
												{$catOptions.slug = $cat_link_rewrite}
											 <span>
									   {l s='Posted by ' mod='smartblog'} {if $smartshowauthor ==1}&nbsp;<i class="icon icon-user"></i><span itemprop="author">{if $smartshowauthorstyle != 0}{$firstname} {$lastname}{else}{$lastname} {$firstname}{/if}</span>&nbsp;<i class="icon icon-calendar"></i>&nbsp;<span itemprop="dateCreated">{$created|date_format}</span>{/if}&nbsp;&nbsp;<i class="icon icon-tags"></i>&nbsp;<span itemprop="articleSection"><a href="{smartblog::GetSmartBlogLink('smartblog_category',$catOptions)}">{$title_category}</a></span> &nbsp;<i class="icon icon-comments"></i>&nbsp; {if $countcomment != ''}{$countcomment}{else}{l s='0' mod='smartblog'}{/if}{l s=' Comments' mod='smartblog'}</span>
										  <a title="" style="display:none" data-itemprop="url" href="#"></a>
							  
							</div>
							<p class="short_description">
								{$post.short_description|truncate:140:'...'|escape:'htmlall':'UTF-8'}
								
							</p>
							<a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{l s='See more' mod='smartbloghomelatestnews'}"  class="r_more">{l s='See more' mod='smartbloghomelatestnews'}<i class="fa fa-angle-double-right"></i></a>
						</div>
                    </div>
                
                {$i=$i+1}
            {/foreach}
        {/if}
     </div>
    </div>
	<div class="lab_boxnp">
		<a class="prev labnewblogprev"><i class="icon-angle-left"></i></a>
		<a class="next labnewblognext"><i class="icon-angle-right"></i></a>
	</div>
</div>
{foreach from=$languages key=k item=language name="languages"}
	{if $language.iso_code == $lang_iso}
		{assign var='rtl' value=$language.is_rtl}
	{/if}
{/foreach}
<script>
    $(document).ready(function() {
	var owl = $(".sdsblog-box-content");
    owl.owlCarousel({
		autoPlay : false,
		items :3,
		itemsDesktop : [1200,3],
		itemsDesktopSmall : [991,2],
		itemsTablet: [767,2],
		itemsMobile : [480,1],
	});
	$(".labnewblognext").click(function(){
	owl.trigger('owl.next');
	})
	$(".labnewblogprev").click(function(){
	owl.trigger('owl.prev');
	})
    });
</script>