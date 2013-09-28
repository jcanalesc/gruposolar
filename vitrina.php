<? 
   require_once("header.php");
if (!isset($_GET['id_remate'])) die(consts::$mensajes[8]); 
?>
<script language="javascript" src="vitrina_script.js"></script>
<style type="text/css">
table.tv td
{
    font-size: 14px;
}
</style>
<?
   ini();
   $idr = $_GET['id_remate'];
   
   // Usar floating div
   class remate
   {
      public $lotes = array();
      public $descripcion = "";
      public $lugar = "";
      public $fecha = "";
      public $hora = "";
      public function agregalote(array $mysqlrow)
      {
         $cols = array('orden', 'nombre','descripcion','precio','foto1','foto2','foto3','foto4', 'link');
         $lote = array();
         foreach($cols as $col)
            $lote[$col] = $mysqlrow[$col];
         $this->lotes[] = $lote;
      }
      public function filas_lotes()
      {
         $res = "";
         foreach($this->lotes as $lote)
         {
            $res .= "<tr>";
            foreach($lote as $key => $val)
                if (substr($key, 0, 4) != "foto" && $key != "link")
                {
                   if ($key == "precio")
                      $res .= "<td><strong>".currf($val)."</strong></td>";
                   else
                      $res .= "<td><strong>$val</strong></td>";
                }
                else if (substr($key, 0, 4) == "foto")
                {
                    $parts = explode("/", $val);
                    $val = $parts[0]."/small/".$parts[1];
                   $res .= "<td><img title=\"Haga clic para ver la foto grande\" width=\"55\" height=\"55\" class=\"zoom\" src=\"$val\" /></td>";
                }
                else
                {
                    if (strlen($val) > 0)
                        $res .= "<td><img src=\"iconcamara.png\" data-video=\"{$val}\" /></td>";
                    else
                        $res .= "<td>&nbsp;</td>";
                }
            $res .= "</tr>";
         }
         return $res;
      }
      public function __construct(array $mysqlrow)
      {
         $this->descripcion = $mysqlrow['descripcion'];
         $this->lugar = $mysqlrow['lugar'];
         $this->fecha = implode("/", array_reverse(explode("-", $mysqlrow['fecha'])));
         $this->hora = $mysqlrow['hora'];
      }
   }
   
   $res = mysql_query("select * from remates where id_remate = {$idr} order by fecha, hora", dbConn::$cn) or dbConn::dbError("obtencion de remates");
   $remates = array( 0 => null);
   while($row = mysql_fetch_assoc($res))
   {
      $remates[0] = new remate($row);
   }
   
   mysql_free_result($res);
   $res = mysql_query("select lotes.orden, productos.nombre, productos.descripcion, precios_f.precio, productos.foto1, productos.foto2, productos.foto3, productos.foto4, productos.link from productos, lotes, (select if(acciones.monto is null, productos.precio_min, max(monto)) as precio, lotes.id_lote from productos join lotes using (id_producto) left join acciones using (id_lote) where acciones.tipo is null or acciones.tipo = 'Adjudicacion' group by lotes.id_lote) as precios_f where productos.id_producto = lotes.id_producto and lotes.id_remate = $idr and lotes.id_lote = precios_f.id_lote order by lotes.orden", dbConn::$cn);
   if (!$res) dbConn::dbError(mysql_error(dbConn::$cn));
   if (mysql_num_rows($res) == 0)
   {
      die("<h4>Este remate aun no tiene lotes asignados.</h4>");
   }
   while($row = mysql_fetch_assoc($res))
   {
      $remates[0]->agregalote($row);
   }
   $encabezado = "<tr><td>Lote</td><td>Nombre producto</td><td>Descripci&oacute;n</td><td>Precio m&iacute;nimo</td><td>Foto 1</td><td>Foto 2</td><td>Foto 3</td><td>Foto 4</td><td>Video</td></tr>";
   foreach($remates as $r)
   {
      $tmp = $r->filas_lotes();
      echo <<<END
      <style type="text/css">
p { text-align: center; font-family: Arial; font-size: 10pt;}
p.agr { font-size: 14pt;}
span.volver { position: absolute; right: 0px; top: 0px; }
</style>
      <h4>Mostrando lotes del remate del {$r->fecha}</h4>
      <h4>{$r->descripcion}</h4>
      <h4>La exhibici&oacute;n del remate es en: {$r->lugar}</h4>
      <br />
      <p>CONSULTAS: al Email: <a href=\"mailto:soporte@portalremate.cl\">soporte@portalremate.cl</a> o al Celular: 9-6114298</p>
      <br />
      <table class="tabla tv">
      $encabezado
      $tmp
      </table>
      <br />
      <p><strong>PORTALREMATE &copy; 2008 - 2012</strong></p><br />
<p>Copyright &copy; Todos los derechos reservados</p>
      
END;
   }
   
?>
<img src="dim.png" id="oscurece" style="display: none;"/>
