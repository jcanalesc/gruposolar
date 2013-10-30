
function enviacorreo(form)
{
   if ($(form).find("input[name='asunto']").val().length > 0)
   {
      if ($(form).find("input[name='msg']").val().length > 0)
      {
         $("#carga").html("Enviando correos... <img src=\"ajax-loader.gif\" />");
         var datos = $(form).serialize();
         $.post("correomasivo.php", datos, function(data)
         {
            $("#carga").html("");
            if (data == "done")
               alert("Correos enviados correctamente.");
            else
               alert("Problemas al enviar correos.("+data+")");
         });
      }
      else
      {
         alert("El cuerpo del mensaje no puede ser vacío.");
      }
   }
   else
      alert("Escriba un asunto.");
}
