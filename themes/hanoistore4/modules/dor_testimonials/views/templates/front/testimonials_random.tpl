<div id="testimonials_block_right" class="col-md-3 col-sm-3 col-xs-12">
  <div id="wrapper-testimonials">
    {if $testimonials}
      <h4 class="title_block">{l s='Client\'s Happy' mod='dor_testimonials'}</h4>
        <ul class="slide">
          {foreach from=$testimonials key=test item=testimonial}
            {if $testimonial.active == 1}
              <li >
                <div class="des_testimonial">“{$testimonial.content|truncate:100}"</div>
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
                  <p class="des_namepost">{$testimonial.name_post}</p>
                  <p class="des_company">{$testimonial.company}</p>
                </div>
              </li>
            {/if}
          {/foreach}
        </ul>
    {/if}
  </div>
</div>
