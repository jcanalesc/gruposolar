// facturacion_script.js
function currency(number)
{
   var i = 0;
   var curr = "";
   var arr = new Array();
   for (var i = 0; i < number.length; i++)
   {
      if (i % 3 == 0 && i != 0)
         arr.push(".");
      arr.push(number.charAt(number.length - 1 - i));
   }
   return arr.reverse().join("");
}
function obtenerpdf(id)
{
   id = parseInt(id.substr(3, id.length - 3));
   window.open("creapdf.php?id="+escape(id));
}
function obtenerpdf_usuario(usuario)
{
	if ($("select[name='selecremate']").val() == null) return;
	id = $("select[name='selecremate']").val();
	window.open("creanota.php?id_remate="+escape(id)+"&rut="+escape(usuario));
}
$(document).ready(function()	
{
   $(".pdfget").click(function()
   {
      obtenerpdf($(this).attr("id"));
   });
   muestraremates("select[name='selecremate']");
   if ($("select[name='selecremate']").val() == null)
	$(".botonpdf").hide(0);
});

function reenvia(src, pagina)
{
   $.get(pagina, function(data)
   {
      $(src).parent().html(data);
   });
}
function muestraremates(select)
{
   var actual = $(select).val();
   var bhay = false;
   var neto = 0;
   var iva = 0.0;
   var total = 0;
   var comm = 0.0;
   $("#adjudicaciones tr").each(function(index)
   {
      if (index != 0 && $(this).attr("class") != "fijo")
      {
         if ($(this).find("td").eq(0).html() != actual)
            $(this).hide(0);
         else
         {
            $(this).show(0);
            neto += parseInt($(this).find("td[val]").attr("val"));
            bhay = true;
         }
      }
   });
   var comision = parseFloat(comisiones[actual]) || 0;
   $("#vcom").prev().html("Valor comisión ("+comision+"%):");
   comm = parseFloat(comision) / 100 * neto;
   netof = neto + comm;
   iva = parseInt(Math.round(parseFloat(netof) * valor_iva));
   total = parseInt(iva + netof);
   
   $("#vneto").html("$"+currency(neto+""));
   $("#viva").html("$"+currency(iva+""));
   $("#vcom").html("$"+currency(comm+""));
   $("#vtotal").html("$"+currency(total+""));
   if (!bhay)
      $("#notengo").show(0);
   else
      $("#notengo").hide(0);
}
