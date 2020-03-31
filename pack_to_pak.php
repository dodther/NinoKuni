<?


$orig_file_name = $argv['1'];
$temp = pathinfo($argv['1']);
$dir_name = str_replace(".".$temp["extension"],"",$argv['1']);

$orig_file = fopen($orig_file_name, "rb");
$head = fread($orig_file,32);
fseek($orig_file,16);

$head_size = hexdec(bin2hex(fread($orig_file,4)));

$data_start = hexdec(bin2hex(fread($orig_file,4)));
fseek($orig_file,32);

//читаеб блок с описанием файлов
$file_opis = fread($orig_file,$head_size-32);
//чтием в массив список файлов
$file_list = file($dir_name."\\name.txt");

$data_startt = 0;

$dadres = 16;

//формируем шапку

foreach($file_list as $value){

$size = filesize($dir_name."\\".trim($value));
$dobivka = 16- ($size%16);
if ($dobivka == 16) $dobivka = 0;
//echo $size."\r\n";
$file_opis = substr_replace($file_opis,pack("NN",$data_startt,$size),$dadres,8);
$dadres  +=  32;
$data_startt += $size+ $dobivka+64;

}



// тут пишем блок с именами файлов сразу с нулями как в оригинале
fseek($orig_file,$head_size);
$file_path = fread($orig_file,$data_start - $head_size);
$file_path_end = strpos($file_path,chr("0").chr("0"));



//а вот тут будем читать файлы и писать их
$null = str_pad("", 64,chr("0")); // нули между файлом всунуть
foreach($file_list as $value){
$file = file_get_contents($dir_name."\\".trim($value));
$size = filesize($dir_name."\\".trim($value));
$dobivka = 16 - ($size%16);
if ($dobivka == 16) $dobivka = 0;

$dobivka = str_pad("", $dobivka,chr("0"));

$data .= $file.$null.$dobivka;




}
$fp = fopen($argv['1'].".pack","wb");
fwrite($fp,$head.$file_opis.$file_path.$data);
fclose($fp);


?>