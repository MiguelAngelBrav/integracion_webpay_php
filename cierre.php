<?php
  // datos enviados desde transbank via POST
  $TBK_RESPUESTA = $_POST["TBK_RESPUESTA"];
  $TBK_ORDEN_COMPRA = $_POST["TBK_ORDEN_COMPRA"];
  $TBK_MONTO = $_POST["TBK_MONTO"];
  $TBK_ID_SESION = $_POST["TBK_ID_SESION"];

  /* rutas a configurar
   ------------------------------------------------------ */
  $myPath = "webpay/log/comun/dato$TBK_ID_SESION.log";
  // archivo para checkmac
  $filename_txt = "webpay/log/comun/MAC01Normal$TBK_ID_SESION.txt";
  // ruta checkmac
  $cmdline = "webpay/tbk_check_mac.cgi $filename_txt";
  // ------------------------------------------------------

  $acepta = true;

  // lectura archivo que guardo pago.php
  if ($fic = fopen($myPath, "r")) {
    $linea = fgets($fic);
    fclose($fic);
  }

  $detalle = split(";", $linea);
  if (count($detalle) >= 1) {
    $monto = $detalle[1];
    $ordenCompra = $detalle[2];
  }

  // guarda los datos del post uno a uno en archivo para la ejecución del MAC
  $fp = fopen($filename_txt,"wt");
  while (list($key, $val) = each($_POST)) {
    fwrite($fp, "$key=$val&");
  }

  fclose($fp);

  // ------------------  VALIDACION 1  ------------------
  // validacion de respuesta de transbank, solo si es 0 continua con la pagina de cierre
  if ($TBK_RESPUESTA == "0") {
    $acepta = true;

  } else { 
    $acepta = false;
  }

  // ------------------  VALIDACION 2  ------------------
  // validacion de monto y Orden de compra
  if ($TBK_MONTO == $monto && $TBK_ORDEN_COMPRA == $ordenCompra && $acepta == true) {
    $acepta = true;

  } else {
    $acepta = false;
  }
  
  // ------------------  VALIDACION 3  ------------------
  //Validación MAC
  if ($acepta == true) {
    exec ($cmdline, $result, $retint);
    if ($result [0] == "CORRECTO") $acepta = true; else $acepta = false;
  }

?>

<html>
<?php if ($acepta==true) {?>
ACEPTADO
<?php 
} else {?>
RECHAZADO
<?php }?>
</html>
