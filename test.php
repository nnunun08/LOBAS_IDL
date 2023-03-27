<?
	header("Content-Type: text/html; charset=UTF-8");

	include $_SERVER["DOCUMENT_ROOT"]."/jnc/PHPExcel-1.8/Classes/PHPExcel.php";
	
	$filePath = "/opt/apache/userdir/pws.withidl.com/lobas/data/";
	$file = "2021_0.xlsx";

	$UpFilePathInfo = pathinfo($filePath.$file);

	$UpFileExt = strtolower($UpFilePathInfo["extension"]);

	//-- 읽을 범위 필터 설정 (아래는 A열만 읽어오도록 설정함  => 속도를 중가시키기 위해)
	class MyReadFilter implements PHPExcel_Reader_IReadFilter {
		public function readCell($column, $row, $worksheetName = '') {
			// Read rows 1 to 7 and columns A to E only
			if (in_array($column,range('E','E'))) {
				return true;
			} else if (in_array($column,range('A','A'))) {
				return true;
			} else if (in_array($column,range('S','S'))) {
				return true;
			}

			return false;
		}
	}

	$filterSubset = new MyReadFilter();

	//파일 타입 설정 (확자자에 따른 구분)
	$inputFileType = 'Excel2007';
	
	if($UpFileExt == "xls") {
		$inputFileType = 'Excel5'; 
	}

	//엑셀리더 초기화
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);

	//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
	$objReader->setReadDataOnly(true); 
	
	//범위 지정(위에 작성한 범위필터 적용)
	$objReader->setReadFilter($filterSubset);
	
	//업로드된 엑셀파일을 서버의 지정된 곳에 옮기기 위해 경로 적절히 설정
	$upload_path = $filePath.$file;

	//업로드된 엑셀 파일 읽기
	$objPHPExcel = $objReader->load($upload_path);
	
	//첫번째 시트로 고정
	$objPHPExcel->setActiveSheetIndex(0);
	
	//고정된 시트 로드
	$objWorksheet = $objPHPExcel->getActiveSheet();
	
	 //시트의 지정된 범위 데이터를 모두 읽어 배열로 저장
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$maxRow = count($sheetData);
	
	echo $maxRow.":".$objWorksheet->getHighestRow()."<br>";
	
	exit;

	foreach($sheetData as $rows) {
		$fld1 = $rows[A]; //A열값을 가져온다.
		$fld2 = $rows[E]; //A열값을 가져온다.
		$fld3 = $rows[S]; //A열값을 가져온다.
		echo $fld1.":".$fld2.":".$fld3."<br>";
	}
?>