// asociado a remate_edit.php
var arrlotes = new Array();
var heads = "<tr><td><input type=\"checkbox\" id=\"s_all\" /></td><td colspan=\"2\">Orden</td><td>Mover a</td><td>Elim.</td><td>Cantidad</td><td>ID Producto</td><td>Nombre</td></tr>";
function updateChart()
{
   var content = heads;
   // alert(arrlotes);
   var elem = 0;
   
   if (arrlotes.length == 0)
   {
      content += "<tr><td colspan=\"8\">No hay elementos.</td></tr>";
      $("#lotes").html(content);
      return;
   }
   for(elem = 0; elem < arrlotes.length; elem++)
   {
      content += "<tr>"
                        +"<td><input class=\"lote_cbox\" type=\"checkbox\" id=\"s"+elem+"\" /></td>"
                        +"<td width=\"24\"><div class=\"btn goup\" id=\"u"+elem+"\">&nbsp;</div></td>"
                        +"<td width=\"24\"><div class=\"btn godown\" id=\"d"+elem+"\">&nbsp;</div></td>"
                        +"<td><input type=\"text\" size=\"5\" value=\""+elem+"\" onchange=\"multimove(this, "+elem+");\" /></td>"
                        +"<td width=\"24\"><div width=\"24\" class=\"btn del\" id=\"e"+elem+"\">&nbsp;</div></td>"
                        +"<td><input type=\"text\" size=\"5\" value=\""+arrlotes[elem].cantidad+"\" onchange=\"update_val(this,"+elem+");\" /></td>"
                        +"<td>"+arrlotes[elem].id_producto+"</td>"
                        +"<td>"+arrlotes[elem].descripcion+"</td>"
                        +"</tr>";
   }
   $("#lotes").html(content);
   $(".goup").one('click',function(event)
   {
      var rid = $(this).attr("id");
      go_up(rid.substr(1,rid.length - 1));
   });
   $(".godown").one('click',function(event)
   {
      var rid = $(this).attr("id");
      go_down(rid.substr(1,rid.length - 1));
   });
   $(".del").one('click',function(event)
   {
      var rid = $(this).attr("id");
      erase(rid.substr(1,rid.length - 1));
   });
   $(".lote_cbox").one('click',function(event)
   {
      toggle(this);
   });
   $("#s_all").one('click',function (event)
   {  
      var check = $(this).attr("checked");
      $(".lote_cbox").each(function(item)
      {
         if ($(this).attr("checked") ? !check : check)
         {
            var v = $(this).attr("checked");
            $(this).attr("checked", !v);
            toggle(this);
         }
      });
   });
   if ($("#lotes").attr("height") > $(window).height() - 40)
   {
      $("#lotes").css({ 'height': ($(window).height() - 40)+"px", 'overflow': 'auto' });
   }
}
function getXMLData(persist)
{
   $.post("header.php", "func="+escape("getAllXML")+"&args="+escape("productos;"+(persist == true ? "true" : "false")), function(data)
   {
      if (data.substr(1, 4) != "?xml")
      {
         alert(data);
         return;
      }
      arrlotes = new Array();
      var xmldoc = $.parseXML(data);
      var texto = $(xmldoc).find("nuevo").text() == "1" ? "Creando nuevos lotes para el remate" : "Editando lotes existentes";
      $("#enc").html(texto);
      $(xmldoc).find("elem").each(function()
      {
         arrlotes.push({
                        "id_producto": $(this).find("id_producto").text(),
                        "descripcion": $(this).find("descripcion").text(),
                        "cantidad": $(this).find("cantidad").text() // si se prefiere
                       });
      });
      updateChart();
   });
}
function go_up(index)
{
   index = parseInt(index);
   //alert("parriba el "+index);
   if (index < 0 || index >= arrlotes.length || index == 0) return;
   var aux = arrlotes[index - 1];
   arrlotes[index - 1] = arrlotes[index];
   arrlotes[index] = aux;
   updateChart();
}

function go_down(index)
{
   index = parseInt(index);
   //alert("pabajo el "+index);
   if (index < 0 || index >= arrlotes.length || index == arrlotes.length - 1) return;
   var aux = arrlotes[index];
   arrlotes[index] = arrlotes[index + 1];
   arrlotes[index + 1] = aux;
   updateChart();
}

function erase(index)
{
   index = parseInt(index);
   // alert("se muere el "+index);
   //alert("a");
   if (arrlotes.length == 1)
      arrlotes.pop();
   else
      arrlotes.splice(index, 1);
   updateChart();
}
function update_val(obj,index)
{
   index = parseInt(index);
   // se asume que es llamado por el input
   var cant = parseInt($(obj).val());
   arrlotes[index].cantidad = cant;
   updateChart();
   // alert(arrlotes[index].cantidad);
}
function multimove(obj, origen)
{
   var destino = parseInt($(obj).val());
   var tmp = arrlotes[origen];
   arrlotes.splice(origen, 1);
   arrlotes.splice(destino, 0, tmp);
   updateChart();
}
function multiDel()
{
   var nuevoarreglo = new Array();
   $(".lote_cbox").each(function()
   {
      if ($(this).attr("checked")) return;
      var rid = $(this).attr("id");
      rid = parseInt(rid.substr(1,rid.length - 1));
      nuevoarreglo.push(arrlotes[rid]);
   });
   arrlotes = nuevoarreglo;
   updateChart();
}

function submitLote()
{
   var ok = true;
   $("#lotes input[type='text']").each(function()
   {
      if ($(this).val().length < 1)
      {
         alert("Hay campos en blanco");
         ok = false;
         return false;
      }
   });
   if (!ok) return;
   // se crean trios (orden, cantidad, id) y se ponen en un solo string
   var triadas = new Array();
   var i = 0;
   for (i = 0; i < arrlotes.length; i++)
   {
      triadas.push([ i,
                     arrlotes[i].cantidad,
                     arrlotes[i].id_producto
                   ].join(","));
   }
   var serializado = triadas.length > 0 ? triadas.join("|") : "";
   // alert(serializado);
   $.post("header.php", "func="+escape("guarda_lotes")+"&args="+escape(serializado), function(data)
   {
      $("#floating div").html(data);
   });
}
function eliminaAcciones()
{
   if (confirm("¿Esta seguro que desea borrar las acciones asociadas a este remate?\n"+
               "Si en el remate hubieron adjudicaciones, seran perdidas."))
   {
      $.post("header.php", "func="+escape("borra_acciones_remate")+"&args=edited", function(data)
      {
         if (data != "done")
            alert(data);
         else
            alert("Acciones asociadas a este remate borradas correctamente.\n"+
                  "Ahora es posible eliminar los lotes.");
      });
   }
}

