/**
 * Created with JetBrains PhpStorm.
 * User: LandOfCoder
 * Date: 1/2/14
 * Time: 10:28 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
  var linkId = $('#media_link_id').val();
  if(linkId){
    if(linkId.length == 11){
      var iframe_youtube = '<iframe width="560" height="315" src="//www.youtube.com/embed/'+linkId+'" frameborder="0" allowfullscreen></iframe>';
      $('#show_iframe').html(iframe_youtube);
    }else if(linkId.length == 8){
      var iframe_vimeo = '<iframe src="//player.vimeo.com/video/'+linkId+'?badge=0" width="500" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
      $('#show_iframe').html(iframe_vimeo);
    }else{
  //    $('#show_iframe').html('video is incorrect.').css("color","red");
    }
  }
});
