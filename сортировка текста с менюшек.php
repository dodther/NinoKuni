<?

$������_�_���� = file("�����_�_�������.txt");
foreach($������_�_���� as $��������)
{
if(substr_count($��������, "txt")) {
	fclose($����);
	$���� = fopen(str_replace("_en", "_fr",trim($��������)),"w");

	}else
	{
	echo $��������;
	fwrite($���� , $��������);
	
	
	}

}


?>