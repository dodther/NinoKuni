<?


$имя_папки_с_картинками = basename ($argv["1"]);

function hexbin($str) {
            $it = strlen($str);
            $ret = '';
            for ($i=0;$i<$it;$i+=2) {
                    $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
                    }
            return $ret;
    }

$filesInFolder = scandir($argv["1"]);



$allFileSize =0;
foreach($filesInFolder as $value){
	if (substr_count($value, ".jpg") != 0 )
	{
	$list_files[] =  $value;
	$fileBlock = filesize($argv["1"]."\\".$value);
	$dobivka =  16 -($fileBlock%16);
	if ($dobivka == 16) $dobivka = 0;
	$fileBlockNull = $fileBlock+$dobivka;

	//размер всех файлов с добивкой нулями
	$allFileSize = $allFileSize +$fileBlockNull;
	}
if (substr_count($value, "a.jpg") != 0 ) $list_files_a[] =  $value;

}

//читаем из реестра путь к файлам настройки
exec("reg query HKEY_CLASSES_ROOT\.gvd\shell\����������_�_jpg /v path", $sss);
$путь_к_файлам_параметров0 = explode("   ", $sss["2"]);
$путь_к_файлам_параметров = trim(addslashes($путь_к_файлам_параметров0["3"]));

$путь_к_файлам_параметров = mb_convert_encoding($путь_к_файлам_параметров, "cp1251","cp866");



$leght_files_opis = count($list_files_a)*32;
//воспольуемся этой цифрой. поскольку подзаголовки тоже по 32 байта и есть у каждого блока фалов
$dataLeght = $leght_files_opis + $allFileSize; //длинна всех картинок вместе

if (substr_count($list_files["0"],"_a") == 0){ $file_type = "JPEG"; $leght_files_opis = count($list_files)*32;}


$подстрока_в_заголовке = file($путь_к_файлам_параметров.$имя_папки_с_картинками);


//данные заголовка
$head = hexbin("475645573031303047564D5030313030".bin2hex($подстрока_в_заголовке["0"])."424C4B5F");
if($file_type == "JPEG") $head = hexbin("47564557303130304A50454730313030".bin2hex($подстрока_в_заголовке["0"])."424C4B5F");
$head2 = hexbin("00000001000000000000002000000004");
//размер заголовка
$head_size = 8 + $leght_files_opis ;
$fp = fopen($argv["1"].".gvd.pack","wb");
fwrite($fp,$head.pack("N",$head_size).$head2);

//начинаем писать описание файлов в шапку
$serviseData = file($путь_к_файлам_параметров.$имя_папки_с_картинками.".txt");
$x=0;

if($file_type != "JPEG") {
foreach($list_files_a as $value){

//считаем длину файла а с его добивкой нулями
$leghtTwinFiles = filesize($argv["1"]."\\".$value)+32;
$dobivka =  16 -($leghtTwinFiles%16);
if ($dobivka == 16) $dobivka = 0;
$leghtTwinFiles = ($leghtTwinFiles + $dobivka);

//если в папке есть файл б то и его считаем
if(file_exists(str_replace("_a","_b",$argv["1"]."\\".$value))) {
	
	
	$leghtTwinFiles = $leghtTwinFiles + filesize(str_replace("_a","_b",$argv["1"]."\\".$value));
	$dobivka =  16 -(filesize(str_replace("_a","_b",$argv["1"]."\\".$value))%16);
	if ($dobivka == 16) $dobivka = 0;
	$leghtTwinFiles = $leghtTwinFiles+ $dobivka;
	}
	
$width_grid = pack("N",hexdec(substr($serviseData[$x],0,8)));
$Height_grid 	= pack("N",hexdec(substr($serviseData[$x],8,8)));
$layer = pack("N",hexdec(substr($serviseData[$x],16,8)));
$width_img = pack("N",hexdec(substr($serviseData[$x],24,8)));
$Height_img =	pack("N",hexdec(substr($serviseData[$x],32,8)));
	
//ну и собственно пишем в файл инфу о файлах	
$file_data = $width_grid.$Height_grid.$layer.pack("N*",$leghtTwinFiles,0,0).$width_img.$Height_img;
	

fwrite($fp,$file_data);

$x++;
}


fwrite($fp,hexbin("424C4B5F").pack("N",$dataLeght).hexbin("0000000200000000"));


//так. теперь начнем писать собственно сами файлы.
$GVMP = hexbin("47564D500000000200000020");
$x = 1;
foreach($list_files_a as $value){

$size_file_a = filesize($argv["1"]."\\".$value);
$dobivka =  16 -($size_file_a%16);
if ($dobivka == 16) $dobivka = 0;
$file_a = file_get_contents($argv["1"]."\\".$value);


$file_a_dobivka = hexbin(str_pad("",$dobivka*2,"0"));


if(file_exists(str_replace("_a","_b",$argv["1"]."\\".$value))) {
	
	
	$size_file_a2 = $size_file_a+32+$dobivka;
	$size_file_b = filesize(str_replace("_a","_b",$argv["1"]."\\".$value));
	$dobivka =  16 -($size_file_b%16);
	if ($dobivka == 16) $dobivka = 0;
	$file_b = file_get_contents(str_replace("_a","_b",$argv["1"]."\\".$value));
	$file_b_dobivka = hexbin(str_pad("",$dobivka*2,"0"));
	//echo $x."\r\n";
	}else
	{
	
	$size_file_a2  = 32;
	$size_file_b = $size_file_a;
	$file_b = "";
	$file_b_dobivka = "";
	}
fwrite($fp,$GVMP.pack("N*",$size_file_a,$size_file_a2,$size_file_b,0,0));
fwrite($fp,$file_a.$file_a_dobivka.$file_b.$file_b_dobivka); 



$x++;	

}








}
else // перенбираем просто картини без а
{
foreach($list_files as $value){

//считаем длину файла  с его добивкой нулями
$leghtTwinFiles = filesize($argv["1"]."\\".$value);

$dobivka =  16 -($leghtTwinFiles%16);
	if ($dobivka == 16) $dobivka = 0;

//$FF =  hexdec(substr($serviseData[$x],40,8));
$width_grid = pack("N",hexdec(substr($serviseData[$x],0,8)));
$Height_grid 	= pack("N",hexdec(substr($serviseData[$x],8,8)));
$layer = pack("N",hexdec(substr($serviseData[$x],16,8)));
$width_img = pack("N",hexdec(substr($serviseData[$x],24,8)));
$Height_img =	pack("N",hexdec(substr($serviseData[$x],32,8)));
	
//ну и собственно пишем в файл инфу о файлах	
$file_data = $width_grid.$Height_grid.$layer.pack("N*",$leghtTwinFiles,$dobivka,0).$width_img.$Height_img;
	

fwrite($fp,$file_data);

$x++;
}


fwrite($fp,hexbin("424C4B5F").pack("N",$dataLeght).hexbin("0000000200000000"));


//так. теперь начнем писать собственно сами файлы.

$x = 1;
foreach($list_files as $value){

$size_file_a = filesize($argv["1"]."\\".$value);
$dobivka =  16 -($size_file_a%16);
if ($dobivka == 16) $dobivka = 0;
$file_a = file_get_contents($argv["1"]."\\".$value);


$file_a_dobivka = hexbin(str_pad("",$dobivka*2,"F"));



fwrite($fp,$file_a.$file_a_dobivka); 



$x++;	

}



}




//print_r($list_files_a);
fclose($fp);




?>