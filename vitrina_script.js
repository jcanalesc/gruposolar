
$(document).ready(function()
{
   $("img.zoom").live('click',function()
   {
      var parts = $(this).attr("src").split("/");
      
      $.fancybox("<img width='400' height='400' src='"+parts[0]+"/"+parts[2]+"' />");
  });
  $("[data-video]").css("cursor", "pointer").live("click",function()
  {
    var ln = $(this).attr("data-video");
    var matches = /watch\?v=([0-9A-Za-z]+).*/.exec(ln);
    var route = "http://www.youtube.com/embed/"+matches[1]+"?hl=es&fs=1";
    $.fancybox('<iframe width="425" height="349" src="'+route+'" frameborder="0" allowfullscreen></iframe>');
  });
});
