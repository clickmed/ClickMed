{extends file='page.tpl'}
{block name='page_header_container'}{/block}
{block name='page_content'}
<div id="dor-smartblog-lists" class="center_column dor-two-cols col-xs-12 col-sm-9">
	{capture name=path}<a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Blog list' mod='smartblog'}</a>

    {if $title_category != ''}
    <span class="navigation-pipe"></span>{$title_category}{/if}{/capture}
    {if $postcategory == ''}
        {if $title_category != ''}
             <p class="error">{l s='No Post in Category' mod='smartblog'}</p>
        {else}
             <p class="error">{l s='No Post in Blog' mod='smartblog'}</p>
        {/if}
    {else}
	{if $smartdisablecatimg == '1'}
                  {assign var="activeimgincat" value='0'}
                    {$activeimgincat = $smartshownoimg} 
        {if $title_category != ''}        
           {foreach from=$categoryinfo item=category}
            <div id="sdsblogCategory">
               {if ($cat_image != "no" && $activeimgincat == 0) || $activeimgincat == 1}
                   <img alt="{$category.meta_title}" src="{$urls.base_url}modules/smartblog/images/category/{$cat_image}-home-default.jpg" class="imageFeatured">
               {/if}
                {$category.description}
            </div>
             {/foreach}  
        {/if}
    {/if}
    <h1 class="h1">{l s='News & Blog' d='Shop.Theme.Actions'}</h1>
    <div id="smartblogcat" class="block row {$dorBlogsStyleCss}">
        <div class="blog-post-content-area blog-right col-lg-12 col-sm-12 col-xs-12">
        {if isset($dorBlogsStyle) && ($dorBlogsStyle == 3 || $dorBlogsStyle == 4 || $dorBlogsStyle == 5)}
            {include file="./category_masonry.tpl" postcategory=$postcategory}
        {elseif isset($dorBlogsStyle) && $dorBlogsStyle == 2}
            {foreach from=$postcategory item=post key=i}
                {include file="./category_loop_v2.tpl" postcategory=$postcategory}
            {/foreach}
        {else}
            {foreach from=$postcategory item=post}
                {include file="./category_loop.tpl" postcategory=$postcategory}
            {/foreach}
        {/if}
        </div>
    </div>
    {if !empty($pagenums)}
    <div class="row">
    <div class="post-page col-md-12">
            <div class="col-md-12 text-center">
                <ul class="pagination">
                    {for $k=0 to $pagenums}
                        {if $title_category != ''}
                            {assign var="options" value=null}
                            {$options.page = $k+1}
                            {$options.id_category = $id_category}
                            {$options.slug = $cat_link_rewrite}
                        {else}
                            {assign var="options" value=null}
                            {$options.page = $k+1}
                        {/if}
                        {if ($k+1) == $c}
                            <li><span class="page-active"><span>{$k+1}</span></span></li>
                        {else}
                                {if $title_category != ''}
                                    <li><a class="page-link" href="{smartblog::GetSmartBlogLink('smartblog_category_pagination',$options)}">{$k+1}</a></li>
                                {else}
                                    <li><a class="page-link" href="{smartblog::GetSmartBlogLink('smartblog_list_pagination',$options)}"><span>{$k+1}</span></a></li>
                                {/if}
                        {/if}
                   {/for}
                </ul>
			</div>
			<div class="col-md-6 hidden">
                <div class="results">{l s="Showing" mod="smartblog"} {if $limit_start!=0}{$limit_start}{else}1{/if} {l s="to" mod="smartlatestnews"} {if $limit_start+$limit >= $total}{$total}{else}{$limit_start+$limit}{/if} {l s="of" mod="smartblog"} {$total} ({$c} {l s="Pages" mod="smartblog"})</div>
            </div>
  </div>
  </div> {/if}
 {/if}
{if isset($smartcustomcss)}
    <style>
        {$smartcustomcss}
    </style>
{/if}
</div>
<div id="dor-smart-blog-right-sidebar" class="col-xs-12 col-sm-3 column">
	{capture name='displaySmartBlogRight'}{hook h='displaySmartBlogRight'}{/capture}
	  {if $smarty.capture.displaySmartBlogRight}
	    <div class="displaySmartBlogRight">
	      {$smarty.capture.displaySmartBlogRight nofilter}
	    </div>
	{/if}
</div>
{/block}
