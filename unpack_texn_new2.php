<?
$fp =  fopen($argv['1'],"rb");
//читаем длинну описаний, она же старт текста
fseek($fp,4);
$text_start = hexdec(bin2hex(fread($fp,4)));
$text_leght = hexdec(bin2hex(fread($fp,4)));

$dobivka =  16 -(($text_start+$text_leght)%16);
if ($dobivka == 16) $dobivka = 0;

//старт блока с интексами разделов
$text_index_all_atart = $text_start+$text_leght+$dobivka;

fseek($fp,$text_index_all_atart+4);
$key_count = hexdec(bin2hex(fread($fp,4)));
$key_leght = hexdec(bin2hex(fread($fp,4)));
$index_leght = hexdec(bin2hex(fread($fp,4)));

fseek($fp,$text_index_all_atart+16);
$key_blosk = fread($fp,$key_leght-16);
$index_block = fread($fp,$index_leght);


$array_index = explode(chr("0"),$index_block);

$start = 0;
print_r($array_index);
foreach($array_index as $value){

if(substr_count($value, "EVENT_")) $value = str_replace("EVENT_","", $value);

if($value == "") break;
$index_key[$value] = substr($key_blosk,$start,4);
$start += 8;


}
/*

EVENT_TEXT_INFO_BEGIN
EVENT_TEXT_INFO
EVENT_TEXT_INFO_END
EVENT_TEXT_INDEX_BEGIN
EVENT_TEXT_INDEX
EVENT_TEXT_INDEX_END







TEXT_INFO_BEGIN  начала блока описания текстов
TEXT_INFO  описание текстов. есть перед каждым описанием
TEXT_INFO_END  собственно окончание блока описания текстов

TEXT_INDEX_BEGIN  иэто индекс. не надо
TEXT_INDEX
TEXT_INDEX_END

NOUN_INFO_BEGIN  начало блока названий
NOUN_INFO   сами названия. тут же еще и множ числа и приставки
NOUN_INFO_END конец блока названия

NOUN_INDEX_BEGIN
NOUN_INDEX
NOUN_INDEX_END
*/
$full_file = file_get_contents($argv['1']);

//NOUN_INFO может не быть. поэтому ошибку подавим. а условие сработает верно
if(@substr_count($full_file, $index_key["NOUN_INFO"]) > 2) {
echo $index_key["TEXT_INFO"];
//блок с описаниями есть
$info_start_pos = strpos($full_file,$index_key["TEXT_INFO"]);
$info_count = substr_count($full_file, $index_key["TEXT_INFO"]);
fseek($fp,$info_start_pos);
$info_index_block = fread($fp,($info_count-1)*20);

//индекст описаний
$info_index_start_pos = strpos($full_file,$index_key["TEXT_INDEX"]);
$info_index_end_pos = strpos($full_file,$index_key["TEXT_INDEX_END"]);
fseek($fp,$info_index_start_pos);
$info_index2_block = fread($fp,$info_index_end_pos-$info_index_start_pos);


// блок названий
$name_start_pos = strpos($full_file,$index_key["NOUN_INFO"]);
$name_count = substr_count($full_file, $index_key["NOUN_INFO"]);
fseek($fp,$name_start_pos);
$name_index_block = fread($fp,($name_count-1)*72);

//индекст названий
$name_index_start_pos = strpos($full_file,$index_key["NOUN_INDEX"]);
$name_index_end_pos = strpos($full_file,$index_key["NOUN_INDEX_END"]);
fseek($fp,$name_index_start_pos);
$name_index2_block = fread($fp,$name_index_end_pos-$name_index_start_pos);

//загоняем в масив инфу с адресами описаний
$info_array = str_split ($info_index_block,20);
$name_index_array = str_split ($name_index2_block,20);//NOUN_INDEX
$name_array = str_split ($name_index_block,72);//NOUN_INFO



foreach($name_index_array as $value){//перебираем и выкидываеш лишние значения

$name_index_arr[hexdec(bin2hex(substr($value,16,4)))] = substr($value,8,4);


}
ksort($name_index_arr); // сортируем по ключам



//загоняем в масив инфу со стартом текста и собственно старт текста выковыриваем


/*
//удаляе
foreach($info_array as $key =>  $value){// удаляем DUMMY
$text_string_start = hexdec(bin2hex(substr($value,16,4)));
$text_string_end = strpos($full_file,chr("0"),$text_string_start+$text_start)+1 ;
$text = substr ($full_file,$text_string_start+$text_start,$text_string_end-($text_string_start+$text_start));

if($text != "DUMMY".chr("0")) $temp[] = $value;//unset($info_array[$key]); 
}
 
$info_array = $temp;
*/

$ftext = fopen($argv['1'].".txt", "w");

$info_index_array = str_split ($info_index2_block,20);
//пишем описание в файл
foreach($info_array as $key =>  $value){

$text_string_start = hexdec(bin2hex(substr($value,16,4)));
$text_string_end = strpos($full_file,chr("0"),$text_string_start+$text_start)+1 ;

//echo "\r\n";
$text = trim(substr ($full_file,$text_string_start+$text_start,$text_string_end-($text_string_start+$text_start)));
if($text_string_start == 4294967295) $text = "4294967295";
//echo $text."\r\n";
//$text_string_code[] = substr($value,8,4);



fwrite($ftext,$text."\r\n");
//unset($name);

}





//названия перебираем
foreach($name_array as $key => $value) {
// адреса названия в единственном числе
$name_string_start = hexdec(bin2hex(substr($value,32,4)));
$name_string_end = strpos($full_file,chr("0"),$name_string_start+$text_start)+1 ;
$name = trim(substr ($full_file,$name_string_start+$text_start,$name_string_end-($name_string_start+$text_start)));

//первая приставка
$name_the_string_start = hexdec(bin2hex(substr($value,20,4)));
if($name_the_string_start != "4294967295"){
$name_the_string_end = strpos($full_file,chr("0"),$name_the_string_start+$text_start)+1 ;
$the = trim(substr ($full_file,$name_the_string_start+$text_start,$name_the_string_end-($name_the_string_start+$text_start)));



}else
{
$the = 4294967295;
}
//вторая приставка
$name_a_string_start = hexdec(bin2hex(substr($value,24,4)));
if($name_a_string_start != "4294967295"){
$name_a_string_end = strpos($full_file,chr("0"),$name_a_string_start+$text_start)+1 ;
$a = trim(substr ($full_file,$name_a_string_start+$text_start,$name_a_string_end-($name_a_string_start+$text_start)));
}else
{
$a = 4294967295;
}
//третья приставка
$name_loaf_string_start = hexdec(bin2hex(substr($value,28,4)));
if($name_loaf_string_start != "4294967295"){
$name_loaf_string_end = strpos($full_file,chr("0"),$name_loaf_string_start+$text_start)+1 ;
$loaf = trim(substr ($full_file,$name_loaf_string_start+$text_start,$name_loaf_string_end-($name_loaf_string_start+$text_start)));
}else
{
$loaf = 4294967295;
}

//теперь множественное число
//первая приставка
$name_the2_string_start = hexdec(bin2hex(substr($value,36,4)));
if($name_the2_string_start != "4294967295"){
$name_the2_string_end = strpos($full_file,chr("0"),$name_the2_string_start+$text_start)+1 ;
$the2 = trim(substr ($full_file,$name_the2_string_start+$text_start,$name_the2_string_end-($name_the2_string_start+$text_start)));
}else
{
$the2 = 4294967295;
}
//вторая приставка
$name_a2_string_start = hexdec(bin2hex(substr($value,40,4)));
if($name_a2_string_start != "4294967295"){
$name_a2_string_end = strpos($full_file,chr("0"),$name_a2_string_start+$text_start)+1 ;
$a2 = trim(substr ($full_file,$name_a2_string_start+$text_start,$name_a2_string_end-($name_a2_string_start+$text_start)));
}else
{
$a2 = 4294967295;
}
//третья приставка
$name_loaf2_string_start = hexdec(bin2hex(substr($value,44,4)));
if($name_loaf2_string_start != "4294967295"){
$name_loaf2_string_end = strpos($full_file,chr("0"),$name_loaf2_string_start+$text_start)+1 ;
$loaf2 = trim(substr ($full_file,$name_loaf2_string_start+$text_start,$name_loaf2_string_end-($name_loaf2_string_start+$text_start)));
}else
{
$loaf2 = 4294967295;
}


$name2_string_start = hexdec(bin2hex(substr($value,48,4)));
if($name2_string_start != "4294967295"){
$name2_string_end = strpos($full_file,chr("0"),$name2_string_start+$text_start)+1 ;
$name2 = trim(substr ($full_file,$name2_string_start+$text_start,$name2_string_end-($name2_string_start+$text_start)));
}else
{
$name2 = 4294967295;
}

$nam .= $the."[@]".$a."[@]".$loaf."[@]".$name."[@]".$the2."[@]".$a2."[@]".$loaf2."[@]".$name2."\r\n";

//echo "\r\n";
} 








fwrite($ftext,$nam);
fclose($ftext);

/*

foreach($info_index_array as $key =>  $value){

$index[hexdec(bin2hex(substr($value,16,4)))] = substr($value,8,4);
//$index[$key]["code"] = substr($value,8,4);
}


ksort($index);
foreach($text_string_code as $key => $value){

$text_strings[$key]["start"] = $text_string_start[$key];
$text_strings[$key]["code"] = bin2hex($value);
$text_strings[$key]["index"] = array_search($value,$index);
unset($index[$a]);
}
*/
print_r($text_strings);







}else
{
//echo "net\r\n";
//блок с описаниями есть
$info_start_pos = strpos($full_file,$index_key["TEXT_INFO"]);
$info_count = substr_count($full_file, $index_key["TEXT_INFO"]);
fseek($fp,$info_start_pos);
$info_index_block = fread($fp,($info_count-1)*20);

//индекст описаний
$info_index_start_pos = strpos($full_file,$index_key["TEXT_INDEX"]);
$info_index_end_pos = strpos($full_file,$index_key["TEXT_INDEX_END"]);
fseek($fp,$info_index_start_pos);
$info_index2_block = fread($fp,$info_index_end_pos-$info_index_start_pos);

$info_array = str_split ($info_index_block,20);
$ftext = fopen($argv['1'].".txt", "w");

$info_index_array = str_split ($info_index2_block,20);
//пишем описание в файл
foreach($info_array as $key =>  $value){

$text_string_start = hexdec(bin2hex(substr($value,16,4)));
$text_string_end = strpos($full_file,chr("0"),$text_string_start+$text_start)+1 ;

//echo "\r\n";
$text = trim(substr ($full_file,$text_string_start+$text_start,$text_string_end-($text_string_start+$text_start)));
if($text_string_start == 4294967295) $text = "4294967295";
//echo $text."\r\n";
//$text_string_code[] = substr($value,8,4);



fwrite($ftext,$text."\r\n");
//unset($name);

}



fclose($ftext);
//echo "net\r\n";
}


/*
foreach($index_key   as $key => $value ){
echo $key."  ".bin2hex($value)."  ";
echo (substr_count($full_file, $value)-1)."\r\n"; // один который в низу самом в индексе
}


$fw = fopen("TEXT_INFO","wb");
fwrite($fw,$info_index_block );
fclose($fw);


$fw = fopen("TEXT_INDEX","wb");
fwrite($fw,$info_index2_block );
fclose($fw);

$fw2 = fopen("NOUN_INFO","wb");
fwrite($fw2,$name_index_block );
fclose($fw2);

$fw = fopen("NOUN_INDEX","wb");
fwrite($fw,$name_index2_block );
fclose($fw);
*/
?>