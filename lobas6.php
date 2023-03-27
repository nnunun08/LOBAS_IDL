<?
	if (empty($yy)) $yy = date("Y")-1;

	$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=6";
	$row = T_select($sql);

	$arr_fld = array();

	for ($i=0;$i<count($row);$i++) {
		for ($j=1;$j<=8;$j++) {
			$arr_fld[$row[$i][rnum]][$j] = trim($row[$i]["fld".$j]);
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
			
			$(this).val(addComma(val));
			
			if (event.keyCode==13) {
				$.get("lobasExe.php",{"yy" : yy,"row" : row,"col" : col,"kap" : val,"cate" : "6","fldsu" : "8"},function(json){
					if(json != null) {
						$(this).val(addComma(val));
						moveInput(row,col);
					} 
				});
			}

			compute(row,col);
		});

		/* 초기 계산 */
			for (let i=1;i<=8 ;i++) {
				$(".suip[col='"+i+"']").each(function(){
					if ($(this).val()!="") {
						row = $(this).attr("row");
						compute(row,i);
					}
				});
			}
	});
	
	function compute(row,col) {
		let kap = [];
		let pay = "";
		let tot = 0;
		let tmp = "";
		
		for (i=1;i<=8;i++) {
			kap[i] = 0;

			if (i!=4 && i!=8) {
				if ($("input[row='"+row+"'][col='"+i+"']").val()!="") {
					pay = $("input[row='"+row+"'][col='"+i+"']").val().replaceAll(",","");
					if (isNaN(pay)) pay = 0;
					kap[i] = parseInt(pay);
				}
			}
		}

		/* 가로계산 */
		kap[4] = kap[1]+kap[2]+kap[3];
		kap[8] = kap[4]-kap[6];

		$("input[row='"+row+"'][col='4']").val(addComma(kap[4]));
		$("input[row='"+row+"'][col='8']").val(addComma(kap[8]));
		
		/* 세로계산 */
		if (col=="1" || col=="2" || col=="3" || col=="6") {
			tmp = ("4,8,"+col).split(",");
		} else {
			tmp = String(col).split(",");	// 숫자를 형변환 해야함
		}
		
		for (i=0;i<tmp.length ;i++ ) {
			tot = 0;
			$("input[class='suip'][col='"+tmp[i]+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay;
			});

			$("input[class='hap'][col='"+tmp[i]+"']").val(addComma(tot));
		}
	}

	function moveInput(row,col) {
		let next = 0;
		
		if (col=="1" || col=="2" || col=="5" || col=="6") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").select();
		} else if (col=="3") {
			next = parseInt(col)+1;
			$("input[col='5'][row='"+row+"']").select();
		} else if (col=="7") {
			if (row=="1") {
				$("input[col='1'][row='2']").select();
			} else {
				$("input[col='1'][row='1']").select();
			}
		}
	}
 //-->
 </SCRIPT> 
</head>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form name="form1" method="post">
	<input type="hidden" name="sw" value="<?=$sw?>">
	<input type="hidden" name="flag" value="<?=$flag?>">
	<input type="hidden" name="company" value="<?=$flag?>">
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">이월재원(수입) 결산</span>
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
						1. 본 화면은 당기의 로바스 출력자료 이월예산 결산보고서 중 이월재원 수입결산 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  이월예산결산보고서-이월재원(수입)결산자료를 엑셀파일로 다운받은 후 해당 내용의 금액을 입력(복사/붙여넣기 )합니다.<br>
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
			<table width=1280 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="12%">
					<col width="11%">
					<col width="11%">
					<col width="11%">
					
					<col width="11%">
					<col width="11%">
					<col width="11%">
					<col width="11%">
					<col width="11%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center rowspan=3>구분<br>(과목)</td>
					<td align=center colspan=5>이월자금예산액</td>
					<td align=center rowspan=3>결 산 액<br>(B)</td>
					<td align=center rowspan=3>수 납 액<br>(C)</td>
					<td align=center rowspan=3>예산액대<br>결산액차이<br>(A-B)</td>
				</tr>

				<tr bgcolor="f6f6f6" height="30">
					<td align=center colspan=4>이월재원충당금</td>
					<td align=center rowspan=2>미지급 이월액</td>
				</tr>


				<tr bgcolor="f6f6f6" height="30">
					<td align=center>사고 이월액</td>
					<td align=center>건설개량 이월액</td>
					<td align=center>계속비 이월액</td>
					<td align=center>합   계 (A)</td>

				</tr>
			
				<tr bgcolor="FFFFFF" height="30">
					<td class="pd">합계 (ㄱ+ㄴ)</td>
					<td align=center><input type="text" class="hap" col="1" none></td>
					<td align=center><input type="text" class="hap" col="2" none></td>
					<td align=center><input type="text" class="hap" col="3" none></td>
					<td align=center><input type="text" class="hap" col="4" none></td>
					<td align=center><input type="text" class="hap" col="5" none></td>
					<td align=center><input type="text" class="hap" col="6" none></td>
					<td align=center><input type="text" class="hap" col="7" none></td>
					<td align=center><input type="text" class="hap" col="8" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd">이월재원충당액(ㄱ)</td>
					<td align=center><input type="text" class="suip" row=1 col="1" value="<?=lobas_format($arr_fld[1][1])?>"></td>
					<td align=center><input type="text" class="suip" row=1 col="2" value="<?=lobas_format($arr_fld[1][2])?>"></td>
					<td align=center><input type="text" class="suip" row=1 col="3" value="<?=lobas_format($arr_fld[1][3])?>"></td>
					<td align=center><input type="text" class="suip" row=1 col="4" value="<?=lobas_format($arr_fld[1][4])?>" none></td>
					<td align=center><input type="text" class="suip" row=1 col="5" value="<?=lobas_format($arr_fld[1][5])?>"></td>
					<td align=center><input type="text" class="suip" row=1 col="6" value="<?=lobas_format($arr_fld[1][6])?>"></td>
					<td align=center><input type="text" class="suip" row=1 col="7" value="<?=lobas_format($arr_fld[1][7])?>"></td>
					<td align=center><input type="text" class="suip" row=1 col="8" value="<?=lobas_format($arr_fld[1][8])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd">미지급이월액(ㄴ)</td>
					<td align=center><input type="text" class="suip" row=2 col="1" value="<?=lobas_format($arr_fld[2][1])?>"></td>
					<td align=center><input type="text" class="suip" row=2 col="2" value="<?=lobas_format($arr_fld[2][2])?>"></td>
					<td align=center><input type="text" class="suip" row=2 col="3" value="<?=lobas_format($arr_fld[2][3])?>"></td>
					<td align=center><input type="text" class="suip" row=2 col="4" value="<?=lobas_format($arr_fld[2][4])?>" none></td>
					<td align=center><input type="text" class="suip" row=2 col="5" value="<?=lobas_format($arr_fld[2][5])?>"></td>
					<td align=center><input type="text" class="suip" row=2 col="6" value="<?=lobas_format($arr_fld[2][6])?>"></td>
					<td align=center><input type="text" class="suip" row=2 col="7" value="<?=lobas_format($arr_fld[2][7])?>"></td>
					<td align=center><input type="text" class="suip" row=2 col="8" value="<?=lobas_format($arr_fld[2][8])?>" none></td>
				</tr>
			</table>
		</td>
	</tr>
</table>