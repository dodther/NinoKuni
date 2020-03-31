<?

    function hexbin($str) {
            $it = strlen($str);
            $ret = '';
            for ($i=0;$i<$it;$i+=2) {
                    $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
                    }
            return $ret;
    }
 


$filesize = filesize($argv['1']);
$pcsblock = $filesize / 65536;
$filesize % 65536;
if (is_float($pcsblock)) {
//дробное
$lastblocksize = $filesize - (floor($pcsblock)*65536);
$pcsfullblock = floor($pcsblock);
$pcsblock = floor($pcsblock) + 1 ;
$lastblock = 1;

}
else
{

//целое 
$lastblock = 0;
$pcsfullblock = $pcsblock;
}


$full_file = file_get_contents($argv['1']);
$x=0;
if(($filesize % 65536)== 0) $x =1;
$leght_block = 65536;
$start_block = 0;

while ($x<=$pcsfullblock)
{

$file = substr($full_file,$start_block ,$leght_block);
//$leght_block = $leght_block + 65536 ;
$start_block = $start_block + 65536;

$file_zip = gzdeflate($file);
$file_zip_size = strlen($file_zip);

$files_size[$x] = $file_zip_size; //размер жатых данных без нулей


$last_null =  round((ceil($file_zip_size/16) - $file_zip_size/16)*16) ;

$file_zip = $file_zip.hexbin(str_pad("",$last_null*2,"0"));//увеличиваем блок до ряда
$block_size[$x] = strlen($file_zip); //размер жатых данных с нулями
$zip = $zip.$file_zip;
$file_zip_test[] = bin2hex($file_zip);
$x++;

}


//считаем длину щапки
if ($pcsblock%2!=0){
$head_leght = (($pcsblock+1)*8)+16;
$head_hull = hexbin(str_pad("",16,"0"));
}else
{
$head_leght = ($pcsblock*8)+16;
}

$start = $head_leght +1;
foreach ($block_size as $key => $value) {

if($key == count($block_size)-1) $l = $lastblocksize;
$file_head[$key] = pack("nnN",$files_size[$key], $l, $start);

$start = $start + $block_size[$key];
}



$file_leght = $head_leght+strlen($zip); //длина жатых данных
$head = hexbin("7A6172630001");
if ($lastblock != 0)$lastblock = $lastblocksize;

//echo bin2hex(pack("nn*",$pcsblock,$pcsfullblock));
echo $имя_зарк_файла = $argv['1'].".zarc";

if(strpos($имя_зарк_файла,".pack"))  $имя_зарк_файла = str_replace( ".pack", "" , $имя_зарк_файла );

$fp = fopen($имя_зарк_файла, 'wb');
fwrite($fp, $head.pack("nnnN",$pcsblock,$pcsfullblock,$lastblock,$file_leght));
foreach ($block_size as $key => $value) {
fwrite($fp,$file_head[$key]);

}
if(isset($head_hull)) fwrite($fp,$head_hull);
fwrite($fp,$zip);

//fwrite($fp,$zip);
fclose($fp);

unlink ($argv['1']);

?>