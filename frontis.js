var Chat = {
	context: null,
	username: null,
	myTimeStamp: null,
	getMsgHandler: null,
	interval: null,
	create: function(ctx, tpl, i)
	{
		this.context = $(ctx).get();
		this.username = "Usuario" + parseInt(Math.random()*10000);
		this.myTimeStamp = (new Date()).getTime();
		this.msgTemplate = $(tpl, this.context).html();
		this.interval = i;
		this.getMsgHandler = setInterval(this.getLastMessages, this.interval * 1000);

		var ref = this;

		$("button[data-action='send']").on("keydown", function(ev)
		{
			if (ev.which == 13)
			{
				ev.preventDefault();
				ref.sendMsg();
			}
		});
	},
	showError: function(err)
	{
		this.addMessage({
			from: "Sistema",
			msg: err
		});
	},
	sendMsg: function()
	{
		var msg = $("button[data-action='send']", this.context);
		$.getJSON("chat/sendmsg.php", {
				message: msg,
				user: this.username
			}, 
			function(retObj)
			{
				if (retObj.error)
					this.showError(retObj.error);
				else
				{
					$("button[data-action='send']", this.context).val("");
				}
			}
		);	
	},
	getLastMessages: function()
	{
		var ref = this;
		$.getJSON("chat/getmsg.php", {
				ts: this.myTimeStamp
			},
			function(retObj)
			{
				if (retObj.error)
					ref.showError(retObj.error);
				else
				{
					ref.myTimeStamp = (new Date()).getTime();
					for (var i = 0; i < retObj.messages.length; i++)
					{
						ref.addMessage(retObj.messages[i]);
					}
				}
			}
		);
	},
	addMessage: function(msg)
	{
		var completemsg = this.msgTemplate.replace("%from%", msg.from).replace("%msg%", msg.msg);
		$(".chatbox", this.context).append(completemsg);
	}
};