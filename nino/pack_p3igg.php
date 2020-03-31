<?
$file_p3img = file_get_contents($argv['1']);
$file_kol_vo = hexdec(bin2hex(substr($file_p3img,0x28 ,4)));
$head_size = bin2hex(substr($file_p3img,0x10 ,4));
$head_size = hexdec($head_size);
$skip = hexdec(bin2hex(substr($file_p3img,0x30 ,4))) ;
$x = 1;
$file_block_start = $head_size+$skip;
echo $file_kol_vo;
while ($x<=$file_kol_vo)
{
$file_block[] = substr($file_p3img,$file_block_start ,48);

$x++;
$file_block_start = $file_block_start+48;
}
$p3igg_name = str_replace(".p3img","",$argv['1'].".p3igg");
$fp = fopen($p3igg_name, 'wb');
foreach ($file_block as $key => $value)
{

$name_start = $head_size + hexdec(bin2hex(substr($value,0 ,4)));
$name_end = strpos($file_p3img,chr("0"),$name_start);
$name = substr($file_p3img,$name_start ,$name_end-$name_start);
$temp_file = substr(file_get_contents($name.".dds"),128);
fwrite($fp, $temp_file);

}

fclose($fp);

//print_r($name);
?>