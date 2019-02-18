{if isset($tags) AND !empty($tags)}
<div  id="tags_blog_block_left"  class="block blogModule boxPlain">
    <div class="section-title"><h2 class='sdstitle_block title_block'><a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Tags Post' mod='smartblogtag'}</a></h2></div>
    <div class="block_content">
            {foreach from=$tags item="tag"}
            {assign var="options" value=null}
                {$options.tag = $tag.alias}
                {if $tag!=""}
                    <a href="{smartblog::GetSmartBlogLink('smartblog_tag',$options)}">{$tag.name}</a>
                {/if}
            {/foreach}
   </div>
</div>
{/if}