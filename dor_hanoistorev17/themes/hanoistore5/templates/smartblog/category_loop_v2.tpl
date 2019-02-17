{assign var="options" value=null}
{$options.id_post = $post.id_post}
{$options.slug = $post.link_rewrite}
{if $i == 0}
<div class="single-blog">
    <div class="blog-image">
        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.meta_title}">
            <img src="{$post.thumb_image}" alt="Blog">
        </a>
    </div>
    <div class="blog-content">
        <div class="blog-info">
            <span class="blog-date">
              <i class="fa fa-clock-o" aria-hidden="true"></i>
               <a href="#">
                    <span class="month-date"><small>{$post.created|date_format:"%B %d, %Y"}</small></span>
               </a>
           </span>
           <span class="comments-number hidden">
                <i class="fa fa-comment"></i>{$post.totalcomment} {l s="Comment"}
            </span>
            {if $smartshowauthor ==1}
            <span class="author-name">
              <i class="fa fa-user-circle-o" aria-hidden="true"></i>{l s="By"} 
                <a rel="author" href="#">
                    {if $smartshowauthorstyle != 0}
                      {$post.firstname} {$post.lastname}
                    {else}
                      {$post.lastname} {$post.firstname}
                    {/if}
                </a>
            </span>
            {/if}
        </div>
        <div class="title-desc">
            <a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'><h4>{$post.meta_title}</h4></a>
            <p>{$post.short_description|strip_tags:'UTF-8'|truncate:550:'...'}</p>
        </div>
        <div class="blog-list-footer clearfix">
          <a href="#" class="readmore pull-left">{l s="Read More"}</a>
          <div class="share-blog-widget pull-right">
            <span class="blog-icon-share"><i class="fa fa-share-alt" aria-hidden="true"></i></span><a href="#">{l s="Share this post"}</a>
          </div>
        </div>
    </div>
</div>
{else}
<div class="single-blog small-item-v2 clearfix">
	<div class="row">
	    <div class="blog-image col-lg-5 col-sm-5 col-xs-12">
	        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.meta_title}">
	            <img src="{$post.thumb_image}" alt="Blog">
	        </a>
	    </div>
	    <div class="blog-content col-lg-7 col-sm-7 col-xs-12">
	        <div class="blog-info">
	            <span class="blog-date">
	              <i class="fa fa-clock-o" aria-hidden="true"></i>
	               <a href="#">
	                    <span class="month-date"><small>{$post.created|date_format:"%B %d, %Y"}</small></span>
	               </a>
	           </span>
	           <span class="comments-number hidden">
	                <i class="fa fa-comment"></i>{$post.totalcomment} {l s="Comment"}
	            </span>
	            {if $smartshowauthor ==1}
	            <span class="author-name">
	              <i class="fa fa-user-circle-o" aria-hidden="true"></i>{l s="By"} 
	                <a rel="author" href="#">
	                    {if $smartshowauthorstyle != 0}
	                      {$post.firstname} {$post.lastname}
	                    {else}
	                      {$post.lastname} {$post.firstname}
	                    {/if}
	                </a>
	            </span>
	            {/if}
	        </div>
	        <div class="title-desc">
	            <a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'><h4>{$post.meta_title}</h4></a>
	            <p>{$post.short_description|strip_tags:'UTF-8'|truncate:210:'...'}</p>
	        </div>
	        <div class="blog-list-footer clearfix">
	          <a href="#" class="readmore pull-left">{l s="Read More"}</a>
	          <div class="share-blog-widget pull-right">
	            <span class="blog-icon-share"><i class="fa fa-share-alt" aria-hidden="true"></i></span><a href="#">{l s="Share this post"}</a>
	          </div>
	        </div>
	    </div>
	</div>
</div>
{/if}