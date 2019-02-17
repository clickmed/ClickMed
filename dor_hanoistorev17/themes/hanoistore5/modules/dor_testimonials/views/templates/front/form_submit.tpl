{capture name=path}
    <span class="navigation-pipe">{$navigationPipe}</span>{l s='Add new testimonial' mod='dor_testimonials'}
{/capture}
<div id="testimonials_block_center" class="block" xmlns="http://www.w3.org/1999/html">
  <h4 class="title_block">{l s='Add new one testimonial' mod='dor_testimonials'}</h4>
  {if $errors}
    <div class="alert alert-danger">
      {foreach from=$errors item=error}
        <p>{$error}</p></br>
      {/foreach}
    </div>
  {/if}
  {if $success}<div class="alert alert-success">{$success}</div>{/if}
   <div class="col-xs-6 col-md-12 form-submit">
    <form name="fr_testimonial" id="fr_testimonial" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label  class="col-sm-4 control-label">{l s='Your Name' mod='dor_testimonials'}:</label>
            <div class="col-sm-10">
                 <input id="name_post" name="name_post" type="text" value="{if isset($name_post)}{$name_post|escape:'html':'UTF-8'}{/if}{if $logged==1}{$cookie->customer_firstname|escape:'htmlall':'UTF-8'} {$cookie->customer_lastname|escape:'htmlall':'UTF-8'}{/if}" size="40" class="form-control grey validate"/>
                 <sup class="require">*</sup>
                </div>
            </div>
         <div class="form-group">
              <label  class="col-sm-4 control-label">{l s='Your Email' mod='dor_testimonials'}:</label>
              <div class="col-sm-10">
                 <p class="form-group">
                    <input id="email" name="email" type="text" size="40" class="form-control grey validate" data-validate="isEmail" value="{if isset($email)}{$email|escape:'html':'UTF-8'}{/if}{if $logged==1 }{$cookie->email|escape:'htmlall':'UTF-8'}{/if}" name="from" />
                    <sup class="require">*</sup>
                 </p>
            </div>
          </div>

      <div class="form-group">
         <label class="col-sm-4 control-label">{l s='Company' mod='dor_testimonials'}:</label>
            <div class="col-sm-10">
            <input id="company" name="company" type="text" value="{if isset($company)}{$company|escape:'html':'UTF-8'}{/if}" size="40" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label">{l s='Address' mod='dor_testimonials'}:</label>
            <div class="col-sm-10">
                <input id="Address" name="address" type="text" value="{if isset($address)}{$address|escape:'html':'UTF-8'}{/if}" size="40" class="form-control"/>
                <sup class="require">*</sup>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">{l s='Multimedia' mod='dor_testimonials'}:</label>
            <div class="col-sm-10">
                <input id="media" name="media" type="file" value="" size="40" class="form-control"/>
                <sup class="require">*</sup>
            </div></div>
        <div class="form-group">
          <label class="col-sm-4 control-label">{l s='Content' mod='dor_testimonials'}:</label>
            <div class="col-sm-10">
                <textarea id="content" name="content" cols="50" rows="8" class="form-control">{if isset($content)}{$content|escape:'html':'UTF-8'|stripslashes}{/if}</textarea>
                <sup class="require">*</sup>
            </div></div>
        {if $captcha == 1}
            <div class="form-group">
            <label class="col-sm-3 control-label">{l s='Captcha' mod='dor_testimonials'}:</label>
            <div class="col-sm-5">
                <input name="captcha" type="text" value="" size="20" class="form-control"/>
                <sup class="require">*</sup>
            </div>
            <div class="col-sm-4">
                <img src="{$captcha_code}" alt="{l s='captcha' mod='dor_testimonials'}"/>
            </div></div>
        {/if}
        <div class="form-group">
            <div class="col-sm-5">
                <button id="submitNewTestimonial"  class="button btn btn-default button-medium" name="submitNewTestimonial" type="submit" value="{l s='Send Your Testimonial' mod='dor_testimonials'}"><span> {l s='send' mod='dor_testimonials'} </span></button>
            </div>
            <label class="col-sm-4 control-label require"> {l s='* required field' mod='dor_testimonials'} </label>
        </div>
    </form>
  </div>
</div>
