<?

$список_файлов_не_сорт = scandir ($argv['1']);
$список_файлов = array_slice($список_файлов_не_сорт, 4); 

foreach($список_файлов AS $значение)
{
if(substr($значение, 0, 2) == "ev") 
	{
	$имя_папки = substr($значение, 0, 7);
	if(is_dir ($имя_папки ))break;
	@mkdir($имя_папки);
	copy($значение,$имя_папки."\\".$значение);
	unlink($значение);
	}
	
if(substr($значение, 0, 3) == "pla") 
		{
		
		$адрес_конца_имени_папки = strpos($значение, "_npc" );
		$имя_папки = substr($значение, 4, $адрес_конца_имени_папки-4);
		//if(is_dir ($имя_папки ))break;
		@mkdir($имя_папки);
		copy($значение,$имя_папки."\\".$значение);
	    unlink($значение);
		
		
		}
	
	if(strpos($значение,"base") )
		{
		
		$адрес_конца_имени_папки = strpos($значение, "_base" );
		$имя_папки = substr($значение, 0, $адрес_конца_имени_папки);
		//if(is_dir ($имя_папки ))break;
		@mkdir($имя_папки);
		copy($значение,$имя_папки."\\".$значение);
	    unlink($значение);
		
		
		}



}













?>