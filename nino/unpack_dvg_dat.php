<?

$file_gvg = fopen($argv['1'], 'rb');
// считываем количество файлов
fseek($file_gvg, 8);
$file_kol_vo =  "0x".bin2hex(fread($file_gvg, 4));
//смещаем чтение к блоку где записан конец шапки
//fseek($file_gvg, 12);
$file_name_start =  "0x".bin2hex(fread($file_gvg, 4));

// смещаемся к началу описания файлов
fseek($file_gvg, 16);

// и собственно читаем в массив
for($x=0;$x<$file_kol_vo;$x++){

$file_head[] = fread($file_gvg,16);

}

$fi = fopen("file_list.txt", 'w');
foreach($file_head as $value){
$name_start = $file_name_start + hexdec(bin2hex(substr($value,0,4)));
$name_leght =  hexdec(bin2hex(substr($value,4,4)));

fseek($file_gvg, $name_start);
$name = fread($file_gvg,$name_leght);

$file_start = $file_name_start + hexdec(bin2hex(substr($value,8,4)));
$file_leght = hexdec(bin2hex(substr($value,12,4)));
fseek($file_gvg, $file_start);
$file = fread($file_gvg,$file_leght);
//пишем имена в файл. чтобы собрать в таком же порядке
fwrite($fi, $name."\r\n");

$fp = fopen($name, 'wb');
fwrite($fp, $file);
fclose($fp);


}

fclose($fi);



?>