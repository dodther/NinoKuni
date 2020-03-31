<?
$full_file = file_get_contents($argv['1']);

//$kol_vo_block = "0x".bin2hex(substr($full_file,0xC ,4));
$start_text = "0x".bin2hex(substr($full_file,0x4 ,4));
$leght_text = "0x".bin2hex(substr($full_file,0x8 ,4));

$text_all = substr($full_file,$start_text ,$leght_text);

$text_array = explode(chr("00"),$text_all);
$text_array = array_diff($text_array, array(""));

$fp = fopen($argv['1'].".txt", 'wb');
foreach ($text_array as  $value)
{

fwrite($fp, $value."[@]\r\n");
}
fclose($fp);
?>