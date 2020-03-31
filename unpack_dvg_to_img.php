<?

$file_gvg = fopen($argv['1'], 'rb');

//читаем тип файла
fseek($file_gvg, 8);
$type = bin2hex(fread($file_gvg, 4));
if ($type == "4a504547"){$file_type = "JPEG";}else{$file_type = "GVMP";}

//читаем какие-то параметры в шапке. потом их в файл кинем
fseek($file_gvg, 16);
$параметры_для_шапки = fread($file_gvg, 8);

//перемещаемся для чтения длинны описания файлов
fseek($file_gvg, 28);
$head_leght = "0x".bin2hex(fread($file_gvg, 4));
// перемещаемся к описанию файлов
fseek($file_gvg, 48);
$file_opis = fread($file_gvg, $head_leght-8);
$x=0;
$start = 0;
//перебираем шапку в масив. так как нигде не указано сколько файлов. то перебираем пока не кончится
while ($x<=10)
{
if(!substr($file_opis,$start,32)) break;
$files[] = substr($file_opis,$start,32);
$start = $start +32;
}

//40 байт щапки 16 байт BLK_


fseek($file_gvg, 40+$head_leght+16); //перемещаем указатель на начало картинок и начинаем читать

$folder_name = basename($argv['1'],".gvd");
//$folder_name = str_replace(".gvd","",$argv['1']);
if(!is_dir($folder_name)) mkdir($folder_name);

//читаем из реестра путь к файлам настройки
exec("reg query HKEY_CLASSES_ROOT\.gvd\shell\����������_�_jpg /v path", $sss);
$путь_к_файлам_параметров0 = explode("   ", $sss["2"]);
$путь_к_файлам_параметров = trim(addslashes($путь_к_файлам_параметров0["3"]));
;
$путь_к_файлам_параметров = mb_convert_encoding($путь_к_файлам_параметров, "cp1251","cp866");

$fp = fopen($путь_к_файлам_параметров.$folder_name.".txt","w");
foreach($files  as $key => $value){
				
//записываем в текстовик данные для сборки
$width_grid = 	bin2hex(substr($value,0,4));
$Height_grid = bin2hex(substr($value,4,4));
$layer  = bin2hex(substr($value,8,4));
$size = "0x".bin2hex(substr($value,12,4));
$width_img = bin2hex(substr($value,24,4));		
$Height_img = bin2hex(substr($value,28,4));
$FF_to_JPEG = 	bin2hex(substr($value,16,4));
$инфа_для_сборки = $width_grid.$Height_grid.$layer.$width_img.$Height_img.$FF_to_JPEG."\r\n";
fwrite($fp, $инфа_для_сборки);



$twin_img = fread($file_gvg, $size);
if($file_type == "GVMP"){//проверяем тип файла
$img_a_leght = "0x".bin2hex(substr($twin_img,12,4));
$img_a_leght_data = "0x".bin2hex(substr($twin_img,16,4)); //длинна перой картинки с заголовком и нулями. если там 00000020 то картинка одна
$img_b_leght = "0x".bin2hex(substr($twin_img,20,4));



$f_a = fopen($folder_name."\\".str_pad($key+1, 4,"0",STR_PAD_LEFT)."_a.jpg","wb");
$img_a = substr($twin_img,32,$img_a_leght);
fwrite($f_a, $img_a);
fclose($f_a);
$добивка_нулями = 16 - (hexdec($img_a_leght) % 16 );
if($добивка_нулями == 16) $добивка_нулями = 0;

//значит всего один файл там
if($img_a_leght_data != 32){
$f_b = fopen($folder_name."\\".str_pad($key+1, 4, "0", STR_PAD_LEFT)."_b.jpg","wb");
$img_b = substr($twin_img,$img_a_leght_data,$img_b_leght);
fwrite($f_b, $img_b);
fclose($f_b);
	}
}
else
{
$f_a = fopen($folder_name."\\".str_pad($key+1, 4,"0",STR_PAD_LEFT).".jpg","wb");
$img_a = substr($twin_img,0,$size);
fseek($file_gvg, hexdec($FF_to_JPEG),SEEK_CUR);


fwrite($f_a, $img_a);
fclose($f_a);
}



}


fclose($fp);
$fp = fopen($путь_к_файлам_параметров.$folder_name,"w");
fwrite($fp, $параметры_для_шапки);
fclose($fp);
//echo $twin_img["0"];






?>