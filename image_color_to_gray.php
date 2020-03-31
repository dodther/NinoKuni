<?
dl("php_gd2.dll");
$img_plt = imagecreatefrompng($argv['1']."_plt.png");
$width_plt = imagesx($img_plt);
$img_alfa = imagecreatefrompng($argv['1']."_alfa.png");
//$img_original = imagecreatefrompng("pause02_color.png");
//echo imagecolorat($img_plt, "0","0")."\r\n"; 
//echo imagecolorat($img_plt, "0","0")& 0xFF; 
for ($x=0,$xx = 0; $x<$width_plt; $x+=4, $xx++)
	{
	$color_a = imagecolorat($img_plt, $x,"0") & 0xFF; 
	$color_r = imagecolorat($img_plt, $x+1,"0") & 0xFF; 
	$color_g = imagecolorat($img_plt, $x+2,"0") & 0xFF; 
	$color_b = imagecolorat($img_plt, $x+3,"0") & 0xFF;
	$alfa_plt[] = 	$color_a;

	$temp_color[] = imagecolorallocate  ( $img_plt , $color_r  , $color_g  , $color_b)."|".$color_a;
	$temp_color_no_a[] = imagecolorallocate  ( $img_plt , $color_r  , $color_g  , $color_b);
	//echo "\r\n";
	}
	//print_r($temp_color);
	$img_color = imagecreatefrompng($argv['1'].".png");
	$width = imagesx($img_color);
	$height = imagesy($img_color);
	for ($y=0; $y<$height; $y++){
	
	for ($x=0; $x<$width; $x++)//перебираем ширину
	{
	$color = imagecolorat($img_color, $x,$y); //цвет пикселя на цветном изображении
	
	$alfa = imagecolorat($img_alfa, $x,$y)& 0xFF; 
	$count_color[] = $color;
	// ищем совпадение цвета с альфой в палитре
	if(!($key = array_search ( $color."|".$alfa , $temp_color ) ) ){
		 // если нет такого цвета, то ищем ближайший
		if(!($key = array_search ( $color , $temp_color_no_a  ))) $key = fund_bliz_key($temp_color_no_a,$color); // позиция цвета в палитре
		
		
		$цвет_для_поиска_альфы = $temp_color_no_a[$key];
		$ключи_для_поиска_альфы = array_keys($temp_color_no_a, $цвет_для_поиска_альфы);
		
		foreach($ключи_для_поиска_альфы as $value){
				
				$массив_с_альфами[] =  $alfa_plt[$value];
				}
				
			$ближайшая_альфа = fund_bliz_num($массив_с_альфами,$alfa);
		
			$key = array_search ( $цвет_для_поиска_альфы."|".$ближайшая_альфа , $temp_color );
			
			unset($массив_с_альфами);
		}
	
	
	
	
			
	$gray_color = imagecolorallocate  ( $img_color, $key , $key , $key);//создаем цвет
	imagesetpixel($img_color , $x , $y ,$gray_color );//назначаем его пикселю
	
	
	
	//$temp_color = imagecolorallocate  ( $img, $color[$color_temp]["r"] , $color[$color_temp]["g"] , $color[$color_temp]["b"]);
	}
	echo $y."\r\n";
	}
	//$keys = array_keys($temp_color_no_a, "4661506" );
	//$aaa = array_unique($rererer);
	//sort($aaa);
	//print_r($aaa);
	//print_r($temp_color);
	imagepng ($img_color , $argv['1']."_grayt.png");
	

	
	
	
	
function color_search($search , $array){

	foreach($array as $key => $value){
	
	if($value == $search)  return $key;
	
	}
	
return "net";



}


function fund_bliz_num($array,$mynum){

$raznica = 157903200;
foreach($array as $key => $value){

if (abs($value-$mynum) < $raznica) {$raznica = abs($value-$mynum); $mykey = $key;}

}
return  $array[$mykey];

}

function fund_bliz_key($array,$mynum){

$raznica = 157903200;
foreach($array as $key => $value){

if (abs($value-$mynum) < $raznica) {$raznica = abs($value-$mynum); $mykey = $key;}

}
return  $mykey;

}



?>