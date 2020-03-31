<?

function hexbin($str) {
            $it = strlen($str);
            $ret = '';
            for ($i=0;$i<$it;$i+=2) {
                    $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
                    }
            return $ret;
    }
/*
$list = scandir($argv['1']);

foreach($list as $value){
if (substr_count($value, ".gvd") != 0 ) $list_files[] =  $value;
}
*/
$list = file("file_list.txt");

foreach($list as $value){
$list_files[] = str_replace("\r\n",'',$value);

}



//считаем размер шапки количество файлов*16байт за файл + размер верхнего заголовка + 10 рядов нулей
$head_size = count($list_files)*16+16+160;
$file_kol_vo = count($list_files);
//print_r($list_files);

//$name
$head = hexbin("5447445430313030").pack("NN",$file_kol_vo,$head_size);
$name_start =0;
$file_start = $file_kol_vo*1024;
$fp = fopen("gvd.dat", 'wb');
fwrite($fp,$head);
foreach($list_files as $value){

$name_leght = strlen($value);
$file_leght = filesize($value);
$head = pack("NNNN",$name_start,$name_leght,$file_start,$file_leght);
fwrite($fp,$head);
$name_start = $name_start +1024;
//160 это количество нулей после окончания файла.
$file_start = $file_start + $file_leght+160;
}

//забиваем нулями оставшиеся место
$null =  str_pad("",$head_size-filesize("gvd.dat"),chr("0"));
fwrite($fp,$null);
//теперь имена файлов пишем
foreach($list_files as $value){
$null =  str_pad("",1024-strlen($value),chr("0"));
fwrite($fp,$value.$null);

}

//ну поехали писать файлы
foreach($list_files as $value){
$file = file_get_contents($value);
$null =  str_pad("",160,chr("0"));
fwrite($fp,$file.$null);

}

fclose($fp);

?>