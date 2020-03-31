<?

$full_file = file_get_contents($argv['1']);
$kol_vo_block = "0x".bin2hex(substr($full_file,6 ,2));


$x = 1;
$head = substr($full_file,16 ,8*$kol_vo_block);
$start = 0;
while ($x<=$kol_vo_block)
{

$file_head[] = substr($head,$start ,8);
$start = $start+8;
$x++;
}
$fp = fopen(str_replace(".zarc","",$argv['1']), 'wb');
foreach ($file_head as $key => $value)
{
$file_start = hexdec(bin2hex(substr($value,4 ,4)))-1;
$file_leght = hexdec(bin2hex(substr($value,0 ,2)));

$block_zip = substr($full_file,$file_start ,$file_leght);
$block = gzinflate($block_zip);
fwrite($fp, $block);



}
fclose($fp);
unlink ($argv['1']);
//echo "output\\".str_replace(".zarc","",$argv['1']);
?>