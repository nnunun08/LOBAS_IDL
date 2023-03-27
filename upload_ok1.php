<?
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/lib.php";
	require_once './Excel/reader.php';
	
	if (!$connect) $connect=dbConn();
	
	//echo $_FILES["lobas1"][name];

	if ($_FILES['lobas1']['size'] > 0) {
		$saveFile = "/opt/apache/userdir/pws.withidl.com/lobas/data/lobas1.xls";
		move_uploaded_file($_FILES["lobas1"][tmp_name],$saveFile);

		$fpath = $saveFile;

		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF8');
		$data->read($fpath);

		for ($k=0;$k<=0;$k++) {
			$rownm = $data->sheets[$k]['numRows'];

			for($j=5;$j<=10;$j++){
				$flds[$j] = $data->sheets[$k]['cells'][$j];//한 row를 읽어들인다.
				
				echo $j."=";

				for ($i=1;$i<=count($flds[$j]);$i++) {
					echo $flds[$j][$i];
				}

				echo "<br>";
			}
		}
	}	

	if ($connect) @mysql_close($connect);
	unset($connect);

	// /opt/apache/userdir/pws.withidl.com/lobas/data
?>