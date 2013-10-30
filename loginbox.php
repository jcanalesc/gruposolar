<script type="text/javascript">
<?
    $dirs = explode("/", $_SERVER['REQUEST_URI']);
    echo "var path = '{$dirs[1]}';\n";
?>
</script>
<script language="JavaScript" src="loginbox_script.js"></script>
<div class="loginbox">
    <?php
    if (!ini()) {
        // formulario para que inicie sesion
    ?>
        <form method="POST" onsubmit="do_login(); return false;">
            <center><table>
                    <tr><td>Rut usuario:</td><td><input type="text" id="rut_u" /></td><td>(Sin d&iacute;gito verificador)</td></tr>
                    <td>Clave: </td><td><input type="password" id="pass_u" /></td></tr>
                    <tr><td align="right"><input class="submitea" type="submit" value="Iniciar sesi&oacute;n"/></td><td><span id="mensajes"></span></td></tr>
                </table></center>
        </form>
    <!--       <script language="Javascript"> do_login(); </script> -->
<?
    } else {
?>
    <div id="welcome">
        Bienvenido, <?= htmlspecialchars($_SESSION['nombres']) ?>
    </div>
    <span id="mensajes"></span>
    <form method="POST" onsubmit="do_logout(); return false;">
        <input class="submitea"  type="submit" value="Cerrar sesi&oacute;n" id="boton_logout">
    </form>
    <div id="room-label">
    <? echo "Sala &#x23; ".consts::$SALA['id_sala']; ?>
    <!--
    <br /><span onclick="location.href='/sistema/frontis.php';" style="cursor:pointer; text-decoration: underline;">Volver al frontis</span>-->
    </div>
    <!-- Alguna otra informacion relevante -->
<?
    }
?>

</div>
<div id='bc' style="display: none;"><p>CUENTA SUSPENDIDA TEMPORALMENTE</p>
<p>ESTIMADO CLIENTE, USTED PRESENTA UNA DEUDA IMPAGA.</p>
<p>FAVOR COMUNICARSE A LA BREVEDAD AL CORREO SOPORTE@PORTALREMATE.CL</p>
<p>O AL TELEFONO: 551-3304</p>
<br/>
<p>EQUIPO PORTALREMATE</p>
<p>Haga clic en esta ventana para cerrarla.</p></div>
<div id="dc" style="display:none;">
<p>ESTIMADO CLIENTE, SU CUENTA HA SIDO DESACTIVADA TEMPORALMENTE POR PRESENTAR PROBLEMAS TÉCNICOS.</p>
<P>FAVOR COMUNICARSE AL CORREO SOPORTE@PORTALREMATE.CL O AL TELEFONO 551-3304</P>
</div>
