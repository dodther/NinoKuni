<?
$full_file = file_get_contents($argv['1']);

$file_kol_vo = "0x".bin2hex(substr($full_file,0x28 ,4));
$head_size = bin2hex(substr($full_file,0x10 ,4)) ;

$head_size = hexdec($head_size);
$skip = hexdec(bin2hex(substr($full_file,0x30 ,4))) ;
$x = 1;
$file_block_start = $head_size+$skip;

while ($x<=$file_kol_vo)
{
$file_block[] = substr($full_file,$file_block_start ,48);

$x++;
$file_block_start = $file_block_start+48;
}


$file_start = 0;
$file_p3igg= file_get_contents(str_replace(".p3img","",$argv['1'].".p3igg"));
foreach ($file_block as $key => $value)
{


$size = bin2hex(substr($value,8 ,4))."!!";
$name_start = $head_size + hexdec(bin2hex(substr($value,0 ,4)));
$visota = "0x".bin2hex(substr($value,26,2));
$visota = hexdec($visota );
$visota = bin2hex(pack("v",$visota));

$shirina = "0x".bin2hex(substr($value,24,2));
$shirina = hexdec($shirina );
$shirina = bin2hex(pack("v",$shirina));


$name_end = strpos($full_file,chr("0"),$name_start);
$name = substr($full_file,$name_start ,$name_end-$name_start);



// сальфой
//$head = "444453207C00000007100800".$visota."0000".$shirina."0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020000000010002000000000010000000FF000000000000000000000000FF00000010000000000000000000000000000000000000" ;
// БЕЗ АЛЬФЫ
/*
$head = "444453207C00000007100800".$visota."0000".$shirina."0000000008000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020000000000002000000000008000000FF0000000000000000000000000000000010000000000000000000000000000000000000";
*/
//dxt3

$head = "444453207C00000007100800".$visota."0000".$shirina."0000004000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020000000040000004458543300000000000000000000000000000000000000000010000000000000000000000000000000000000";


// щапка для палитроы
if(substr_count($name, "_plt")) $head = "444453207C000000071008000100000000040000000400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020000000000002000000000008000000FF0000000000000000000000000000000010000000000000000000000000000000000000";


$size = "0x".$size;
$file = substr($file_p3igg,$file_start,$size);
$file_start  = $file_start+$size;

$fp = fopen($name.".".dds, 'wb');
fwrite($fp, hexbin($head).$file);
fclose($fp);


//$name = 
//echo $shirina."!";
}

rename ( str_replace(".p3img","",$argv['1'].".p3igg"),str_replace(".p3img","",$argv['1'].".p3igg")."_" );
function hexbin($str) {
            $it = strlen($str);
            $ret = '';
            for ($i=0;$i<$it;$i+=2) {
                    $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
                    }
            return $ret;
    }

?>