{if isset($categories) AND !empty($categories)}
<div id="category_blog_block_left"  class="block blogModule boxPlain">
  <div class="section-title"><h2 class="title_block"><a href="{smartblog::GetSmartBlogLink('smartblog_list')}">{l s='Categories' mod='smartblogcategories'}</a></h2></div>
   <div class="sideber-menu">
      <ul>
      {foreach from=$categories item="category"}
      {assign var="options" value=null}
      {$options.id_category = $category.id_smart_blog_category}
      {$options.slug = $category.link_rewrite}
        <li>
          <a href="{smartblog::GetSmartBlogLink('smartblog_category',$options)}">{$category.meta_title}</a>
        </li>
      {/foreach}
      </ul>
    </div>
</div>
{/if}