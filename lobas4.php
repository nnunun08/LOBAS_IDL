<?
	if (empty($yy)) $yy = date("Y")-1;

	$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=4";
	$row = T_select($sql);

	$arr_fld = array();

	for ($i=0;$i<count($row);$i++) {
		for ($j=1;$j<=6;$j++) {
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
			let step = $(this).attr("step");
			
			let val = $(this).val().replace(/[^0-9]/g,"");
			let yy = $("select[name='yy']").val();

			$(this).val(addComma(val));
			
			if (event.keyCode==13) {
				$.get("lobasExe.php",{"yy" : yy,"row" : row,"col" : col,"kap" : val,"cate" : "4","fldsu" : "6"},function(json){
					if(json != null) {
						$(this).val(addComma(val));
						moveInput(row,col);
					} 
				});
			}

			compute(step,row,col);
		});

		/* 초기 계산 */
		{		
			let col = "0";
			let row = "0";
			let step = "0";

			for (let i=1;i<=6 ;i++) {
				$("input[col='"+i+"']").each(function(){
					if (i!=4 && i!=6) {
						if ($(this).val()!="") {
							col = i;
							row = $(this).attr("row");
							step = $(this).attr("step");
							
							compute(step,row,col);
						}
					}
				});
			}
		}
	});

	function convStep(step) {
		/*
			step 4 -> key (item);
			step 3 -> key (class); 
		*/

		if (step=="4") {
			return "item";
		} else {
			return "class";
		}
	}

	function compute(step,row,col) {
		let key = "";
		let kap = [];
		let pay = "";
		let tot = 0;
		let tmp = "";
		let parent = "";

		parent = $("input[row='"+row+"'][col='"+col+"']").attr("parent");
		key = $("input[row='"+row+"'][col='"+col+"']").attr(convStep(step));
		
		selector = "input["+convStep(step)+"='"+key+"']";
		
		for (i=1;i<=6;i++) {
			kap[i] = 0;

			if (i!=4 && i!=6) {
				if ($(selector+"[row='"+row+"'][col='"+i+"']").val()!="") {
					pay = $(selector+"[row='"+row+"'][col='"+i+"']").val().replaceAll(",","");
					
					if (isNaN(pay)) pay = 0;
				
					kap[i] = parseInt(pay);
				}
			}
		}

		/* 가로계산 */
		kap[4] = kap[2]-kap[3];			// 실제수납액(C=D-E)	
		kap[6] = kap[1]-kap[4]-kap[5];	// 미수금(B-C-F)
		
		$(selector+"[row='"+row+"'][col='4']").val(addComma(kap[4]));
		$(selector+"[row='"+row+"'][col='6']").val(addComma(kap[6]));
	
		/* 세로계산 */

		tmp = ("4,6,"+col).split(",");	// 입력되는 부분과 자동합계부분

		if (step=="4") {
			for (i=0;i<tmp.length ;i++) {
				tot = 0;

				$(selector+"[col='"+tmp[i]+"']").each(function(i){
					pay = parseInt($(this).val().replaceAll(",",""));
					if (isNaN(pay)) pay = 0;
					
					tot += pay;
				});

				/* step3 합 */
				$("input[class='"+key+"'][col='"+tmp[i]+"']").val(addComma(tot));
			}
		}

		/* step2 합계 */
		for (i=0;i<tmp.length ;i++) {
			tot = 0;
			
			$("input[step='3'][parent='"+parent+"'][col='"+tmp[i]+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay;
			});

			$("input[class='"+parent+"'][col='"+tmp[i]+"']").val(addComma(tot));
		}

		/* step1 합계 */
		for (i=0;i<tmp.length ;i++) {
			tot = 0;
			
			$("input[step='2'][col='"+tmp[i]+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay;
			});

			$("input[class='100'][col='"+tmp[i]+"']").val(addComma(tot));
		}
	}

	function moveInput(row,col) {
		let next = 0;
		
		if (col=="1" || col=="2") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").select();
		} else if (col=="3") {
			next = parseInt(col)+1;
			$("input[col='5'][row='"+row+"']").select();
		} else if (col=="5") {
			next = parseInt(row)+1;
			$("input[col='1'][row='"+next+"']").select();

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
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">자본적 수입</span>
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
						1. 본 화면은 당기의 로바스 출력자료 중 자본예산 결산보고서-자본적수입의 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  자본예산결산보고서-자본적수입자료를 엑셀파일로 다운받은 후 해당 계정과목의 금액을 입력(복사/붙여넣기 )합니다.<br>
						3. 금액이 없는 계정과목은  입력하지 않고 비워둡니다.<br>
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
			<table width=1200 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="4%">
					<col width="4%">
					<col width="4%">
					<col width="16%">
					
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center colspan=4>과목</td>
					<td align=center rowspan=2>결산액<br>(징수결정액)<br>(B)</td>
					<td align=center colspan=3>수 납 액</td>
					<td align=center rowspan=2>불납결손액<br>(F)</td>
					<td align=center rowspan=2>미수금<br>(B-C-F)</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td align=center>관</td>
					<td align=center>항</td>
					<td align=center>세항</td>
					<td align=center>목</td>

					<td align=center>수 입 액(D)</td>
					<td align=center>과오납환불액(E)</td>
					<td align=center>실제수납액(C=D-E)</td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td colspan=4 class="pd">100<br>자본적수입</td>
					<td align=center><input type="text"	class="100" col="1" none></td>
					<td align=center><input type="text"	class="100" col="2" none></td>
					<td align=center><input type="text"	class="100" col="3" none></td>
					<td align=center><input type="text"	class="100" col="4" none></td>
					<td align=center><input type="text"	class="100" col="5" none></td>
					<td align=center><input type="text"	class="100" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">110<br>투자자산처분</td>
				
					<td align=center><input type="text" step="2" class="110" col="1" none></td>
					<td align=center><input type="text" step="2" class="110" col="2" none></td>
					<td align=center><input type="text" step="2" class="110" col="3" none></td>
					<td align=center><input type="text" step="2" class="110" col="4" none></td>
					<td align=center><input type="text" step="2" class="110" col="5" none></td>
					<td align=center><input type="text" step="2" class="110" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">112<br>대여금회수수입</td>
				
					<td align=center><input type="text" step="3" parent="110" class="112" row="1" col="1" value="<?=lobas_format($arr_fld[1][1])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="112" row="1" col="2" value="<?=lobas_format($arr_fld[1][2])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="112" row="1" col="3" value="<?=lobas_format($arr_fld[1][3])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="112" row="1" col="4" value="<?=lobas_format($arr_fld[1][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="110" class="112" row="1" col="5" value="<?=lobas_format($arr_fld[1][5])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="112" row="1" col="6" value="<?=lobas_format($arr_fld[1][6])?>" none></td>
				</tr>																													   
																																		   
				<tr bgcolor="FFFFFF" height="30">																						   
					<td align=center></td>																								   
					<td align=center></td>																								   
					<td colspan=2 class="pd">113<br>투자유가증권처분수입</td>															   
																																		   
					<td align=center><input type="text" step="3" parent="110" class="113" row="2" col="1" value="<?=lobas_format($arr_fld[2][1])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="113" row="2" col="2" value="<?=lobas_format($arr_fld[2][2])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="113" row="2" col="3" value="<?=lobas_format($arr_fld[2][3])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="113" row="2" col="4" value="<?=lobas_format($arr_fld[2][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="110" class="113" row="2" col="5" value="<?=lobas_format($arr_fld[2][5])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="113" row="2" col="6" value="<?=lobas_format($arr_fld[2][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">119<br>기타투자자산처분수입</td>
				
					<td align=center><input type="text" step="3" parent="110" class="119" row="3" col="1" value="<?=lobas_format($arr_fld[3][1])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="119" row="3" col="2" value="<?=lobas_format($arr_fld[3][2])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="119" row="3" col="3" value="<?=lobas_format($arr_fld[3][3])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="119" row="3" col="4" value="<?=lobas_format($arr_fld[3][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="110" class="119" row="3" col="5" value="<?=lobas_format($arr_fld[3][5])?>"></td>
					<td align=center><input type="text" step="3" parent="110" class="119" row="3" col="6" value="<?=lobas_format($arr_fld[3][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">120<br>유형자산처분</td>
				
					<td align=center><input type="text" step="2" class="120" col="1" none></td>
					<td align=center><input type="text" step="2" class="120" col="2" none></td>
					<td align=center><input type="text" step="2" class="120" col="3" none></td>
					<td align=center><input type="text" step="2" class="120" col="4" none></td>
					<td align=center><input type="text" step="2" class="120" col="5" none></td>
					<td align=center><input type="text" step="2" class="120" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">121<br>재산매각수입</td>
				
					<td align=center><input type="text" step="3" parent="120" class="121" col="1" none></td>
					<td align=center><input type="text" step="3" parent="120" class="121" col="2" none></td>
					<td align=center><input type="text" step="3" parent="120" class="121" col="3" none></td>
					<td align=center><input type="text" step="3" parent="120" class="121" col="4" none></td>
					<td align=center><input type="text" step="3" parent="120" class="121" col="5" none></td>
					<td align=center><input type="text" step="3" parent="120" class="121" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>토지매각수입</td>
				
					<td align=center><input type="text" step="4" parent="120" item="121" row="4" col="1" value="<?=lobas_format($arr_fld[4][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="4" col="2" value="<?=lobas_format($arr_fld[4][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="4" col="3" value="<?=lobas_format($arr_fld[4][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="4" col="4" value="<?=lobas_format($arr_fld[4][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="4" col="5" value="<?=lobas_format($arr_fld[4][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="4" col="6" value="<?=lobas_format($arr_fld[4][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>건물매각수입</td>																				  
																																		  
					<td align=center><input type="text" step="4" parent="120" item="121" row="5" col="1" value="<?=lobas_format($arr_fld[5][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="5" col="2" value="<?=lobas_format($arr_fld[5][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="5" col="3" value="<?=lobas_format($arr_fld[5][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="5" col="4" value="<?=lobas_format($arr_fld[5][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="5" col="5" value="<?=lobas_format($arr_fld[5][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="5" col="6" value="<?=lobas_format($arr_fld[5][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>구축물매각수입</td>
				
					<td align=center><input type="text" step="4" parent="120" item="121" row="6" col="1" value="<?=lobas_format($arr_fld[6][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="6" col="2" value="<?=lobas_format($arr_fld[6][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="6" col="3" value="<?=lobas_format($arr_fld[6][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="6" col="4" value="<?=lobas_format($arr_fld[6][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="6" col="5" value="<?=lobas_format($arr_fld[6][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="6" col="6" value="<?=lobas_format($arr_fld[6][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">04<br>입목매각수입</td>																				  
																																		  
					<td align=center><input type="text" step="4" parent="120" item="121" row="7" col="1" value="<?=lobas_format($arr_fld[7][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="7" col="2" value="<?=lobas_format($arr_fld[7][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="7" col="3" value="<?=lobas_format($arr_fld[7][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="7" col="4" value="<?=lobas_format($arr_fld[7][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="7" col="5" value="<?=lobas_format($arr_fld[7][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="121" row="7" col="6" value="<?=lobas_format($arr_fld[7][6])?>" none></td>
				</tr>

				<!-- -->
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">122<br>물품매각수입</td>
				
					<td align=center><input type="text" step="3" parent="120" class="122" col="1" none></td>
					<td align=center><input type="text" step="3" parent="120" class="122" col="2" none></td>
					<td align=center><input type="text" step="3" parent="120" class="122" col="3" none></td>
					<td align=center><input type="text" step="3" parent="120" class="122" col="4" none></td>
					<td align=center><input type="text" step="3" parent="120" class="122" col="5" none></td>
					<td align=center><input type="text" step="3" parent="120" class="122" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>기계장치매각수입</td>
				
					<td align=center><input type="text" step="4" parent="120" item="122" row="8" col="1" value="<?=lobas_format($arr_fld[8][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="8" col="2" value="<?=lobas_format($arr_fld[8][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="8" col="3" value="<?=lobas_format($arr_fld[8][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="8" col="4" value="<?=lobas_format($arr_fld[8][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="8" col="5" value="<?=lobas_format($arr_fld[8][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="8" col="6" value="<?=lobas_format($arr_fld[8][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>차량운반구매각수입</td>																		  
																																		  
					<td align=center><input type="text" step="4" parent="120" item="122" row="9" col="1" value="<?=lobas_format($arr_fld[9][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="9" col="2" value="<?=lobas_format($arr_fld[9][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="9" col="3" value="<?=lobas_format($arr_fld[9][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="9" col="4" value="<?=lobas_format($arr_fld[9][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="9" col="5" value="<?=lobas_format($arr_fld[9][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="9" col="6" value="<?=lobas_format($arr_fld[9][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>공기구비품매각수입</td>
				
					<td align=center><input type="text" step="4" parent="120" item="122" row="10" col="1" value="<?=lobas_format($arr_fld[10][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="10" col="2" value="<?=lobas_format($arr_fld[10][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="10" col="3" value="<?=lobas_format($arr_fld[10][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="10" col="4" value="<?=lobas_format($arr_fld[10][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="10" col="5" value="<?=lobas_format($arr_fld[10][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="10" col="6" value="<?=lobas_format($arr_fld[10][6])?>" none></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">09<br>기타유형자산매각수입</td>																		    
																																		    
					<td align=center><input type="text" step="4" parent="120" item="122" row="11" col="1" value="<?=lobas_format($arr_fld[11][1])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="11" col="2" value="<?=lobas_format($arr_fld[11][2])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="11" col="3" value="<?=lobas_format($arr_fld[11][3])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="11" col="4" value="<?=lobas_format($arr_fld[11][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="11" col="5" value="<?=lobas_format($arr_fld[11][5])?>"></td>
					<td align=center><input type="text" step="4" parent="120" item="122" row="11" col="6" value="<?=lobas_format($arr_fld[11][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">130<br>기타비유동자산처분</td>
				
					<td align=center><input type="text" step="2" class="130" col="1" none></td>
					<td align=center><input type="text" step="2" class="130" col="2" none></td>
					<td align=center><input type="text" step="2" class="130" col="3" none></td>
					<td align=center><input type="text" step="2" class="130" col="4" none></td>
					<td align=center><input type="text" step="2" class="130" col="5" none></td>
					<td align=center><input type="text" step="2" class="130" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">131<br>보증금회수수입</td>
				
					<td align=center><input type="text" step="3" parent="130" class="131" row="12" col="1" value="<?=lobas_format($arr_fld[12][1])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="131" row="12" col="2" value="<?=lobas_format($arr_fld[12][2])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="131" row="12" col="3" value="<?=lobas_format($arr_fld[12][3])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="131" row="12" col="4" value="<?=lobas_format($arr_fld[12][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="130" class="131" row="12" col="5" value="<?=lobas_format($arr_fld[12][5])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="131" row="12" col="6" value="<?=lobas_format($arr_fld[12][6])?>" none></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=2 class="pd">139<br>기타비유동자산매각수입</td>															     
																																		     
					<td align=center><input type="text" step="3" parent="130" class="139" row="13" col="1" value="<?=lobas_format($arr_fld[13][1])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="139" row="13" col="2" value="<?=lobas_format($arr_fld[13][2])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="139" row="13" col="3" value="<?=lobas_format($arr_fld[13][3])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="139" row="13" col="4" value="<?=lobas_format($arr_fld[13][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="130" class="139" row="13" col="5" value="<?=lobas_format($arr_fld[13][5])?>"></td>
					<td align=center><input type="text" step="3" parent="130" class="139" row="13" col="6" value="<?=lobas_format($arr_fld[13][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">140<br>유동부채수입</td>
				
					<td align=center><input type="text" step="2" class="140" col="1" none></td>
					<td align=center><input type="text" step="2" class="140" col="2" none></td>
					<td align=center><input type="text" step="2" class="140" col="3" none></td>
					<td align=center><input type="text" step="2" class="140" col="4" none></td>
					<td align=center><input type="text" step="2" class="140" col="5" none></td>
					<td align=center><input type="text" step="2" class="140" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">141<br>선수금수입</td>
				
					<td align=center><input type="text" step="3" parent="140" class="141" row="14" col="1" value="<?=lobas_format($arr_fld[14][1])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="141" row="14" col="2" value="<?=lobas_format($arr_fld[14][2])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="141" row="14" col="3" value="<?=lobas_format($arr_fld[14][3])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="141" row="14" col="4" value="<?=lobas_format($arr_fld[14][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="140" class="141" row="14" col="5" value="<?=lobas_format($arr_fld[14][5])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="141" row="14" col="6" value="<?=lobas_format($arr_fld[14][6])?>" none></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=2 class="pd">142<br>단기차입금수입</td>																	     
																																		     
					<td align=center><input type="text" step="3" parent="140" class="142" row="15" col="1" value="<?=lobas_format($arr_fld[15][1])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="142" row="15" col="2" value="<?=lobas_format($arr_fld[15][2])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="142" row="15" col="3" value="<?=lobas_format($arr_fld[15][3])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="142" row="15" col="4" value="<?=lobas_format($arr_fld[15][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="140" class="142" row="15" col="5" value="<?=lobas_format($arr_fld[15][5])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="142" row="15" col="6" value="<?=lobas_format($arr_fld[15][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">149<br>기타유동부채수입</td>
				
					<td align=center><input type="text" step="3" parent="140" class="149" row="16" col="1" value="<?=lobas_format($arr_fld[16][1])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="149" row="16" col="2" value="<?=lobas_format($arr_fld[16][2])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="149" row="16" col="3" value="<?=lobas_format($arr_fld[16][3])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="149" row="16" col="4" value="<?=lobas_format($arr_fld[16][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="140" class="149" row="16" col="5" value="<?=lobas_format($arr_fld[16][5])?>"></td>
					<td align=center><input type="text" step="3" parent="140" class="149" row="16" col="6" value="<?=lobas_format($arr_fld[16][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">150<br>비유동부채수입</td>
				
					<td align=center><input type="text" step="2" class="150" col="1" none></td>
					<td align=center><input type="text" step="2" class="150" col="2" none></td>
					<td align=center><input type="text" step="2" class="150" col="3" none></td>
					<td align=center><input type="text" step="2" class="150" col="4" none></td>
					<td align=center><input type="text" step="2" class="150" col="5" none></td>
					<td align=center><input type="text" step="2" class="150" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">152<br>정부차입금수입</td>
				
					<td align=center><input type="text" step="3" parent="150" class="152" col="1" none></td>
					<td align=center><input type="text" step="3" parent="150" class="152" col="2" none></td>
					<td align=center><input type="text" step="3" parent="150" class="152" col="3" none></td>
					<td align=center><input type="text" step="3" parent="150" class="152" col="4" none></td>
					<td align=center><input type="text" step="3" parent="150" class="152" col="5" none></td>
					<td align=center><input type="text" step="3" parent="150" class="152" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>시도지역개발기금차입금수입</td>
				
					<td align=center><input type="text" step="4" parent="150" item="152" row=17 col="1" value="<?=lobas_format($arr_fld[17][1])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=17 col="2" value="<?=lobas_format($arr_fld[17][2])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=17 col="3" value="<?=lobas_format($arr_fld[17][3])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=17 col="4" value="<?=lobas_format($arr_fld[17][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=17 col="5" value="<?=lobas_format($arr_fld[17][5])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=17 col="6" value="<?=lobas_format($arr_fld[17][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>시군구지역개발기금차입금수입</td>																  
																																		  
					<td align=center><input type="text" step="4" parent="150" item="152" row=18 col="1" value="<?=lobas_format($arr_fld[18][1])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=18 col="2" value="<?=lobas_format($arr_fld[18][2])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=18 col="3" value="<?=lobas_format($arr_fld[18][3])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=18 col="4" value="<?=lobas_format($arr_fld[18][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=18 col="5" value="<?=lobas_format($arr_fld[18][5])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=18 col="6" value="<?=lobas_format($arr_fld[18][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>중앙정부차입금수입</td>
				
					<td align=center><input type="text" step="4" parent="150" item="152" row=19 col="1" value="<?=lobas_format($arr_fld[19][1])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=19 col="2" value="<?=lobas_format($arr_fld[19][2])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=19 col="3" value="<?=lobas_format($arr_fld[19][3])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=19 col="4" value="<?=lobas_format($arr_fld[19][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=19 col="5" value="<?=lobas_format($arr_fld[19][5])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=19 col="6" value="<?=lobas_format($arr_fld[19][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">04<br>자치단체차입금수입</td>																		  
																																		  
					<td align=center><input type="text" step="4" parent="150" item="152" row=20 col="1" value="<?=lobas_format($arr_fld[20][1])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=20 col="2" value="<?=lobas_format($arr_fld[20][2])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=20 col="3" value="<?=lobas_format($arr_fld[20][3])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=20 col="4" value="<?=lobas_format($arr_fld[20][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=20 col="5" value="<?=lobas_format($arr_fld[20][5])?>"></td>
					<td align=center><input type="text" step="4" parent="150" item="152" row=20 col="6" value="<?=lobas_format($arr_fld[20][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">153<br>금융기관차입금수입</td>
				
					<td align=center><input type="text" step="3" parent="150" class="153" row=21 col="1" value="<?=lobas_format($arr_fld[21][1])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="153" row=21 col="2" value="<?=lobas_format($arr_fld[21][2])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="153" row=21 col="3" value="<?=lobas_format($arr_fld[21][3])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="153" row=21 col="4" value="<?=lobas_format($arr_fld[21][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="150" class="153" row=21 col="5" value="<?=lobas_format($arr_fld[21][5])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="153" row=21 col="6" value="<?=lobas_format($arr_fld[21][6])?>" none></td>
				</tr>																													   
																																		   
				<tr bgcolor="FFFFFF" height="30">																						   
					<td align=center></td>																								   
					<td align=center></td>																								   
					<td colspan=2 class="pd">154<br>공사.공단채수입</td>																   
																																		   
					<td align=center><input type="text" step="3" parent="150" class="154" row=22 col="1" value="<?=lobas_format($arr_fld[22][1])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="154" row=22 col="2" value="<?=lobas_format($arr_fld[22][2])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="154" row=22 col="3" value="<?=lobas_format($arr_fld[22][3])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="154" row=22 col="4" value="<?=lobas_format($arr_fld[22][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="150" class="154" row=22 col="5" value="<?=lobas_format($arr_fld[22][5])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="154" row=22 col="6" value="<?=lobas_format($arr_fld[22][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">155<br>국외차입금수입</td>
				
					<td align=center><input type="text" step="3" parent="150" class="155" row=23 col="1" value="<?=lobas_format($arr_fld[23][1])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="155" row=23 col="2" value="<?=lobas_format($arr_fld[23][2])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="155" row=23 col="3" value="<?=lobas_format($arr_fld[23][3])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="155" row=23 col="4" value="<?=lobas_format($arr_fld[23][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="150" class="155" row=23 col="5" value="<?=lobas_format($arr_fld[23][5])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="155" row=23 col="6" value="<?=lobas_format($arr_fld[23][6])?>" none></td>
				</tr>																													   
																																		   
				<tr bgcolor="FFFFFF" height="30">																						   
					<td align=center></td>																								   
					<td align=center></td>																								   
					<td colspan=2 class="pd">159<br>기타비유동부채수입</td>																   
																																		   
					<td align=center><input type="text" step="3" parent="150" class="159" row=24 col="1" value="<?=lobas_format($arr_fld[24][1])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="159" row=24 col="2" value="<?=lobas_format($arr_fld[24][2])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="159" row=24 col="3" value="<?=lobas_format($arr_fld[24][3])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="159" row=24 col="4" value="<?=lobas_format($arr_fld[24][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="150" class="159" row=24 col="5" value="<?=lobas_format($arr_fld[24][5])?>"></td>
					<td align=center><input type="text" step="3" parent="150" class="159" row=24 col="6" value="<?=lobas_format($arr_fld[24][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">160<br>자본금수입</td>
				
					<td align=center><input type="text" step="2" class="160" col="1" none></td>
					<td align=center><input type="text" step="2" class="160" col="2" none></td>
					<td align=center><input type="text" step="2" class="160" col="3" none></td>
					<td align=center><input type="text" step="2" class="160" col="4" none></td>
					<td align=center><input type="text" step="2" class="160" col="5" none></td>
					<td align=center><input type="text" step="2" class="160" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">161<br>자본금수입</td>
				
					<td align=center><input type="text" step="3" parent="160" class="161" row=25 col="1" value="<?=lobas_format($arr_fld[25][1])?>"></td>
					<td align=center><input type="text" step="3" parent="160" class="161" row=25 col="2" value="<?=lobas_format($arr_fld[25][2])?>"></td>
					<td align=center><input type="text" step="3" parent="160" class="161" row=25 col="3" value="<?=lobas_format($arr_fld[25][3])?>"></td>
					<td align=center><input type="text" step="3" parent="160" class="161" row=25 col="4" value="<?=lobas_format($arr_fld[25][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="160" class="161" row=25 col="5" value="<?=lobas_format($arr_fld[25][5])?>"></td>
					<td align=center><input type="text" step="3" parent="160" class="161" row=25 col="6" value="<?=lobas_format($arr_fld[25][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">170<br>자본잉여금수입</td>
				
					<td align=center><input type="text" step="2" class="170" col="1" none></td>
					<td align=center><input type="text" step="2" class="170" col="2" none></td>
					<td align=center><input type="text" step="2" class="170" col="3" none></td>
					<td align=center><input type="text" step="2" class="170" col="4" none></td>
					<td align=center><input type="text" step="2" class="170" col="5" none></td>
					<td align=center><input type="text" step="2" class="170" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">171<br>시설분담금수입</td>
				
					<td align=center><input type="text" step="3" parent="170" class="171" row=26 col="1" value="<?=lobas_format($arr_fld[26][1])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="171" row=26 col="2" value="<?=lobas_format($arr_fld[26][2])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="171" row=26 col="3" value="<?=lobas_format($arr_fld[26][3])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="171" row=26 col="4" value="<?=lobas_format($arr_fld[26][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="170" class="171" row=26 col="5" value="<?=lobas_format($arr_fld[26][5])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="171" row=26 col="6" value="<?=lobas_format($arr_fld[26][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">173<br>타회계건설보조금수입</td>
				
					<td align=center><input type="text" step="3" parent="170" class="173" col="1" none></td>
					<td align=center><input type="text" step="3" parent="170" class="173" col="2" none></td>
					<td align=center><input type="text" step="3" parent="170" class="173" col="3" none></td>
					<td align=center><input type="text" step="3" parent="170" class="173" col="4" none></td>
					<td align=center><input type="text" step="3" parent="170" class="173" col="5" none></td>
					<td align=center><input type="text" step="3" parent="170" class="173" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>국고보조금수입</td>
				
					<td align=center><input type="text" step="4" parent="170" item="173" row=27 col="1" value="<?=lobas_format($arr_fld[27][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=27 col="2" value="<?=lobas_format($arr_fld[27][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=27 col="3" value="<?=lobas_format($arr_fld[27][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=27 col="4" value="<?=lobas_format($arr_fld[27][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=27 col="5" value="<?=lobas_format($arr_fld[27][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=27 col="6" value="<?=lobas_format($arr_fld[27][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>타회계건설보조금수입</td>																		  
																																		  
					<td align=center><input type="text" step="4" parent="170" item="173" row=28 col="1" value="<?=lobas_format($arr_fld[28][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=28 col="2" value="<?=lobas_format($arr_fld[28][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=28 col="3" value="<?=lobas_format($arr_fld[28][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=28 col="4" value="<?=lobas_format($arr_fld[28][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=28 col="5" value="<?=lobas_format($arr_fld[28][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=28 col="6" value="<?=lobas_format($arr_fld[28][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>시도비보조금수입</td>
				
					<td align=center><input type="text" step="4" parent="170" item="173" row=29 col="1" value="<?=lobas_format($arr_fld[29][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=29 col="2" value="<?=lobas_format($arr_fld[29][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=29 col="3" value="<?=lobas_format($arr_fld[29][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=29 col="4" value="<?=lobas_format($arr_fld[29][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=29 col="5" value="<?=lobas_format($arr_fld[29][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=29 col="6" value="<?=lobas_format($arr_fld[29][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">09<br>기타건설보조금수입</td>																		  
																																		  
					<td align=center><input type="text" step="4" parent="170" item="173" row=30 col="1" value="<?=lobas_format($arr_fld[30][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=30 col="2" value="<?=lobas_format($arr_fld[30][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=30 col="3" value="<?=lobas_format($arr_fld[30][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=30 col="4" value="<?=lobas_format($arr_fld[30][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=30 col="5" value="<?=lobas_format($arr_fld[30][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="173" row=30 col="6" value="<?=lobas_format($arr_fld[30][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">174<br>보조금수입</td>
				
					<td align=center><input type="text" step="3" parent="170" class="174" col="1" none></td>
					<td align=center><input type="text" step="3" parent="170" class="174" col="2" none></td>
					<td align=center><input type="text" step="3" parent="170" class="174" col="3" none></td>
					<td align=center><input type="text" step="3" parent="170" class="174" col="4" none></td>
					<td align=center><input type="text" step="3" parent="170" class="174" col="5" none></td>
					<td align=center><input type="text" step="3" parent="170" class="174" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>타회계보조금수입</td>
				
					<td align=center><input type="text" step="4" parent="170" item="174" row=31 col="1" value="<?=lobas_format($arr_fld[31][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=31 col="2" value="<?=lobas_format($arr_fld[31][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=31 col="3" value="<?=lobas_format($arr_fld[31][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=31 col="4" value="<?=lobas_format($arr_fld[31][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=31 col="5" value="<?=lobas_format($arr_fld[31][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=31 col="6" value="<?=lobas_format($arr_fld[31][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>국고보조금수입</td>																			  
																																		  
					<td align=center><input type="text" step="4" parent="170" item="174" row=32 col="1" value="<?=lobas_format($arr_fld[32][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=32 col="2" value="<?=lobas_format($arr_fld[32][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=32 col="3" value="<?=lobas_format($arr_fld[32][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=32 col="4" value="<?=lobas_format($arr_fld[32][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=32 col="5" value="<?=lobas_format($arr_fld[32][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=32 col="6" value="<?=lobas_format($arr_fld[32][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>자치단체보조금수입</td>
				
					<td align=center><input type="text" step="4" parent="170" item="174" row=33 col="1" value="<?=lobas_format($arr_fld[33][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=33 col="2" value="<?=lobas_format($arr_fld[33][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=33 col="3" value="<?=lobas_format($arr_fld[33][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=33 col="4" value="<?=lobas_format($arr_fld[33][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=33 col="5" value="<?=lobas_format($arr_fld[33][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="174" row=33 col="6" value="<?=lobas_format($arr_fld[33][6])?>" none></td>
				</tr>

				<!-- -->
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">175<br>공사부담금수입</td>
				
					<td align=center><input type="text" step="3" parent="170" class="175" col="1" none></td>
					<td align=center><input type="text" step="3" parent="170" class="175" col="2" none></td>
					<td align=center><input type="text" step="3" parent="170" class="175" col="3" none></td>
					<td align=center><input type="text" step="3" parent="170" class="175" col="4" none></td>
					<td align=center><input type="text" step="3" parent="170" class="175" col="5" none></td>
					<td align=center><input type="text" step="3" parent="170" class="175" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>타회계공사부담금수입</td>
				
					<td align=center><input type="text" step="4" parent="170" item="175" row=34 col="1" value="<?=lobas_format($arr_fld[34][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=34 col="2" value="<?=lobas_format($arr_fld[34][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=34 col="3" value="<?=lobas_format($arr_fld[34][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=34 col="4" value="<?=lobas_format($arr_fld[34][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=34 col="5" value="<?=lobas_format($arr_fld[34][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=34 col="6" value="<?=lobas_format($arr_fld[34][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>타자치단체공사부담금수입</td>																	  
																																		  
					<td align=center><input type="text" step="4" parent="170" item="175" row=35 col="1" value="<?=lobas_format($arr_fld[35][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=35 col="2" value="<?=lobas_format($arr_fld[35][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=35 col="3" value="<?=lobas_format($arr_fld[35][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=35 col="4" value="<?=lobas_format($arr_fld[35][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=35 col="5" value="<?=lobas_format($arr_fld[35][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=35 col="6" value="<?=lobas_format($arr_fld[35][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09<br>기타공사부담금수입</td>
				
					<td align=center><input type="text" step="4" parent="170" item="175" row=36 col="1" value="<?=lobas_format($arr_fld[36][1])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=36 col="2" value="<?=lobas_format($arr_fld[36][2])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=36 col="3" value="<?=lobas_format($arr_fld[36][3])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=36 col="4" value="<?=lobas_format($arr_fld[36][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=36 col="5" value="<?=lobas_format($arr_fld[36][5])?>"></td>
					<td align=center><input type="text" step="4" parent="170" item="175" row=36 col="6" value="<?=lobas_format($arr_fld[36][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">176<br>수탁자산보조금수입</td>
				
					<td align=center><input type="text" step="3" parent="170" class="176" row=37 col="1" value="<?=lobas_format($arr_fld[37][1])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="176" row=37 col="2" value="<?=lobas_format($arr_fld[37][2])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="176" row=37 col="3" value="<?=lobas_format($arr_fld[37][3])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="176" row=37 col="4" value="<?=lobas_format($arr_fld[37][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="170" class="176" row=37 col="5" value="<?=lobas_format($arr_fld[37][5])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="176" row=37 col="6" value="<?=lobas_format($arr_fld[37][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">179<br>기타자본잉여금수입</td>
				
					<td align=center><input type="text" step="3" parent="170" class="179" row=38 col="1" value="<?=lobas_format($arr_fld[38][1])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="179" row=38 col="2" value="<?=lobas_format($arr_fld[38][2])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="179" row=38 col="3" value="<?=lobas_format($arr_fld[38][3])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="179" row=38 col="4" value="<?=lobas_format($arr_fld[38][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="170" class="179" row=38 col="5" value="<?=lobas_format($arr_fld[38][5])?>"></td>
					<td align=center><input type="text" step="3" parent="170" class="179" row=38 col="6" value="<?=lobas_format($arr_fld[38][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">180<br>유보자금</td>
				
					<td align=center><input type="text" step="2" class="180" none col="1"></td>
					<td align=center><input type="text" step="2" class="180" none col="2"></td>
					<td align=center><input type="text" step="2" class="180" none col="3"></td>
					<td align=center><input type="text" step="2" class="180" none col="4"></td>
					<td align=center><input type="text" step="2" class="180" none col="5"></td>
					<td align=center><input type="text" step="2" class="180" none col="6"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">181<br>순세계잉여금</td>
				
					<td align=center><input type="text" step="3" parent="180" class="181" col="1" none></td>
					<td align=center><input type="text" step="3" parent="180" class="181" col="2" none></td>
					<td align=center><input type="text" step="3" parent="180" class="181" col="3" none></td>
					<td align=center><input type="text" step="3" parent="180" class="181" col="4" none></td>
					<td align=center><input type="text" step="3" parent="180" class="181" col="5" none></td>
					<td align=center><input type="text" step="3" parent="180" class="181" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>순세계잉여금</td>
				
					<td align=center><input type="text" step="4" parent="180" item="181" row=39 col="1" value="<?=lobas_format($arr_fld[39][1])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="181" row=39 col="2" value="<?=lobas_format($arr_fld[39][2])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="181" row=39 col="3" value="<?=lobas_format($arr_fld[39][3])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="181" row=39 col="4" value="<?=lobas_format($arr_fld[39][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="180" item="181" row=39 col="5" value="<?=lobas_format($arr_fld[39][5])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="181" row=39 col="6" value="<?=lobas_format($arr_fld[39][6])?>" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">182<br>미수금</td>
				
					<td align=center><input type="text" step="3" parent="180" class="182" col="1" none></td>
					<td align=center><input type="text" step="3" parent="180" class="182" col="2" none></td>
					<td align=center><input type="text" step="3" parent="180" class="182" col="3" none></td>
					<td align=center><input type="text" step="3" parent="180" class="182" col="4" none></td>
					<td align=center><input type="text" step="3" parent="180" class="182" col="5" none></td>
					<td align=center><input type="text" step="3" parent="180" class="182" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>영업미수금</td>
				
					<td align=center><input type="text" step="4" parent="180" item="182" row=40 col="1" value="<?=lobas_format($arr_fld[40][1])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=40 col="2" value="<?=lobas_format($arr_fld[40][2])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=40 col="3" value="<?=lobas_format($arr_fld[40][3])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=40 col="4" value="<?=lobas_format($arr_fld[40][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=40 col="5" value="<?=lobas_format($arr_fld[40][5])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=40 col="6" value="<?=lobas_format($arr_fld[40][6])?>" none></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">02<br>기타미수금</td>																				  
																																		  
					<td align=center><input type="text" step="4" parent="180" item="182" row=41 col="1" value="<?=lobas_format($arr_fld[41][1])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=41 col="2" value="<?=lobas_format($arr_fld[41][2])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=41 col="3" value="<?=lobas_format($arr_fld[41][3])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=41 col="4" value="<?=lobas_format($arr_fld[41][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=41 col="5" value="<?=lobas_format($arr_fld[41][5])?>"></td>
					<td align=center><input type="text" step="4" parent="180" item="182" row=41 col="6" value="<?=lobas_format($arr_fld[41][6])?>" none></td>
				</tr>

				<!-- -->
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">190<br>기타자본적수입</td>
				
					<td align=center><input type="text" step="2" class="190" col="1" none></td>
					<td align=center><input type="text" step="2" class="190" col="2" none></td>
					<td align=center><input type="text" step="2" class="190" col="3" none></td>
					<td align=center><input type="text" step="2" class="190" col="4" none></td>
					<td align=center><input type="text" step="2" class="190" col="5" none></td>
					<td align=center><input type="text" step="2" class="190" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">191<br>기타자본적수입</td>
				
					<td align=center><input type="text" step="3" parent="190" class="191" col="1" none></td>
					<td align=center><input type="text" step="3" parent="190" class="191" col="2" none></td>
					<td align=center><input type="text" step="3" parent="190" class="191" col="3" none></td>
					<td align=center><input type="text" step="3" parent="190" class="191" col="4" none></td>
					<td align=center><input type="text" step="3" parent="190" class="191" col="5" none></td>
					<td align=center><input type="text" step="3" parent="190" class="191" col="6" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09<br>기타자본적수입</td>
				
					<td align=center><input type="text" step="4" parent="190" item="191" row=42 col="1" value="<?=lobas_format($arr_fld[42][1])?>"></td>
					<td align=center><input type="text" step="4" parent="190" item="191" row=42 col="2" value="<?=lobas_format($arr_fld[42][2])?>"></td>
					<td align=center><input type="text" step="4" parent="190" item="191" row=42 col="3" value="<?=lobas_format($arr_fld[42][3])?>"></td>
					<td align=center><input type="text" step="4" parent="190" item="191" row=42 col="4" value="<?=lobas_format($arr_fld[42][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="190" item="191" row=42 col="5" value="<?=lobas_format($arr_fld[42][5])?>"></td>
					<td align=center><input type="text" step="4" parent="190" item="191" row=42 col="6" value="<?=lobas_format($arr_fld[42][6])?>" none></td>
				</tr>
			</table>
		</td>
	</tr>
</table>