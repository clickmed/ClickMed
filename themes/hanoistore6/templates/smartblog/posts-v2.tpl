<h1 class="page-heading bottom-indent hidden">{l s="News & Blog" mod='smartblog'}</h1>
{capture name=path}<a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Blog list' mod='smartblog'}</a><span class="navigation-pipe"></span>{$meta_title}{/capture}
<div id="content" class="block dorBlogDetailV2">
   <div itemtype="#" itemscope="" id="sdsblogArticle" class="blog-post">
		{assign var="catOptions" value=null}
		{$catOptions.id_category = $id_category}
		{$catOptions.slug = $cat_link_rewrite}
      	<div data-itemprop="articleBody">
      		<div class="blog-post-content-area blog-details">
                <!-- single-blog-start -->
                <div class="single-blog">
                    <div class="blog-content">
                        <div class="blog-info">
                            <span class="author-name">
                                <i class="fa fa-user"></i>{l s="By"} 
                                <a href="#">{if $smartshowauthor ==1}<span data-itemprop="author">{if $smartshowauthorstyle != 0}{$firstname} {$lastname}{else}{$lastname} {$firstname}{/if}</span>{/if}</a>
                            </span>
                            <span class="blog-date">
                               <a href="#">
                                    <span class="month-date"><small>{$post.created|date_format:"%B %d, %Y"}</small></span>
                               </a>
                           </span>
                           <span class="comments-number">
                                <i class="fa fa-comment"></i>{if $countcomment != ''}{$countcomment}{else}{l s='0' mod='smartblog'}{/if} {l s="Comment"}
                            </span>
                        </div>
                        <div class="title-desc">
                            <h4>{$meta_title}</h4>
                            <div class="content-blog-detail">{$content}</div>
                        </div>
                    </div>
                    <div class="blog-detail-footer clearfix">
                    	{if $tags}
                       <span class="single-post-tags pull-left">
                            <i class="fa fa-tags"></i>
                            {foreach from=$tags item=tag}
	                            {assign var="options" value=null}
	                            {$options.tag = $tag.name}
	                            <a title="tag" href="{smartblog::GetSmartBlogLink('smartblog_tag',$options)}">{$tag.name}</a>
	                        {/foreach}
                        </span>
                        {/if}
                        <div class="share-blog-widget pull-right">
				            <span class="blog-icon-share"><i class="fa fa-share-alt" aria-hidden="true"></i></span><a href="#">{l s="Share this post"}</a>
				        </div>
                    </div>
                </div>
                <!-- single-blog-end -->
                {if $smartshowauthor ==1}
                <div class="blog-detail-author clearfix">
                	<div class="author-media-info pull-left">
                		<div><img src="{$base_dir}img/cms/dorado/blog/author-avatar-detail.jpg" alt=""></div>
                	</div>
                	<div class="author-content-info pull-left">
                		<h3>{if $smartshowauthorstyle != 0}{$firstname} {$lastname}{else}{$lastname} {$firstname}{/if}</h3>
                		<div>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Indemon strunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus.</div>
                		<div class="shere-post">
					        <ul>
					            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
					            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
					            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
					            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
					        </ul>
					    </div>
                	</div>
                </div>
                {/if}
            </div>
      </div>
     
      <div class="sdsarticleBottom">
        {$HOOK_SMART_BLOG_POST_FOOTER}
      </div>
   </div>
<div class="shere-button-area clearfix">
    <div class="nav-button pull-left">
        <a href="#">
            <i class="fa fa-caret-left"></i><span>{l s="Previous"}</span>
        </a>
    </div>
    
    <div class="nav-button pull-right">
        <a href="#">
            <span>{l s="Next"}</span><i class="fa fa-caret-right"></i>
        </a>
    </div>
</div>
{if $countcomment != ''}
<div id="articleComments" class="clearfix">
            <h3 class="total-comment">
            	{if $countcomment > 1}
            		{l s='Comments' mod='smartblog'}
            	{else}
            		{l s='Comment' mod='smartblog'}
            	{/if}
            	<span>
            	{if $countcomment != ''}
            		{if $countcomment < 10}
            			(0{$countcomment})
            		{else}
            			({$countcomment})
            		{/if}
            	{else}
            		({l s='0' mod='smartblog'})
            	{/if}
            	
            	</span>
            </h3>
        <div id="comments">      
            <ul class="commentList">
                  {$i=1}
                {foreach from=$comments item=comment}
                    
                       {include file="./comment_loop.tpl" childcommnets=$comment}
                   
                  {/foreach}
            </ul>
        </div>
</div>
 {/if}

</div>
{if Configuration::get('smartenablecomment') == 1}
{if $comment_status == 1}
<div class="smartblogcomments clearfix dorBlogDetailV2" id="respond">
    <!-- <h4 id="commentTitle">{l s="Leave a Comment"  mod="smartblog"}</h4> -->
    	<h4 class="comment-reply-title" id="reply-title">{l s="Leave your thought"  mod="smartblog"} <small style="float:right;">
                <a style="display: none;" href="/wp/sellya/sellya/this-is-a-post-with-preview-image/#respond" 
                   id="cancel-comment-reply-link" rel="nofollow">{l s="Cancel Reply" mod="smartblog"}</a>
            </small>
        </h4>
		<div id="commentInput">
			<form action="" method="post" id="commentform">
			<div>
				
				<div class="field-cmt">
					<div class="row">
						<div class="field-mini-row col-lg-4 col-sm-4 col-sx-12">
							<input type="text" tabindex="1" class="inputName form-control cmt-field" value="" name="name" placeholder='{l s="Name" mod="smartblog"}'>
						</div>
						<div class="field-mini-row col-lg-4 col-sm-4 col-sx-12">
							<input type="text" tabindex="2" class="inputMail form-control cmt-field" value="" name="mail" placeholder='{l s="Email" mod="smartblog"}'>
						</div>
						<div class="field-mini-row col-lg-4 col-sm-4 col-sx-12">
							<input type="text" tabindex="3" value="" name="website" class="form-control grey" placeholder='{l s="Website" mod="smartblog"}'>
						</div>
					</div>
				</div>
				<div class="field-cmt clearfix">
					<textarea tabindex="4" class="inputContent form-control cmt-field" rows="8" cols="50" name="comment" placeholder='{l s="Comment" mod="smartblog"}'></textarea>
				</div>
			</div>
			{if Configuration::get('smartcaptchaoption') == '1'}
			<div class="captcha-blog clearfix">
				<div class="captcha-blog-image"><img src="{$urls.base_url}smartblog/classes/CaptchaSecurityImages.php?width=100&height=40&characters=5"></div>
				<div class="col-lg-6 col-sm-6 col-xs-12 row">
					<input placeholder='{l s="Enter Captcha" mod="smartblog"}' type="text" tabindex="" value="" name="smartblogcaptcha" class="smartblogcaptcha form-control cmt-field">
				</div>
			</div>
			{/if}
                 <input type='hidden' name='comment_post_ID' value='1478' id='comment_post_ID' />
                  <input type='hidden' name='id_post' value='{$id_post}' id='id_post' />

                <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
			<div class="button-submit-comment clearfix">
		        <div class="submit">
		            <input type="submit" name="addComment" id="submitComment" class="bbutton btn btn-default button-medium" value="Post Comment">
				</div>
			</div>
        </form>
	</div>
</div>

<script type="text/javascript">
$('#submitComment').bind('click',function(event) {
event.preventDefault();
 
 
var data = { 'action':'postcomment', 
'id_post':$('input[name=\'id_post\']').val(),
'comment_parent':$('input[name=\'comment_parent\']').val(),
'name':$('input[name=\'name\']').val(),
'website':$('input[name=\'website\']').val(),
'smartblogcaptcha':$('input[name=\'smartblogcaptcha\']').val(),
'comment':$('textarea[name=\'comment\']').val(),
'mail':$('input[name=\'mail\']').val() };
	$.ajax( {
	  url: baseDir + 'modules/smartblog/ajax.php',
	  data: data,
	  
	  dataType: 'json',
	  
	  beforeSend: function() {
				$('.success, .warning, .error').remove();
				$('#submitComment').attr('disabled', true);
				$('#commentInput').before('<div class="attention"><img src="http://321cart.com/sellya/catalog/view/theme/default/image/loading.gif" alt="" />Please wait!</div>');

				},
				complete: function() {
				$('#submitComment').attr('disabled', false);
				$('.attention').remove();
				},
		success: function(json) {
			if (json['error']) {
					 
						$('#commentInput').before('<div class="warning">' + '<i class="icon-warning-sign icon-lg"></i>' + json['error']['common'] + '</div>');
						
						if (json['error']['name']) {
							$('.inputName').after('<span class="error">' + json['error']['name'] + '</span>');
						}
						if (json['error']['mail']) {
							$('.inputMail').after('<span class="error">' + json['error']['mail'] + '</span>');
						}
						if (json['error']['comment']) {
							$('.inputContent').after('<span class="error">' + json['error']['comment'] + '</span>');
						}
						if (json['error']['captcha']) {
							$('.smartblogcaptcha').after('<span class="error">' + json['error']['captcha'] + '</span>');
						}
					}
					
					if (json['success']) {
						$('input[name=\'name\']').val('');
						$('input[name=\'mail\']').val('');
						$('input[name=\'website\']').val('');
						$('textarea[name=\'comment\']').val('');
				 		$('input[name=\'smartblogcaptcha\']').val('');
					
						$('#commentInput').before('<div class="success">' + json['success'] + '</div>');
						setTimeout(function(){
							$('.success').fadeOut(300).delay(450).remove();
													},2500);
					
					}
				}
			} );
		} );
		
 




    var addComment = {
	moveForm : function(commId, parentId, respondId, postId) {

		var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID');

		if ( ! comm || ! respond || ! cancel || ! parent )
			return;
 
		t.respondId = respondId;
		postId = postId || false;

		if ( ! t.I('wp-temp-form-div') ) {
			div = document.createElement('div');
			div.id = 'wp-temp-form-div';
			div.style.display = 'none';
			respond.parentNode.insertBefore(div, respond);
		}


		comm.parentNode.insertBefore(respond, comm.nextSibling);
		if ( post && postId )
			post.value = postId;
		parent.value = parentId;
		cancel.style.display = '';

		cancel.onclick = function() {
			var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

			if ( ! temp || ! respond )
				return;

			t.I('comment_parent').value = '0';
			temp.parentNode.insertBefore(respond, temp);
			temp.parentNode.removeChild(temp);
			this.style.display = 'none';
			this.onclick = null;
			return false;
		};

		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},

	I : function(e) {
		return document.getElementById(e);
	}
};

      
      
</script>
{/if}
{/if}
{if isset($smartcustomcss)}
    <style>
        {$smartcustomcss}
    </style>
{/if}