{if isset($posts) AND !empty($posts)}
<div id="recent_article_smart_blog_block_left"  class="block blogModule boxPlain">
   <div class="section-title"><h2 class="title_block"><a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Popular Posts' mod='smartblogpopularposts'}</a></h2></div>
   <div class="popular-post">
        {foreach from=$posts item="post"}
         {assign var="options" value=null}
         {$options.id_post= $post.id_smart_blog_post}
         {$options.slug= $post.link_rewrite}
        <div class="single-popular-post clearfix">
          <div class="post-image">
              <a title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                 <img alt="{$post.meta_title}" src="{$post.thumb_image}">
             </a>
          </div>
          <div class="popular-post-details">
              <div class="popular-post-date">
                  <span>{$post.created|date_format:"%B %d, %Y"}</span>
              </div>
              <h3>
                  <a title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.meta_title}</a>
              </h3>
          </div>
        </div>
        {/foreach}
   </div>
</div>
{/if}