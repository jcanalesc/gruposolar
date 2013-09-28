var fila = "";
function toggleTipo()
{
   if ($("#radio_persona").attr("checked"))
   {
      fila = $("#facturacion_row").html();
      $("#facturacion_row").html("");
   }
   else
   {
      $("#facturacion_row").html(fila);
   }
}
$(document).ready(function()
{
   toggleTipo();
   $("input[name='tipof']").bind('change',function()
   {
      toggleTipo();
   });
   $("input[name='rfrut'], input[name='rrut']").keydown(function(event)
   {
      if (event.keyCode == 0) return false;
      if ((event.keyCode < 96 || event.keyCode > 105) && event.keyCode > 57)
      {
         return false;
      }
   });
   $("#reg :input[obligatorio='si']").filter(":not(input[name='rdv']):not(input[name='rf_dv'])").each(function()
   {
      $(this).parent().prev().append("(*)".bold());
   });
   act_comunas(document.getElementById('rregion'),"rcomuna");
	act_comunas(document.getElementById('rf_region'),"rf_comuna");
   
});
function act_comunas(obj, selname)
{
   $.post("regiones.php","region="+escape($(obj).val()), function(data)
   {
      $("select[id='"+selname+"']").html(data);
      $("select[id='"+selname+"']").attr("disabled", false);
   });
}
function validaRut(texto, dv)
{
   // si tiene puntos, se matan
   texto = texto.split(".").join("");
   var arr = [3, 2, 7, 6, 5, 4, 3, 2];
   var digitos = new Array();
   if (texto.length < 7 || texto.length > 8)
      return false;
      
   for (var i = 0; i < (8 - texto.length); i++)
      digitos.push(0);
      
   for (var i = 0; i < texto.length; i++)
   {
      if ("0123456789".indexOf(texto.charAt(i)) == -1)
         return false;
      digitos.push(parseInt(texto.charAt(i)));
   }
   var res = 0;
   for (var i = 0; i < digitos.length; i++)
   {
      res += digitos[i] * arr[i];
   }
   res = 11 - (res % 11);
   if (res == 10) res = "k";
   if (res == 11) res = "0";
   return (res == dv.toLowerCase());
}
function registra(form)
{
   // validacion
   var ok = true;
   $("#reg :input:not(input[type='submit'])").each(function()
   {
      var campo = $(this).attr("name");
      var valor = $(this).val();
      if (valor.length < 1 && $(this).attr("obligatorio") == "si")
      {
         alert("Hay campos obligatorios sin completar");
         ok = false;
      }
      else if (campo == "rcomuna" && valor == "-")
      {
         alert("Seleccione region y comuna");
         ok = false;
      }
      else if (campo == "rrut" && !validaRut(valor, $("#reg :input[name='rdv']").val()))
      {
         alert("Rut invalido");
         ok = false;
      }
      else if (campo  == "remail" && $(this).val() != $("#reg :input[name='remail2']").val())
      {
         alert("Emails no coinciden");
         ok = false;
      }
      else if (!$("#accept").attr("checked"))  
      {
         alert("Debe aceptar los términos y condiciones para poder registrarse.");
         ok = false;
      }
      if (!ok)
      {
         $(this).select().focus();
         return false;
      }
   });
   
   if (ok)
   {
      $.post("registro.php", $("#reg").serialize(), function(data)
      {
         $("#hd").html(data);
      });
   }
}
