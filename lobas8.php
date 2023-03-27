<?
	if (empty($yy)) $yy = date("Y")-1;
	
	$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=8";
	$row = T_select($sql);

	$arr_fld = array();

	for ($i=0;$i<count($row);$i++) {
		for ($j=1;$j<=5;$j++) {
			$arr_fld[$row[$i][rnum]][$j] = $row[$i]["fld".$j];
		}
	}
?>

<head>
 <style>
	.pd {
		padding-left:5px;
	}

	input {
		text-align: center;
	}
 </style>
 <SCRIPT LANGUAGE="JavaScript">
 <!--
	$(function(){
		$("input").attr("size","19");

		$("input:text[none]").attr("disabled",true);
		$("input:text[none]").css("border","none");
		$("input:text[none]").css("background","white");
		
		$("input").on('focus',function(){
			$(this).select();
		});

		$("input[row='1'][col='1']").select();

		$("input").on("keyup", function() {
			let row = $(this).attr("row");
			let col = $(this).attr("col");
			
			let val = $(this).val().replace(/[^0-9]/g,"");
			
			let yy = $("select[name='yy']").val();

			if (event.keyCode==13) {
				$.get("lobasExe.php",{"yy" : yy,"row" : row,"col" : col,"kap" : val,"cate" : "8","fldsu" : "4"},function(json){
					if(json != null) {
						$(this).val(addComma(val));
						moveInput(row,col);
					} 
				});
			}
		});

		$("select[name='yy']").change(function(){
			form1.action = "/lobas/";
			form1.submit();
		});
	});

	function moveInput(row,col) {
		let next = 0;
		
		next = parseInt(col)+1;
		
		if (next=="6") {
			$("input[col='1']").focus();
		} else {
			$("input[col='"+next+"']").focus();
		}
	}
 //-->
 </SCRIPT> 
</head>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form name="form1" method="post">
	<input type="hidden" name="sw" value="<?=$sw?>">
	<input type="hidden" name="flag" value="<?=$flag?>">
	<input type="hidden" name="mseq" value="<?=$mseq?>">
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">수입지출외 현금 현재액 조서</span>
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
						1. 본 화면은 당기의 로바스 출력자료 중 수입지출외 현금 현재액 조서의 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  수입지출외 현금 현재액 조서를 엑셀파일로 다운받은 후 합계 금액만을 입력(복사/붙여넣기 )합니다.<br>
						3. 금액이 없는 계정과목은 입력하지 않고 비워둡니다.<br>
						4. 입력은 원단위입니다. 
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<div style="padding-bottom:10px;">
				<span>회계년도</span> 
				<span>
					<select name="yy">
					<? for ($i=date("Y")-2;$i<=(date("Y")+3);$i++) { ?>
						<option value="<?=$i?>" <? if ($i==$yy) echo "selected"; ?> ><?=$i?>년</option>
					<? } ?>
					</select>
				</span>
			</div>
			<table width=800 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center>관리종목</td>
					<td align=center>전기이월액</td>
					<td align=center>당기수입액</td>
					<td align=center>당기지출액</td>
					<td align=center>기말잔액</td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center>합계</td>
					<td align=center><input type="text" row=1 col=1 value="<?=lobas_format($arr_fld[1][1])?>"></td>
					<td align=center><input type="text" row=1 col=2 value="<?=lobas_format($arr_fld[1][2])?>"></td>
					<td align=center><input type="text" row=1 col=3 value="<?=lobas_format($arr_fld[1][3])?>"></td>
					<td align=center><input type="text" row=1 col=4 value="<?=lobas_format($arr_fld[1][4])?>"></td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>