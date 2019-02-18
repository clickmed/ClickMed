<!-- Subscribe Popup 1 -->
<section class="subscribe-me">
    <a href="#close" onclick="return false" class="sb-close-btn close popup-cls b-close"><i class="fa-times fa"></i></a>      
    <div class="modal-content {if $dorSubsPop == 1}subscribe-1 wht-clr{else}subscribe-2 blk-clr{/if}">   
        <div class="login-wrap text-center">                        
            <h2 class="sec-title fsz-50">{l s='NEWSLETTER' mod='blocknewsletter'}</h2>
            <h3 class="fsz-15 bold-font-4"> {l s='Did you know that we ship to over' mod='blocknewsletter'} <span class="thm-clr"> {l s='24 different countries' mod='blocknewsletter'} </span> </h3>
            <div class="login-form spctop-30"> 
                <form class="subscribe" action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
                    <div class="form-group"><input type="text" placeholder="{l s='Enter your name' mod='blocknewsletter'}" class="form-control"></div>
                    <div class="form-group{if isset($msg) && $msg } {if $nw_error}form-error{else}form-ok{/if}{/if}" >
                        <input class="inputNew form-control grey newsletter-input" id="dorNewsletter-input" type="text" name="email" size="18" value=""  placeholder="{l s='Your email address' mod='blocknewsletter'}"/>
                    </div>
                    <div class="form-group checkAgainSubs"><input type="checkbox" name="notShowSubs"> <span>{l s="Don't show this popup again" mod='blocknewsletter'}</span></div>
                    <div class="form-group">
                        <button class="alt fancy-button" type="submit" name="submitNewsletter"> <span class="fa fa-envelope"></span> {l s='Subscribe' mod='blocknewsletter'} </button>
                        <input type="hidden" name="action" value="0" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- / Subscribe Popup 1 -->