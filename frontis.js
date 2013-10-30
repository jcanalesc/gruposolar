var Chat = {
	context: null,
	username: null,
	myTimeStamp: null,
	getMsgHandler: null,
	interval: null,
	create: function(ctx, tpl, i)
	{
		Chat.context = $(ctx).get();
		Chat.username = "Invitado";
		Chat.myTimeStamp = 0;
		Chat.msgTemplate = $(tpl, Chat.context).html();
		Chat.interval = i;
		Chat.getMsgHandler = setInterval(Chat.getLastMessages, Chat.interval * 1000);

		$(".chatbox-controls input", Chat.context).on("keydown", function(ev)
		{
			if (ev.which == 13)
			{
				Chat.sendMsg();
			}
		});

		$(".chatbox-controls button[data-action='send']").click(function()
		{
			Chat.sendMsg();
		});
	},
	showError: function(err)
	{
		Chat.addMessage({
			from: "Sistema",
			msg: err
		});
	},
	sendMsg: function()
	{
		var msg = $(".chatbox-controls input", Chat.context).val();
		$.getJSON("chat/sendmsg.php", {
				message: msg,
				user: Chat.username,
				ts: (new Date()).getTime()
			}, 
			function(retObj)
			{
				if (retObj.error)
					Chat.showError(retObj.error);
				else
				{
					$(".chatbox-controls input", Chat.context).val("");
					Chat.getLastMessages();
				}
			}
		);	
	},
	addMessage: function(msg)
	{
		var completemsg = Chat.msgTemplate.replace("%from%", msg.from).replace("%msg%", msg.msg);
		$(".chatbox", Chat.context).append(completemsg);
	},
	getLastMessages: function()
	{
		$.getJSON("chat/getmsg.php", {
				ts: Chat.myTimeStamp
			},
			function(retObj)
			{
				if (retObj.error)
					Chat.showError(retObj.error);
				else
				{
					var newts = Chat.myTimeStamp;
					for (var i = 0; i < retObj.messages.length; i++)
					{
						Chat.addMessage(retObj.messages[i]);
						// console.log("timestamp: " + retObj.messages[i].ts);
						if (parseInt(retObj.messages[i].ts) > newts)
							newts = parseInt(retObj.messages[i].ts);
					}
					Chat.myTimeStamp = newts;
				}
			}
		);
	}	
};
/*
<div class="mauction" data-idma="%idmr%">
	<p class="textochico">GANADOR ACTUAL: %ganador_actual%</p>
	<img src="%imagen%" width="100" height="100">
	<p class="nombrep">%nombre_producto%</p>
	<p class="restante">RESTAN: <span class="tiempor"></span></p>
	<p class="ficha">VER FICHA</p>
	<p class="oferta">Oferta actual: $%oferta_actual% + IVA</p>
	<button>MEJORAR OFERTA A <span>$%oferta_mejorar%</span> + IVA</button>
</div>
*/

function format_currency(str)
{
	var data = str.split("").reverse();
	var result = "";
	for (var i = 0; i < data.length; i++)
	{
		result += data[i];
		if (i > 0 && (i+1) % 3 == 0 && i < data.length - 1)
		{
			result += ".";
		}
	}
	return result.split("").reverse().join("");
}

function mask_rut(str)
{
	var data = str.split("").reverse();
	data[0] = data[1] = data[2] = "X";
	return data.reverse().join("");
}
var Mremates = {
	template: "",
	updateMRHandler: null,
	interval: 0,
	context: null,
	create: function(ctx, tpl, i)
	{
		Mremates.context = $(ctx).get();
		Mremates.template = $(tpl, Mremates.context).html();
		Mremates.interval = i;
		Mremates.updateMRHandler = setInterval(Mremates.getMRData, Mremates.interval * 1000);

	},
	getMRData: function()
	{
		$.getJSON("miniauction_updater.php", function(obj)
		{
			/*
122108: Object
delta: "134"
fecha_inicio: "2013-10-24 22:05:48"
fecha_termino: "2013-10-31 01:00:00"
foto: "uploads/minir/icon.png"
id_miniremate: "122108"
id_producto: "1"
incremento: "1"
killme: "0"
limpio: "1"
monto: "$10.100"
monto2: "$10.200"
monto_actual: "10100"
monto_inicial: "10000"
restante: "146:51:57"
rut_ganador: "7531868"
rutoculto_puntos: "7.531.XXX"
texto: "Escriba descripción"
titulo: "ESCRIBA TíTULO"
yogano: false
*/
			for (mrid in obj)
			{
				Mremates.updateMRBox({
					id: mrid,
					foto: obj[mrid].foto,
					nombre: obj[mrid].titulo,
					alive: !(obj[mrid].killme == "1"),
					ganador: obj[mrid].rut_ganador,
					oferta_actual: obj[mrid].monto,
					oferta_mejorar: obj[mrid].monto2,
					tiemporestante: obj[mrid].restante,
					ganando: obj[mrid].yogano
				});
			}
		});
	},
	updateMRBox: function(boxdata)
	{
		var target = $(".mauction[data-idma='"+boxdata.id+"']", Mremates.context);
		if (target.length == 0)
		{
			// create element
			var htmldata = Mremates.template.replace("%idmr%", boxdata.id)
											.replace("%imagen%", boxdata.foto)
											.replace("%nombre_producto%", boxdata.nombre);
			$(Mremates.context).append(htmldata);
			target = $(".mauction[data-idma='"+boxdata.id+"']", Mremates.context);
			$("img, p.ficha", target.get()).click(function()
			{
				$.get("ficha_miniremate.php", { id: target.attr("data-idma") }, function(data)
				{
					$.fancybox(data);
				});
			})
			$("button", target.get()).click(function()
			{
				if ($(this).data("ganando") == true) return;
				var valoractual = $(this).find("span").html();
				valoractual = parseInt(valoractual.substr(1).split(".").join(""));
				var texto = $(this).text().toLowerCase();
				if (confirm("¿Está seguro que desea " + texto + "?"))
					if (confirm("Presione 'Aceptar' para confirmar su oferta."))
						$.getJSON("miniauction_bid.php", {
								id_miniremate: target.attr("data-idma"),
								oferta: valoractual
							},
							function(obj)
							{
								if (obj && obj.error)
									alert(obj.error);
							}
						);
			});
		}
		// update box time/winner/bid/visibility
		if (boxdata.alive)
		{
			$(".ganador_actual", target.get()).html(mask_rut(boxdata.ganador));
			$(".oferta_actual", target.get()).html(boxdata.oferta_actual);
			$(".oferta_mejorar", target.get()).html(boxdata.oferta_mejorar);
			$(".tiempor", target.get()).html(boxdata.tiemporestante);
			if (boxdata.ganando)
			{
				$("button", target.get()).data("ganando", true)
					.addClass("button-ganando").html("VA GANANDO");
			}
			else
			{
				$("button", target.get()).data("ganando", false)
					.removeClass("button-ganando").html()

			}
		}
		else
		{
			target.remove();
		}

	}
};