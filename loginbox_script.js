var banContent = "";
var texto = "";
$.post("header.php","func="+escape("get_const")+"&args="+escape("mensajes;3"), function(data)
{
   texto = data;
});
function showBanMessage()
{
    $("#bc").css(
    {
        position: 'absolute',
        top: '70px',
        width: '500px',
        left: (($("#main").width() - 500) / 2) + 'px',
        background: '#FFFFFF',
        border: '1px solid #000000'
    }).show();
    $("#bc").bind("click",function()
    {
        $(this).hide();
    });
}
function showDisabledMessage()
{
    $("#dc").css(
    {
        position: 'absolute',
        top: '70px',
        width: '500px',
        left: (($("#main").width() - 500) / 2) + 'px',
        background: '#FFFFFF',
        border: '1px solid #000000'
    }).show();
    $("#dc").bind("click",function()
    {
        $(this).hide();
    });
}
function do_login()
{ 
    $.post
        ("header.php",
        "func=login&args="+
        escape
        (
            $("#rut_u").attr("value")
            +";"+
            $("#pass_u").attr("value")
        ),
        function(data)
        {
            if (data == texto)
            {
                location.reload();
            }
            else if (data == "Usuario bloqueado")
            {
                showBanMessage();
            }
            else if (data == "Usuario inhabilitado")
            {
                showDisabledMessage();
            }
            else
            {
                $("#mensajes").html(data);
            }
        });
}

function do_logout()
{
   $.post("header.php", "func=logout", function(data)
   {
      location.href="/frontis.php";
   });
}
