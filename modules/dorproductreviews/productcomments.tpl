{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
var productcomments_controller_url = '{$productcomments_controller_url}';
var confirm_report_message = '{l s='Are you sure that you want to report this comment?' mod='dorproductreviews' js=1}';
var secure_key = '{$secure_key}';
var productcomments_url_rewrite = '{$productcomments_url_rewriting_activated}';
var productcomment_added = '{l s='Your comment has been added!' mod='dorproductreviews' js=1}';
var productcomment_added_moderation = '{l s='Your comment has been submitted and will be available once approved by a moderator.' mod='dorproductreviews' js=1}';
var productcomment_title = '{l s='New comment' mod='dorproductreviews' js=1}';
var productcomment_ok = '{l s='OK' mod='dorproductreviews' js=1}';
var moderation_active = {$moderation_active};
</script>
	<div id="product_comments_block_tab">
	{if $comments}
		<div class="product_comment_list_reviews">
		{foreach from=$comments item=comment}
			{if $comment.content}
			<div class="comment row" itemprop="review" itemscope itemtype="https://schema.org/Review">
				<div class="comment_author col-sm-12">
					<span class="avatar-review">
						<img src="{$urls.img_url}dorado/avatar_user.jpg" alt="{$comment.customer_name|escape:'html':'UTF-8'}" title="{$comment.customer_name|escape:'html':'UTF-8'}" />
					</span>
					<div class="comment-text">
						<div class="meta-rating-area">
							<div class="meta-area">
                            {if $comment.user_name != ""}
                                <strong itemprop="author">{$comment.user_name|escape:'html':'UTF-8'}</strong>
                            {else}
                                <strong itemprop="author">{$comment.customer_name|escape:'html':'UTF-8'}</strong>
                            {/if}
                                <span>{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}</span>
                            </div>
                            <div class="star_content user-rating"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
								{section name="i" start=0 loop=5 step=1}
									{if $comment.grade le $smarty.section.i.index}
										<div class="star"></div>
									{else}
										<div class="star star_on"></div>
									{/if}
								{/section}
	            				<meta itemprop="worstRating" content = "0" />
								<meta itemprop="ratingValue" content = "{$comment.grade|escape:'html':'UTF-8'}" />
	            				<meta itemprop="bestRating" content = "5" />
							</div>
						</div>
						<div class="description">
                            <p itemprop="reviewBody">{$comment.content|escape:'html':'UTF-8'|nl2br}</p>
                        </div>
					</div>
				</div>
			</div>
			{/if}
		{/foreach}
		</div>
        {if (!$too_early AND ($logged OR $allow_guests))}
		<p class="align_center">
			<a id="new_comment_tab_btn" class="open-comment-form" href="#new_comment_form">{l s='Write your review' mod='dorproductreviews'} !</a>
		</p>
        {/if}
	{else}
		{if (!$too_early AND ($logged OR $allow_guests))}
		<p class="align_center">
			<a id="new_comment_tab_btn" class="open-comment-form" href="#new_comment_form">{l s='Be the first to write your review' mod='dorproductreviews'} !</a>
		</p>
		{else}
		<p class="align_center">{l s='No customer reviews for the moment.' mod='dorproductreviews'}</p>
		{/if}
	{/if}	
	</div>

{if isset($product) && $product}
<!-- Fancybox -->
<div id="dor-review-form">
	<div id="new_comment_form">
		<form id="id_new_comment_form" action="#">
			<h2 class="title hidden">{l s='Write your review' mod='dorproductreviews'}</h2>
			<div class="new_comment_form_content">
				<div id="new_comment_form_error" class="error" style="display:none;padding:15px 25px">
					<ul></ul>
				</div>
				<h2>{l s='Add a review' mod='dorproductreviews'}</h2>
				<p class="comment-notes">{l s='Your email address will not be published.Required fields are marked' mod='dorproductreviews'}<span class="required">*</span></p>

				{if $criterions|@count > 0}
					<ul id="criterions_list">
					{foreach from=$criterions item='criterion'}
						<li>
							<label>{$criterion.name|escape:'html':'UTF-8'}</label>
							<div class="star_content">
								<input class="star" type="radio" name="criterion[{$criterion.id_dorproduct_comment_criterion|round}]" value="1" />
								<input class="star" type="radio" name="criterion[{$criterion.id_dorproduct_comment_criterion|round}]" value="2" />
								<input class="star" type="radio" name="criterion[{$criterion.id_dorproduct_comment_criterion|round}]" value="3" />
								<input class="star" type="radio" name="criterion[{$criterion.id_dorproduct_comment_criterion|round}]" value="4" />
								<input class="star" type="radio" name="criterion[{$criterion.id_dorproduct_comment_criterion|round}]" value="5" checked="checked" />
							</div>
							<div class="clearfix"></div>
						</li>
					{/foreach}
					</ul>
				{/if}
				<div class="review-form-group">
					<div class="cmt-review-group row clearfix">
						{if $allow_guests == true && !$logged}
						<div class="field-review-cmt col-lg-4 col-sm-4 col-xs-12">
							<label class="hidden">{l s='Your name' mod='dorproductreviews'}<sup class="required">*</sup></label>
							<input id="commentCustomerName" name="customer_name" type="text" value="" placeholder="{l s='Your name' mod='dorproductreviews'}"/>
						</div>
						{/if}
						<div class="field-review-cmt col-lg-4 col-sm-4 col-xs-12">
							<label for="comment_email" class="hidden">{l s='Your email' mod='dorproductreviews'}<sup class="required">*</sup></label>
							<input id="comment_email" name="email" type="text" value="" placeholder="{l s='E-mail' mod='dorproductreviews'}"/>
						</div>
						<div class="field-review-cmt col-lg-4 col-sm-4 col-xs-12">
							<label for="comment_website" class="hidden">{l s='Your website' mod='dorproductreviews'}<sup class="required">*</sup></label>
							<input id="comment_website" name="website" type="text" value="" placeholder="{l s='Website' mod='dorproductreviews'}"/>
						</div>
					</div>
					<div class="cmt-review-group row clearfix">
						<div class="field-review-cmt col-lg-12 col-sm-12 col-xs-12">
							<label for="comment_title" class="hidden">{l s='Title for your review' mod='dorproductreviews'}<sup class="required">*</sup></label>
							<input id="comment_title" name="title" type="text" value="" placeholder="{l s='Title for your review' mod='dorproductreviews'}" />
						</div>
						<div class="field-review-cmt field-comment-review col-lg-12 col-sm-12 col-xs-12">
							<label for="content" class="hidden">{l s='Your review' mod='dorproductreviews'}<sup class="required">*</sup></label>
							<textarea id="content" name="content" placeholder="{l s='Comment:' mod='dorproductreviews'}"></textarea>
						</div>
						<div id="new_comment_form_footer">
							<input id="id_product_comment_send" name="id_product" type="hidden" value='{$id_product_comment_form}' />
							<p class="fr">
								<button id="submitNewMessage" name="submitMessage" type="submit">{l s='Submit' mod='dorproductreviews'}</button>&nbsp;
								{l s='or' mod='dorproductreviews'}&nbsp;<a href="#" onclick="return false" class="close-comment-form">{l s='Cancel' mod='dorproductreviews'}</a>
							</p>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</form><!-- /end new_comment_form_content -->
	</div>
</div>
<!-- End fancybox -->
{/if}
