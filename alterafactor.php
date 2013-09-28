<?
    // alterafactor.php
    // recibo f por get, devuelvo objeto con valor
    include("header.php");
    if (!adminGeneral() || !isset($_GET['f'])) die(consts::$mensajes[8]);
    
    $lastVal = consts::$factor_usuarios;
    $newVal = $_GET['f']+0.0;
    if (is_float($newVal))
    {
        consts::$factor_usuarios = $newVal;
        rematelog("factor: $newVal");
        consts::save_config();
        die(json_encode(array('res' => $newVal, 'success' => true)));
    }
    else die(json_encode(array('res' => consts::$factor_usuarios, 'success' => false)));
?>
