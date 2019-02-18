$(function() {
	$('input.star').rating();
	$('.auto-submit-star').rating();
	jQuery(".open-comment-form").click(function(){
		jQuery("#dor-review-form").show();
	});
	jQuery(".close-comment-form").click(function(){
		jQuery("#dor-review-form").hide();
	});
	$('button.usefulness_btn').click(function() {
		var id_product_comment = $(this).data('id-product-comment');
		var is_usefull = $(this).data('is-usefull');
		var parent = $(this).parent();

		$.ajax({
			url: productcomments_controller_url + '?rand=' + new Date().getTime(),
			data: {
				id_product_comment: id_product_comment,
				action: 'comment_is_usefull',
				value: is_usefull
			},
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			success: function(result){
				parent.fadeOut('slow', function() {
					parent.remove();
				});
			}
		});
	});

	$('span.report_btn').click(function() {
		if (confirm(confirm_report_message))
		{
			var idProductComment = $(this).data('id-product-comment');
			var parent = $(this).parent();

			$.ajax({
				url: productcomments_controller_url + '?rand=' + new Date().getTime(),
				data: {
					id_product_comment: idProductComment,
					action: 'report_abuse'
				},
				type: 'POST',
				headers: { "cache-control": "no-cache" },
				success: function(result){
					parent.fadeOut('slow', function() {
						parent.remove();
					});
				}
			});
		}
	});

	$('#submitNewMessage').click(function(e) {
		// Kill default behaviour
		e.preventDefault();
		// Form element
		var validate = true;
		
		if(validate){
	        url_options = '?';
	        if (!productcomments_url_rewrite)
	            url_options = '&';

			$.ajax({
				url: productcomments_controller_url + url_options + 'action=add_comment&secure_key=' + secure_key + '&rand=' + new Date().getTime(),
				data: $('#id_new_comment_form').serialize(),
				type: 'POST',
				headers: { "cache-control": "no-cache" },
				dataType: "json",
				success: function(data){
					if (data.result)
					{
						ReviewMessPop("Your comment has been added and will be available once approved by a moderator.");
						$("#id_new_comment_form")[0].reset();
					}
					else
					{
						$('#new_comment_form_error ul').html('');
						$.each(data.errors, function(index, value) {
							$('#new_comment_form_error ul').append('<li>'+value+'</li>');
						});
						$('#new_comment_form_error').slideDown('slow');
					}
				}
			});
		}
		return false;
	});
});

function productcommentRefreshPage() {
    window.location.reload();
}
function ReviewMessPop(mess){
	var htmlPop = '<div class="dor-jspop-wishlist">';
			htmlPop += '<span class="dor-jspop-wishlist-close"></span>';
			htmlPop += '<div class="dor-jspop-wishlist-info">';
				htmlPop += mess;
			htmlPop += '</div>';
		htmlPop += '</div>';
	jQuery(".dor-jspop-wishlist").remove();
	setTimeout(function(){
		jQuery("body").append(htmlPop);
		jQuery(".dor-jspop-wishlist-close").click(function(){
			jQuery(".dor-jspop-wishlist").remove();
		});
	},300);
	setTimeout(function(){
		jQuery(".dor-jspop-wishlist").remove();
	},5000);
}