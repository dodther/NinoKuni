<?
//dl("php_gd2.dll");

//$size_arr = getimagesize($argv['1']."_plt.png");
$img_plt = imagecreatefrompng($argv['1']."_plt.png");
$width_plt = imagesx($img_plt); 
//$rgb = imagecolorat($img_plt, "0","0");

for ($x=0,$xx = 0; $x<$width_plt; $x+=4, $xx++)
	{
	$color[$xx]["a"]  = imagecolorat($img_plt, $x,"0") & 0xFF; 
	$color[$xx]["r"] = imagecolorat($img_plt, $x+1,"0") & 0xFF; 
	$color[$xx]["g"] = imagecolorat($img_plt, $x+2,"0") & 0xFF; 
	$color[$xx]["b"] = imagecolorat($img_plt, $x+3,"0") & 0xFF; 
$alfa[]= imagecolorat($img_plt, $x,"0") & 0xFF; 

	}
	
	$img = imagecreatefrompng($argv['1'].".png");
	$img_alfa = imagecreatefrompng($argv['1'].".png");
	$width = imagesx($img);
	$height = imagesy($img);
	//$img_alfa = imagecreate ($width, $height);
	imagealphablending($img, false);
	imagesavealpha ($img, TRUE);
	//$png = imagecreatetruecolor($width,"1");
	//$temp_img = imagecreatetruecolor("1","1");
	for ($y=0; $y<$height; $y++){
	
	for ($x=0; $x<$width; $x++)//перебираем ширину
	{
	$rgb = imagecolorat($img, $x,$y);
	$color_temp = $rgb & 0xFF; 
	$color[$color_temp]["r"];
	$alfa_temp = ceil(128-($color[$color_temp]["a"] / 2))-1;
	//$alfa_temp = ($color[$color_temp]["b"]/2)-1;
	//$temp_color = imagecolorallocatealpha  ( $img, $color[$color_temp]["r"] , $color[$color_temp]["g"] , $color[$color_temp]["b"] ,$alfa_temp);
	$temp_color = imagecolorallocate  ( $img, $color[$color_temp]["r"] , $color[$color_temp]["g"] , $color[$color_temp]["b"]);
	 $temp_color_alfa = imagecolorallocate  ( $img_alfa, $color[$color_temp]["a"] , $color[$color_temp]["a"] , $color[$color_temp]["a"]);
	 //echo "\r\n";
	 imagesetpixel($img , $x , $y ,$temp_color );
	imagesetpixel($img_alfa , $x , $y ,$temp_color_alfa );
	}
	}
	 imagepng ($img , $argv['1']."_color.png");
	 imagepng ($img_alfa , $argv['1']."_alfa.png");
	//print_r($alfa);



?>