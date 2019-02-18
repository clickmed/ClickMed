<div class="tab-pane fade in " id="video-product">
{foreach from=$videos item=video name=videos}
<object width="{$video.width}" height="{$video.height}">
  <param name="movie"
         value="https://www.youtube.com/v/{$video.videoCode}?version=4&autoplay=0"></param>
  <param name="allowScriptAccess" value="always"></param>
  <embed src="https://www.youtube.com/v/{$video.videoCode}?version=4&autoplay=0"
         type="application/x-shockwave-flash"
         allowscriptaccess="always"
         width="{$video.width}" height="{$video.height}"></embed>
</object>
{/foreach}
</div>