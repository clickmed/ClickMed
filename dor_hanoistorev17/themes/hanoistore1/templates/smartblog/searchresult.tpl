{capture name=path}<a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Blog list' mod='smartblog'}</a>
     {if $title_category != ''}
    <span class="navigation-pipe"></span>{$title_category}{/if}{/capture}
 
    {if $postcategory == ''}
        {include file="./search-not-found.tpl" postcategory=$postcategory}
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

