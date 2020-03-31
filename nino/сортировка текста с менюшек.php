<?

$тексты_с_меню = file("Текст_с_менюшек.txt");
foreach($тексты_с_меню as $значение)
{
if(substr_count($значение, "txt")) {
	fclose($файл);
	$файл = fopen(str_replace("_en", "_fr",trim($значение)),"w");

	}else
	{
	echo $значение;
	fwrite($файл , $значение);
	
	
	}

}


?>