function fill(num)
{
	if (num > 9)
		return ""+num;
	else
		return "0"+num;
}
function clockme()
{
var d = new Date();
$("#clock span").html((fill(d.getDate())+"/"+fill(d.getMonth()+1)+"/"+d.getFullYear()+" "+fill(d.getHours())+":"+fill(d.getMinutes())+":"+fill(d.getSeconds())));
setTimeout('clockme()',1000);
}
clockme();