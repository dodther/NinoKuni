<?

$картинка_с_палитрой = fopen($argv['1'], "rb");

fseek($картинка_с_палитрой, 12);
$размеры_картинки = fread($картинка_с_палитрой,8);
$размеры_картинки = bin2hex($размеры_картинки);

fseek($картинка_с_палитрой, 128);

$несортированая_палитра = fread($картинка_с_палитрой,1024);
$картинка = fread($картинка_с_палитрой, filesize($argv['1']));


$имя_картинки = str_replace ( "_palitred" , "" ,$argv['1']);
$имя_палитры = str_replace ( "_palitred" , "_plt" ,$argv['1']);


$заголовок_картинки = "444453207C00000007100800".$размеры_картинки."000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020000000000002000000000008000000FF0000000000000000000000000000000010000000000000000000000000000000000000";

$заголовок_палитры = "444453207C000000071008000100000000040000000400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020000000000002000000000008000000FF0000000000000000000000000000000010000000000000000000000000000000000000";

$fp = fopen($имя_палитры, 'wb');
fwrite($fp, hexbin($заголовок_палитры));

fseek($картинка_с_палитрой, 128);

for ($x=1; $x<=256; $x++) {

$первый =  fread($картинка_с_палитрой, 1);
$второй =  fread($картинка_с_палитрой, 1);
$третий =  fread($картинка_с_палитрой, 1);
//$цвета = fread($картинка_с_палитрой, 3);
$альфа = fread($картинка_с_палитрой, 1);


fwrite($fp, $альфа);
//fwrite($fp, $цвета);

fwrite($fp, $первый );
fwrite($fp, $второй );
fwrite($fp, $третий );

}
fclose($fp);

$fp = fopen($имя_картинки, 'wb');

fwrite($fp, hexbin($заголовок_картинки));
fwrite($fp, $картинка);
fclose($fp);




 function hexbin($str) {
            $it = strlen($str);
            $ret = '';
            for ($i=0;$i<$it;$i+=2) {
                    $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
                    }
            return $ret;
    }
 




?>