<?
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/lib.php";
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/etc.php";
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/PHPExcel-1.8/Classes/PHPExcel.php";
	
	ini_set('memory_limit','-1');

	if (!$connect) $connect=dbConn();

	$sql = "select count(*) from admin_lobas9_file where company=$adm_com and yymm='$cyy'";
	$cnt = T_read($sql);

	if ($cnt==0) {
		$fld = "yymm,company,regid,regdt";
		$val = "'$cyy',$adm_com,'$adm_id','".userdate()."'";

		if ($_FILES["file"][name]) {
			${"filesize"} = $_FILES["file"][size];
			$ext = split("\.",$_FILES["file"][name]);
			
			if (${"filesize"}>0) {
				${"newfname"} = $cyy."_".$adm_com.".".$ext[count($ext)-1];

				$fld .= ",file,ofile";
				$val .= ",'".${"newfname"}."','".$_FILES["file"][name]."'";

				$saveFile = "/opt/apache/userdir/pws.withidl.com/lobas/data/".${"newfname"};
			
				move_uploaded_file($_FILES["file"][tmp_name],$saveFile);
				
				$sql = "insert into admin_lobas9_file ($fld) values ($val)";
			}
		}
	} else {
		if ($_FILES["file"][name]) {

			${"filesize"} = $_FILES["file"][size];
			$ext = split("\.",$_FILES["file"][name]);
			
			if (${"filesize"}>0) {
				${"newfname"} = $cyy."_".$adm_com.".".$ext[count($ext)-1];
				
				$saveFile = "/opt/apache/userdir/pws.withidl.com/lobas/data/".${"newfname"};
				move_uploaded_file($_FILES["file"][tmp_name],$saveFile);

				$val = "file='".${"newfname"}."',ofile='".$_FILES["file"][name]."'";
				
				$sql = "update admin_lobas9_file set $val where yymm='$cyy' and company=$adm_com";
			}
		}
	}
	
	if (${"filesize"}>0) {
		$file = ${"newfname"};
		$filePath = "/opt/apache/userdir/pws.withidl.com/lobas/data/";

		$UpFilePathInfo = pathinfo($filePath.$file);
		$UpFileExt = strtolower($UpFilePathInfo["extension"]);

		//-- 읽을 범위 필터 설정 (아래는 A열만 읽어오도록 설정함  => 속도를 중가시키기 위해)
		class MyReadFilter implements PHPExcel_Reader_IReadFilter {
			public function readCell($column, $row, $worksheetName = '') {
				// Read rows 1 to 7 and columns A to E only
				if (in_array($column,range('D','F'))) {
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

		$num = 0;
		$err = "0";

		foreach($sheetData as $rows) {
			$num += 1;

			$fld1 = preg_replace("/[^0-9]*/s", "", trim($rows[A]));	//일자
			$fld2 = trim($rows[D]); //세항코드
			$fld3 = trim($rows[E]); //목
			$fld4 = nullOfZero(trim($rows[S])); //금액

			if ($num==2) {
				$eyy = substr($fld1,0,4);
				
				if ($cyy!=$eyy) {
					$err = "1";
					echo ${"newfname"};

					$file = ${"newfname"};
                    $filename = $filePath.$file;
					delFile($filename);	

					break;
				} else {
					if ($cnt==0) {
						// 인서트
						T_insert($sql);
					} else {
						// 업데이트
						T_update($sql);
					}
					
					$sql = "delete from admin_lobas9 where company=$adm_com and yymm='$cyy'";
					T_delete($sql);
				}
			} 

			if (strlen($fld1)==8) {
				$fld = "yymm,company,fld1,fld2,fld3,fld4";
				$val = "'$cyy',$adm_com,'$fld1','$fld2','$fld3',$fld4";

				$sql = "insert into admin_lobas9 ($fld) values ($val)";
				T_insert($sql);
			}
		}
	}

	if ($connect) @mysql_close($connect);
	unset($connect);
?>

<form name="form1" method="post">
<input type="hidden" name="sw" value="<?=$sw?>">
<input type="hidden" name="cyy" value="<?=$cyy?>">
<input type="hidden" name="flag" value="<?=$flag?>">
<input type="hidden" name="mseq" value="<?=$mseq?>">
</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
	let err = "<?=$err?>";
	
	if (err=="0") {
		alert("처리되었습니다");
	} else {
		alert("엑셀 파일의 회계년도를 확인해 주세요");
	}

	form1.action = "index.php";
	form1.submit();
//-->
</SCRIPT>
