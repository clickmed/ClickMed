<div class="tab-category-container-slider col-xs-12 col-lg-12 col-md-12 col-sm-12">
    <div class="container-inner row dor-tab-list-category-wrapper">
            <div class="title_block title-header-tab head-tab-shopcategory">
                <h3>
                    <span><i class="icon-shop-category"></i>{$slideOptions.titleModule}</span>
                </h3>
            </div>
            <div class="tab_category_container dorButtonArrow">
                <ul class="lists_category">
                {$count = 0}
                {foreach from=$productCates item=category name=posCategory}
                    <li class="{if $count==0}start{/if} wow fadeInUp" data-wow-delay="{$smarty.foreach.posCategory.iteration}00ms" >
                        <div>
                            <div class="images">
                                <div>
                                    <a href="{$category.cate.link_cate}"> <img class="img-responsive" src="{$category.cate.thumb_image}" alt="" /> </a>
                                </div>
                                 <a href="{$category.cate.link_cate}" class="btn-view">+</a>
                            </div>
                             <div class="category_name">  
                                <a href="{$category.cate.link_cate}"> {$category.cate.name_category} </a>
                                <p>({$category.totalProduct} Products)</p>
                             </div>
                        </div>
                    </li>
                    {$count = $count+1}
                {/foreach}
                </ul>
            </div> <!-- .tab_container -->
    </div>
</div>