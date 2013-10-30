<? include("header.php"); ?>
// El script del martillero agrega la vision de numero y lista de usuarios conectados,
// el acceso a los modulos clientes/productos/remates, botar usuarios, 
// setear las propiedades de un remate antes de que comience (tipo de puja y cantidad, 
// tiempo por lote) y poder pasar lotes manualmente.
var timeout_instance = null;
var arregloEventos = new Array();
var arrei = 0;
var pausedFlag = false;
var loteactual = null;
var colorGris = "#7F7F7F";
var colorRojo = "#FF2020";
var colorVerde = "#37FF00";
var montoActual = 0;
var noPasaNada = false;
var estado = 0;
var selectInactivo = true;
var lastFactor = <?= consts::$factor_usuarios ?>;
function updatefactor()
{
    var factor = $(this).val();
    if (parseFloat(factor) > 0)
    {
        $.getJSON("alterafactor.php", { f: parseFloat(factor) } , function(obj)
        {
            $(this).val(obj.res);
            lastFactor = obj.res;
            console.log(obj);
        });
    }
    else
        $(this).val(lastFactor);
}
function goto(url)
{
   $.get(url, function(data)
	{
		var heads = "";
		var prop = 2.0 / 3.0;
		$("#visors").html("<div id='content'></div>");
		$("#visors div[id='content']").html(data).css(
			{
				'width': ($(window).width() - 20)+'px',
				'height': ($(window).height() * prop - 30)+'px'
			});
		$("#visors").prepend(heads).append("<div id=\"closev\">X</div>").css(
			{
				'width': '100%',
				'height': ($(window).height() * prop)+"px"
			}).show();
	});
}
function abrir_visor(tipo)
{
	goto("administra.php?tipo="+escape(tipo));
}

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
    return parts.join("/");s
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
	   // $("#eventos").scrollTop($("#eventos").scrollTop() + total.length);
   }
}

function act_Estado(xmldoc)
{
   // Posibilidades: no ha empezado, en curso, termino.
   var activo = ($(xmldoc).find("activo").text() == "1");
   montoActual = parseInt($(xmldoc).find("precioactual").text());
   if (activo)
   {
      estado = 1;
      // En curso. Ver si voy ganando, perdiendo o aun no pasa nada.
      
      
      if ($(xmldoc).find("ofertas").text() == "0") // Aun no pasa nada.
      {
         noPasaNada = true;
         $("#estado").html("Sin ofertas.");
         $("#ofertador").attr("disabled", false);
         $("#oferta").attr("disabled", false);
         $("#ofertador").css("background-color", colorGris);
      }
      else // o gano o pierdo
      {
         noPasaNada = false;
         var gano = ($(xmldoc).find("ganador").text() == "1");
         if (gano)
         {
            $("#estado").html("Usted posee la mejor oferta!");
            $("#ofertador").attr("disabled", true);
            $("#oferta").attr("disabled", false);
            $("#ofertador").css("background-color", colorVerde);
         }
         else
         {
            $("#estado").html("Su oferta ha sido superada!");
            $("#ofertador").attr("disabled", false);
            $("#ofertador").css("background-color", colorRojo);
            $("#oferta").attr("disabled", false);
         }
      }
   }
   else // o aun no empieza o ya termino
   {
      var termino = (parseInt($(xmldoc).find('tiempo').text()) == 0);
      $("#ofertador").attr("disabled", true);
      $("#ofertador").css("background-color", colorGris);
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
      var sumo = ($(xmldoc).find("sumo").text() == 1);
      if (sumo)
      {
         $("#sup_der + span").show(0).delay(2000).hide(0);
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
   if ($(xmldoc).find("chat").text().length == 0) return;
   $(xmldoc).find("chat").each(function()
   {
		var chatd = $(this).text().split("|");
      var mart = (chatd[2] != undefined ? " martmsg": "");
      var dest = (chatd[3] != undefined ? " a "+chatd[3] : "");
      mart = (chatd[3] != "undefined" && chatd[2] != undefined && chatd[3] != "<?= consts::$data[6] ?>" ? " pmsg" : mart);
      textoChat += "<p class=\"entry"+mart+"\"><strong>"+chatd[0]+dest+": </strong>"+chatd[1]+"</p>";
   });
      
   if (textoChat != $("#chat").html())
   {
      $("#chat").html(textoChat);
      var last = $(xmldoc).find("chat").last().text();
      var last_arr = last.split("|");
      if (last_arr.length > 1 && last_arr[3] != undefined && last_arr[3] == "<?= consts::$data[6] ?>") // es mensaje del martillero
		document.getElementById("sonido_msg").play();
      // $("#chat").scrollTop($("#chat").scrollTop() + textoChat.length);
   }
}
function act_Logged(xmldoc)
{
	$("#cant").html($(xmldoc).find("nusers").text());
	var textoUsers = "";
	var textoUsersSelect = "";
	$(xmldoc).find("users").each(function()
	{
		textoUsersSelect += "<option value=\""+$(this).text()+"\">"+$(this).text()+"</option>";
		textoUsers += "<p class=\"ban\">"+$(this).text()+"</p>\n";
	});
   
	if ($("#list").html() != textoUsers)
		$("#list").html(textoUsers);
	if ($("#receive").html() != textoUsersSelect && selectInactivo)
		$("#receive").html(textoUsersSelect);
}
function banear(rut)
{
	// banea!
   if (confirm("Seguro que desea BANEAR al usuario "+rut+"?"))
   {
	   $.post("header.php", "func="+escape("banea")+"&args="+escape(rut), function(data)
      {
         if (data == "done")
            alert("Usuario " + rut + " baneado.");
         else
            alert("No es posible banear su propia cuenta.");
      });
   }
}
function congelar(rut)
{
    if (confirm("Seguro que desea congelar al usuario "+rut+"?"))
    {
        $.post("header.php", { func: "congela", args: rut }, function(data)
        {
            if (data == "done")
                alert("Usuario " + rut + " congelado.");
            else
                alert("Problemas al intentar congelar al usuario.");
        });
    }
}
function toggleAuctionState(id)
{
   $.post("header.php", "func="+encodeURIComponent("toggle_remate")+"&args="+encodeURIComponent(id+""), function(data)
   {
      if (data != "done")
         alert("Error al intentar alternar el estado del remate: " + data);
      else
      {
         pausedFlag = !pausedFlag;
         $("#pause").attr("value", (pausedFlag === true ? "Reanudar Remate" : "Pausar Remate"));
      }
   });
}
function updateLote()
{
   // Obtener info del lote
   $.post("lotes.php", "id_remate=<?= $_GET['id'] ?>", function(data)
   {
      if (data.substr(1, 4) == "?xml")
      {
         var xmldoc = $.parseXML(data)
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
         // seguirle1
      }
      else
      {
         $("#estado").html(data);
         return;
      }
      
      
   });
}
function send()
{
   if ($("#msg").attr("value").length == 0 || $("#receive").val() == "Seleccione destinatario") return;
   var menj = $("#msg").attr("value");
   $("#msg").attr("value", "");
   $.post("talk.php","id_remate=<?= $_GET['id'] ?>&receive="+escape($("#receive").attr("value"))+"&msg="+encodeURIComponent(menj), function(data)
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
function sendb()
{
	if ($("#broadcast").attr("value").length == 0) return;
    var bcmg = $("#broadcast").attr("value");
    $("#broadcast").attr("value", "");
   $.post("talk.php","msg="+encodeURIComponent(bcmg)+"&broadcast=<?= $_GET['id'] ?>", function(data)
   {
      if (data != "done")
      {
         alert ("Error:\n"+data);
      }
      else
      {
         
         $("#broadcast").attr("value", "");
         $("#broadcast").focus();
      }
   });
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
          $("#estado").html(data);
   //alert(data);
         return;
      }
      var xmldoc = $.parseXML(data);
      // datos obtenidos:
      // 'ganador' 'precioactual' 'eventos' 'tiempo' 'tiempof' 'cuantofalta' 'activo' 'sumo' 'chat' 'loteactual'
      // elementos de la pagina involucrados:
      // #titulo, #producto, #fotogrande, #oferta, #estado, #sup_der, #eventos, #chat
      act_Producto(xmldoc);
      act_Eventos(xmldoc);
      act_Estado(xmldoc);
      act_Reloj(xmldoc);
      act_Chat(xmldoc);
      // solo para admins
      act_Logged(xmldoc);
      
   });
   timeout_instance = setTimeout('updateauction()', 500);
}
function ceder_lote()
{
   $.post("header.php", "func="+escape("pasar_sgte_lote")+"&args="+escape("<?= $_GET['id'] ?>;true"), function(data)
   {
      alert("Lote saltado.");
   });
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
$(document).ready(function()
{
   
   $("#oferta, #npuja, #durlote").keydown(function(event)
   {
      if (event.keyCode == 0) return false;
      if ((event.keyCode < 96 || event.keyCode > 105) && event.keyCode > 57)
      {
         return false;
      }
   });
   $("#closev").live('click',function()
    {
        $("#visors").hide();
    });
    
    $(".ban").live('click',function(event)
    {
        banear($(this).html());
    }).live("contextmenu", function(event)
    {
        congelar($(this).html());
    });
   $("p[ty]").click(function(event)
   {
		abrir_visor($(this).attr("ty"));
	});   
   $("#receive").focus(function()
   {
   	selectInactivo = false;
   });
   $("#receive").blur(function()
   {
   	selectInactivo = true;
   });
	$("#pause").click(function()
   {
      toggleAuctionState(<?= $_GET['id'] ?>);
   });
   $("#changefact").change(updatefactor);
   updateLote();
   updateauction();
});
