<?
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/PHPExcel-1.8/Classes/PHPExcel.php";
	
	/* 첫해입력 */
	if (empty($cyy)) {
		$sql = "select count(*) from admin_lobas9_year where company=$adm_com";
		$cnt = T_read($sql);
		
		$cyy = date("Y")-1;
	
		if ($cnt=="0") {
			$sql = "insert into admin_lobas9_year (company,iyy,cyy,myy) values ($adm_com,'$cyy','$cyy','$cyy')";
			T_insert($sql);
			
			$myy = $cyy;
		} else {
			$myy = T_read("select myy from admin_lobas9_year where company=$adm_com");
			
			if ($cyy>$myy) {
				$sql = "update admin_lobas9_year set myy='$cyy',cyy='$cyy' where company=$adm_com";
				T_update($sql);
			
				$myy = $cyy;
			} else {
				$cyy = $myy;
			}	
		}
	} else {
		$myy = T_read("select myy from admin_lobas9_year where company=$adm_com");
	
		if ($cyy>$myy) {
			$sql = "update admin_lobas9_year set myy='$cyy' where company=$adm_com";
			T_update($sql);
			$myy = $cyy;
		}	
	}
	
	$iyy = T_read("select iyy from admin_lobas9_year where company=$adm_com");
	
	$filePath = "/opt/apache/userdir/pws.withidl.com/lobas/data/";

	$sql = "select * from admin_lobas9_file where yymm='$cyy' and company=$adm_com";
	$sub = T_select($sql);
	
	$kifile = getFileChk($filePath,$sub[0][file]);
	
	if ($kifile!="") {
		$kifile = $sub[0][ofile];
	
		$sql = "select sum(fld3) from admin_lobas9 where yymm='$cyy' and company=$adm_com";
		$fld3 = T_read($sql);
	}
?>
<head>
 <style>
	#btn {
		width: 100px;
		cursor: pointer;
		border-radius: 5px;
		transition: transform 300ms ease;
	}

	#btn:hover {
		transform: scale(1.1);
	}
	
	#iyy {
		position: relative;

	}

	#nextYY, #initYY {
		height: 23px;
	}
 </style>
 <SCRIPT LANGUAGE="JavaScript">
 <!--
	let myy = "<?=$myy?>";

	function getExt(filename) {     
		var _fileLen = filename.length;    
		var _lastDot = filename.lastIndexOf('.');
		var _fileExt = filename.substring(_lastDot, _fileLen).toLowerCase();     
		return _fileExt;
	}

	$(function(){
		$("#btn").click(function(){
			let file = $("input[name='file']").val();
			let tmp = file.split(".");
			
			let cyy = $("input[name='cyy']").val();
			
			if (cyy!=myy) {
				alert("당기년도만 업로드가 가능합니다");
				return;
			}

			if (file == "") {
				alert("자금지출기록부 엑셀파일을 선택하요 주세요");
				return;
			}

			if (getExt(file)==".xlsx" || getExt(file)==".xls") {
			} else {
				alert("파일은 엑셀 파일만 됩니다.");
				return;
			}
			
			let result = confirm("업로드 하시겠습니까?");
			
			if (result==true) {
				form1.action = "lobas9_act.php";
				form1.submit();
			}
		});

		$("select[name='iyy']").change(function(){
			let cyy = $(this).val();

			$("input[name='cyy']").val(cyy);

			form1.action = "index.php";
			form1.submit();
		});

		/* 다음년도 */
		$("#nextYY").click(function(){
			let cyy = parseInt($("input[name='cyy']").val())+1;
			$("input[name='cyy']").val(cyy);
			
			form1.action = "index.php";
			form1.submit();
		});

		/* 초기화 */
		$("#initYY").click(function(){
			let result = confirm("초기화 하시겠습니까?");

			if (result==true) {
				form1.action = "lobas9_init.php";
				form1.submit();
			}
		});
	});	
 //-->
 </SCRIPT>
</head>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form name="form1" method="post" enctype="multipart/form-data">
	<input type="hidden" name="sw" value="<?=$sw?>">
	<input type="hidden" name="cyy" value="<?=$cyy?>">
	<input type="hidden" name="flag" value="<?=$flag?>">
	<input type="hidden" name="mseq" value="<?=$mseq?>">
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">자금지출기록부 업로드</span>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="titLine" height="1" bgcolor="#E7E7E7"></td>
	</tr>
	<tr>
		<td>
			<table class="writeRule" cellpadding="1" cellspacing="1" border="0">
				<tr height="32" bgcolor="#FFFFFF">
					<td>
						<div class="togleBtn" onclick="writeRuleToggle()">
							<span>작성요령</span>
							<i></i>
						</div>
						<script src="../js/writeRule.js"></script>
						1. 본 화면은 당기의 로바스 출력자료 중 자금지출기록부의 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  자금지출기록부를 엑셀파일로 다운받은 후 엑셀파일을 업로드 합니다<br>
						3. 입력은 원단위입니다. 
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="padding-bottom:10px;">
				<span>회계년도</span> 
				<td class="filterYear">
					<select name="iyy" style="height:23px;">
					<? for ($i=$iyy;$i<=$myy;$i++) { ?>
						<option value="<?=$i?>" <? if ($cyy==$i) echo "selected"; ?> ><?=$i?></option>
					<? } ?>
					</select>
				</span>
			<? if ($adm_id=="admin") { ?>
				<span><button type="button" id="nextYY">다음년도</button></span>
				<span><button type="button" id="initYY">초기화</button></span>
				<span>* 관리자만 보이는 메뉴 입니다.</span>
			<? } ?>
			</div>
			<table width=1000 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="20%">
					<col width="40%">
					<col width="15%">
					<col width="25%">
				</colgroup>
				
				<tr bgcolor="FFFFFF" height="45">
					<td align=center>자금지출기록부</td>
					<td align=center style="padding-top:3px;">
						<input type="file" name="file" style="width:250px;">
					<? if ($kifile) { ?>
						&nbsp;<?=$kifile?>
					<? } ?>
					</td>
					<td align=center>
						<input id="btn" type="button" value="업로드">
					</td>
					<td align=center>
						<span>결과</span>
						<? 
							if ($fld3) echo ": ".number_format($fld3); 
						?>
					</td>
				</tr>	
			</table>
		</td>
	</tr>
	</form>
</table>