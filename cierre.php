<?php
//rescate de datos de POST.
$TBK_RESPUESTA=$_POST["TBK_RESPUESTA"];
$TBK_ORDEN_COMPRA=$_POST["TBK_ORDEN_COMPRA"];
$TBK_MONTO=$_POST["TBK_MONTO"];
$TBK_ID_SESION=$_POST["TBK_ID_SESION"];
/****************** CONFIGURAR AQUI *******************/
$myPath = "webpay/log/comun/dato$TBK_ID_SESION.log";
//GENERA ARCHIVO PARA MAC
$filename_txt = "webpay/log/comun/MAC01Normal$TBK_ID_SESION.txt";
$filename_debug_txt = "webpay/log/comun/log.txt";
// Ruta Checkmac
$cmdline = "webpay/tbk_check_mac.cgi $filename_txt";
/****************** FIN CONFIGURACION *****************/

$acepta=true;
$log_array[0] = "==>paso 01 ";
// lectura archivo que guardo pago.php
if ($fic = fopen($myPath, "r")){
	$log_array[1] = "==>paso 02 ";
	$linea=fgets($fic);
	fclose($fic);
	$log_array[2] = "==>paso 03 ";
}
$log_array[3] = "==>paso 04 ";
$detalle=split(";", $linea);
if (count($detalle)>=1){
	$log_array[4] = "==>paso 05 ";
	$monto=$detalle[1];
	$ordenCompra=$detalle[2];
	$log_array[5] = "==>paso 06 ";
}
//guarda los datos del post uno a uno en archivo para la ejecución del MAC
$log_array[6] = "==>paso 07 ";
$fp=fopen($filename_txt,"wt");
while(list($key, $val)=each($_POST)){
	$log_array[7] = "==>paso 08 ";
	fwrite($fp, "$key=$val&");
	$log_array[8] = "==>paso 09 ";
}
$log_array[9] = "==>paso 10 ";
fclose($fp);
$log_array[10] = "==>TBK_RESPUESTA: $TBK_RESPUESTA ";
$log_array[11] = "==>TBK_MONTO: $TBK_MONTO ";
$log_array[12] = "==>monto: $monto ";
$log_array[13] = "==>TBK_ORDEN_COMPRA: $TBK_ORDEN_COMPRA ";
$log_array[14] = "==>ordenCompra: $ordenCompra ";

//Validación de respuesta de Transbank, solo si es 0 continua con la pagina de cierre
if($TBK_RESPUESTA=="0"){ $acepta=true; $log_array[15] = "==>paso 12 ";} else { $acepta=false; $log_array[16] = "==>paso 13 ";}
//validación de monto y Orden de compra
if ($TBK_MONTO==$monto && $TBK_ORDEN_COMPRA==$ordenCompra && $acepta==true){ $acepta=true;$log_array[17] = "==>paso 14 ";}
else{ $acepta=false;$log_array[18] = "==>paso 15 ";}
$log_array[19] = "==>paso 16 ";
//Validación MAC
if ($acepta==true){
	$log_array[20] = "==>paso 17 ";
	exec ($cmdline, $result, $retint);
	$log_array[21] = "==>paso 18 ";
	if ($result [0] =="CORRECTO") $acepta=true; else $acepta=false;
	$log_array[22] = "==>paso 19 ";
	$log_array[23] =  $result [0];
}
$log_array[24] = "==>paso 21 ";

$fp=fopen($filename_debug_txt,"wt");
while(list($key, $value) = each($log_array)){
	fwrite($fp, "$value \n");
}
fclose($fp);


?>
<html>
<?php if ($acepta==true){?>
ACEPTADO
<?php } else {?>
RECHAZADO
<?php }?>
</html>
