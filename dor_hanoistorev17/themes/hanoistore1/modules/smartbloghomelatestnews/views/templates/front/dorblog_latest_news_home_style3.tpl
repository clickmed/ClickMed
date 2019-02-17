<!-- Latest News -->
{if isset($view_data) AND !empty($view_data)}
    {assign var='i' value=1}
<section id="dorhomeStyle3" class="blog-home-data arrowStyleDot1 size2">
    <div class="theme-container">
        <div class="gst-column">
            <div class="fancy-heading text-center">
                <h2>#{l s="Blog Update" mod="smartbloghomelatestnews"}</h2>
                <span>-{l s="Keep up to date with us" mod="smartbloghomelatestnews"}-</span>
            </div>
            <div class="gst-post-list row-item">
                {foreach from=$view_data item=post key=i}
                {assign var="catOptions" value=null}
                {assign var="options" value=null}
                {$options.id_post = $post.id}
                {$options.slug = $post.link_rewrite}
                {$catOptions.id_category = $post.category}
                {$catOptions.slug = $post.category_link_rewrite}
                <div class="main-item-blog col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="image-blog-item">
                        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                            <img src="{$post.thumb_image}" alt="" />
                        </a>
                    </div>
                    <div class="info-blog-item">
                        <div class="entry-meta pull-left">
                            <div class="entry-time meta-date">
                                <time datetime="">
                                    <span class="month-date"><small>{$post.date_added|date_format:"%e"}</small><em>{$post.date_added|date_format:"%b"}</em></span>
                                </time>
                            </div>
                        </div>
                        <div class="media-body clearfix">
                            <div class="entry-header">
                                <!-- Post Categories -->
                                  <span class="entry-categories" data-itemprop="articleSection">
                                    <a href="{smartblog::GetSmartBlogLink('smartblog_category',$catOptions)}">{$post.category_name}</a>
                                  </span>
                                <h3 class="entry-title">
                                    <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.title}">{$post.title|truncate:50:'...'|escape:'htmlall':'UTF-8'}</a>
                                </h3>
                                <p class="news-desc">{$post.short_description|truncate:120:'...'|escape:'htmlall':'UTF-8'}</p>
                                <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="read-more-link thm-clr">{l s="Read More" mod="smartbloghomelatestnews"}<i class="fa fa-long-arrow-right hidden"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>
</section>
{/if}
<!-- Latest News -->
