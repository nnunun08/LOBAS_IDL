<?
	if (empty($yy)) $yy = date("Y")-1;
	
	$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=7";
	$row = T_select($sql);

	$arr_fld = array();

	for ($i=0;$i<count($row);$i++) {
		for ($j=1;$j<=5;$j++) {
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
		let col = 0;
		let row = 0;

		$("input").attr("size","19");

		$("input:text[none]").attr("disabled",true);
		$("input:text[none]").css("border","none");
		$("input:text[none]").css("background","white");
		
		$("input").on('focus',function(){
			$(this).select();
		});

		$("input[row='1'][col='1']").select();

		$("input").on("keyup", function() {
			row = $(this).attr("row");
			col = $(this).attr("col");
			
			let val = $(this).val().replace(/[^0-9]/g,"");
			
			let yy = $("select[name='yy']").val();
			
			$(this).val(addComma(val));
			
			if (event.keyCode==13) {
				$.get("lobasExe.php",{"yy" : yy,"row" : row,"col" : col,"kap" : val,"cate" : "7","fldsu" : "5"},function(json){
					if(json != null) {
						$(this).val(addComma(val));
						moveInput(row,col);
					} 
				});
			}

			compute(row,col);
		});

		$("select[name='yy']").change(function(){
			form1.action = "/lobas/";
			form1.submit();
		});

		/* 초기 계산 */
		for (i=1;i<=5 ;i++) {
			$("input[col='"+i+"']").each(function(){
				if ($(this).val()!="") {
					col = i;
					row = $(this).attr("row");
					
					compute(row,col);
				}
			});
		}
	});

	function compute(row,col) {
		let pay = 0;
		let tot = 0;
		let parent = 0;
		let selector = "input[row='"+row+"'][col='"+col+"']";
		let item = $(selector).attr("item");

		let step = $(selector).attr("step");
		
		$("input[item='"+item+"'][col='"+col+"']").each(function(){
			pay = parseInt($(this).val().replaceAll(",",""));
			if (isNaN(pay)) pay = 0;
			tot += pay; 
		});

		$("."+item+"[col='"+col+"']").val(addComma(tot));
		parent = $("."+item+"[col='"+col+"']").attr("parent");
		
		if (step=="3") {
			item = $("."+item+"[col='"+col+"']").attr("item");
			
			tot = 0;
			$("input[item='"+item+"'][col='"+col+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay; 
			});
			
			$("."+item+"[col='"+col+"']").val(addComma(tot));

			parent = $("."+item+"[col='"+col+"']").attr("parent");

			tot = 0;
			$("input[parent='"+parent+"'][col='"+col+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay; 
			});

			$("."+parent+"[col='"+col+"']").val(addComma(tot));
		} else {
			//step1 계산
			tot = 0;
			$("input[parent='"+parent+"'][col='"+col+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay; 
			});

			$("."+parent+"[col='"+col+"']").val(addComma(tot));
		}
		
		// 총합더하기
		tot = 0;
		$("input[step='1'][col='"+col+"']").each(function(){
			pay = parseInt($(this).val().replaceAll(",",""));
			if (isNaN(pay)) pay = 0;
			tot += pay; 
		});
		
		$(".hap[col='"+col+"']").text(addComma(tot));	
	}

	function moveInput(row,col) {
		let next = 0;
		
		if (col<"5") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").focus();
		} else if (col=="5") {
			next = parseInt(row)+1;
			$("input[col='1'][row='"+next+"']").focus();

			const toBeLoc = document.querySelector(`input[col='1'][row="${next}"]`);
			toBeLoc.scrollIntoView({ block: 'center' });
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
			<span id="jTit">이월재원(지출) 결산</span>
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
						1. 본 화면은 당기의 로바스 출력자료 이월예산 결산보고서 중 이월재원(지출)결산의 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  이월예산 결산보고서 중 이월재원(지출)결산 자료를 엑셀파일로 다운받은 후 해당 계정과목의 금액을 항-세항-목의 구분별로 입력(복사/붙여넣기 )합니다.<br>
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
			<table width=1100 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="7%">
					<col width="7%">
					<col width="12%">
					
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center colspan=3>구분(과목)</td>
					<td align=center colspan=2>결 산 액</td>
					<td align=center rowspan=2>지 출 액<br>(E)</td>
					<td align=center rowspan=2>미지급금<br>(B+C-E)</td>
					<td align=center rowspan=2>익  년  도이  월  액<br>(F)</td>
				</tr>

				<tr bgcolor="f6f6f6" height="30">
					<td align=center>항</td>
					<td align=center>세항</td>
					<td align=center>목</td>
					<td align=center>채무확정액(C)</td>
					<td align=center>지급결정액(D)</td>
				</tr>

				<tr bgcolor="f6f6f6" height="30">
					<td class="pd" colspan=3>합   계( ㄱ + ㄴ + ㄷ )</td>
					<td align=center class="hap" col=1></td>
					<td align=center class="hap" col=2></td>
					<td align=center class="hap" col=3></td>
					<td align=center class="hap" col=4></td>
					<td align=center class="hap" col=5></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>수익적지출 계(ㄱ)</td>

					<td align=center><input type="text" step=1 class="100" col=1 none></td>
					<td align=center><input type="text" step=1 class="100" col=2 none></td>
					<td align=center><input type="text" step=1 class="100" col=3 none></td>
					<td align=center><input type="text" step=1 class="100" col=4 none></td>
					<td align=center><input type="text" step=1 class="100" col=5 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>영업비용</td>

					<td align=center><input type="text" parent=100 class=110 col=1 none></td>
					<td align=center><input type="text" parent=100 class=110 col=2 none></td>
					<td align=center><input type="text" parent=100 class=110 col=3 none></td>
					<td align=center><input type="text" parent=100 class=110 col=4 none></td>
					<td align=center><input type="text" parent=100 class=110 col=5 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>원수및취수비</td>

					<td align=center><input type="text" item=110 row=1 col=1 value="<?=lobas_format($arr_fld[1][1])?>"></td>
					<td align=center><input type="text" item=110 row=1 col=2 value="<?=lobas_format($arr_fld[1][2])?>"></td>
					<td align=center><input type="text" item=110 row=1 col=3 value="<?=lobas_format($arr_fld[1][3])?>"></td>
					<td align=center><input type="text" item=110 row=1 col=4 value="<?=lobas_format($arr_fld[1][4])?>"></td>
					<td align=center><input type="text" item=110 row=1 col=5 value="<?=lobas_format($arr_fld[1][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>정수비</td>

					<td align=center><input type="text" item=110 row=2 col=1 value="<?=lobas_format($arr_fld[2][1])?>"></td>
					<td align=center><input type="text" item=110 row=2 col=2 value="<?=lobas_format($arr_fld[2][2])?>"></td>
					<td align=center><input type="text" item=110 row=2 col=3 value="<?=lobas_format($arr_fld[2][3])?>"></td>
					<td align=center><input type="text" item=110 row=2 col=4 value="<?=lobas_format($arr_fld[2][4])?>"></td>
					<td align=center><input type="text" item=110 row=2 col=5 value="<?=lobas_format($arr_fld[2][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>배·급수비</td>

					<td align=center><input type="text" item=110 row=3 col=1 value="<?=lobas_format($arr_fld[3][1])?>"></td>
					<td align=center><input type="text" item=110 row=3 col=2 value="<?=lobas_format($arr_fld[3][2])?>"></td>
					<td align=center><input type="text" item=110 row=3 col=3 value="<?=lobas_format($arr_fld[3][3])?>"></td>
					<td align=center><input type="text" item=110 row=3 col=4 value="<?=lobas_format($arr_fld[3][4])?>"></td>
					<td align=center><input type="text" item=110 row=3 col=5 value="<?=lobas_format($arr_fld[3][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>관거비</td>

					<td align=center><input type="text" item=110 row=4 col=1 value="<?=lobas_format($arr_fld[4][1])?>"></td>
					<td align=center><input type="text" item=110 row=4 col=2 value="<?=lobas_format($arr_fld[4][2])?>"></td>
					<td align=center><input type="text" item=110 row=4 col=3 value="<?=lobas_format($arr_fld[4][3])?>"></td>
					<td align=center><input type="text" item=110 row=4 col=4 value="<?=lobas_format($arr_fld[4][4])?>"></td>
					<td align=center><input type="text" item=110 row=4 col=5 value="<?=lobas_format($arr_fld[4][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>펌프장비</td>

					<td align=center><input type="text" item=110 row=5 col=1 value="<?=lobas_format($arr_fld[5][1])?>"></td>
					<td align=center><input type="text" item=110 row=5 col=2 value="<?=lobas_format($arr_fld[5][2])?>"></td>
					<td align=center><input type="text" item=110 row=5 col=3 value="<?=lobas_format($arr_fld[5][3])?>"></td>
					<td align=center><input type="text" item=110 row=5 col=4 value="<?=lobas_format($arr_fld[5][4])?>"></td>
					<td align=center><input type="text" item=110 row=5 col=5 value="<?=lobas_format($arr_fld[5][5])?>"></td>
				</tr>																						  
				<tr bgcolor="FFFFFF" height="30">															  
					<td align=center></td>																	  
					<td class="pd" colspan=2>처리장비</td>													  
																											  
					<td align=center><input type="text" item=110 row=6 col=1 value="<?=lobas_format($arr_fld[6][1])?>"></td>
					<td align=center><input type="text" item=110 row=6 col=2 value="<?=lobas_format($arr_fld[6][2])?>"></td>
					<td align=center><input type="text" item=110 row=6 col=3 value="<?=lobas_format($arr_fld[6][3])?>"></td>
					<td align=center><input type="text" item=110 row=6 col=4 value="<?=lobas_format($arr_fld[6][4])?>"></td>
					<td align=center><input type="text" item=110 row=6 col=5 value="<?=lobas_format($arr_fld[6][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>급·배수공사비</td>

					<td align=center><input type="text" item=110 row=7 col=1 value="<?=lobas_format($arr_fld[7][1])?>"></td>
					<td align=center><input type="text" item=110 row=7 col=2 value="<?=lobas_format($arr_fld[7][2])?>"></td>
					<td align=center><input type="text" item=110 row=7 col=3 value="<?=lobas_format($arr_fld[7][3])?>"></td>
					<td align=center><input type="text" item=110 row=7 col=4 value="<?=lobas_format($arr_fld[7][4])?>"></td>
					<td align=center><input type="text" item=110 row=7 col=5 value="<?=lobas_format($arr_fld[7][5])?>"></td>
				</tr>																						  
				<tr bgcolor="FFFFFF" height="30">															  
					<td align=center></td>																	  
					<td class="pd" colspan=2>일반관리비</td>												  
																											  
					<td align=center><input type="text" item=110 row=8 col=1 value="<?=lobas_format($arr_fld[8][1])?>"></td>
					<td align=center><input type="text" item=110 row=8 col=2 value="<?=lobas_format($arr_fld[8][2])?>"></td>
					<td align=center><input type="text" item=110 row=8 col=3 value="<?=lobas_format($arr_fld[8][3])?>"></td>
					<td align=center><input type="text" item=110 row=8 col=4 value="<?=lobas_format($arr_fld[8][4])?>"></td>
					<td align=center><input type="text" item=110 row=8 col=5 value="<?=lobas_format($arr_fld[8][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>징수및수용가관리비</td>

					<td align=center><input type="text" item=110 row=9 col=1 value="<?=lobas_format($arr_fld[9][1])?>"></td>
					<td align=center><input type="text" item=110 row=9 col=2 value="<?=lobas_format($arr_fld[9][2])?>"></td>
					<td align=center><input type="text" item=110 row=9 col=3 value="<?=lobas_format($arr_fld[9][3])?>"></td>
					<td align=center><input type="text" item=110 row=9 col=4 value="<?=lobas_format($arr_fld[9][4])?>"></td>
					<td align=center><input type="text" item=110 row=9 col=5 value="<?=lobas_format($arr_fld[9][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>영업외비용</td>

					<td align=center><input type="text" parent=100 class=120 col=1 none></td>
					<td align=center><input type="text" parent=100 class=120 col=2 none></td>
					<td align=center><input type="text" parent=100 class=120 col=3 none></td>
					<td align=center><input type="text" parent=100 class=120 col=4 none></td>
					<td align=center><input type="text" parent=100 class=120 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>이자비용</td>

					<td align=center><input type="text" item=120 row=10 col=1 value="<?=lobas_format($arr_fld[10][1])?>"></td>
					<td align=center><input type="text" item=120 row=10 col=2 value="<?=lobas_format($arr_fld[10][2])?>"></td>
					<td align=center><input type="text" item=120 row=10 col=3 value="<?=lobas_format($arr_fld[10][3])?>"></td>
					<td align=center><input type="text" item=120 row=10 col=4 value="<?=lobas_format($arr_fld[10][4])?>"></td>
					<td align=center><input type="text" item=120 row=10 col=5 value="<?=lobas_format($arr_fld[10][5])?>"></td>
				</tr>																						    
																											    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>기타영업외비용</td>											    
																											    
					<td align=center><input type="text" item=120 row=11 col=1 value="<?=lobas_format($arr_fld[11][1])?>"></td>
					<td align=center><input type="text" item=120 row=11 col=2 value="<?=lobas_format($arr_fld[11][2])?>"></td>
					<td align=center><input type="text" item=120 row=11 col=3 value="<?=lobas_format($arr_fld[11][3])?>"></td>
					<td align=center><input type="text" item=120 row=11 col=4 value="<?=lobas_format($arr_fld[11][4])?>"></td>
					<td align=center><input type="text" item=120 row=11 col=5 value="<?=lobas_format($arr_fld[11][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>자본적지출 계(ㄴ)</td>

					<td align=center><input type="text" step=1 class="200" col=1 none></td>
					<td align=center><input type="text" step=1 class="200" col=2 none></td>
					<td align=center><input type="text" step=1 class="200" col=3 none></td>
					<td align=center><input type="text" step=1 class="200" col=4 none></td>
					<td align=center><input type="text" step=1 class="200" col=5 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>재고자산취득</td>

					<td align=center><input type="text" parent=200 class="205" col=1 none></td>
					<td align=center><input type="text" parent=200 class="205" col=2 none></td>
					<td align=center><input type="text" parent=200 class="205" col=3 none></td>
					<td align=center><input type="text" parent=200 class="205" col=4 none></td>
					<td align=center><input type="text" parent=200 class="205" col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>용지조성사업비</td>

					<td align=center><input type="text" item=205 row=12 col=1 value="<?=lobas_format($arr_fld[12][1])?>"></td>
					<td align=center><input type="text" item=205 row=12 col=2 value="<?=lobas_format($arr_fld[12][2])?>"></td>
					<td align=center><input type="text" item=205 row=12 col=3 value="<?=lobas_format($arr_fld[12][3])?>"></td>
					<td align=center><input type="text" item=205 row=12 col=4 value="<?=lobas_format($arr_fld[12][4])?>"></td>
					<td align=center><input type="text" item=205 row=12 col=5 value="<?=lobas_format($arr_fld[12][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>미완성토지</td>												    
																											    
					<td align=center><input type="text" item=205 row=13 col=1 value="<?=lobas_format($arr_fld[13][1])?>"></td>
					<td align=center><input type="text" item=205 row=13 col=2 value="<?=lobas_format($arr_fld[13][2])?>"></td>
					<td align=center><input type="text" item=205 row=13 col=3 value="<?=lobas_format($arr_fld[13][3])?>"></td>
					<td align=center><input type="text" item=205 row=13 col=4 value="<?=lobas_format($arr_fld[13][4])?>"></td>
					<td align=center><input type="text" item=205 row=13 col=5 value="<?=lobas_format($arr_fld[13][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>분양주택건설비</td>

					<td align=center><input type="text" item=205 row=14 col=1 value="<?=lobas_format($arr_fld[14][1])?>"></td>
					<td align=center><input type="text" item=205 row=14 col=2 value="<?=lobas_format($arr_fld[14][2])?>"></td>
					<td align=center><input type="text" item=205 row=14 col=3 value="<?=lobas_format($arr_fld[14][3])?>"></td>
					<td align=center><input type="text" item=205 row=14 col=4 value="<?=lobas_format($arr_fld[14][4])?>"></td>
					<td align=center><input type="text" item=205 row=14 col=5 value="<?=lobas_format($arr_fld[14][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>미완성주택</td>												    
																											    
					<td align=center><input type="text" item=205 row=15 col=1 value="<?=lobas_format($arr_fld[15][1])?>"></td>
					<td align=center><input type="text" item=205 row=15 col=2 value="<?=lobas_format($arr_fld[15][2])?>"></td>
					<td align=center><input type="text" item=205 row=15 col=3 value="<?=lobas_format($arr_fld[15][3])?>"></td>
					<td align=center><input type="text" item=205 row=15 col=4 value="<?=lobas_format($arr_fld[15][4])?>"></td>
					<td align=center><input type="text" item=205 row=15 col=5 value="<?=lobas_format($arr_fld[15][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>저장품</td>

					<td align=center><input type="text" item=205 row=16 col=1 value="<?=lobas_format($arr_fld[16][1])?>"></td>
					<td align=center><input type="text" item=205 row=16 col=2 value="<?=lobas_format($arr_fld[16][2])?>"></td>
					<td align=center><input type="text" item=205 row=16 col=3 value="<?=lobas_format($arr_fld[16][3])?>"></td>
					<td align=center><input type="text" item=205 row=16 col=4 value="<?=lobas_format($arr_fld[16][4])?>"></td>
					<td align=center><input type="text" item=205 row=16 col=5 value="<?=lobas_format($arr_fld[16][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>재고자산건설자금이자</td>

					<td align=center><input type="text" item=205 row=17 col=1 value="<?=lobas_format($arr_fld[17][1])?>"></td>
					<td align=center><input type="text" item=205 row=17 col=2 value="<?=lobas_format($arr_fld[17][2])?>"></td>
					<td align=center><input type="text" item=205 row=17 col=3 value="<?=lobas_format($arr_fld[17][3])?>"></td>
					<td align=center><input type="text" item=205 row=17 col=4 value="<?=lobas_format($arr_fld[17][4])?>"></td>
					<td align=center><input type="text" item=205 row=17 col=5 value="<?=lobas_format($arr_fld[17][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>투자자산취득</td>

					<td align=center><input type="text" parent=200 class=210 col=1 none></td>
					<td align=center><input type="text" parent=200 class=210 col=2 none></td>
					<td align=center><input type="text" parent=200 class=210 col=3 none></td>
					<td align=center><input type="text" parent=200 class=210 col=4 none></td>
					<td align=center><input type="text" parent=200 class=210 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>대여금</td>

					<td align=center><input type="text" item=210 row=18 col=1 value="<?=lobas_format($arr_fld[18][1])?>"></td>
					<td align=center><input type="text" item=210 row=18 col=2 value="<?=lobas_format($arr_fld[18][2])?>"></td>
					<td align=center><input type="text" item=210 row=18 col=3 value="<?=lobas_format($arr_fld[18][3])?>"></td>
					<td align=center><input type="text" item=210 row=18 col=4 value="<?=lobas_format($arr_fld[18][4])?>"></td>
					<td align=center><input type="text" item=210 row=18 col=5 value="<?=lobas_format($arr_fld[18][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>투자자산증권</td>												    
																											    
					<td align=center><input type="text" item=210 row=19 col=1 value="<?=lobas_format($arr_fld[19][1])?>"></td>
					<td align=center><input type="text" item=210 row=19 col=2 value="<?=lobas_format($arr_fld[19][2])?>"></td>
					<td align=center><input type="text" item=210 row=19 col=3 value="<?=lobas_format($arr_fld[19][3])?>"></td>
					<td align=center><input type="text" item=210 row=19 col=4 value="<?=lobas_format($arr_fld[19][4])?>"></td>
					<td align=center><input type="text" item=210 row=19 col=5 value="<?=lobas_format($arr_fld[19][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>기타투자자산</td>

					<td align=center><input type="text" class=223 item=210 col=1 none></td>
					<td align=center><input type="text" class=223 item=210 col=2 none></td>
					<td align=center><input type="text" class=223 item=210 col=3 none></td>
					<td align=center><input type="text" class=223 item=210 col=4 none></td>
					<td align=center><input type="text" class=223 item=210 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td class="pd">퇴직연금적립금</td>

					<td align=center><input type="text" step=3 item=223 row=20 col=1 value="<?=lobas_format($arr_fld[20][1])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=20 col=2 value="<?=lobas_format($arr_fld[20][2])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=20 col=3 value="<?=lobas_format($arr_fld[20][3])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=20 col=4 value="<?=lobas_format($arr_fld[20][4])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=20 col=5 value="<?=lobas_format($arr_fld[20][5])?>"></td>
				</tr>																								   
				<tr bgcolor="FFFFFF" height="30">																	   
					<td align=center></td>																			   
					<td align=center></td>																			   
					<td class="pd">기타투자자산</td>																   
																													   
					<td align=center><input type="text" step=3 item=223 row=21 col=1 value="<?=lobas_format($arr_fld[21][1])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=21 col=2 value="<?=lobas_format($arr_fld[21][2])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=21 col=3 value="<?=lobas_format($arr_fld[21][3])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=21 col=4 value="<?=lobas_format($arr_fld[21][4])?>"></td>
					<td align=center><input type="text" step=3 item=223 row=21 col=5 value="<?=lobas_format($arr_fld[21][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>유형자산취득</td>

					<td align=center><input type="text" parent=200 class=230 col=1 none></td>
					<td align=center><input type="text" parent=200 class=230 col=2 none></td>
					<td align=center><input type="text" parent=200 class=230 col=3 none></td>
					<td align=center><input type="text" parent=200 class=230 col=4 none></td>
					<td align=center><input type="text" parent=200 class=230 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>토지</td>

					<td align=center><input type="text" item=230 row=22 col=1 value="<?=lobas_format($arr_fld[22][1])?>"></td>
					<td align=center><input type="text" item=230 row=22 col=2 value="<?=lobas_format($arr_fld[22][2])?>"></td>
					<td align=center><input type="text" item=230 row=22 col=3 value="<?=lobas_format($arr_fld[22][3])?>"></td>
					<td align=center><input type="text" item=230 row=22 col=4 value="<?=lobas_format($arr_fld[22][4])?>"></td>
					<td align=center><input type="text" item=230 row=22 col=5 value="<?=lobas_format($arr_fld[22][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>입목</td>														    
																											    
					<td align=center><input type="text" item=230 row=23 col=1 value="<?=lobas_format($arr_fld[23][1])?>"></td>
					<td align=center><input type="text" item=230 row=23 col=2 value="<?=lobas_format($arr_fld[23][2])?>"></td>
					<td align=center><input type="text" item=230 row=23 col=3 value="<?=lobas_format($arr_fld[23][3])?>"></td>
					<td align=center><input type="text" item=230 row=23 col=4 value="<?=lobas_format($arr_fld[23][4])?>"></td>
					<td align=center><input type="text" item=230 row=23 col=5 value="<?=lobas_format($arr_fld[23][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>건물</td>

					<td align=center><input type="text" item=230 row=24 col=1 value="<?=lobas_format($arr_fld[24][1])?>"></td>
					<td align=center><input type="text" item=230 row=24 col=2 value="<?=lobas_format($arr_fld[24][2])?>"></td>
					<td align=center><input type="text" item=230 row=24 col=3 value="<?=lobas_format($arr_fld[24][3])?>"></td>
					<td align=center><input type="text" item=230 row=24 col=4 value="<?=lobas_format($arr_fld[24][4])?>"></td>
					<td align=center><input type="text" item=230 row=24 col=5 value="<?=lobas_format($arr_fld[24][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>구축물</td>													    
																											    
					<td align=center><input type="text" item=230 row=25 col=1 value="<?=lobas_format($arr_fld[25][1])?>"></td>
					<td align=center><input type="text" item=230 row=25 col=2 value="<?=lobas_format($arr_fld[25][2])?>"></td>
					<td align=center><input type="text" item=230 row=25 col=3 value="<?=lobas_format($arr_fld[25][3])?>"></td>
					<td align=center><input type="text" item=230 row=25 col=4 value="<?=lobas_format($arr_fld[25][4])?>"></td>
					<td align=center><input type="text" item=230 row=25 col=5 value="<?=lobas_format($arr_fld[25][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>기계장치</td>

					<td align=center><input type="text" item=230 row=26 col=1 value="<?=lobas_format($arr_fld[26][1])?>"></td>
					<td align=center><input type="text" item=230 row=26 col=2 value="<?=lobas_format($arr_fld[26][2])?>"></td>
					<td align=center><input type="text" item=230 row=26 col=3 value="<?=lobas_format($arr_fld[26][3])?>"></td>
					<td align=center><input type="text" item=230 row=26 col=4 value="<?=lobas_format($arr_fld[26][4])?>"></td>
					<td align=center><input type="text" item=230 row=26 col=5 value="<?=lobas_format($arr_fld[26][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>차량운반구</td>												    
																											    
					<td align=center><input type="text" item=230 row=27 col=1 value="<?=lobas_format($arr_fld[27][1])?>"></td>
					<td align=center><input type="text" item=230 row=27 col=2 value="<?=lobas_format($arr_fld[27][2])?>"></td>
					<td align=center><input type="text" item=230 row=27 col=3 value="<?=lobas_format($arr_fld[27][3])?>"></td>
					<td align=center><input type="text" item=230 row=27 col=4 value="<?=lobas_format($arr_fld[27][4])?>"></td>
					<td align=center><input type="text" item=230 row=27 col=5 value="<?=lobas_format($arr_fld[27][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>공기구비품</td>

					<td align=center><input type="text" item=230 row=28 col=1 value="<?=lobas_format($arr_fld[28][1])?>"></td>
					<td align=center><input type="text" item=230 row=28 col=2 value="<?=lobas_format($arr_fld[28][2])?>"></td>
					<td align=center><input type="text" item=230 row=28 col=3 value="<?=lobas_format($arr_fld[28][3])?>"></td>
					<td align=center><input type="text" item=230 row=28 col=4 value="<?=lobas_format($arr_fld[28][4])?>"></td>
					<td align=center><input type="text" item=230 row=28 col=5 value="<?=lobas_format($arr_fld[28][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>기타유형자산</td>												    
																											    
					<td align=center><input type="text" item=230 row=29 col=1 value="<?=lobas_format($arr_fld[29][1])?>"></td>
					<td align=center><input type="text" item=230 row=29 col=2 value="<?=lobas_format($arr_fld[29][2])?>"></td>
					<td align=center><input type="text" item=230 row=29 col=3 value="<?=lobas_format($arr_fld[29][3])?>"></td>
					<td align=center><input type="text" item=230 row=29 col=4 value="<?=lobas_format($arr_fld[29][4])?>"></td>
					<td align=center><input type="text" item=230 row=29 col=5 value="<?=lobas_format($arr_fld[29][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>건설중인자산</td>

					<td align=center><input type="text" item=230 row=30 col=1 value="<?=lobas_format($arr_fld[30][1])?>"></td>
					<td align=center><input type="text" item=230 row=30 col=2 value="<?=lobas_format($arr_fld[30][2])?>"></td>
					<td align=center><input type="text" item=230 row=30 col=3 value="<?=lobas_format($arr_fld[30][3])?>"></td>
					<td align=center><input type="text" item=230 row=30 col=4 value="<?=lobas_format($arr_fld[30][4])?>"></td>
					<td align=center><input type="text" item=230 row=30 col=5 value="<?=lobas_format($arr_fld[30][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>임대주택</td>													    
																											    
					<td align=center><input type="text" item=230 row=31 col=1 value="<?=lobas_format($arr_fld[31][1])?>"></td>
					<td align=center><input type="text" item=230 row=31 col=2 value="<?=lobas_format($arr_fld[31][2])?>"></td>
					<td align=center><input type="text" item=230 row=31 col=3 value="<?=lobas_format($arr_fld[31][3])?>"></td>
					<td align=center><input type="text" item=230 row=31 col=4 value="<?=lobas_format($arr_fld[31][4])?>"></td>
					<td align=center><input type="text" item=230 row=31 col=5 value="<?=lobas_format($arr_fld[31][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>무형자산및기타비유동자산취득</td>

					<td align=center><input type="text" parent=200 class=240 col=1 none></td>
					<td align=center><input type="text" parent=200 class=240 col=2 none></td>
					<td align=center><input type="text" parent=200 class=240 col=3 none></td>
					<td align=center><input type="text" parent=200 class=240 col=4 none></td>
					<td align=center><input type="text" parent=200 class=240 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>산업재산권등</td>

					<td align=center><input type="text" item=240 row=32 col=1 value="<?=lobas_format($arr_fld[32][1])?>"></td>
					<td align=center><input type="text" item=240 row=32 col=2 value="<?=lobas_format($arr_fld[32][2])?>"></td>
					<td align=center><input type="text" item=240 row=32 col=3 value="<?=lobas_format($arr_fld[32][3])?>"></td>
					<td align=center><input type="text" item=240 row=32 col=4 value="<?=lobas_format($arr_fld[32][4])?>"></td>
					<td align=center><input type="text" item=240 row=32 col=5 value="<?=lobas_format($arr_fld[32][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>영업권</td>													    
																											    
					<td align=center><input type="text" item=240 row=33 col=1 value="<?=lobas_format($arr_fld[33][1])?>"></td>
					<td align=center><input type="text" item=240 row=33 col=2 value="<?=lobas_format($arr_fld[33][2])?>"></td>
					<td align=center><input type="text" item=240 row=33 col=3 value="<?=lobas_format($arr_fld[33][3])?>"></td>
					<td align=center><input type="text" item=240 row=33 col=4 value="<?=lobas_format($arr_fld[33][4])?>"></td>
					<td align=center><input type="text" item=240 row=33 col=5 value="<?=lobas_format($arr_fld[33][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>소프트웨어</td>

					<td align=center><input type="text" item=240 row=34 col=1 value="<?=lobas_format($arr_fld[34][1])?>"></td>
					<td align=center><input type="text" item=240 row=34 col=2 value="<?=lobas_format($arr_fld[34][2])?>"></td>
					<td align=center><input type="text" item=240 row=34 col=3 value="<?=lobas_format($arr_fld[34][3])?>"></td>
					<td align=center><input type="text" item=240 row=34 col=4 value="<?=lobas_format($arr_fld[34][4])?>"></td>
					<td align=center><input type="text" item=240 row=34 col=5 value="<?=lobas_format($arr_fld[34][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>보증금</td>													    
																											    
					<td align=center><input type="text" item=240 row=35 col=1 value="<?=lobas_format($arr_fld[35][1])?>"></td>
					<td align=center><input type="text" item=240 row=35 col=2 value="<?=lobas_format($arr_fld[35][2])?>"></td>
					<td align=center><input type="text" item=240 row=35 col=3 value="<?=lobas_format($arr_fld[35][3])?>"></td>
					<td align=center><input type="text" item=240 row=35 col=4 value="<?=lobas_format($arr_fld[35][4])?>"></td>
					<td align=center><input type="text" item=240 row=35 col=5 value="<?=lobas_format($arr_fld[35][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>기타비유동자산</td>

					<td align=center><input type="text" item=240 row=36 col=1 value="<?=lobas_format($arr_fld[36][1])?>"></td>
					<td align=center><input type="text" item=240 row=36 col=2 value="<?=lobas_format($arr_fld[36][2])?>"></td>
					<td align=center><input type="text" item=240 row=36 col=3 value="<?=lobas_format($arr_fld[36][3])?>"></td>
					<td align=center><input type="text" item=240 row=36 col=4 value="<?=lobas_format($arr_fld[36][4])?>"></td>
					<td align=center><input type="text" item=240 row=36 col=5 value="<?=lobas_format($arr_fld[36][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>비가동설비자산취득</td>

					<td align=center><input type="text" parent=200 class=250 col=1 none></td>
					<td align=center><input type="text" parent=200 class=250 col=2 none></td>
					<td align=center><input type="text" parent=200 class=250 col=3 none></td>
					<td align=center><input type="text" parent=200 class=250 col=4 none></td>
					<td align=center><input type="text" parent=200 class=250 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>건설중인자산</td>

					<td align=center><input type="text" item=250 row=37 col=1 value="<?=lobas_format($arr_fld[37][1])?>"></td>
					<td align=center><input type="text" item=250 row=37 col=2 value="<?=lobas_format($arr_fld[37][2])?>"></td>
					<td align=center><input type="text" item=250 row=37 col=3 value="<?=lobas_format($arr_fld[37][3])?>"></td>
					<td align=center><input type="text" item=250 row=37 col=4 value="<?=lobas_format($arr_fld[37][4])?>"></td>
					<td align=center><input type="text" item=250 row=37 col=5 value="<?=lobas_format($arr_fld[37][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>유동부채상환</td>

					<td align=center><input type="text" parent=200 class=260 col=1 none></td>
					<td align=center><input type="text" parent=200 class=260 col=2 none></td>
					<td align=center><input type="text" parent=200 class=260 col=3 none></td>
					<td align=center><input type="text" parent=200 class=260 col=4 none></td>
					<td align=center><input type="text" parent=200 class=260 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>선수금상환</td>

					<td align=center><input type="text" item=260 row=38 col=1 value="<?=lobas_format($arr_fld[38][1])?>"></td>
					<td align=center><input type="text" item=260 row=38 col=2 value="<?=lobas_format($arr_fld[38][2])?>"></td>
					<td align=center><input type="text" item=260 row=38 col=3 value="<?=lobas_format($arr_fld[38][3])?>"></td>
					<td align=center><input type="text" item=260 row=38 col=4 value="<?=lobas_format($arr_fld[38][4])?>"></td>
					<td align=center><input type="text" item=260 row=38 col=5 value="<?=lobas_format($arr_fld[38][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>단기차입금상환</td>											    
																											    
					<td align=center><input type="text" item=260 row=39 col=1 value="<?=lobas_format($arr_fld[39][1])?>"></td>
					<td align=center><input type="text" item=260 row=39 col=2 value="<?=lobas_format($arr_fld[39][2])?>"></td>
					<td align=center><input type="text" item=260 row=39 col=3 value="<?=lobas_format($arr_fld[39][3])?>"></td>
					<td align=center><input type="text" item=260 row=39 col=4 value="<?=lobas_format($arr_fld[39][4])?>"></td>
					<td align=center><input type="text" item=260 row=39 col=5 value="<?=lobas_format($arr_fld[39][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>기타유동부채상환</td>

					<td align=center><input type="text" item=260 row=40 col=1 value="<?=lobas_format($arr_fld[40][1])?>"></td>
					<td align=center><input type="text" item=260 row=40 col=2 value="<?=lobas_format($arr_fld[40][2])?>"></td>
					<td align=center><input type="text" item=260 row=40 col=3 value="<?=lobas_format($arr_fld[40][3])?>"></td>
					<td align=center><input type="text" item=260 row=40 col=4 value="<?=lobas_format($arr_fld[40][4])?>"></td>
					<td align=center><input type="text" item=260 row=40 col=5 value="<?=lobas_format($arr_fld[40][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>비유동부채상환</td>

					<td align=center><input type="text" parent=200 class=270 col=1 none></td>
					<td align=center><input type="text" parent=200 class=270 col=2 none></td>
					<td align=center><input type="text" parent=200 class=270 col=3 none></td>
					<td align=center><input type="text" parent=200 class=270 col=4 none></td>
					<td align=center><input type="text" parent=200 class=270 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>정부차입금원금상환</td>

					<td align=center><input type="text" item=270 row=41 col=1 value="<?=lobas_format($arr_fld[41][1])?>"></td>
					<td align=center><input type="text" item=270 row=41 col=2 value="<?=lobas_format($arr_fld[41][2])?>"></td>
					<td align=center><input type="text" item=270 row=41 col=3 value="<?=lobas_format($arr_fld[41][3])?>"></td>
					<td align=center><input type="text" item=270 row=41 col=4 value="<?=lobas_format($arr_fld[41][4])?>"></td>
					<td align=center><input type="text" item=270 row=41 col=5 value="<?=lobas_format($arr_fld[41][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>금융기관차입금원금상환</td>

					<td align=center><input type="text" item=270 row=42 col=1 value="<?=lobas_format($arr_fld[42][1])?>"></td>
					<td align=center><input type="text" item=270 row=42 col=2 value="<?=lobas_format($arr_fld[42][2])?>"></td>
					<td align=center><input type="text" item=270 row=42 col=3 value="<?=lobas_format($arr_fld[42][3])?>"></td>
					<td align=center><input type="text" item=270 row=42 col=4 value="<?=lobas_format($arr_fld[42][4])?>"></td>
					<td align=center><input type="text" item=270 row=42 col=5 value="<?=lobas_format($arr_fld[42][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>공사공단채원금상환</td>

					<td align=center><input type="text" item=270 row=43 col=1 value="<?=lobas_format($arr_fld[43][1])?>"></td>
					<td align=center><input type="text" item=270 row=43 col=2 value="<?=lobas_format($arr_fld[43][2])?>"></td>
					<td align=center><input type="text" item=270 row=43 col=3 value="<?=lobas_format($arr_fld[43][3])?>"></td>
					<td align=center><input type="text" item=270 row=43 col=4 value="<?=lobas_format($arr_fld[43][4])?>"></td>
					<td align=center><input type="text" item=270 row=43 col=5 value="<?=lobas_format($arr_fld[43][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>국외차입금원금상환</td>										    
																											    
					<td align=center><input type="text" item=270 row=44 col=1 value="<?=lobas_format($arr_fld[44][1])?>"></td>
					<td align=center><input type="text" item=270 row=44 col=2 value="<?=lobas_format($arr_fld[44][2])?>"></td>
					<td align=center><input type="text" item=270 row=44 col=3 value="<?=lobas_format($arr_fld[44][3])?>"></td>
					<td align=center><input type="text" item=270 row=44 col=4 value="<?=lobas_format($arr_fld[44][4])?>"></td>
					<td align=center><input type="text" item=270 row=44 col=5 value="<?=lobas_format($arr_fld[44][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>기타비유동부채상환</td>

					<td align=center><input type="text" item=270 row=45 col=1 value="<?=lobas_format($arr_fld[45][1])?>"></td>
					<td align=center><input type="text" item=270 row=45 col=2 value="<?=lobas_format($arr_fld[45][2])?>"></td>
					<td align=center><input type="text" item=270 row=45 col=3 value="<?=lobas_format($arr_fld[45][3])?>"></td>
					<td align=center><input type="text" item=270 row=45 col=4 value="<?=lobas_format($arr_fld[45][4])?>"></td>
					<td align=center><input type="text" item=270 row=45 col=5 value="<?=lobas_format($arr_fld[45][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>기타자본적지출</td>

					<td align=center><input type="text" parent=200 class=280 col=1 none></td>
					<td align=center><input type="text" parent=200 class=280 col=2 none></td>
					<td align=center><input type="text" parent=200 class=280 col=3 none></td>
					<td align=center><input type="text" parent=200 class=280 col=4 none></td>
					<td align=center><input type="text" parent=200 class=280 col=5 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>정부보조금반환</td>

					<td align=center><input type="text" item=280 row=46 col=1 value="<?=lobas_format($arr_fld[46][1])?>"></td>
					<td align=center><input type="text" item=280 row=46 col=2 value="<?=lobas_format($arr_fld[46][2])?>"></td>
					<td align=center><input type="text" item=280 row=46 col=3 value="<?=lobas_format($arr_fld[46][3])?>"></td>
					<td align=center><input type="text" item=280 row=46 col=4 value="<?=lobas_format($arr_fld[46][4])?>"></td>
					<td align=center><input type="text" item=280 row=46 col=5 value="<?=lobas_format($arr_fld[46][5])?>"></td>
				</tr>																						    
				<tr bgcolor="FFFFFF" height="30">															    
					<td align=center></td>																	    
					<td class="pd" colspan=2>배당금등</td>													    
																											    
					<td align=center><input type="text" item=280 row=47 col=1 value="<?=lobas_format($arr_fld[47][1])?>"></td>
					<td align=center><input type="text" item=280 row=47 col=2 value="<?=lobas_format($arr_fld[47][2])?>"></td>
					<td align=center><input type="text" item=280 row=47 col=3 value="<?=lobas_format($arr_fld[47][3])?>"></td>
					<td align=center><input type="text" item=280 row=47 col=4 value="<?=lobas_format($arr_fld[47][4])?>"></td>
					<td align=center><input type="text" item=280 row=47 col=5 value="<?=lobas_format($arr_fld[47][5])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td class="pd" colspan=2>기타자본적지출</td>

					<td align=center><input type="text" item=280 row=48 col=1 value="<?=lobas_format($arr_fld[48][1])?>"></td>
					<td align=center><input type="text" item=280 row=48 col=2 value="<?=lobas_format($arr_fld[48][2])?>"></td>
					<td align=center><input type="text" item=280 row=48 col=3 value="<?=lobas_format($arr_fld[48][3])?>"></td>
					<td align=center><input type="text" item=280 row=48 col=4 value="<?=lobas_format($arr_fld[48][4])?>"></td>
					<td align=center><input type="text" item=280 row=48 col=5 value="<?=lobas_format($arr_fld[48][5])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td class="pd" colspan=3>미지급 비용ㆍ금(ㄷ)</td>

					<td align=center><input type="text" step=1 class=300 row=49 col=1 value="<?=lobas_format($arr_fld[49][1])?>"></td>
					<td align=center><input type="text" step=1 class=300 row=49 col=2 value="<?=lobas_format($arr_fld[49][2])?>"></td>
					<td align=center><input type="text" step=1 class=300 row=49 col=3 value="<?=lobas_format($arr_fld[49][3])?>"></td>
					<td align=center><input type="text" step=1 class=300 row=49 col=4 value="<?=lobas_format($arr_fld[49][4])?>"></td>
					<td align=center><input type="text" step=1 class=300 row=49 col=5 value="<?=lobas_format($arr_fld[49][5])?>"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>