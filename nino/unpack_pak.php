<?

$full_file = file_get_contents($argv['1']);
$file_kol_vo = "0x".bin2hex(substr($full_file,0x4 ,4));
$head_size = "0x".bin2hex(substr($full_file,0x10 ,4)) ;
$data_start = "0x".bin2hex(substr($full_file,0x14 ,4)) ;
//$head_size = hexdec($head_size);
$file_block_start = "0x20" ;

$x=1;
while ($x<=$file_kol_vo)
{
$file_block[] = substr($full_file,$file_block_start ,32);

$x++;
$file_block_start = $file_block_start+32;
}

$name_block = (substr($full_file,$head_size ,$data_start-$head_size));
//echo $name_block;

$filein = pathinfo($argv['1']);

$dir_name = str_replace(".".$filein["extension"],"",$argv['1']);
if(!is_dir($dir_name)) mkdir($dir_name);

$fpath = fopen($dir_name."\\path.txt", 'wb');
$fname = fopen($dir_name."\\name.txt", 'wb');
foreach ($file_block as $key => $value){


$name_start = "0x".bin2hex(substr($value,0x18 ,4)) ;
$name_end = strpos($name_block,chr("0"),$name_start);
$name_path = substr($name_block,$name_start ,$name_end-$name_start);
$name_aray = pathinfo($name_path);

//записаваем как файлы следовали в оригинале
fwrite($fpath, $name_aray['dirname']."\r\n");
fwrite($fname, $name_aray["basename"]."\r\n");

$data_block_start = "0x".bin2hex(substr($value,0x10 ,4)) ;
$data_block_leght = "0x".bin2hex(substr($value,0x14 ,4)) ;
$data = substr($full_file,$data_start+$data_block_start ,$data_block_leght);
$name = $name_aray["basename"];
$fp = fopen($dir_name."\\".$name, 'wb');
fwrite($fp, $data);
fclose($fp);
$x++;
}
fclose($fpath);
fclose($fname);
//print_r($name);


?>