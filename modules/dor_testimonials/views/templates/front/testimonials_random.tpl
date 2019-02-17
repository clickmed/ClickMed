<div id="testimonials_block_right" class="block">
  <h4 class="title_block">{l s='Testimonials' mod='dor_testimonials'}</h4>
  <div id="wrapper">
    {if $testimonials}
        <ul class="slide">
          {foreach from=$testimonials key=test item=testimonial}
            {if $testimonial.active == 1}
              <li >
                <div class="media-content">
                  {if $testimonial.media}
                    {if in_array($testimonial.media_type,$arr_img_type)}
                      <a class="fancybox-media" rel="fancybox-button">
                        <img src="{$mediaUrl}{$testimonial.media}" alt="Image Testimonial">
                      </a>

                    {/if}
                    {if in_array($testimonial.media_type,$video_types) }
                        <video width="260" height="240" controls>
                            <source src="{$mediaUrl}{$testimonial.media}" type="video/mp4" />
                        </video>
                    {/if}
                  {else}
                    <a class="fancybox-media" rel="fancybox-button">
                      <img src="{$module_dir}assets/front/img/demo1.jpg" alt="Image Testimonial" >
                        </a>
                  {/if}

                </div>
                <div class="content_test">
                  <p class="des_testimonial">“{$testimonial.content|truncate:1000}"  <a href="{$link->getModuleLink('dor_testimonials','views',['process'=>'view','id'=>$testimonial.id_labtestimonial])}" class="read_more">{l s='Read More' mod='dor_testimonials'}</a></p>
                  <p class="des_namepost">{$testimonial.name_post}</p>
                </div>
              </li>
            {/if}
          {/foreach}
        </ul>
    {/if}
      <div class="button_testimonial">
          <div class="view_all"><a class="btn btn-default button button-small" href="{$link->getModuleLink('dor_testimonials','views',['process' => 'view'])}">{l s='View All' mod='dor_testimonials'}</a></div>
          <div class="submit_link"><a class="btn btn-default button button-small" href="{$link->getModuleLink('dor_testimonials','views',['process' => 'form_submit'])}"> {l s='Submit Testimonial' mod='dor_testimonials'}</a></div>
      </div>
  </div>
</div>
