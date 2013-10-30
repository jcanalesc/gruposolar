<?php
include("header.php");
ini();
?>
<p>Hola, <?= htmlspecialchars($_SESSION['nombres']) ?>!!</p>
<p>Tus datos:</p>
<table>
      <TR>
         <td>Nombre completo:</td><td><?= htmlspecialchars(strtoupper($_SESSION['nombres']." ".$_SESSION['apellidop']." ".$_SESSION['apellidom'])) ?></td>
      </TR>
      <tr><TD>Direccion:</TD><td><?= htmlspecialchars(strtoupper($_SESSION['direccion'])) ?></td></tr>
      <tr><TD>Telefono de contacto: </TD><td><?= htmlspecialchars($_SESSION['telefono']) ?></=></td></tr>
      <tr><TD>E-mail:</TD><td><?= htmlspecialchars($_SESSION['email']) ?></td></tr>
</table>
<p>Para cualquier modificaci&oacute;n de estos datos, favor contactarse con:</p>
<p>Eduardo Mundt: (aca va el mail)</p>
