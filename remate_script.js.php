<? 
require_once("header.php"); 
function strip_opening_tag($str)
{
   return substr($str, strlen("<script type=\"text/javascript\">\n"));
}

ob_start("strip_opening_tag");
?>
<script type="text/javascript">
var arregloEventos = new Array();
var hiceSonido = false;
var arrei = 0;
var yaLoAbri = false;
var debug = true;
var gano = false;
var baseval = 1;
var loteactual = null;
var opcionesRemate = {
                        tipo_puja: 'Fijo',
                        valor_puja: 10000,
                        ultimo_estado: "0"
                     };
var montoActual = 0;
var noPasaNada = false;

var estado = 0;

var scrolling_lotes = true;

function makeSmall(string)
{
    var parts = string.split("/");
    parts.splice(1,0,"small");
    return parts.join("/");
}
function makeBig(string)
{
    var parts = string.split("/");
    parts.splice(1,1);
    return parts.join("/");
}
function cambiaFoto(index)
{
   var tmp = loteactual.fotos[0];
   loteactual.fotos[0] = makeBig(loteactual.fotos[index]);
   loteactual.fotos[index] = makeSmall(tmp);
   $("#fotogrande img").attr("src", loteactual.fotos[0]).hide().fadeIn(500);
   var ind = 1;
   $(".thumb img").each(function()
   {
      $(this).attr("src", loteactual.fotos[ind++]);
   });
}
function act_Producto(xmldoc)
{
   if (loteactual != null && scrolling_lotes == true) return;
   if (loteactual != null && loteactual.id_lote == parseInt($(xmldoc).find('loteactual').text()))
   // El lote no ha cambiado
      return;
   arrei++;
   updateLote();
   if (loteactual != null)
      montoActual = loteactual.precio_min;
   
}
function act_Eventos(xmldoc)
{
   // Extraer contenido
   // arregloEventos contiene los logs de eventos de cada lote, por su orden como indice. Comparar con el que corresponde pero mostrarlos todos.
   var content = "";
   $(xmldoc).find("eventos").each(function()
   {
      content += "<p>" + $(this).text() + "</p>\n";
   });
   if (arregloEventos[arrei] == undefined || arregloEventos[arrei].length < content.length)
      arregloEventos[arrei] = content;
   var total = "";
   for (var i = 0; i < arregloEventos.length; i++)
   if (arregloEventos[i] != undefined)
      total += arregloEventos[i];
   if ($("#eventos").html() != total)
   {
		
	   $("#eventos").html(total); //.prepend(arregloEventos.join(""));
	   // if ($("#eventos").find("p").last().text().search(/adjudicado/) != -1)
	   // document.getElementById("sonido_adj").play();
	   $("#eventos").scrollTop($("#eventos").scrollTop() + total.length);
   }
}

function act_Estado(xmldoc)
{
   // Posibilidades: no ha empezado, en curso, termino.
   var activo = ($(xmldoc).find("activo").text() == "1");
   if (!scrolling_lotes)
      montoActual = parseInt($(xmldoc).find("precioactual").text());
   else
      montoActual = parseInt(loteactual.precio_min);
   if (activo)
   {
      estado = 1;
      gano = false;
      // En curso. Ver si voy ganando, perdiendo o aun no pasa nada.
      if ($(xmldoc).find("ofertas").text() == "0") // Aun no pasa nada.
      {
         noPasaNada = true;
         $("#estado").html("Sin ofertas.");

         if (opcionesRemate.tipo_puja != "Sin Minimo")
         {
            $("#ofertador").attr("disabled", false);
            $("#oferta").attr("disabled", false);
            $("#oferta").attr("value", montoActual);
            $("#ofertador").removeClass("rojo verde").addClass("plomo");
            $("#ofertador").html("Ofertar $"+currency(montoActual+""));
         }
         else 
         {
            $("#ofertador").html("Ofertar");
            $("button.pujasmb").attr("disabled", false);
            $("#pujasm_1").html(currency((baseval)+""));
            $("#pujasm_2").html(currency((baseval*500)+""));
            $("#pujasm_3").html(currency((baseval*1000)+""));
         }
      }
      else // o gano o pierdo
      {
         noPasaNada = false;
         gano = ($(xmldoc).find("ganador").text() == "1");
         if (gano)
         {
            $("#estado").html("Usted posee la mejor oferta!").removeClass("plomoestado rojo").addClass("verde");
            $("#ofertador").attr("disabled", true).html("VA GANANDO");
            $("#oferta").attr("disabled", false);
            $("#ofertador").removeClass("plomo rojo").addClass("verde");
            if (opcionesRemate.tipo_puja == "Sin Minimo")
            {
               $("button.pujasmb").attr("disabled", true);
               if (montoActual != 1)
               {
                  $("#pujasm_1").html(currency((montoActual*opcionesRemate.valor_puja)+""));
                  $("#pujasm_2").html(currency(Math.round(montoActual*opcionesRemate.valor_puja*1.5)+""));
                  $("#pujasm_3").html(currency(Math.round(montoActual*opcionesRemate.valor_puja*2)+""));
               }
               else
               {
                  $("#pujasm_1").html(currency((baseval)+""));
                  $("#pujasm_2").html(currency((baseval*500)+""));
                  $("#pujasm_3").html(currency((baseval*1000)+""));
               }  
            }
         }
         else
         {
            $("#estado").html("VA PERDIENDO: su oferta ha sido superada!").removeClass("plomoestado verde").addClass("rojo");
            $("#ofertador").removeClass("plomo verde").addClass("rojo");
            if (opcionesRemate.tipo_puja != "Sin Minimo")
            {
               var incremento = 0;
               if (opcionesRemate.tipo_puja == "Fijo")
                  incremento = parseInt(opcionesRemate.valor_puja);
               else
                  incremento = Math.floor((parseFloat(opcionesRemate.valor_puja) / 100) * parseInt(loteactual.precio_min));
               incremento = parseInt((arregloEventos[arrei] != undefined && arregloEventos[arrei].length > 3 ? incremento / 2 : incremento));
               $("#oferta").attr("value", montoActual + incremento);
               $("#ofertador").attr("disabled", false).html("Ofertar $"+currency((montoActual+incremento)+""));
               $("#oferta").attr("disabled", false);
            }
            else
            {
               $("#ofertador").html("VA PERDIENDO");

               $("button.pujasmb").attr("disabled", false);
              if (montoActual != 1)
               {
                  $("#pujasm_1").html(currency((montoActual*opcionesRemate.valor_puja)+""));
                  $("#pujasm_2").html(currency(Math.round(montoActual*opcionesRemate.valor_puja*1.5)+""));
                  $("#pujasm_3").html(currency(Math.round(montoActual*opcionesRemate.valor_puja*2)+""));
               }
               else
               {
                  $("#pujasm_1").html(currency((baseval*1)+""));
                  $("button.pujasmb[data-puja='1']").attr("disabled", true);
                  $("#pujasm_2").html(currency((baseval*500)+""));
                  $("#pujasm_3").html(currency((baseval*1000)+""));
               }  
               
            }
         }
      }
   }
   else // o aun no empieza o ya termino
   {
      var termino = (parseInt($(xmldoc).find('tiempo').text()) == 0);
      $("#ofertador").attr("disabled", true).html("Ofertar");
      if (opcionesRemate.tipo_puja == "Sin Minimo")
      {
         $("#ofertador").html("Ofertar");
      }
      $("#ofertador").removeClass("rojo verde").addClass("plomo");
      $("#estado").removeClass("rojo verde").addClass("plomoestado");
      $("button.pujasmb").attr("disabled", true);

      if (termino)
      {
         $("#estado").html("El lote ha finalizado. Espere el siguiente.");
         estado = 2;
      }
      else
      {
         $("#estado").html("El lote a&uacute;n no comienza. Espere.");
         estado = 0;
      }
   }
   $("#pactual").html(currency(montoActual+""));
   
}
function act_Reloj(xmldoc)
{
   var tiempoF = $(xmldoc).find("tiempof").text();
   var cuantoFalta = $(xmldoc).find("cuantofalta").text();
   if (estado == 0) // aun no empieza
   {
      $("#sup_der").html(cuantoFalta);
   }
   else if (estado == 1) // en curso
   {
      $("#sup_der").html(tiempoF);
      if ($(xmldoc).find("tiempo").text() <= 10)
         $("#sup_der").addClass("pocotiempo");
      else
         $("#sup_der").removeClass("pocotiempo");
      var sumo = ($(xmldoc).find("sumo").text() == 1);
      if (sumo)
      {
         $("#sup_der + span").css({'color': 'black'}).show(0).delay(2000).hide(0);
      }
   }
   else // termino
   {
      $("#sup_der").html("00:00:00");
   }
}
function act_Chat(xmldoc)
{
   var textoChat = "";
   if ($(xmldoc).find("chat").text().length == 0)
   {
		$("#chat").html("");
		return;
	}
   $(xmldoc).find("chat").each(function()
   {
      var chatd = $(this).text().split("|");
      var mart = (chatd[2] != undefined ? " martmsg": "");
      var dest = (chatd[3] != undefined ? " a " + ( chatd[3] == "<?= consts::$data[6] ?>" ? "<?= consts::$data[6] ?>" : chatd[3]): "");
      mart = (chatd[3] != "undefined" && chatd[2] != undefined && chatd[3] != "<?= consts::$data[6] ?>" ? " pmsg" : mart);
      textoChat += "<p class=\"entry"+mart+"\"><strong>"+chatd[0]+dest+": </strong>"+chatd[1]+"</p>\n";
   });
   
   if (textoChat != $("#chat").html())
   {
      $("#chat").html(textoChat);
      var last = $(xmldoc).find("chat").last().text();
      var last_arr = last.split("|");
      $("#chat").scrollTop($("#chat").scrollTop() + textoChat.length);
      if (last_arr.length > 1 && last_arr[3] != undefined && last_arr[3] == "<?= consts::$data[6] ?>" && !hiceSonido) // es mensaje del martillero
      {
         //$.sound.play($("#sonido_msg").attr("src"));
         document.getElementById("sonido_msg").play();
      }
   }
}

function updateLoteCustom(lotenum)
{
   if (!scrolling_lotes) return;
   $.getJSON("lotesc.php", { id_remate: "<?=$_GET['id']?>", orden: lotenum }, function(obj)
   {

         loteactual = obj;

         loteactual.fotos[1] = makeSmall(loteactual.fotos[1]);
         loteactual.fotos[2] = makeSmall(loteactual.fotos[2]);
         loteactual.fotos[3] = makeSmall(loteactual.fotos[3]);

         $("#fotogrande img").attr("src", loteactual.fotos[0]);
         var ind = 1;
         $(".thumb img").each(function()
         {
            $(this).attr("src", loteactual.fotos[ind++]);
            $(this).unbind();
            $(this).click(function()
            {
               if ($(this).attr("src").length > 1)
                  cambiaFoto($(this).attr("index"));
            });
         });
         $("#descr").html("<p></p>");
         $("#descr p").html(loteactual.descripcion);
         $("#descr").prepend("<h3>Lote num. "+loteactual.orden+":  "+loteactual.nombre+"</h3>");
         // seguirle
         montoActual = loteactual.precio_min;
         $("#pactual").html(currency(montoActual+""));
   });
}
function updateLote()
{
   // Obtener info del lote
   $.ajax({
      type: "post",
      url: "lotes.php", 
      data: "id_remate=<?= $_GET['id'] ?>", 
      success: function(data)
   {

      if (data.substr(1, 4) == "?xml")
      {
		 yaLoAbri = false;
		 gano = false;
         var xmldoc = $.parseXML(data);
         opcionesRemate.tipo_puja = $(xmldoc).find('tipo_puja').text();
         opcionesRemate.valor_puja = parseFloat($(xmldoc).find('valor_puja').text());
         loteactual = {
                        'id_producto': $(xmldoc).find('id_producto').text(),
                        'id_lote': $(xmldoc).find('id_lote').text(),
                        'cantidad': $(xmldoc).find('cantidad').text(),
                        'nombre': $(xmldoc).find('nombre').text(),
                        'descripcion': $(xmldoc).find('descripcion').text(),
                        'fotos': new Array(
                                           $(xmldoc).find('foto1').text(),
                                           makeSmall($(xmldoc).find('foto2').text()),
                                           makeSmall($(xmldoc).find('foto3').text()),
                                           makeSmall($(xmldoc).find('foto4').text())
                                           ),
                        'precio_min': parseInt($(xmldoc).find('precio_min').text()),
                        'orden': parseInt($(xmldoc).find('orden').text())
                      };
         $("#fotogrande img").attr("src", loteactual.fotos[0]);
         var ind = 1;
         $(".thumb img").each(function()
         {
            $(this).attr("src", loteactual.fotos[ind++]);
            $(this).unbind();
            $(this).click(function()
            {
               if ($(this).attr("src").length > 1)
                  cambiaFoto($(this).attr("index"));
            });
         });
         $("#descr").html("<p></p>");
         $("#descr p").html(loteactual.descripcion);
         $("#descr").prepend("<h3>Lote num. "+loteactual.orden+":  "+loteactual.nombre+"</h3>");

         if (opcionesRemate.tipo_puja == "Sin Minimo")
         {
            $("#ofertador").html("Ofertar");
            $("button.pujasmb").attr("disabled", true);
            $("#pujasm_1").html(currency((baseval)+""));
            $("#pujasm_2").html(currency((baseval*500)+""));
            $("#pujasm_3").html(currency((baseval*1000)+""));
         }
      }
      else
      {
         $("#estado").html(data);
         return;
      }
      
   }, async: false});
   
}
function send()
{
   if ($("#msg").attr("value").length == 0) return;
   $.post("talk.php","id_remate=<?= $_GET['id'] ?>&receive="+escape($("#receive").val())+"&msg="+encodeURIComponent($("#msg").attr("value")), function(data)
   {
      if (data != "done")
      {
         alert ("Error:\n"+data);
      }
      else
      {
         
         $("#msg").attr("value", "");
         $("#msg").focus();
      }
   });
   //alert($("#chat").scrollTop());
   
}
function muestraSuma()
{
   $("#sup_der_2").html(" +8");
}
function ocultaSuma()
{
   $("#sup_der_2").html("");
}
function currency(number)
{
   number = parseInt(number)+"";
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
function updateauction()
{
   $.post("auction_updater.php", "id_remate=<?= $_GET['id'] ?>", function(data)
   {
      if (data.substr(1, 4) != "?xml")
      {
 //        $("#estado").html(data);
         return;
      }
      var xmldoc = $.parseXML(data);

      if (parseInt($(xmldoc).find("tiempo").text()) < 10*60) // ya no lo dejo pasar
      {
         scrolling_lotes = false;
         $(".flecha").hide(0);
      }
      if(parseInt($(xmldoc).find("kickme").text()) == 1)
      {
         alert("Has sido expulsado del remate.");
         window.history.back();
         window.close();
         return;
      }

      if ($(xmldoc).find("finremate").text() == "1")
      {
			if (opcionesRemate.ultimo_estado != $(xmldoc).find("finremate").text())
			{
				// Mostrar fin del remate
            alert("El remate ha terminado. En los próximos minutos se le enviará una nota de venta con las adjudicaciones realizadas. El martillero se encuentra disponible en la sala de chat para resolver dudas.");
			}
			opcionesRemate.ultimo_estado = $(xmldoc).find("finremate").text();
         
         act_Estado(xmldoc);
         act_Eventos(xmldoc);
         act_Chat(xmldoc);
         $("#conectados span").html($(xmldoc).find("nusers").text());
         $("#estado").html("El remate ha terminado.");
         return;
      }
      if ($(xmldoc).find("loteactual").text() == "-1")
      {
         $("#estado").html("Este remate no tiene lotes asociados.");
         return;
      }
      // datos obtenidos:
      // 'ganador' 'precioactual' 'eventos' 'tiempo' 'tiempof' 'cuantofalta' 'activo' 'sumo' 'chat' 'loteactual'
      // elementos de la pagina involucrados:
      // #titulo, #producto, #fotogrande, #oferta, #estado, #sup_der, #eventos, #chat
      act_Producto(xmldoc);
      act_Eventos(xmldoc);
      act_Estado(xmldoc);
      act_Reloj(xmldoc);
      act_Chat(xmldoc);
      var disp = $(xmldoc).find("cantidaddisp").text();
      $("#cantidadp").html(disp);
      $(".filabuttons input[type='radio']").each(function(event, ui)
      {
			if (parseInt($(this).attr("value")) > parseInt(disp))
				$(this).attr("disabled", true);
			else
				$(this).attr("disabled", false);
		});
      $("#tiempotoma").html($(xmldoc).find("trestanteganador").text());
      if ($(xmldoc).find("trestanteganador").text() != "-1" && !yaLoAbri)
      {
			abreDialogo();
			yaLoAbri = true;
	   }
	  if ($(xmldoc).find("trestanteganador").text() == "-1" && debug == true)
		cierraDialogo();
		
	  if ($(xmldoc).find("habilitar").text() == "1")
	  {
		  $("#derechof input:submit").attr("disabled", false);
        $("#but2").attr("disabled", false);
	  }
	  else if (!gano)
	  {
		  $("#derechof input:submit").attr("disabled", true);
        $("#but2").attr("disabled", true);
	  }
      $("#conectados span").html($(xmldoc).find("nusers").text());
   });
   setTimeout('updateauction()', 800);
   
}
function ofertar()
{
   // alert("oferta: " + parseInt($("#oferta").val()) + "\n" + "precio actual: " + PRECIO_ACTUAL);
   if ($("#oferta").val().length == 0) return;
   if (!noPasaNada && parseInt($("#oferta").val()) <= montoActual)
   {
      alert("Oferta muy baja");
      return;
   }
   $.post("auction_updater.php", "id_remate=<?= $_GET['id'] ?>&oferta="+escape($("#oferta").val())+"&lote="+escape(loteactual.id_lote+""), function(data)
   {
      if (data.substr(1, 4) != "?xml")
      {
         //alert(data);
         return;
      }
   });
}

function ofertar_sm(monto)
{
   if (!noPasaNada && monto <= montoActual) return;
   $.post("auction_updater.php", {id_remate: "<?= $_GET['id'] ?>", oferta: monto, lote: loteactual.id_lote}, function(data)
   {
      if (data.substr(1,4) != "?xml") return;
   });
}

$(document).ready(function()
{
   
   $("#oferta").keydown(function(event)
   {
      if (event.keyCode == 0) return false;
      if ((event.keyCode < 96 || event.keyCode > 105) && event.keyCode > 57)
      {
         return false;
      }
      $("#oferta").text(currency($("#oferta").text()));
   });
   $(".thumb").attr("title", "Haga clic para ver en el recuadro grande");
   updateLote();
   updateauction();
   $(".filabuttons input").click(function(event)
   {
      $(this).attr("checked", "checked");
      envia_derecho(); 
   });
   $("#but2").attr("disabled", true);
	$("#derechof input:submit").attr("disabled", true);
   cierraDialogo();

   if (opcionesRemate.tipo_puja != "Sin Minimo")
   {
      
      $("#pop_sm p, #pop_sm table").hide();
   }
   else
   {
      $("#pop_sm p, #pop_sm table").show();
      $("button.pujasmb").click(function()
      {
         var idx = $(this).attr("data-puja");
         var vals = { "1": 1, "2": 1.5, "3": 2};
         var vals_ini = {"1": 1, "2": 500, "3": 1000};
         var basem = montoActual <= baseval ? baseval : montoActual;
         var mult = noPasaNada ? 1 : opcionesRemate.valor_puja;
         var monto = montoActual == 1 ? parseInt(basem*mult*vals_ini[idx]) : parseInt(Math.round(basem*mult*vals[idx]));
         ofertar_sm(monto);
      });
   }

   // botones de edicion de lote
   if (scrolling_lotes)
   {
      $(".flecha a").click(function()
      {
         var idd = $(this).attr("id");
         if (idd == "lote_anterior")
         {
            updateLoteCustom(parseInt(loteactual.orden) - 1);
         }
         else
         {
            updateLoteCustom(parseInt(loteactual.orden) + 1);
         }
      });
   }


});
function abreDialogo()
{
   $("#pop").removeClass("onlylogo").addClass("nologo");
   $("#pop *").show(0);
	if (gano)
	{
		$("#pop span.tex").eq(1).hide();
		$("#pop span.tex").eq(0).show();
		$("#derechof input:submit").removeAttr("disabled");
      $(".numerico input").attr("name", "cantidad");
      $(".numerico").show();
      $("#cantidadpordefecto").removeAttr("name");
      $("#pad_perdedor").hide();
      $("#gcancel").show();
      $("#cantidadd").hide();
	}
	else
	{
      $("#cantidadd").show();
      $("#texto_p").html("Lote " + loteactual.orden + " disponible: ");
      $(".numerico input").removeAttr("name");
      $(".numerico").hide();
      $("#cantidadpordefecto").attr("name", "cantidad");
      $("#pad_perdedor").show(0);
      $("#gcancel").hide();
		$("#pop span.tex").eq(0).hide();
		$("#pop span.tex").eq(1).show();
	}
}
function cierraDialogo()
{
   $("#pop").removeClass("nologo").addClass("onlylogo");
   $(".numerico input").removeAttr("checked");
   $("#pop *").hide(0);
}
function envia_derecho()
{
	var eleccion = $("#derechof").serialize();
   // alert(eleccion);
	$.post("send_adj.php", "lote="+escape(loteactual.id_lote)+"&"+eleccion, function(data)
	{
		var el = eleccion.split("=");
		if (data == "yes")
		{
			alert("Ha adjudicado " + el[1] + " unidad(es) del producto.");
		}
		else if (data == "no")
		{
			alert("Las unidades se han agotado o ya adjudicó en este lote. No pudo adjudicar.");
		}
		else
		{
			alert("Error al adjudicar: " + data);
		}
		cierraDialogo();
	});
}
<?
ob_end_flush();
?>
