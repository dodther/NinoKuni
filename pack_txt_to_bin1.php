<?



$входяший_txt =  $argv['1'];
$входяший_bin = str_replace(".txt","",$входяший_txt);
$выходящий_запак = $входяший_bin;

//читаем из реестра путь к файлам настройки
exec("reg query HKEY_CLASSES_ROOT\.1 /v path", $sss);
$путь_к_файлам_параметров0 = explode("   ", $sss["2"]);
$путь_к_файлам_параметров = trim(addslashes($путь_к_файлам_параметров0["3"]));

$путь_к_файлам_параметров = mb_convert_encoding($путь_к_файлам_параметров, "cp1251","cp866");
$входяший_bin2 = basename($входяший_bin);
$входяший_bin = $путь_к_файлам_параметров.$входяший_bin2;
if(!file_exists($входяший_bin)) {echo iconv("UTF-8", "CP866", "\n\rОтсутствует файл bin ".$входяший_bin); system("pause"); exit();}


$file_bin = fopen($входяший_bin, 'rb');
$full_bin = file_get_contents($входяший_bin);
$file_texts = file($входяший_txt);
//$leght_text = filesize($argv['1'].".txt");

//первые 16 байт. тут хранится длинна такста
$_1head = fread($file_bin,16);

//определяем первое вхождение текста
$text_block_start = strpos($full_bin,hexbin(B60B8176));

//количество строк в описаниях
//echo $text_count = hexdec(bin2hex(substr($full_bin,24,4))); // не верно определяло
$text_count =  substr_count($full_bin,hexbin("B60B8176"))-1;  // минус одина штука что в подвале

if(substr_count($file_texts['0'],hexbin("EFBBBF"))) $file_texts['0'] = str_replace(hexbin("EFBBBF"),"",$file_texts['0']); 
if($text_count < count($file_texts)){ //проверочка на наличие 2й части


foreach($file_texts as $key => $value){

                  
if($text_count > $key)$file_text[] = $value;
if($text_count <= $key) $name_text[] = $value;
}

}
else
{
$file_text = $file_texts;

}

//читаем все до начала  блока с адресами текстов
$_2head = fread($file_bin,$text_block_start-16);// 16 это первая строчка которая в передыдущий файл записали

// провнряем наличие сервисного текста
//echo substr_count($full_bin,hexbin("3B2B78DA"));
if(substr_count($full_bin,hexbin("3B2B78DA"))) {

$LAST_UPDATE_DATE_TIME =  hexdec(bin2hex(substr ( $full_bin, $text_block_start+16 , 4 )))-1;

 
 }
$text_start_teku = 0;
$arr_text = array();
//echo arr_ser($file_texts,"<START_MSG_ARG1> down, <START_MSG_ARG2> to go!");
foreach($file_text as $key => $value){
//echo $LAST_UPDATE_DATE_TIME;
if(isset($LAST_UPDATE_DATE_TIME)) $text = substr($full_bin,hexdec(bin2hex(substr ( $_1head, 4 , 4 ))),$LAST_UPDATE_DATE_TIME+1);
if(isset($LAST_UPDATE_DATE_TIME)) $прибавка_для_повтора = $LAST_UPDATE_DATE_TIME+1; // дописал для прибавки длины к повторяющимся значениям


$orig_index = fread($file_bin,20);
unset($LAST_UPDATE_DATE_TIME);
if(trim($value) != "4294967295" )
{

if(arr_ser($arr_text,$value) == "net")
			{
			$text_start = strlen($text);

			$text .= trim($value).chr("0");
			$arr_text[] = $value;
			}
			else{
			
			$text_start =  arr_content_count($arr_text,arr_ser($arr_text,$value))+$прибавка_для_повтора;
			//echo "  ".$value;
			//echo "\r\n";
			//echo dechex(arr_content_count($arr_text,arr_ser($arr_text,$value)))."\r\n";
			}
			
		$text_indexx = substr_replace($orig_index,pack("N",$text_start),16,4);		
}
else
{
$text .= "";
//$arr_start[] = "FFFFFFFF";
$text_indexx = substr_replace($orig_index,pack("nn","65535","65535"),16,4);			
}
$text_indexs .= $text_indexx;
//echo arr_ser($arr_text,$value)."   ".trim($value)."   ".$key."\r\n";


//$arr_start[] = dechex($text_start_teku);



}
//echo dechex(arr_content_count($arr_text, "259"));
 


$text_block_end = strpos($full_bin,hexbin("9521F658"));
$name_block_start = strpos($full_bin,hexbin("DDBF156C"));
if($name_block_start < 1) $name_block_start = hexdec(bin2hex(substr ( $_1head, 4 , 4 )));

fseek($file_bin,$text_block_end);
$TEXT_INDEX = fread($file_bin,$name_block_start-$text_block_end);


if($text_count < count($file_texts)){//отрезаем формирование второго блока
// начинаем форимровать 2й блок. описание суффиксов
$name_start = strlen($text);

foreach($name_text as $key => $value){
$orig_index = fread($file_bin,72);

//все суффиксы в массив и по переменным
$suffix_arr = explode("[@]",$value);
$the =  $suffix_arr["0"];
$a = $suffix_arr["1"];
$loaf =  $suffix_arr["2"];
$name = $suffix_arr["3"];
$the2 =  $suffix_arr["4"];
$a2 = $suffix_arr["5"];
$loaf2 =  $suffix_arr["6"];
$name2 = trim($suffix_arr["7"]);


//echo $key." => ".$value."\r\n";

// первая приставка. проверяем на FFF
if($the == "4294967295") {
	$to_the = hexbin("FFFFFFFF");
	}else{
	
	
	// аздесь до кучи проверяем была ли уже эта приставка
		if(!strstr( $the_temp,$the)){
			$names .= $the." ".chr("0");
			$to_the = pack("N",$name_start);
			$the_arr[$the] = $name_start;
			$name_start += strlen($the)+2;
			
		    
			$the_temp .= $the;
									}
									else
									{
				$to_the = pack("N",$the_arr[$the]);						
									}
									
	
	
}


// вторя на FFF
if($a == "4294967295") {
	$to_a = hexbin("FFFFFFFF");
	}else{
	
	       
	// аздесь до кучи проверяем была ли уже эта приставка
		if(!strstr($a_temp,$a)){
			$names .= $a." ".chr("0");
			$to_a = pack("N",$name_start);
			$a_arr[$a] = $name_start;
			$name_start += strlen($a)+2;
			
		    
			$a_temp .= $a;
									}
									else
									{
				$to_a = pack("N",$a_arr[$a]);						
									}
									
	
	
}



// третья приставка 
if($loaf == "4294967295") {
	$to_loaf = hexbin("FFFFFFFF");
	}else{
	
	
	// аздесь до кучи проверяем была ли уже эта приставка
		if(!strstr( $loaf_temp,$loaf)){
			$names .= $loaf." ".chr("0");
			$to_loaf = pack("N",$name_start);
			$loaf_arr[$loaf] = $name_start;
			$name_start += strlen($loaf)+2;
			
		    
			$loaf_temp .= $loaf;
									}
									else
									{
				$to_loaf = pack("N",$loaf_arr[$loaf]);						
									}
									
	
	
}



			// название в единственном
			$names .= $name.chr("0");
			$to_name = pack("N",$name_start);
			$to_name2_start = $name_start;
			$name_start += strlen($name)+1;
			
		    
			
									
	
	

// первая приставка. множественное. проверяем на FFF
if($the2 == "4294967295") {
	$to_the2 = hexbin("FFFFFFFF");
	}else{
	
	
	// аздесь до кучи проверяем была ли уже эта приставка
		if(!strstr( $the2_temp,$the2)){
			$names .= $the2." ".chr("0");
			$to_the2 = pack("N",$name_start);
			$the2_arr[$the2] = $name_start;
			$name_start += strlen($the2)+2;
			
		    
			$the2_temp .= $the2;
									}
									else
									{
				$to_the2 = pack("N",$the2_arr[$the2]);						
									}
									
	
	
}

// второе  множественное
if($a2 == "4294967295") {
	$to_a2 = hexbin("FFFFFFFF");
	}else{
	
	       
	// аздесь до кучи проверяем была ли уже эта приставка
		if(!strstr($a2_temp,$a2)){
			$names .= $a2." ".chr("0");
			$to_a2 = pack("N",$name_start);
			$a2_arr[$a2] = $name_start;
			$name_start += strlen($a2)+2;
			
		    
			$a2_temp .= $a2;
									}
									else
									{
				$to_a2 = pack("N",$a2_arr[$a2]);						
									}
									
	
	
}

// третья приставка 
if($loaf2 == "4294967295") {
	$to_loaf2 = hexbin("FFFFFFFF");
	}else{
	
	
	// аздесь до кучи проверяем была ли уже эта приставка
		if(!strstr( $loaf2_temp,$loaf2)){
			$names .= $loaf2." ".chr("0");
			$to_loaf2 = pack("N",$name_start);
			$loaf2_arr[$loaf2] = $name_start;
			$name_start += strlen($loaf2)+2;
			
		    
			$loaf2_temp .= $loaf2;
									}
									else
									{
				$to_loaf2 = pack("N",$loaf2_arr[$loaf2]);						
									}
									
	
	
}

// имя в множественном
if($name2 == "4294967295") {
	$to_name2 = hexbin("FFFFFFFF");
	}else{
	
	
	
			if($name != $name2){
			$names .= $name2.chr("0");
			$to_name2 = pack("N",$name_start);
			$name_start += strlen($name2)+1;
								}else
								{
								
								$to_name2 = pack("N",$to_name2_start);
								
								}
		    
			
									
									
	
	
}



$name_string .= substr_replace($orig_index,$to_the.$to_a.$to_loaf.$to_name.$to_the2.$to_a2.$to_loaf2.$to_name2 ,20,32);







}


// индексты второго блока
$name_block_end = strpos($full_bin,hexbin("0A5682E3"));
$text_block_start = strpos($full_bin,hexbin("05823E06"));
fseek($file_bin,$name_block_end);

$NAME_INDEX = fread($file_bin,$text_block_start-$name_block_end+8);// захватить прям до имен

//добиваем индекс FFFF
$dobivka =   16 - (strlen($_1head.$_2head.$text_indexs.$TEXT_INDEX.$name_string.$NAME_INDEX)%16);
//echo strlen($_1head.$_2head.$text_indexs.$TEXT_INDEX);
if ($dobivka == 16) $dobivka = 0;



//Добивка
$NFFF = hexbin(str_pad("",$dobivka*2,"F"));
//$NFFF = hexbin(str_pad("",$dobivka,"FF"));

$NAME_INDEX .= $NFFF ;

//добиваем все индексты до конца строки. так ка текст должен с новой начатся

}
else
{

if(substr_count($full_bin,hexbin("05823E06")) AND substr_count($full_bin,hexbin("DDBF156C"))){

$начало_индекса = strpos($full_bin,hexbin("DDBF156C"));
$конец_индекса = strpos($full_bin,hexbin("05823E06"));
fseek($file_bin,$начало_индекса);

$NAME_INDEX  = fread($file_bin,$конец_индекса-$начало_индекса+8);// захватить прям до имен

//добиваем индекс FFFF
$dobivka =   16 - (strlen($_1head.$_2head.$text_indexs.$TEXT_INDEX.$name_string.$NAME_INDEX)%16);
//echo strlen($_1head.$_2head.$text_indexs.$TEXT_INDEX);
if ($dobivka == 16) $dobivka = 0;



//Добивка
$NFFF = hexbin(str_pad("",$dobivka*2,"F"));
//$NFFF = hexbin(str_pad("",$dobivka,"FF"));

$NAME_INDEX .= $NFFF ;
}
}




// добиваем текст до конца строки
$all_leght = strlen($_1head.$_2head.$text_indexs.$TEXT_INDEX.$name_string.$NAME_INDEX.$text.$names);
$dobivka =  16 -($all_leght%16);
if ($dobivka == 16) $dobivka = 0;
$FFF = hexbin(str_pad("",$dobivka*2,"F"));


// определяем начало подвала, аго длинну и собственно читаем
fseek($file_bin,4);
$head_leght = hexdec(bin2hex(fread($file_bin,4)));
$text_leght = hexdec(bin2hex(fread($file_bin,4)));
$dobivka =  16 -(($text_leght+$head_leght)%16);
if ($dobivka == 16) $dobivka = 0;
$footer_start = $text_leght+$head_leght+$dobivka;
$footer_size = filesize($входяший_bin)-$footer_start;
fseek($file_bin,$footer_start);
$footer = fread($file_bin,$footer_size);


$new_text_size = strlen($text.$names);
$_1head = substr_replace($_1head,pack("N",$new_text_size) ,8,4);

$ftest = fopen($выходящий_запак.".pack","wb");
fwrite($ftest,$_1head.$_2head.$text_indexs.$TEXT_INDEX.$name_string.$NAME_INDEX.$text.$names.$FFF.$footer);
fclose($ftest);



/*
$ftest = fopen("ttt","wb");
fwrite($ftest,$text);
fclose($ftest);

$ftest = fopen("name_ind","wb");
fwrite($ftest,$name_string);
fclose($ftest);

$ftest = fopen("name","wb");
fwrite($ftest,$names);
fclose($ftest);
*/
function hexbin($str) {
            $it = strlen($str);
            $ret = '';
            for ($i=0;$i<$it;$i+=2) {
                    $ret .= pack("H",$str[$i]) | pack("h", $str[$i+1]);
                    }
            return $ret;
    }

	
function arr_ser($array, $search){

	foreach($array as $key => $value){
	
	if(strstr($value,$search) AND strlen(trim($value)) ==  strlen(trim($search))) return $key;
	
	}
	
return "net";



}
function arr_content_count($array, $keys)
	{
		foreach($array as $key => $value)
		{
		$legt += strlen(trim($value))+1;
		if( $key == ($keys-1)) break ;
		}
		return $legt;
	}
?>