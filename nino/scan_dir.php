<?


// функция копирования файлов (включая вложеные) из папки $source в $res
function copy_files($source, $res, $filtr){
    $hendle = opendir($source); // открываем директорию
    while ($file = readdir($hendle)) {
	
	
        if (($file!=".")&&($file!="..")&&(substr_count($file, "translate") == 0)) {
           // if (is_dir($source."\\".$file) == true ) echo $file;
			
			if (substr_count($file, $filtr) != 0  OR is_dir($source."\\".$file) == true ){
			if (is_dir($source."\\".$file) == true ) {
			
			
                if(is_dir($res."\\".$file)!=true) // существует ли папка
                    mkdir($res."\\".$file, 0777); // создаю папку
					
                   copy_files ($source."\\".$file, $res."\\".$file, $filtr);
				   
            }
			
			
            else{
			//if (substr_count($file, "_en.") != 0 ){
                if(   !copy($source."\\".$file, $res."\\".$file)      ) { 
                    print ("при копировании файла $file произошла ошибка...<br>\n"); 
                }// end if copy
            //} 
			}
			}
        } // else $file == ..
		
    } // end while
    closedir($hendle);
}


function RemoveEmptySubFolders($path) {
 $empty=true; 
 foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file) { 
 $empty &= is_dir($file) && RemoveEmptySubFolders($file); 
 } return 
 $empty && rmdir($path); 
}

function GetListFiles($folder,&$all_files){
    $fp=opendir($folder);
    while($cv_file=readdir($fp)) {
        if(is_file($folder."/".$cv_file)) {
            $all_files[]=$folder."\\".$cv_file;
        }elseif($cv_file!="." && $cv_file!=".." && is_dir($folder."\\".$cv_file)){
            GetListFiles($folder."\\".$cv_file,$all_files);
        }
    }
    closedir($fp);
}

//$test_igg[] = "translate\\eng\\hd01\\data\\menu\\pause\\en\\pause01.p3igg.zarc"; 
//$test_igg[] = "translate\\eng\\hd01\\data\\menu\\pause\\en\\pause01.p3img.zarc";
//translate\eng\hd01\data\menu\pause\en\1_eng_zarc

function unpackZarc($path){




$path_parts = pathinfo($path);

// следующий проход. подправляем пути. так как файл уже в другом месте
if(substr_count($path, "p3img") != 0  ){
$path = $path_parts['dirname']."\\\\1_eng_zarc\\\\".$path_parts['basename'];
}
$folder = $path;


if(!is_file($path)) return FALSE;

$full_file = file_get_contents($path);
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



$new_path = $path_parts['dirname']."\\\\".$path_parts['basename']."\\\\".str_replace(".zarc","",$path_parts['basename']);
if(substr_count($path, "p3img") != 0) $new_path = $path_parts['dirname']."\\\\".str_replace("p3img", "p3igg",$path_parts['basename'])."\\\\".str_replace(".zarc","",$path_parts['basename']);

//создаем временную папку если ее еще нет
if(is_dir($path_parts['dirname']."\\\\1_eng_zarc")== false) mkdir($path_parts['dirname']."\\\\1_eng_zarc");
//перемещаем туда наш файл
rename($path,$path_parts['dirname']."\\\\1_eng_zarc\\\\".$path_parts['basename']);

//если в имени есть p3igg перемещать будем и 2й файлю так как они идут парой
if(substr_count($path, "p3igg") != 0  ) rename(str_replace("p3igg", "p3img",$path),$path_parts['dirname']."\\\\1_eng_zarc\\\\".str_replace("p3igg", "p3img",$path_parts['basename']));


//создаем папку с именем файла там где он был
@mkdir($folder);

$fp = fopen($new_path, 'wb');
foreach ($file_head as $key => $value)
{
$file_start = hexdec(bin2hex(substr($value,4 ,4)))-1;
$file_leght = hexdec(bin2hex(substr($value,0 ,2)));

$block_zip = substr($full_file,$file_start ,$file_leght);
$block = gzinflate($block_zip);
fwrite($fp, $block);



}
fclose($fp);
}

function unpackText($path){

$full_file = file_get_contents($path);


$start_text = "0x".bin2hex(substr($full_file,0x4 ,4));
$leght_text = "0x".bin2hex(substr($full_file,0x8 ,4));

$text_all = substr($full_file,$start_text ,$leght_text);

$text_array = explode(chr("00"),$text_all);
$text_array = array_diff($text_array, array(""));

$path_parts = pathinfo($path);

print_r($path_parts );

$fp = fopen("translate\\eng\\text\\".$path_parts['basename'].".txt", 'w');
foreach ($text_array as  $value)
{

fwrite($fp, $value."\r\n");
}
fclose($fp);




}


copy_files($argv['1'],"translate\\eng", "_en.");


//сканирование папки с игрой. ищем папки со словом en

$all_files=array();
GetListFiles($argv['1'],$all_files);
foreach ($all_files as $key => $value)
{
if (substr_count($value, "\\en\\") != 0 AND substr_count($value, "sound") == 0 AND substr_count($value, "translate") == 0 AND substr_count($value, "lipsync") == 0) $folder_en[] = $value;
if (substr_count($value, "_en") != 0 AND substr_count($value, "sound") == 0 AND substr_count($value, "translate") == 0 ) $files_en[] = $value;
}
//print_r($files_en);
//print_r($folder_en);
//перебираем массив папок en и копируем в переводы. lipsync


foreach ($folder_en as $key => $value){
$position = strpos ($value,"ni_no_kuni");
$put = substr ( $value, $position+11 );
//echo $put."\r\n";
copy($put, "translate\\eng\\".$put);
}
//перебрали и скопировали


//удаляем пустые папки
RemoveEmptySubFolders("translate\\eng");
/*

// сканируем файлы в папке переводов
unset($all_files);
$all_files=array();
GetListFiles("translate\\eng",$all_files);
foreach ($all_files as  $value)
{
if (substr_count($value, ".zarc")!=0 AND substr_count($value, "1_eng_zarc") == 0) $zarc_path[] = str_replace("\\","\\\\",$value);

}


//распаковываем архивы
foreach ($zarc_path as  $value){
unpackZarc($value);
}


unset($all_files);
$all_files=array();
GetListFiles("translate\\eng",$all_files);
foreach ($all_files as  $value)
{
if (substr_count($value, "cfg.bin")!=0 AND substr_count($value, "1_eng_zarc") == 0) $text_path[] = str_replace("\\","\\\\",$value);
}

//print_r($text_path);
foreach ($text_path as  $value){
unpackText($value);
}
*/

?>