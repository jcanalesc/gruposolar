function goto(url)
{
	$("#principal").html("<img class=\"carga\" src=\"ajax-loader.gif\" />");
   $.get(url, function(data)
   {
      $("#principal").html(data);
   });
}
function ocultaprod(idp)
{
  $.get("ocultarprod.php", { "idp": idp}, function(data)
  {
    if (data != "done")
    {
      alert("Error al intentar ocultar.");
    }
    else
      $("#fila"+idp).slideUp("slow");
  });
}