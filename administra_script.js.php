<?
   function sanitiza_input_numerico($arr)
   {
      foreach($arr as $v)
      {
         echo <<<END
         $("#$v").keydown(function(event)
         {
            if (event.keyCode == 0) return false;
            if ((event.keyCode < 96 || event.keyCode > 105) && event.keyCode > 57)
            {
               return false;
            }
         });
END;
      }
   }
?>
function calendario(id_objetivo)
{
   new JsDatePick({
			useMode:2,
			target:id_objetivo,
			dateFormat:"%Y-%m-%d",
			yearsRange:[2011,2020]
		});
      $("#"+id_objetivo).addClass("clickea");
}
function hora(id_objetivo)
{
   $("#"+id_objetivo).timePicker(
      {
         step: 15
      }).addClass("clickea");
}
   

function toggle(obj)
{
   if (!$(obj).attr("checked"))
      $(obj).parent().parent().css("background-color", "#FFFFFF");
   else
      $(obj).parent().parent().css("background-color", "#C4FF00");
}

$(":not(#i_all):checkbox").click(function (event)
{
   toggle(this);
});

$("#i_all").click(function (event)
{
   var check = $(this).attr("checked");
   $(":not(#i_all):checkbox").each(function(item)
   {
      if ($(this).attr("checked") ? !check : check)
      {
         var v = $(this).attr("checked");
         $(this).attr("checked", !v);
         toggle(this);
      }
   });
});

$("#add").click(function(event)
{
   $.post("agrega.php","tipo=<?= urlencode($_GET['tipo']) ?>", function(data)
   {
      $("#floating div").html(data);
      $("#floating").css({
            position: "fixed",
            top: "10px",
            left: (($(window).width() - 460)/2)+"px",
            width: "460px",
            height: "95%"
        }).show("slow");
      <?
      switch($_GET['tipo'])
      {
         case "users": break;
         case "productos": break;
         case "remates":
            echo "calendario('ffecha');\n";
            echo "hora('fhora');\n";
         break;
      }
      if ($_GET['tipo'] == "remates")
      {
         sanitiza_input_numerico(array("fduracion_lote"));
      }  
      if ($_GET['tipo'] == "productos")
      {
         sanitiza_input_numerico(array("fprecio_min"));
      }
      ?>
   });
});
$("#del").click(function(event)
{
   var fields = new Array();
   $(":not(#i_all):checkbox[checked]").each(function()
   {
      fields.push($(this).attr("id"));
   });
   if (confirm("Esta seguro que desea eliminar los elementos de ID's:\n" + fields.join("\n") ) )
   {
      $.post("writer.php", "ftipo=<?= urlencode($_GET['tipo']) ?>&felements=" + escape(fields.join(";")) , function(data)
      {
         goto("administra.php?tipo=<?= urlencode($_GET['tipo']) ?>");
         $.fancybox(data);
         
      });
   }
});

$(".edit").click(function(event)
{
   //alert("Editando a " + $(this).attr("id"));
   var real_id = $(this).attr("id").substr(1,$(this).attr("id").length);
   //alert(real_id);
   $.post("modifica.php","tipo=<?= urlencode($_GET['tipo']) ?>&id="+escape(real_id), function(data)
   {
      $("#floating div").html(data);
      $("#floating").css({
            position: "fixed",
            top: "10px",
            left: (($(window).width() - 460)/2)+"px",
            width: "460px",
            height: "95%"
        }).show("slow");
      <?
      switch($_GET['tipo'])
      {
         case "users": break;
         case "productos": break;
         case "remates":
            echo "calendario('ffecha');\n";
            echo "hora('fhora');\n";
         break;
      }
      if ($_GET['tipo'] == "remates")
      {
         sanitiza_input_numerico(array("fduracion_lote"));
      }  
      if ($_GET['tipo'] == "productos")
      {
         sanitiza_input_numerico(array("fprecio_min"));
      }
      ?>
   });
});

function compara_pass()
{
   return ($("#fpassword").val() == $("#fpassword2").val());
   
}
// Asociado a agrega.php:
function do_add()
{
   
   // tengo la info de los inputs fColumna, en el form de id add_form
   if (compara_pass())
   {
      if (confirm("Esta seguro de ingresar los datos?"))
      {
         $.post("writer.php", $("#add_form").serialize(), function(data)
         {
            goto("administra.php?tipo=<?= urlencode($_GET['tipo']) ?>");
            $.fancybox(data);
            
         });
      }
   }
   else
      alert("Contrase&ntilde;as no coinciden");
   
}

function act_comunas(who)
{
    console.log(who);
	var pf = "";
	if ($(who).attr("id").substr(1,2) == "f_")
		pf = "f_";
//    alert(encodeURIComponent($(who).val()));
    
   $.post("regiones.php","region="+encodeURIComponent($(who).val()), function(data)
   {
      $("#f"+pf+"comuna").html(data);
      $("#f"+pf+"comuna").attr("disabled", false);
   });
}
// Asociado a modifica.php:


function do_edit()
{
   // compara #fpassword y #fpassword2
   // alert($("#mod_form").serialize());
   
   if (compara_pass())
   {
      if (confirm("Esta seguro de modificar los datos?"))
      {
         var serialized = $("#mod_form").serialize();
         $("input[disabled]").each(function()
         {
            serialized += "&" + $(this).attr("name") + "=" + escape($(this).val());
         });
         
         // alert(serialized);
         $.post("writer.php",serialized , function(data)
         {
            goto("administra.php?tipo=<?= urlencode($_GET['tipo']) ?>");
            $("#floating div").html(data);
            $("#floating").css({
                position: "fixed",
                top: "20px",
                left: (($(window).width() - $("#floating div").width()) / 2)+"px"
            });
         });
      }
   }
   else
      alert("Contrase&ntilde;as no coinciden");
}

function upload_file(frm)
{
   alert("Subiendo");
   //frm.target = "hiddenf"; 
   //frm.submit();
   return false;
}
function editar_lotes(remate)
{
   $.post("remate_edit_new.php", "id_remate="+escape(remate), function(data)
   {
      $.fancybox(data);
   });
}
function editar_galeria(remate)
{
    $.get("modgaleria.php", { "id_remate" : remate } , function(data)
    {
        $.fancybox(data);
    });
}
function ir_a_lotes(remate)
{
    $.post("remate_edit.php", "id_remate="+escape(remate), function(data)
   {
       $.fancybox(data, { autoDimensions: false, width: "80%", height: "90%"});
   });
}
$("#searchword").keyup(function(event)
{
    if (event.keyCode == '13' && $(this).val().length > 1)
        goto("administra.php?tipo=<?= $_GET['tipo'] ?>&search="+encodeURIComponent($(this).val()));
});
function revisatipo(elem)
{
    if ($(elem).val() == "Presencial")
    {
        $("[name='ftipo_puja'], [name='fvalor_puja'], [name='fduracion_lote'], [name='ffactor']").parent().parent().hide();
    }
    else
    {
        $("[name='ftipo_puja'], [name='fvalor_puja'], [name='fduracion_lote'], [name='ffactor']").parent().parent().show();
    }
}