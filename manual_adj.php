<?
   include("header.php");
   if (!esAdmin()) die(consts::$mensajes[9]);
?>
<script type="text/javascript">
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

var arrlotes = new Array();
function act_lotes(sel)
{
   var remate = $(sel).val();
   arrLotes = [];
   $.post("header.php", "func=obtLotes&args="+encodeURIComponent(remate), function(data)
   {
      $("#slotes").attr("disabled", false);
      //$("#susers").attr("disabled", false);
      var $xml = $($.parseXML(data));
      var content = "";
      //var users = "";
      $xml.find("lote").each(function()
      {
          var descr_prod = $(this).find("descr").text();
          var len = Math.max(descr_prod.length, 40);
         content += "<option value=\""+$(this).find("id").text()+"\">Lote "+$(this).find("orden").text()+": "+descr_prod.substr(0,len)+"</option>\n";
         arrlotes.push({ id: $(this).find("id").text(), precio: $(this).find("precio").text(), disp: $(this).find("disp").text() });
      });
      
      //$xml.find("user").each(function()
      //{
      //  users += "<option value=\""+$(this).find("rut").text()+"\">("+$(this).find("rutc").text()+") "+$(this).find("nombrec").text()+"</option>\n";
      //});
      //$("#susers").html(users);
      
      $("#slotes").html(content);
      
      //if (users.length == 0)
      //{
      //   $("#susers").html("Remate sin participantes");
      //   $("#susers").attr("disabled", true);
      //}
      
      if (content.length == 0)
      {
         $("#slotes").html("Remate sin lotes");
         $("#slotes").attr("disabled", true);
      }
      $("#preciolote").html("Seleccione un lote");
   });
}
function act_precio(lote)
{
   var seleccionado = $(lote).val();
   for(var i = 0; i < arrlotes.length; i++)
   {
      x = arrlotes[i];
      if (x.id == seleccionado)
      {
         $("#preciolote").html("$"+currency(x.precio));
         $("#disponibles").html(x.disp);
         break;   
      }
   }
}
function send_adj()
{
   var lote = $("select[name='lote']").val();
   var cantidad = $("input[name='cantidad']").val();
   var rut = $("input[name='rutusuario']").val();
   var querystring = ["lote="+escape(lote), "cantidad="+escape(cantidad), "rut="+escape(rut)].join("&");
   $.post("send_adj2.php", querystring, function(data)
   {
      if (data == "yes")
      {
         alert("Adjudicacion realizada.");
      }
      else if (data == "no")
      {
         alert("Operacion fallida: no queda stock.");
      }
      else
         alert("Error: " + data);
   });
   // remate, rutusuario, cantidad, lote
   
}
function remunit()
{
   var lote = $("select[name='lote']").val();
   var rut = $("input[name='rutusuario']").val();
   $.post("remadj.php", ["lote="+escape(lote), "rut="+escape(rut)].join("&"), function(data)
   {
      if (data == "yes")
      {
         alert("Eliminacion realizada.");
      }
      else if (data == "no")
      {
         alert("Operacion fallida: La adjudicacion especificada no existe.");
      }
      else
         alert("Error: " + data);
   });
   
}
</script>
<h4>Adjudicaci&oacute;n manual</h4>
<p>Para generar adjudicaciones adicionales a las realizadas en el remate, ingrese los datos a continuaci&oacute;n:</p>
<form id="f_addadj" onsubmit="send_adj(); return false;">
<table class="tabla">
   <tr><td>Remate: </td>
            <td>
               <select name="remate" onblur="act_lotes(this);" onchange="act_lotes(this);">
               <?
               $semiadmin = "";
               if (!adminGeneral())
                $semiadmin = "and rut_owner = {$_SESSION['rut']}";
               $res = mysql_query("select id_remate, fecha, hora from remates where tipo != 'Presencial' $semiadmin", dbConn::$cn);
               $a_remates = array();
               while($row = mysql_fetch_assoc($res))
                  $a_remates[] = $row;
               mysql_free_result($res);
               foreach($a_remates as $r)
               {
                  $fechafull = implode("/", array_reverse(explode("-", $r['fecha'])))." a las ".$r['hora'];
                  echo "<option value=\"{$r['id_remate']}\">Remate n&deg; {$r['id_remate']} del $fechafull</option>\n";
               }
               ?>
               </select>
            </td></tr>
   <tr><td>Rut del usuario: </td><td><input type="text" name="rutusuario" id="susers"></td></tr>
   <tr><td>Lote: </td>
            <td>
               <select name="lote" disabled="disabled" id="slotes" onblur="act_precio(this);" onchange="act_precio(this);">
                  <option value="">Seleccionar remate...</option>
               </select>
            </td></tr>
   <tr><td>Precio: </td><td><span id="preciolote">Seleccione un lote</span></td></tr>
   <tr><td>Cantidad: (de <span id="disponibles">0</span> disponibles):</td><td><input type="text" name="cantidad" value="1" /></td></tr>
   <tr><td><input type="button" value="Eliminar 1 unidad" onclick="remunit();" class="submitea" /></td><td><input type="submit" class="submitea" value="Enviar Adjudicacion" /></td></tr>
</table>
</form>
