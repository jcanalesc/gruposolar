<?php
// Alternativamente se puede llamar al script con argumentos POST por AJAX, para ejecutar una funcion especifica
function rematelog($msg)
{
   error_log("RemateLog: $msg");
}
class Publicidad
    {
        public $data = null;
        private static $instances = array();
        public function __construct($dbrow = null)
        {
            if ($dbrow != null)
            {
                $this->data = $dbrow;
            }
            self::$instances[]= $this;
        }
        public function type()
        {
            return $this->data['tipo'];
        }
        public function id()
        {
            return $this->data['id_pub'];
        }
        public static function getbyId($id)
        {
            if (isset(self::$instances[$id-1]))
                return self::$instances[$id-1];
            else return "";
        }
        public function __toString()
        {
            if ($this->data['tipo'] == "imagen")
            {
                $datos= explode("|", $this->data['html']);
                $img = $datos[0];
                $link = "#";
                if (count($datos) > 1)
                  $link = $datos[1];
                return "<a target='_blank' href='{$link}'><img width='160' height='140' src='{$img}' /></a>";
            }
            else if ($this->data['tipo'] == "flash")
            {
                $idp = $this->data['id_pub'];
                $archivo = $this->data['html'];
                return <<<ASDF
<object id="flash$idp" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="160" height="140" wmode="transparent">
        <param name="movie" value="$archivo" />
        <param name="wmode" value="transparent">
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="$archivo" width="160" height="140" wmode="transparent">
        <!--<![endif]-->
          <p>Alternative content</p>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
ASDF;
            }
            else if ($this->data['tipo'] == "youtube")
            {
                $idp = $this->data['id_pub'];
                list($link, $imagen) = explode("////", $this->data['html']);
                return <<<ASDF
<img src="$imagen" width="160" height="140" id="imagenyoutube$idp" style="cursor: pointer; ">
<script type="text/javascript">
$("#imagenyoutube$idp").css("cursor", "pointer").click(function()
{
  $.fancybox('$link');
});
</script>
ASDF;
            }
            else
                return $this->data['html'];
        }
    }
class Cursos
{
    public $texto = "";
    public $link = "";
    public $etiqueta = "";
    
    public static $cursos = array();
    public static $it = 0;
    public static function fetch()
    {
        if (count(self::$cursos) == 0) return null;
        return self::$cursos[self::$it++ % count(self::$cursos)];
    }
    public function __construct($row)
    {
        $this->texto = $row['texto'];
        $this->link = $row['link'];
        $this->etiqueta = $row['etiqueta'];
        self::$cursos[] = $this;
    }
    public function __toString()
    {
        return <<<ENDC
{$this->texto}
<p><a href="{$this->link}">{$this->etiqueta}</a></p>
ENDC;
    }
}

class consts
{
   public static $key = '78yuihjkwresdfxvcioujklnm,sdf';
   public static $data = array
      (
       "localhost", // Host a conectar
       "gruposolaruser", // usuario de la base de datos
       "p0r74lr3m4T",        // clave del usuario de la BDD en el servidor remoto
       3 => "gruposolar", // Base de datos a usar
       "sessid", // nombre de la cookie a usar
       "Invitado", // nick del usuario no logeado en chat
       6 => "Sala", // Etiqueta para referirse a un mensaje enviado a todos los usuarios conectados (chat)
       array("14119452", "15377820", "17596597","5126663"), // Cuentas de administrador
       array("users", "productos", "remates"),     // tablas
       9 => array("frut", "fprecio_min", "fcomision", "fid_sala"),    // campos numericos en tablas 
       array("insert", "update", "delete"), // Tipos de operaciones del script writer.php
       "Martillero"     // Etiqueta para referirse a los mensajes que solo le llegan al martillero
      );
   public static $allowed_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 _-.,#()@ñÑáéíóúÁÉÍÓÚäëïöüÄËÜÏÖÀÈÌÒÙàèòìù+*/:\$%&\n'";
   public static $allowed_password_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 _-.,#()@:{}[]'";
   public static $mensajes = array
      (
       "Usuario no existe",
       "Estimado cliente:\nPara poder activar su cuenta y participar en Remates Online y Miniremates debe llamar al teléfono fijo: 02-5513304 y contactarse con Sr. Andrés Uribe. Gracias.",
       "Usuario bloqueado",
       3 => "Sesión iniciada",
       "Contraseña incorrecta",
       "Función inválida",
       "Sesión terminada",
       7 => "Sesión no iniciada",
       "Argumento o llamada inválida",
       "Acceso denegado",
       10 => "Operación realizada exitosamente",
       "Error en la operación",
       "Formato inválido"
      );
   public static $factor_usuarios = 1.5; // factor del numero real de usuarios para obtener el numero aparente
   public static $tiempo_limite = 10;     // en segundos, tiempo restante maximo para empezar a sumar segundos
   public static $tiempo_adicional = 10;   // en segundos, tiempo que se suma con cada oferta luego del tiempo limite
   public static $tiempo_postlote = 20; // en segundos, tiempo entre que termina un lote y empieza el siguiente
   public static $tiempo_prelote = 5;
   public static $tiempo_adj_ganador = 20;
   public static $from_email = "Informaciones GrupoSolar.cl<info@gruposolar.cl>";
   public static $asunto_email_registro = "Inscripcion GrupoSolar.cl";
   public static $asunto_email_recclave = "Recuperacion Clave GrupoSolar.cl";
   public static $marquesina = "
   <p>No se pudo cargar el archivo de configuracion</p>";
   public static $empresas = "pr2-img/AUSP.jpg";
   public static $bases = "archivos/Terminos_y_Condiciones_GrupoSolar_Online.pdf";

   public static $videos_remate = array(
    array("text" => "", "visible" => false, "url" => ""),
    array("text" => "", "visible" => false, "url" => "")
    );

   public static $iva = 0.19;
   
   
   public static function cuerpo_email_registro($nombre, $rut, $password)
   {
      return preg_replace(array('/%nombre%/', '/%rut%/', '/%password%/'),array($nombre, $rut, $password), self::$cuerpo_email_registrob);
   }
   public static $cuerpo_email_registrob = "<strong>Estimado %nombre%,</strong>
      <p>Le damos la bienvenida a GrupoSolar. Su cuenta ha sido correctamente activada para usarla. Para entrar a la p&aacute;gina ingrese <a href=\"http://www.gruposolar.cl/principal.php\">aqu&iacute;</a> con los siguientes datos:</p>
      <ul>
         <li><strong>Rut: </strong>%rut%</li>
         <li><strong>Contrase&ntilde;a: </strong>%password%</li>
      </ul>
";
   public static function cuerpo_email_recclave($nombre, $rut, $password)
   {
      $auth = urlencode(urlsafe_b64encode(md5($password)));
      $url_html = "http://www.gruposolar.cl/cambiaclave.php?key=$auth&rut=$rut";
      return preg_replace(array('/%nombre%/', '/%rut%/', '/%password%/', '/%auth%/', '/%url_html%/'),array($nombre, $rut, $password, $auth, $url_html ), self::$cuerpo_email_recclaveb);
   }
   public static $cuerpo_email_recclaveb = "<strong>Estimado %nombre%,</strong>
      <p>Usted ha solicitado una nueva contrase&ntilde;a para GrupoSolar.cl:</p>
      <ul>
         <li><strong>Rut: </strong>%rut%</li>
         <li><strong>Contrase&ntilde;a: </strong>%password%</li>
      </ul>
      <p>Haga clic en el siguiente enlace para hacer efectivo este cambio de contrase&ntilde;a:</p>
      <p><a href=\"http://www.gruposolar.cl/cambiaclave.php?key=%auth%&rut=%rut%\">%url_html%</a></p>
      <p>Si no desea cambiar su contrase&ntilde;a actual y esta solicitud fue un error, agredecemos ignorar este correo.</p>
";
   public static $remates_ciclicos = array();
   public static $remate_destacado = 8;
   public static $config_filename = "config.xml";
   public static $SALA = array();
   public static $rutminimo = 17000000;
   public static $rutmaximo = 22000000;
   public static $automaticos = array(0, 0, 0, 0);
   public static $hinicio_rut = "00:00";
   public static $htermino_rut = "07:00";
   public static $h_vacios_minimos = 4;
   public static $footer_linea1 = "GrupoSolar &copy; 2012-2013";
   public static $footer_linea2 = "Copyright &copy; Todos los derechos reservados.";
   public static $footer_linea3 = "GrupoSolar es un medio de comunicación, por lo cual no se hace responsable
por las publicaciones realizadas en este portal de noticias.";
   
   public static $logo = "LOGOverano.png";
   public static $pdfbases = "archivos/Terminos_y_Condiciones_GrupoSolar_Online.pdf";
   public static $docregistro = 'bases_registro.pdf';
   public static $registro_incluir_pdf = "1";
   
   public static $menu = array(
                    array( 'texto' =>"QUIÉNES SOMOS", 'link' => ""),
                    array( 'texto' =>"CONTÁCTENOS", 'link' => ""),
                    array( 'texto' =>"PUBLIQUE SUS REMATES", 'link' => "")
                    );
   public static $cursos = array();
   public static $slogan = "";
   public static $titulo_frontis = ":: GrupoSolar ::";
   public static $descripcion_pag = "REMATES ONLINE ENERGIA SOLAR, ENERGIA EOLICA, GENERADORES, GASTRONOMIA, MOTOSIERRAS, AUDIO, MATERIALES DE CONSTRUCCION, HERRAMIENTAS BENCINERAS, PANELES FOTOVOLTAICOS";
   public static $palabras_clave = "REMATES ONLINE,ENERGIA SOLAR,ENERGIA EOLICA,GENERADORES,GASTRONOMIA,MOTOSIERRAS,AUDIO,MATERIALES DE CONSTRUCCION,HERRAMIENTAS BENCINERAS,PANELES FOTOVOLTAICOS";
   public static $modificables = array
   (
      'slogan', 'titulo_frontis', 'descripcion_pag', 'palabras_clave',
      'tiempo_prelote',
      'tiempo_postlote',
      'tiempo_limite',
      'tiempo_adicional',
      'tiempo_adj_ganador',
      'from_email',
      'asunto_email_registro',
      'asunto_email_recclave',
      'cuerpo_email_registrob',
      'cuerpo_email_recclaveb',
      'factor_usuarios',
      'remate_destacado',
      'empresas',
      'bases',
      'marquesina',
      'logo',
      'pdfbases',
      'rutminimo',
      'rutmaximo',
      'docregistro',
      'hinicio_rut',
      'htermino_rut',
      'h_vacios_minimos',
      'registro_incluir_pdf',
      'footer_linea1',
      'footer_linea2',
      'footer_linea3',
      'iva'
   );
   public static function obtain_config()
   {
    //rematelog("inicio config");
      if (!file_exists(self::$config_filename))
      {
         rematelog("Warning: Se intento leer '".self::$config_filename."' pero no se encontro");
         if (!file_put_contents(self::$config_filename,"<?xml version=\"1.0\" encoding=\"utf-8\" ?><constants></constants>"))
            rematelog("Fallo al intentar crear ".self::$config_filename);
         return;
      }
      
      $xml = simplexml_load_file(self::$config_filename);
      if ($xml === FALSE)
      {
         // rematelog(self::$config_filename." invalido");
         // self::save_config();
         // $xml = simplexml_load_file(self::$config_filename);
         return;
      }
      foreach($xml->cst as $node)
      {
          if (isset(self::${$node->name}))
          {
              if ($node->name == "menu" || $node->name == "cursos" || $node->name == "automaticos" || $node->name == "videos_remate")
              {
                  self::${$node->name} = unserialize(urlsafe_b64decode($node->value));
              }
              else
              {
                  self::${$node->name} = $node->value;
              }
          }
      }
      //rematelog("fin config");
   }
   public static function save_config()
   {
      $type = "constants";
      $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><{$type}></{$type}>");
      foreach(array("menu", "cursos", "automaticos", "videos_remate") as $elem)
      {
        $v0 = $xml->addChild("cst");
        $v0->addChild("name", $elem);
        $v0->addChild("value", urlsafe_b64encode(serialize(self::${$elem})));
        
      }
      foreach(self::$modificables as $var)
      {
         $v = $xml->addChild("cst");
         $v->addChild("name", $var);
         $v->addChild("value", htmlspecialchars(self::$$var));
      }
      $pf = fopen(self::$config_filename, "w+");
      fputs($pf,$xml->asXML());
      fclose($pf);
   }
}



class switches
{
   public static $tr = array
   (
      'nombres' => "Nombres",
      'apellidom' => "Apellido Materno",
      'apellidop' => "Apellido Paterno",
      'en_curso' => "Remate en curso",
      'garantia' => "Requiere garantia",
      'email' => "E-Mail",
      'logged' => "Conectado",
      'activated' => "Activar cuenta",
      'banned' => "Baneado",
      'lote_actual' => "Lote actual",
      'precio_min' => "Precio minimo",
      'foto1' => "Foto 1",
      'foto2' => "Foto 2",
      'foto3' => "Foto 3",
      'foto4' => "Foto 4",
      'id_producto' => "ID Producto",
      'id_remate' => "ID Remate",
      'dv' => "DV",
      'telefono2' => "Telefono Celular",
      'tipo_puja' => "Tipo de puja",
      'valor_puja' => "Valor de la puja (en \$ ó %)",
      'duracion_lote' => "Duración de cada lote (en segundos)",
      'inscrito' => "Recibe correos masivos",
      'tiempo_prelote' => "Tiempo (en segundos) de sincronización al principio de cada lote",
      'tiempo_postlote' => "Tiempo (en segundos) que transcurre entre que el lote termina y se pasa al lote siguiente",
      'tiempo_limite' => "Tiempo máximo restante (en segundos) del lote para que las pujas sumen segundos",
      'tiempo_adicional'=> "Tiempo que se suma por las pujas realizadas después del tiempo límite",
      'tiempo_adj_ganador' => "Tiempo para adjudicacion de lotes adicionales. (mitad para ganador, mitad para la sala)",
      'from_email' => "Email que figura como remitente en los correos enviados por el sistema",
      'asunto_email_registro' => "Asunto del correo enviado a las personas que se han registrado",
      'asunto_email_recclave' => "Asunto del correo enviado a las personas que han solicitado recuperación de clave",
      'cuerpo_email_registrob' => "Cuerpo del mensaje del email de registro",
      'cuerpo_email_recclaveb' => "Cuerpo del mensaje del email de recuperación de clave",
      'factor_usuarios' => "Factor que se aplica al numero de usuarios en el sistema, visto por los clientes",
      'f_rut' => "Rut",
      'f_giro' => "Giro",
      'f_nombre' => "Nombre",
      'f_direccion' => "Dirección",
      'f_telefono' => "Teléfono",
      'f_region' => "Región",
      'f_comuna' => "Comuna",
      'f_email' => "Correo electrónico",
      'f_dv' => "DV",
      'en_curso' => "Remate activo",
      'autorizado_rsm' => "Usuario autorizado para remates sin mínimo",
      'requiere_auth' => "Requiere validar a clientes",
      'texto_usuario_noauth' => "Mensaje de alerta para usuarios no autorizados",

      'contacto_email' => "Email de contacto",
      'contacto_fijo' => "Teléfono(s) de contacto",
      'contacto_movil' => "Celular de contacto",

      'remate_destacado' => "Determina el remate que será mostrado en el frontis como remate destacado. Se ingresa el ID del remate.",
      'slogan' => "Determina el texto que se verá bajo el logo de GrupoSolar.", 
      'titulo_frontis' => "Título de la página principal.", 
      'descripcion_pag' => "Descripción que usa la página para mostrarse en resultados de buscadores.", 
      'palabras_clave' => "Palabras separadas por coma que usan los buscadores para indexación.",
      'disabled' => "Usuario congelado",
      'marquesina' => "Texto que aparecerá en la portada cuando un cliente inicie sesión.",
      'rutminimo' => "Valor mínimo de RUT de usuario que necesitará activar su cuenta para poder usarla.",
      'rutmaximo' => "Valor máximo de RUT de usuario que necesitará activar su cuenta para poder usarla.",
      'causal' => "Causal del bloqueo",
      'registro_incluir_pdf' => "Inclusión del PDF en el email de registro. Escriba '0' para desactivar y '1' para activar.",
      'footer_linea1' => "Linea inferior 1",
      'footer_linea2' => "Linea inferior 2",
      'footer_linea3' => "Linea inferior 3",
      'iva_comision' => "Comisión afecta a IVA",
      'iva' => "Factor del IVA a utilizar en el sistema. (para 19% utilice 0.19, por ejemplo)"
   );
   public static function tra($str)
   {
      $str2 = htmlspecialchars($str);
      if (in_array($str, array_keys(self::$tr)))
         return htmlspecialchars(self::$tr[$str]);
      else
         return ucwords($str2);
   }
}
function get_const($name, $index)
{
   if ($name === "data")
      return consts::$data[$index];
   else if ($name === "mensajes")
      return consts::$mensajes[$index];
   else
      return consts::$mensajes[8];
}
function changepass($claveo, $clave1, $clave2, $rut)
{
   if ($clave1 != $clave2)
      return "Claves no coinciden.";
   $res = mysql_query("select password from users where rut = {$rut}", dbConn::$cn) or dbConn::dbError("Rut nulo.");
   if (mysql_num_rows($res) == 0)
      return "Usuario inexistente.";
   list($passw) = mysql_fetch_row($res);
   if (md5($claveo) != $passw)
      return "Clave original incorrecta.";
   if(strlen($clave1) < 4 || strlen($clave1) != strspn($clave1, consts::$allowed_password_chars))
      return "Clave inválida";
   $query = "update users set password = MD5('{$clave1}') where rut = {$rut}";
   $res = mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   return "done";
}
class dbConn
{
   public static $cn;
   public static function init()
   {
      self::$cn = mysql_connect(consts::$data[0], consts::$data[1], consts::$data[2]);
      mysql_select_db(consts::$data[3], self::$cn);
   }
   public static function dbError($query)
   {
      echo consts::$mensajes[11];
      //rematelog("problem? ".mysql_error(self::$cn)."\n query: $query");
      
         echo "<br />\nInformaci&oacute;n de depuraci&oacute;n: <br />\n".$query."<br />\n".mysql_error(self::$cn);
            
      
      die();
   }
}


function assoc_array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function revisar_sala()
{
    if (!isset($_SERVER['REQUEST_URI'])) return;
    $sala = (isset($_GET['sala']) ? $_GET['sala'] : 1);
    $r = mysql_query("select * from salas where id_sala = " . mysql_real_escape_string($sala), dbConn::$cn);
    $is = mysql_num_rows($r) != 1 ? true : false;
    consts::$SALA = mysql_fetch_assoc($r);
    mysql_free_result($r);
    if ($is)
    {
        echo file_get_contents("notfound.php");
        die();
    }
}
function hh($clave)
{
   return md5($clave);
}
function urlsafe_b64encode($string)
{
  $data = base64_encode($string);
  $data = str_replace(array('+','/','='),array('-','_','.'),$data);
  return $data;
}
function urlsafe_b64decode($string)
{
  $data = str_replace(array('-','_','.'),array('+','/','='),$string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
    $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}
function oculta ($rut)
{
   $c = currf($rut);
   $c = substr($c, 1, strlen($c) - 1);
   $ind = strlen($c) - 3;
   $c[$ind] = $c[$ind + 1] = $c[$ind + 2] = 'X';
   return $c;
}
function obtDatosSesion($rut)
{
   $res = mysql_query("select logged, activated, banned, autorizado_rsm from users where rut = {$rut}", dbConn::$cn);
   if ($res === false) return;
   list($_SESSION['logged'], $_SESSION['activated'],$_SESSION['banned'],$_SESSION['autorizado_rsm']) = mysql_fetch_row($res);
}
function ini()
 {
   if (isset($_COOKIE[consts::$data[4]]))
   {
      session_write_close();
      session_id($_COOKIE[consts::$data[4]]);
      session_start();
      if (isset($_SESSION['rut']))
         obtDatosSesion($_SESSION['rut']);
      // print_r($_SESSION);
      if (!isset($_SESSION['banned']) || !isset($_SESSION['activated']))
      {
         $_SESSION["nombres"] = consts::$data[5];
         return false;
      }
      if ($_SESSION['banned'] == 1 || $_SESSION['activated'] == 0)
      {
         logout();
         // die(consts::$mensajes[9]);
         return false;
      }
      return true;
   }
   else
   {
      session_write_close();
      session_regenerate_id();
      session_start();
      $_SESSION["nombres"] = consts::$data[5];
      return false;
   }
}

function fin()
{
   mysql_close(dbConn::$cn);
}
function validaRut($str, $dv)
{
   //echo "Argumentos: $str $dv\n";
   $str = implode(explode(".", $str));
   $arr = array(3, 2, 7, 6, 5, 4, 3, 2);
   $digitos = array();
   //echo strlen($str)." es el largo del string \"$str\"\n";
   if (strlen($str) < 7 || strlen($str) > 8)
      return false;
   for ($i = 0; $i < (8 - strlen($str)); $i++)
      $digitos[] = 0;
   for ($i = 0; $i < strlen($str); $i++)
   {
      $digitos[] = (int) $str[$i];
   }
   //echo implode(",", $digitos)."\n";
   $res = 0;
   for ($i = 0; $i < count($digitos); $i++)
   {
      //echo "Digito $i {$digitos[$i]} * {$arr[$i]}: ".($digitos[$i] * $arr[$i])."\n";
      $res += $digitos[$i] * $arr[$i];
   }
   // echo "Suma: $res\n";
   $res = 11 - ($res % 11);
   if ($res == 10) $res = "k";
   if ($res == 11) $res = "0";
   
   return ($res == strtolower($dv));
}
function email_valido($str)
{
   if (strlen($str) > 60) return false;
   $res = preg_match('/^(.+)@([^\(\);:,<>]+\.[a-zA-Z]{2,4})/', $str);
   return ($res > 0);
}
function login($usuario, $clave)
{
   $usuario = strtok($usuario, "-");
   $usuario = implode("", explode(".", $usuario));
   if (strlen($usuario) == 9)
      $usuario = substr($usuario, 0, 8);
   $fila = mysql_query("select * from ".consts::$data[8][0]." where rut = ".mysql_real_escape_string($usuario), dbConn::$cn);
   if (!$fila || mysql_num_rows($fila) == 0)
      return consts::$mensajes[0];
   else
   {
      $row = mysql_fetch_assoc($fila);
      
      if ($row["password"] === hh(utf8_decode($clave)))
      {
          if (!($row["activated"] == "1"))
             return consts::$mensajes[1];
          if ($row["banned"])
             return consts::$mensajes[2];
          if ($row["disabled"])
             return "Su cuenta ha sido bloqueada temporalmente. \n Causa: " . $row['causal'];
         setcookie(consts::$data[4], hh($usuario), time() + 3600*24*30); // cookie de 1 mes
         session_id(hh($usuario));
         session_start();
         foreach($row as $key => $value)
         {
            if ($key != "password")
               $_SESSION[$key] = $value; // se guardan los campos de la bdd en la sesion
         }
         $res = mysql_query("UPDATE ".consts::$data[8][0]." SET fecha_ultimavisita = NOW(), logged = true where rut = ".mysql_real_escape_string($usuario), dbConn::$cn);
         if ($res)
            return consts::$mensajes[3];
         else
            return consts::$mensajes[11];
      }
      else
         return consts::$mensajes[4];
   }
}

function logout()
{
   if (isset($_COOKIE[consts::$data[4]]))
   {
      session_id($_COOKIE[consts::$data[4]]);
      session_start();
      mysql_query("update users set logged = false where rut = {$_SESSION['rut']}", dbConn::$cn);
      setcookie(consts::$data[4], false, time() - 3600*24*2);
      $_SESSION = array();
      session_destroy();
      return consts::$mensajes[6];
   }
   else return consts::$mensajes[7];
}

function create_table($which, $page = 0, $unitsperpage = 50, $searchword = null)
{
    if ($unitsperpage != -1)
        $offset = $unitsperpage*$page;
    else
        $offset = 0;
    
    $appended = " limit $offset, $unitsperpage";
    $inputsearch=  $which == "users" ? "<input type=\"text\" value=\"$searchword\" id=\"searchword\"/>" : "";
   echo "<p><button id=\"add\">Agregar</button><button id=\"del\">Eliminar seleccionados</button><button onclick=\"goto('administra.php?tipo=$which&units=all');\">Mostrar lista completa</button>$inputsearch</p>";
   $searchwordint = (int)$searchword;
   $query1 = "select SQL_CALC_FOUND_ROWS IF(users.f_rut is null OR users.f_rut = users.rut,'P','E') as tipo, users.rut as id, users.dv, users.nombres, users.apellidop, users.apellidom, users.email, IF (users.banned = 0, 'No', 'Si') as baneado, IF(users.activated = 0, 'No', 'Si') as activado, users.direccion, comunas.region, comunas.nombre as comuna, users.telefono, users.telefono2, users.nacionalidad from users, comunas where users.comuna = comunas.codigo".($searchword != null ? " and (users.nombres like '%$searchword%' or users.apellidop like '%$searchword%' or users.apellidom like '%$searchword%' or users.rut like '$searchwordint%' or users.email like '%$searchword%') " : "").($unitsperpage != -1 ? $appended : "");
   $query2 = "select SQL_CALC_FOUND_ROWS id_producto as id, nombre, descripcion, precio_min, foto1, foto2, foto3, foto4 from productos where visible = true".(adminGeneral() ? "" : "and rut_owner = {$_SESSION['rut']}").($unitsperpage != -1 ? $appended : "");
   $query3 = "select SQL_CALC_FOUND_ROWS id_remate as id, fecha, hora, lugar, descripcion from remates where id_sala = ".consts::$SALA['id_sala'].(!adminGeneral() ? " and rut_owner = {$_SESSION['rut']}" : "")." order by id_remate desc ".($unitsperpage != -1 ? $appended : "");
   $q = "";
   switch($which)
   {
      case consts::$data[8][0]: 
         $q = $query1; 
         break;
      case consts::$data[8][1]:
         $q = $query2;
         break;
      case consts::$data[8][2]:
         $q = $query3;
         break;
      default:
         echo __FUNCTION__.consts::$mensajes[8]."</table>";
         return;
   }
   $res = mysql_query($q, dbConn::$cn);
   list($numrows) = mysql_fetch_row(mysql_query("select FOUND_ROWS()", dbConn::$cn));
   $pagecount = ceil($numrows / $unitsperpage);
   if ($unitsperpage == -1) $pagecount = 1;
   $keepsearch = $searchword != null ? "&search=$searchword" : "";
   if ($page > 0)
    {
        echo "<span class='pager changep' onclick=\"goto('administra.php?tipo=$which&page=0".$keepsearch."');\">Primera</span>";
        echo "<span class='pager changep' onclick=\"goto('administra.php?tipo=$which&page=".($page - 1).$keepsearch."');\">&lt;---Prev</span>";
    }
    $actual = $page+1;
    echo "<span class='pager'>Página $actual de $pagecount</span>";
    if ($page < $pagecount - 1)
    {
        echo "<span class='pager changep' onclick=\"goto('administra.php?tipo=$which&page=".($page + 1).$keepsearch."');\">Siguiente ---&gt;</span>";
        echo "<span class='pager changep' onclick=\"goto('administra.php?tipo=$which&page=".($pagecount-1).$keepsearch."');\">Ultima</span>";
    }
   echo "<table class=\"tabla\">";
   $table = array();
   while($table[] = mysql_fetch_assoc($res));
   array_pop($table);
   if (count($table) < 1)
   {
      echo "<tr><td>Sin entradas.</td></tr></table>";
      return;
   }
   echo "<tr><td colspan=\"2\"><input type=\"checkbox\" id=\"i_all\" /></td>";
   foreach(array_keys($table[0]) as $key)
      echo "<td>".switches::tra($key)."</td>";
   if ($which == consts::$data[8][1])
    echo "<td>Ocultar</td>";
   echo "</tr>\n";
   foreach ($table as $row)
   {
      echo "<tr id=\"fila{$row['id']}\"><td><input type=\"checkbox\" id=\"i{$row['id']}\" /></td><td><div class=\"edit\" id=\"e{$row['id']}\"></div></td>";
      foreach($row as $key => $val)
      {
         if (substr($key, 0, 4) != "foto")
         {
            if ($key != "precio_min")
               echo "<td>".$val."</td>";
            else
               echo "<td>".currf($val)."</td>";
         }
         else
         {
            $parts = explode("/", $val);
            $val2 = $parts[0]."/small/".$parts[1];
            echo "<td><img width=\"55\" height=\"55\" src=\"$val2\" /></td>";
         }
         
      }
      if ($which == consts::$data[8][1])
          echo "<td><a href=\"javascript:ocultaprod(".$row['id'].");\">&times;</a></td>";
      echo "</tr>";
   }
   echo "</table>";
}
function esAdmin()
{
   return (ini() && isset($_SESSION['rut']) && (adminGeneral() || adminSala()));
}
function adminGeneral()
{
    return ini() && in_array($_SESSION['rut'], consts::$data[7]);
}
function adminSala()
{
    $r = mysql_query("select rut_owner from salas where id_sala = " . $_GET['sala'], dbConn::$cn);
    $row = mysql_fetch_row($r);
    mysql_free_result($r);
    if ($row[0] == $_SESSION['rut']) return true;
    else return false;
}
function tieneSalas()
{
    $r = mysql_query("select id_sala from salas where rut_owner = {$_SESSION['rut']}", dbConn::$cn);
    if (mysql_num_rows($r) >= 1)
    {
        list($sala) = mysql_fetch_row($r);
        rematelog("{$_SESSION['rut']} tiene sala $sala");
        mysql_free_result($r);
        return $sala;
    }
    else return 0;
}
function xmlparse($c,$a)
{
   // Si es un array asociativo, crear hijos, si es numerico, crear copias de la key padre con los datos
   foreach($a as $k=>$v) 
   {
      if(is_array($v)) 
      {
         $keys = array_keys($v);
         if (count($v) > 0 && is_numeric($keys[0]))
         {
            foreach($v as $val)
            {
               $c->addChild($k, htmlspecialchars($val));
            }
         }
         else
         {
            $ch=$c->addChild($k);
            xmlparse($ch,$v);
         }
      } 
      else 
      {
         $c->addChild($k,htmlentities($v));
      }
   
   }
}

function string_chop(&$str, $frontchop, $endchop)
{
   
   $str = substr($str, $frontchop, strlen($str) - ($frontchop + $endchop));
   return $str;
}
   
function assocArrayToXML($root_element_name,$ar)
{
    $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><{$root_element_name}></{$root_element_name}>");
    xmlparse($xml,$ar);
    return $xml->asXML();
}
function getAllXML($type, $persistent)
{
   if (!esAdmin())
      return consts::$mensajes[9];   
   $query = "";
   $semiadmin = "";
   if (!adminGeneral())
    $semiadmin = "where rut_owner = {$_SESSION['rut']}";
   $query2 = "select CONCAT(nombre,' ',descripcion) as descripcion, id_producto, precio_min, 1 as cantidad from ".mysql_real_escape_string($type)." $semiadmin order by ultimo_orden";
   if (isset($_SESSION['remate_editado']) && is_numeric($_SESSION['remate_editado']) && $persistent == "true")
      $query = "select lotes.orden, lotes.cantidad, CONCAT(productos.nombre,' ',productos.descripcion) as descripcion, lotes.id_producto from lotes, productos where lotes.id_producto = productos.id_producto and lotes.id_remate = {$_SESSION['remate_editado']} order by orden";
   
   $res = mysql_query((strlen($query) > 0 ? $query : $query2), dbConn::$cn);
   if (!$res)
      return consts::$mensajes[8];
   if (mysql_num_rows($res) < 1)
   {
      $res = mysql_query($query2, dbConn::$cn);
   }
   $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><{$type}></{$type}>");
   $datos = array();
   while($row = mysql_fetch_assoc($res))
      $datos[] = $row;
   foreach($datos as $fila)
   {
      $child = $xml->addChild("elem");
      foreach($fila as $key => $value)
         $child->addChild($key, $value);
   }
   mysql_free_result($res);
   if ($persistent == "true")
      $xml->addChild("nuevo", 0);
   else
      $xml->addChild("nuevo", 1);
   return $xml->asXML();
}
function actualiza_lotes($remate)
{
      $tq1 = "(select duracion_lote from remates where id_remate = {$remate})";
      $tq2 = "(select CONCAT(fecha, ' ', hora) as times from remates where id_remate = {$remate})";
      $query2 = "update lotes set repartido = false, fecha_inicio = @var:=TIMESTAMPADD(SECOND,orden*({$tq1}+".consts::$tiempo_adicional."),{$tq2}), fecha_termino = TIMESTAMPADD(SECOND,{$tq1},@var) where id_remate = ".$remate;
      $res = mysql_query($query2, dbConn::$cn);
      mysql_query("update remates set lote_actual = (select id_lote from lotes where id_remate = ".$remate." and orden = 0) where id_remate = ".$remate, dbConn::$cn);
      if (!$res) dbConn::dbError($query2);            
}
function pausar_remate($id)
{
   list($pausado) = mysql_fetch_row(mysql_query("select IF(tiempo_pausa is null, 0, 1) as pausado from remates where id_remate = $id", dbConn::$cn));
   if ($pausado == "1")
		return "paused";
   $query = "update remates set tiempo_pausa = NOW() where id_remate = ".mysql_real_escape_string($id);
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   $query = "insert into acciones (id_lote, rut, tipo) values ((select lote_actual from remates where id_remate = ".mysql_real_escape_string($id)."), 17596597, 'EL REMATE SE REANUDARA EN UNOS INSTANTES.')";
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   return "paused";
}
function reanudar_remate($id)
{
   list($tp) = mysql_fetch_row(mysql_query("select tiempo_pausa from remates where id_remate = ".mysql_real_escape_string($id), dbConn::$cn));
   if (strlen($tp) == 0) return "resumed";
   //rematelog("llamada a reanudar, id: $id");
   $query = "update lotes set fecha_termino = TIMESTAMPADD(SECOND, TIMESTAMPDIFF(SECOND, (select tiempo_pausa from remates where id_remate = ".mysql_real_escape_string($id)."), NOW()), fecha_termino), fecha_inicio = TIMESTAMPADD(SECOND, TIMESTAMPDIFF(SECOND, (select tiempo_pausa from remates where id_remate = ".mysql_real_escape_string($id)."), NOW()), fecha_inicio) where id_lote = (select lote_actual from remates where id_remate = ".mysql_real_escape_string($id).")";
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   $query = "update remates set tiempo_pausa = NULL where id_remate = ".mysql_real_escape_string($id);
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   $query = "insert into acciones (id_lote, rut, tipo) values ((select lote_actual from remates where id_remate = ".mysql_real_escape_string($id)."), 17596597, 'EL REMATE HA SIDO REANUDADO')";
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   return "resumed";
}
function toggle_remate($id)
{
   if (!esAdmin()) die(consts::$mensajes[9]);
   list($pausado) = mysql_fetch_row(mysql_query("select tiempo_pausa from remates where id_remate = ".mysql_real_escape_string($id), dbConn::$cn));
   if (strlen($pausado) > 0) // pausado, despausar
   {
      reanudar_remate($id);
   }
   else
   {
      pausar_remate($id);
   }
   return "done";
}
/*
function estado_remate($id)
{
    // opciones: no ha empezado, en curso, finalizado.
    
}
* */
function pasar_sgte_lote($id_remate, $force = false)
{
   // Primero, revisar lote actual y chequear si efectivamente es un lote ya repartido.
   mysql_query("lock tables remates write, lotes");
   $query = "select * from remates join lotes on (lotes.id_lote = remates.lote_actual) where remates.id_remate = {$id_remate}";
   $datos = mysql_fetch_assoc(mysql_query($query, dbConn::$cn));
   if ($datos['repartido'] == "1" || $force == "true") // Si ya se repartió, podemos avanzar
   {
      // Chequear que exista un lote siguiente
      $sgte = (int)$datos['orden'] + 1;
      $query2 = "select id_lote from lotes where id_remate = {$id_remate} and orden = {$sgte}";
      $res2 = mysql_query($query2, dbConn::$cn);
      if (mysql_num_rows($res2) == 0) // Es el último
      {
         if (in_array($id_remate, consts::$remates_ciclicos))
         {
            reinicia_remate($id_remate);
         }
         else
         {
             $r = mysql_query("update remates set en_curso = false, publico = false where id_remate = {$id_remate}", dbConn::$cn);
             return true;
         }
         mysql_query("unlock tables");
         return true;
      }
      else // Se asigna a lote_actual el nuevo lote
      {
         list($id_sgte) = mysql_fetch_row($res2);
         $tiempo_prelote = consts::$tiempo_prelote;
         $duracion_lote = $datos['duracion_lote'];
         mysql_query("update lotes set fecha_inicio = @var := TIMESTAMPADD(SECOND, {$tiempo_prelote}, NOW()), fecha_termino = TIMESTAMPADD(SECOND,{$duracion_lote}, @var) where id_lote = {$id_sgte}", dbConn::$cn);
         mysql_query("update remates set lote_actual = {$id_sgte} where id_remate = {$id_remate}", dbConn::$cn);
         mysql_query("unlock tables");
         return false;        
      }
      
   }
   else return false; // No hay pa que cambiar el lote pueh
}
function reinicia_remate($id)
{
   $query = "update remates set en_curso = true, fecha = NOW(), hora = DATE_ADD(NOW(), INTERVAL 20 SECOND) where id_remate = ".$id;
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   $query = "delete from chat where id_remate = $id";
   mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
   borra_acciones_remate($id);
   actualiza_lotes($id);
}
function guarda_lotes($ser)
{
   if (!esAdmin())
      return consts::$mensajes[9];
   $res = mysql_query("select fecha, hora, duracion_lote from remates where id_remate = {$_SESSION['remate_editado']}", dbConn::$cn);
   $row = mysql_fetch_assoc($res);
   $fecha = $row['fecha'];
   $hora = $row['hora'];
   $tiempo_lote = $row['duracion_lote'];
   $timestamp = $fecha." ".$hora;
   $arr_lotes = explode("|",$ser);
   // Eliminamos la configuracion anterior
   $res = mysql_query("delete from lotes where id_remate = {$_SESSION['remate_editado']}",dbConn::$cn);
   if (!$res)
      dbConn::dbError("Eliminacion de lotes obsoletos del remate {$_SESSION['remate_editado']}.\n Es posible que los lotes tengan acciones asociadas.");
   $query = "insert into lotes (id_producto, id_remate, cantidad, orden, fecha_inicio, fecha_termino) values ";
   $append = array();
   if (strlen($ser) > 0) foreach($arr_lotes as $lote)
   {
      list($orden, $cantidad, $id) = explode(",",$lote); 
      mysql_query("update productos set ultimo_orden = $orden where id_producto = $id", dbConn::$cn);
      $append[] = "({$id},{$_SESSION['remate_editado']},{$cantidad},{$orden},@inicio := TIMESTAMPADD(SECOND,".$tiempo_lote*$orden.",'{$timestamp}'),TIMESTAMPADD(SECOND,".$tiempo_lote.",@inicio))";
   }
   if (count($append) > 0)
   {
      $query .= implode(",",$append);
      $res = mysql_query($query, dbConn::$cn);
      if (!$res)
         dbConn::dbError($query);
      else
      {
         mysql_query("update remates set lote_actual = (select id_lote from lotes where id_remate = {$_SESSION['remate_editado']} and orden = 0) where id_remate = {$_SESSION['remate_editado']}",dbConn::$cn);
         return "Lotes modificados correctamente.";
      }
   }
   else
      return "Lotes eliminados del remate.";
   
}
function borra_acciones_remate($id)
{
   if (esAdmin() && $id == "edited")
      $id = $_SESSION['remate_editado'];
   mysql_query("delete from acciones where id_lote in (select id_lote from lotes where id_remate = $id)", dbConn::$cn);
   return "done";
}
function currf($str)
{
   $i = strlen($str) - 1;
   $j = 0;
   $nstr = "";
   for (; $i >= 0; $i--, $j++)
   {
      if ($j % 3 == 0 && $j != 0)
         $nstr = "." . $nstr;
      $nstr = $str[$i] . $nstr;
   }
   $nstr = "$" . $nstr;
   return $nstr;
}
function banea($rut)
{
   if (!esAdmin()) return consts::$mensajes[9];
   if (strspn($rut, "0123456789") != strlen($rut))
      return consts::$mensajes[8];
   if ($rut == $_SESSION['rut'])
      return "error";
   $res = mysql_query("update users set banned = true where rut = " . mysql_real_escape_string($rut));
   return "done";
   
}
function desbanea($rut, $validacion)
{
   if ($validacion != "monoculiao24255") return consts::$mensajes[9];
   $res = mysql_query("update users set banned = false where rut = " . mysql_real_escape_string($rut));
   return true;
}
function post_request($url, $vars)
{
	$querystring = http_build_query($vars);
	//rematelog("post_request: url=$url, vars=(".implode(",",$vars).")");
	$c = curl_init();
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_POSTFIELDS, $querystring);
	$contents = curl_exec($c);
	curl_close($c);
	if ($contents) return $contents;
}
function obtLotes($remate)
{
	// Devuelve id_lote, descripcion, monto final
	if (!esAdmin()) die(consts::$mensajes[9]);
    $idr = mysql_real_escape_string($remate);
	$query = "select lotes.orden, lotes.id_lote, productos.descripcion, precios_f.precio, lotes.cantidad as disp from lotes join productos using (id_producto) join (select if(acciones.monto is null, productos.precio_min, acciones.monto) as precio,lotes.id_lote from productos join lotes using (id_producto) left join (select id_lote, monto from acciones where tipo = 'Adjudicacion' group by id_lote) as acciones using (id_lote)) as precios_f using (id_lote) where lotes.id_remate = $idr order by lotes.orden";


  $qwery = "select f.orden, f.id_lote, f.descripcion, IF(acciones.monto is null, f.precio_min, acciones.monto) as precio, f.cantidad from (select id_lote, orden, cantidad, precio_min, descripcion from lotes join productos using (id_producto) where id_remate = $idr) as f left join acciones using (id_lote) where (acciones.tipo = 'Adjudicacion' or acciones.tipo is null) group by id_lote";


	$res = mysql_query($qwery, dbConn::$cn) or dbConn::dbError($query);
	$type = "datos";
	$xmlobj = new SimpleXMLElement("<?xml version=\"1.0\"?><{$type}></{$type}>");
	while(list($orden, $idl, $descr, $precio, $disp) = mysql_fetch_row($res))
	{
		$elem = $xmlobj->addChild("lote");
		$elem->addChild("orden", $orden);
		$elem->addChild("id", $idl);
		$elem->addChild("descr", substr($descr, 0, 100));
		$elem->addChild("precio", $precio);
		$elem->addChild("disp", $disp);
	}

    /*
	$query = "select users.rut, CONCAT(users.rut,'-',users.dv) as rutc, CONCAT(users.nombres,' ', users.apellidop,' ', users.apellidom) as nombrec from users join (select rut from conectados_hoy union select CAST(sender as UNSIGNED) as rut from chat where id_remate = $idr) as k";
	$res = mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
	while(list($rut, $rutc, $nombrec) = mysql_fetch_row($res))
	{ 
		$elem = $xmlobj->addChild("user");
		$elem->addChild("rut", $rut);
		$elem->addChild("rutc", $rutc);
		$elem->addChild("nombrec", $nombrec);
	}
	*/
	// user que tenga rut, rutc, nombrec
	
	return $xmlobj->asXML();
}
function congela($rut)
{
    if ($_SESSION['rut'] == $rut) return false;
    if (!esAdmin()) return false;
    if (!is_numeric($rut)) return false;
    $rut = mysql_real_escape_string($rut);
    $r = mysql_query("update users set disabled = true where rut = $rut", dbConn::$cn);
    return ($r ? "done" : "false");
}
function saveCursos($c1, $c2, $c3)
{
    $ar = array(explode("|", $c1),explode("|",$c2),explode("|",$c3));
    for($i = 0; $i < 3; $i++)
        consts::$cursos[$i] = array("texto" => $ar[$i][0],
                                     "link" => $ar[$i][1],
                                     "etiqueta" => $ar[$i][2]);
    consts::save_config();
    return "ok";
}
dbConn::init();
consts::obtain_config();
revisar_sala();
// argumentos se reciben en un string separado por puntoycomas: arg1;arg2;arg3
if (isset($_POST["func"]) && count($_POST) < 3)
{
   if (isset($_POST["args"]) && count($_POST) == 2)
      $res = call_user_func_array($_POST["func"], explode(";", $_POST["args"]) );
   else
      $res = call_user_func($_POST["func"]);
   
   echo($res);
}

?>
