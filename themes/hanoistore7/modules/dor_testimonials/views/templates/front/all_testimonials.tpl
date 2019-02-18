{capture name=path}
    <span class="navigation-pipe">{$navigationPipe}</span>{l s='Testimonial' mod='dor_testimonials'}
{/capture}
<div class="content-testimonials block">
    <h4 class="title_block">{l s='Testimonial' mod='dor_testimonials'}</h4>
    <a class="button btn btn-default button-small" href="{$link->getModuleLink('dor_testimonials','views',['process' => 'form_submit'])}"><span>{l s='Submit Testimonial' mod='dor_testimonials'}</span></a>
    {if $id != 0}
        <h3>{l s='Current testimonial' mod='dor_testimonials'}</h3>
    {/if}
    {foreach $testimoninals as $alltestimoninal}
        {if $alltestimoninal.content !='' }

        <div class="wrapper-alltestimoninals">
            {if ($alltestimoninal.media_type)!=''}
                {if in_array($alltestimoninal.media_type,$image_type)}

                    <div class="img-content" >
                        <a class="alltestimoninal" href="{$img_ps_dir}{$name}/{$alltestimoninal.media}">
                        <img src="{$img_ps_dir}{$name}/{$alltestimoninal.media}" class="image-alltestimoninal" ></a>
                    </div>
                {/if}



            {/if}
            <div class="alltestimonial-content">
                “{$alltestimoninal.content}“
            </div>
            <div class="info-user">
                <span class="username">  {$alltestimoninal.name_post} </span>
                <br>
                <span class="date-add"> {$alltestimoninal.date_add}</span> - {$alltestimoninal.email} -{$alltestimoninal.company}
            </div>
            <div style="clear:both;"></div>
        </div>
        {/if}
    {/foreach}


    <div id="pagination" class="pagination">
        {if $nbpagination < $alltestimoninals|@count}
            <ul class="pagination">
                {if $page != 1}
                    {assign var='p_previous' value=$page-1}
                    <li id="pagination_previous">
                        <a href="{testimonialpaginationlink p=$p_previous n=$nbpagination}" title="{l s='Previous' mod='testimoninals'}" rel="nofollow">&laquo;&nbsp;{l s='Previous' mod='dor_testimonials'}</a>
                    </li>
                {else}
                    <li id="pagination_previous" class="disabled"><span>&laquo;&nbsp;{l s='Previous' mod='dor_testimonials'}</span></li>
                {/if}
                {if $page > 2}
                    <li><a href="{testimonialpaginationlink p='1' n=$nbpagination}" rel="nofollow">1</a></li>
                    {if $page > 3}
                        <li class="truncate">...</li>
                    {/if}
                {/if}
                {section name=pagination start=$page-1 loop=$page+2 step=1}
                    {if $page == $smarty.section.pagination.index}
                        <li class="current"><span>{$page|escape:'htmlall':'UTF-8'}</span></li>
                    {elseif $smarty.section.pagination.index > 0 && $alltestimoninals|@count+$nbpagination > ($smarty.section.pagination.index)*($nbpagination)}
                        <li><a href="{testimonialpaginationlink p = $smarty.section.pagination.index n=$nbpagination}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
                    {/if}
                {/section}
                {if $max_page-$page > 1}
                    {if $max_page-$page > 2}
                        <li class="truncate">...</li>
                    {/if}
                    <li><a href="{testimonialpaginationlink p=$max_page n=$nbpagination}">{$max_page}</a></li>
                {/if}
                {if $alltestimoninals|@count > $page * $nbpagination}
                    {assign var='p_next' value=$page+1}
                    <li id="pagination_next"><a href="{testimonialpaginationlink p=$p_next n=$nbpagination}" title="Next" rel="nofollow">{l s='Next' mod='dor_testimonials'}&nbsp;&raquo;</a></li>
                {else}
                    <li id="pagination_next" class="disabled"><span>{l s='Next' mod='dor_testimonials'}&nbsp;&raquo;</span></li>
                {/if}
            </ul>
        {/if}
        {if $alltestimoninals|@count > 10}
            <form action="{$pagination_link}" method="get" class="pagination">
                <p>
                    <input type="submit" class="button_mini" value="{l s='OK' mod='dor_testimonials'}" />
                    <label for="nb_item">{l s='items:' mod='testimoninals'}</label>
                    <select name="n" id="nb_item">
                        {foreach from=$nArray item=nValue}
                            {if $nValue <= $alltestimoninals|@count}
                                <option value="{$nValue|escape:'htmlall':'UTF-8'}" {if $nbpagination == $nValue}selected="selected"{/if}>{$nValue|escape:'htmlall':'UTF-8'}</option>
                            {/if}
                        {/foreach}
                    </select>
                    <input type="hidden" name="p" value="1" />
                </p>
            </form>
        {/if}
    </div>
</div>

