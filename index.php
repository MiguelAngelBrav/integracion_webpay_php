<?php
  // genera orden de compra a partir de fecha
  date_default_timezone_set('America/Santiago');
  $fechaactual = date('m/d/Y h:i:s a', time());
  $ano = date("Y", strtotime($fechaactual));
  $mes = date("m", strtotime($fechaactual));
  $dia = date("d", strtotime($fechaactual));
  $hora = date("h", strtotime($fechaactual));
  $minuto = date("i", strtotime($fechaactual));
  $segundo = date("s", strtotime($fechaactual));
  $oc = "OC_".$ano.$mes.$dia.$hora.$minuto.$segundo;
  $TBK_MONTO = 10000;
  $TBK_ORDEN_COMPRA = $oc;

  /* configurar
   ------------------------------------------------------ */
  $myPath     = "webpay/log/comun/dato".$oc.".log";
  //------------------------------------------------------/

  //Grabado de datos en archivo de transaccion      
  $fic = fopen($myPath, "w+");     
  $fechalog = strftime("%d-%m-%YT%T");
  $linea = " $fechalog;$TBK_MONTO;$oc";     
  fwrite ($fic,$linea);     
  fclose($fic);
?>

<html>
<head>
  <meta charset="utf-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <title>tienda ejemplo kcc 6.0.2 - transaccion normal</title>
</head>
<body bgcolor="#3069c6" topmargin="10" leftmargin="0" marginwidth="0" marginheight="0">
  <p align="center"><font face=arial size="5" color="white"> transaccion normal - windows asp – kcc6.0.2</font></p>
  <form method="post" action="/cgi-bin/tbk_bp_pago.cgi"> 

    <table border="0" align="center">
      <tr>
        <td align="center"><font face=arial size="3" color="white">monto transacción</font><input type="text" name="TBK_MONTO" value="<?php echo $TBK_MONTO ?>"></td>
        <td align="center"><input type="hidden" name="TBK_TIPO_TRANSACCION" value="TR_NORMAL"></td>
      </tr>
      <tr>
        <td align="center"><font face="arial" size="3" color="white">no de orden</font><input type="text" name="TBK_ORDEN_COMPRA" value="<?php echo $oc ?>"></td>
        <td align="center"><input type="hidden" name="TBK_ID_SESION" value="<?php echo $oc ?>">  </td>
      </tr>
    </table>
    
    <table border="0" align="center">
      <tr>
        <td align="center"><input type="hidden" name="TBK_URL_EXITO" size="40" value="http://45.55.187.70/exito.php" size="50"></td>
        <td align="center"><input type="hidden" name="TBK_URL_FRACASO" size="40" value="http://45.55.187.70/fracaso.php" size="50"></td>
      </tr>
    </table>
    <table border="0" align="center">
      <tr>
        <td align="center"><input type="submit" value="pagar con tarjeta de crédito" size="20"></td>
      </tr>
    </table>
  </form> 
</body>
</html>
