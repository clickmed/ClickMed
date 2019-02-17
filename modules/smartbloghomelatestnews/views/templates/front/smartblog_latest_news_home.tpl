<!-- Latest News -->
{if isset($view_data) AND !empty($view_data)}
    {assign var='i' value=1}
<div class="container">
    <div class="row">
        <section class="gst-row row-latest-news ovh">
            <div class="container theme-container">
                <div class="gst-column col-lg-12 no-padding">
                    <div class="fancy-heading text-center">
                        <h3>Latest <span class="thm-clr">News</span></h3>
                        <h5 class="funky-font-2">News from our blog</h5>
                    </div>
                    <div class="row gst-post-list">
                        {foreach from=$view_data item=post}
                        {assign var="catOptions" value=null}
                        {assign var="options" value=null}
                        {$options.id_post = $post.id}
                        {$options.slug = $post.link_rewrite}
                        {$catOptions.id_category = $post.category}
                        {$catOptions.slug = $post.category_link_rewrite}
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                                <img src="{$post.thumb_image}" alt="" />
                            </a>
                            <div class="media clearfix">
                                <div class="entry-meta media-left">
                                    <div class="entry-time meta-date">
                                        <time datetime="2015-12-09T21:10:20+00:00">
                                            <span class="entry-time-date dblock">{$post.date_added|date_format:"%e"}</span>
                                            {$post.date_added|date_format:"%b"}
                                        </time>
                                    </div>
                                    <div class="entry-reply">
                                        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}#comments" class="comments-link">
                                            <i class="fa fa-comment dblock"></i>
                                            {$post.totalcomment}
                                        </a>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="entry-header">
                                        <span class="vcard author entry-author">
                                            <a class="url fn n" rel="author" href="#">
                                                {$post.firstname} {$post.lastname}
                                            </a>
                                        </span>
                                        <span class="entry-categories">
                                            <a href="{smartblog::GetSmartBlogLink('smartblog_category',$catOptions)}">{$post.category_name}</a>
                                        </span>
                                        <h3 class="entry-title">
                                            <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a>
                                        </h3>
                                        <p class="news-desc">{$post.short_description|truncate:60:'...'|escape:'htmlall':'UTF-8'}</p>
                                        <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="read-more-link thm-clr">Read More <i class="fa fa-long-arrow-right"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {$i=$i+1}
                    {/foreach}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
{/if}
<!-- Latest News -->