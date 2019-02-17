{extends file='page.tpl'}
{block name='page_header_container'}{/block}
{block name='page_content'}
<div id="dor-blog-tags" class="center_column dor-two-cols col-xs-12 col-sm-9">

{capture name=path}<a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Blog list' mod='smartblog'}</a>
     {if $title_category != ''}
    <span class="navigation-pipe"></span>{$title_category}{/if}{/capture}
 
    {if $postcategory == ''}
             <p class="error">{l s='No Post in This Tag' mod='smartblog'}</p>
    {else}
    <div id="smartblogcat" class="block">
{foreach from=$postcategory item=post}
    {include file="./category_loop.tpl" postcategory=$postcategory}
{/foreach}
    </div>
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