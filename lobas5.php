<?
	if (empty($yy)) $yy = date("Y")-1;

	$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=5";
	$row = T_select($sql);

	$arr_fld = array();

	for ($i=0;$i<count($row);$i++) {
		for ($j=1;$j<=7;$j++) {
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
				//moveInput(row,col);

				$.get("lobasExe.php",{"yy" : yy,"row" : row,"col" : col,"kap" : val,"cate" : "5","fldsu" : "7"},function(json){
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

			for (let i=1;i<=7 ;i++) {
				$("input[col='"+i+"']").each(function(){
					if (i!=3 && i!=4) {
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
			step 5 -> key (item);
			step 4 -> key (class); 
		*/

		if (step=="5") {
			return "item";
		} else {
			return "class";
		}
	}

	function moveInput(row,col) {
		let next = 0;
		
		if (col=="1" || col=="5" || col=="6") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").select();
		} else if (col=="2") {
			next = parseInt(col)+1;
			$("input[col='5'][row='"+row+"']").select();
		} else if (col=="7") {
			next = parseInt(row)+1;
			$("input[col='1'][row='"+next+"']").select();

			const toBeLoc = document.querySelector(`input[col='1'][row="${next}"]`);
			toBeLoc.scrollIntoView({ block: 'center' });
		}
	}

	function compute(step,row,col) {
		let key = "";
		let kap = [];
		let pay = "";
		let tot = 0;
		let tmp = "";
		let imsi = "";
		let parent = "";

		parent = $("input[row='"+row+"'][col='"+col+"']").attr("parent");
		key = $("input[row='"+row+"'][col='"+col+"']").attr(convStep(step));
		
		selector = "input["+convStep(step)+"='"+key+"']";
		
		for (i=1;i<=7;i++) {
			kap[i] = 0;

			if (i!=3 && i!=4) {
				if ($(selector+"[row='"+row+"'][col='"+i+"']").val()!="") {
					pay = $(selector+"[row='"+row+"'][col='"+i+"']").val().replaceAll(",","");
					
					if (isNaN(pay)) pay = 0;
				
					kap[i] = parseInt(pay);
				}
			}
		}

		/* 가로계산 */
		kap[3] = kap[1]-kap[2];			// 미지급비용(C-D)	
		kap[4] = kap[5]+kap[6]+kap[7];	// 계(E)
		
		if (col=="1" || col=="2") {
			$(selector+"[row='"+row+"'][col='3']").val(addComma(kap[3]));
		}

		if (col>="5") {
			$(selector+"[row='"+row+"'][col='4']").val(addComma(kap[4]));
		}

		/* 세로계산 */
		if (col=="1" || col=="2") {
			tmp = ("3,"+col).split(",");	// 입력되는 부분과 자동합계부분
		} else {
			tmp = ("4,"+col).split(",");	// 입력되는 부분과 자동합계부분
		}

		if (step=="5") {
			for (i=0;i<tmp.length ;i++) {
				tot = 0;

				$(selector+"[col='"+tmp[i]+"']").each(function(i){
					pay = parseInt($(this).val().replaceAll(",",""));
					if (isNaN(pay)) pay = 0;
					
					tot += pay;
				});

				/* step4 합 */
				$("input[class='"+key+"'][col='"+tmp[i]+"']").val(addComma(tot));
				
				/* step3 합 */
				parent = $("input[class='"+key+"'][col='"+tmp[i]+"']").attr("parent");
				
				$("input[class='"+parent+"'][col='"+tmp[i]+"']").val(addComma(tot));

				parent = $("input[class='"+parent+"'][col='"+tmp[i]+"']").attr("parent");
			}
		}
		
		if (step=="4") {
			for (i=0;i<tmp.length ;i++) {
				tot = 0;
				
				$("input[parent='"+parent+"'][col='"+tmp[i]+"']").each(function(i){
					pay = parseInt($(this).val().replaceAll(",",""));
					if (isNaN(pay)) pay = 0;
					
					tot += pay;
				});
				
				//alert(tot);

				/* step3 합 */
				$("input[class='"+parent+"'][col='"+tmp[i]+"']").val(addComma(tot));
			}

			parent = $("input[class='"+parent+"'][col='1']").attr("parent");
		}
		
		//alert(tmp.length);

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

			$("input[class='200'][col='"+tmp[i]+"']").val(addComma(tot));
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
			<span id="jTit">자본적 지출</span>
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
			<table width=1400 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="3%">
					<col width="3%">
					<col width="3%">
					<col width="3%">
					<col width="18%">

					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center colspan=5>과목</td>
					<td align=center rowspan=2>결산액<br>(채무확정액)<br>(C)</td>
					<td align=center rowspan=2>지출액<br>(D)</td>
					<td align=center rowspan=2>미지급비용<br>(C-D)</td>
					<td align=center colspan=4>익년도이월액</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td align=center>관</td>
					<td align=center>항</td>
					<td align=center>세항</td>
					<td align=center colspan=2>목</td>

					<td align=center>계(E)</td>
					<td align=center>건설계량이월</td>
					<td align=center>사고이월</td>
					<td align=center>계속비이월</td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td colspan=5 class="pd">200<br>자본적지출</td>
					<td align=center><input type="text" class="200" col=1 none></td>
					<td align=center><input type="text" class="200" col=2 none></td>
					<td align=center><input type="text" class="200" col=3 none></td>
					<td align=center><input type="text" class="200" col=4 none></td>
					<td align=center><input type="text" class="200" col=5 none></td>
					<td align=center><input type="text" class="200" col=6 none></td>
					<td align=center><input type="text" class="200" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">210<br>재고자산취득</td>
				
					<td align=center><input type="text" step="2" parent="200" class="210" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="210" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="210" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="210" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="210" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="210" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="210" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">211<br>용지조성사업비</td>
				
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=1 value="<?=lobas_format($arr_fld[1][1])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=2 value="<?=lobas_format($arr_fld[1][2])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=3 value="<?=lobas_format($arr_fld[1][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=4 value="<?=lobas_format($arr_fld[1][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=5 value="<?=lobas_format($arr_fld[1][5])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=6 value="<?=lobas_format($arr_fld[1][6])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="211" row=1 col=7 value="<?=lobas_format($arr_fld[1][7])?>"></td>
				</tr>																				  
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">212<br>미완성토지</td>
				
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=1 value="<?=lobas_format($arr_fld[2][1])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=2 value="<?=lobas_format($arr_fld[2][2])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=3 value="<?=lobas_format($arr_fld[2][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=4 value="<?=lobas_format($arr_fld[2][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=5 value="<?=lobas_format($arr_fld[2][5])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=6 value="<?=lobas_format($arr_fld[2][6])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="212" row=2 col=7 value="<?=lobas_format($arr_fld[2][7])?>"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">213<br>분양주택건설비</td>
				
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=1 value="<?=lobas_format($arr_fld[3][1])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=2 value="<?=lobas_format($arr_fld[3][2])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=3 value="<?=lobas_format($arr_fld[3][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=4 value="<?=lobas_format($arr_fld[3][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=5 value="<?=lobas_format($arr_fld[3][5])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=6 value="<?=lobas_format($arr_fld[3][6])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="213" row=3 col=7 value="<?=lobas_format($arr_fld[3][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">214<br>미완성주택</td>
				
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=1 value="<?=lobas_format($arr_fld[4][1])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=2 value="<?=lobas_format($arr_fld[4][2])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=3 value="<?=lobas_format($arr_fld[4][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=4 value="<?=lobas_format($arr_fld[4][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=5 value="<?=lobas_format($arr_fld[4][5])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=6 value="<?=lobas_format($arr_fld[4][6])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="214" row=4 col=7 value="<?=lobas_format($arr_fld[4][7])?>"></td>
				</tr>																												   
																																	   
				<tr bgcolor="FFFFFF" height="30">																					   
					<td align=center></td>																							   
					<td align=center></td>																							   
					<td colspan=3 class="pd">215<br>저장품</td>																		   
																																	   
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=1 value="<?=lobas_format($arr_fld[5][1])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=2 value="<?=lobas_format($arr_fld[5][2])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=3 value="<?=lobas_format($arr_fld[5][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=4 value="<?=lobas_format($arr_fld[5][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=5 value="<?=lobas_format($arr_fld[5][5])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=6 value="<?=lobas_format($arr_fld[5][6])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="215" row=5 col=7 value="<?=lobas_format($arr_fld[5][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">216<br>재고자산건설자금이자</td>
				
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=1 value="<?=lobas_format($arr_fld[6][1])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=2 value="<?=lobas_format($arr_fld[6][2])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=3 value="<?=lobas_format($arr_fld[6][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=4 value="<?=lobas_format($arr_fld[6][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=5 value="<?=lobas_format($arr_fld[6][5])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=6 value="<?=lobas_format($arr_fld[6][6])?>"></td>
					<td align=center><input type="text" step="3" parent="210" class="216" row=6 col=7 value="<?=lobas_format($arr_fld[6][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">220<br>투자자산취득</td>
				
					<td align=center><input type="text" step="2" parent="200" class="220" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="220" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="220" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="220" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="220" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="220" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="220" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">222<br>대여금</td>
				
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=1 value="<?=lobas_format($arr_fld[7][1])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=2 value="<?=lobas_format($arr_fld[7][2])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=3 value="<?=lobas_format($arr_fld[7][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=4 value="<?=lobas_format($arr_fld[7][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=5 value="<?=lobas_format($arr_fld[7][5])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=6 value="<?=lobas_format($arr_fld[7][6])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="222" row=7 col=7 value="<?=lobas_format($arr_fld[7][7])?>"></td>
				</tr>																												   
																																	   
				<tr bgcolor="FFFFFF" height="30">																					   
					<td align=center></td>																							   
					<td align=center></td>																							   
					<td colspan=3 class="pd">223<br>투자자산증권</td>																   
																																	   
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=1 value="<?=lobas_format($arr_fld[8][1])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=2 value="<?=lobas_format($arr_fld[8][2])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=3 value="<?=lobas_format($arr_fld[8][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=4 value="<?=lobas_format($arr_fld[8][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=5 value="<?=lobas_format($arr_fld[8][5])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=6 value="<?=lobas_format($arr_fld[8][6])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="223" row=8 col=7 value="<?=lobas_format($arr_fld[8][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">224<br>예탁금</td>
				
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=1 value="<?=lobas_format($arr_fld[9][1])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=2 value="<?=lobas_format($arr_fld[9][2])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=3 value="<?=lobas_format($arr_fld[9][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=4 value="<?=lobas_format($arr_fld[9][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=5 value="<?=lobas_format($arr_fld[9][5])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=6 value="<?=lobas_format($arr_fld[9][6])?>"></td>
					<td align=center><input type="text" step="3" parent="220" class="224" row=9 col=7 value="<?=lobas_format($arr_fld[9][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">229<br>기타투자자산</td>
				
					<td align=center><input type="text" step="3" parent="220" class="229" col=1 none></td>
					<td align=center><input type="text" step="3" parent="220" class="229" col=2 none></td>
					<td align=center><input type="text" step="3" parent="220" class="229" col=3 none></td>
					<td align=center><input type="text" step="3" parent="220" class="229" col=4 none></td>
					<td align=center><input type="text" step="3" parent="220" class="229" col=5 none></td>
					<td align=center><input type="text" step="3" parent="220" class="229" col=6 none></td>
					<td align=center><input type="text" step="3" parent="220" class="229" col=7 none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">511<br>퇴직연금적립금</td>
				
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=1 value="<?=lobas_format($arr_fld[10][1])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=2 value="<?=lobas_format($arr_fld[10][2])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=3 value="<?=lobas_format($arr_fld[10][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=4 value="<?=lobas_format($arr_fld[10][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=5 value="<?=lobas_format($arr_fld[10][5])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=6 value="<?=lobas_format($arr_fld[10][6])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="511" row=10 col=7 value="<?=lobas_format($arr_fld[10][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=2 class="pd">521<br>기타투자자산</td>																     
																																	     
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=1 value="<?=lobas_format($arr_fld[11][1])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=2 value="<?=lobas_format($arr_fld[11][2])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=3 value="<?=lobas_format($arr_fld[11][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=4 value="<?=lobas_format($arr_fld[11][4])?>" none></td>
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=5 value="<?=lobas_format($arr_fld[11][5])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=6 value="<?=lobas_format($arr_fld[11][6])?>"></td>
					<td align=center><input type="text" step="4" parent="229" class="521" row=11 col=7 value="<?=lobas_format($arr_fld[11][7])?>"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">230<br>유형자산취득</td>
				
					<td align=center><input type="text" step="2" parent="200" class="230" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="230" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="230" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="230" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="230" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="230" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="230" col=7 none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">231<br>토지</td>
				
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=1 value="<?=lobas_format($arr_fld[12][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=2 value="<?=lobas_format($arr_fld[12][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=3 value="<?=lobas_format($arr_fld[12][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=4 value="<?=lobas_format($arr_fld[12][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=5 value="<?=lobas_format($arr_fld[12][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=6 value="<?=lobas_format($arr_fld[12][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="231" row=12 col=7 value="<?=lobas_format($arr_fld[12][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">232<br>입목</td>																		     
																																	     
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=1 value="<?=lobas_format($arr_fld[13][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=2 value="<?=lobas_format($arr_fld[13][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=3 value="<?=lobas_format($arr_fld[13][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=4 value="<?=lobas_format($arr_fld[13][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=5 value="<?=lobas_format($arr_fld[13][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=6 value="<?=lobas_format($arr_fld[13][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="232" row=13 col=7 value="<?=lobas_format($arr_fld[13][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">233<br>건물</td>
				
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=1 value="<?=lobas_format($arr_fld[14][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=2 value="<?=lobas_format($arr_fld[14][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=3 value="<?=lobas_format($arr_fld[14][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=4 value="<?=lobas_format($arr_fld[14][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=5 value="<?=lobas_format($arr_fld[14][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=6 value="<?=lobas_format($arr_fld[14][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="233" row=14 col=7 value="<?=lobas_format($arr_fld[14][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">234<br>구축물</td>																		     
																																	     
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=1 value="<?=lobas_format($arr_fld[15][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=2 value="<?=lobas_format($arr_fld[15][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=3 value="<?=lobas_format($arr_fld[15][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=4 value="<?=lobas_format($arr_fld[15][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=5 value="<?=lobas_format($arr_fld[15][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=6 value="<?=lobas_format($arr_fld[15][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="234" row=15 col=7 value="<?=lobas_format($arr_fld[15][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">235<br>기계장치</td>
				
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=1 value="<?=lobas_format($arr_fld[16][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=2 value="<?=lobas_format($arr_fld[16][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=3 value="<?=lobas_format($arr_fld[16][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=4 value="<?=lobas_format($arr_fld[16][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=5 value="<?=lobas_format($arr_fld[16][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=6 value="<?=lobas_format($arr_fld[16][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="235" row=16 col=7 value="<?=lobas_format($arr_fld[16][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">236<br>차량운반구</td>																	     
																																	     
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=1 value="<?=lobas_format($arr_fld[17][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=2 value="<?=lobas_format($arr_fld[17][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=3 value="<?=lobas_format($arr_fld[17][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=4 value="<?=lobas_format($arr_fld[17][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=5 value="<?=lobas_format($arr_fld[17][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=6 value="<?=lobas_format($arr_fld[17][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="236" row=17 col=7 value="<?=lobas_format($arr_fld[17][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">237<br>공기구비품</td>
				
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=1 value="<?=lobas_format($arr_fld[18][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=2 value="<?=lobas_format($arr_fld[18][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=3 value="<?=lobas_format($arr_fld[18][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=4 value="<?=lobas_format($arr_fld[18][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=5 value="<?=lobas_format($arr_fld[18][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=6 value="<?=lobas_format($arr_fld[18][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="237" row=18 col=7 value="<?=lobas_format($arr_fld[18][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">238<br>기타유형자산</td>																     
																																	     
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=1 value="<?=lobas_format($arr_fld[19][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=2 value="<?=lobas_format($arr_fld[19][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=3 value="<?=lobas_format($arr_fld[19][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=4 value="<?=lobas_format($arr_fld[19][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=5 value="<?=lobas_format($arr_fld[19][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=6 value="<?=lobas_format($arr_fld[19][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="238" row=19 col=7 value="<?=lobas_format($arr_fld[19][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">239<br>건설중인자산</td>
				
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=1 value="<?=lobas_format($arr_fld[20][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=2 value="<?=lobas_format($arr_fld[20][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=3 value="<?=lobas_format($arr_fld[20][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=4 value="<?=lobas_format($arr_fld[20][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=5 value="<?=lobas_format($arr_fld[20][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=6 value="<?=lobas_format($arr_fld[20][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="239" row=20 col=7 value="<?=lobas_format($arr_fld[20][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">244<br>임대주택</td>
				
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=1 value="<?=lobas_format($arr_fld[21][1])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=2 value="<?=lobas_format($arr_fld[21][2])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=3 value="<?=lobas_format($arr_fld[21][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=4 value="<?=lobas_format($arr_fld[21][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=5 value="<?=lobas_format($arr_fld[21][5])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=6 value="<?=lobas_format($arr_fld[21][6])?>"></td>
					<td align=center><input type="text" step="3" parent="230" class="244" row=21 col=7 value="<?=lobas_format($arr_fld[21][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">250<br>무형자산및기타비유동자산취득</td>
				
					<td align=center><input type="text" step="2" parent="200" class="250" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="250" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="250" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="250" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="250" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="250" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="250" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">251<br>산업재산권등</td>
				
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=1 value="<?=lobas_format($arr_fld[22][1])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=2 value="<?=lobas_format($arr_fld[22][2])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=3 value="<?=lobas_format($arr_fld[22][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=4 value="<?=lobas_format($arr_fld[22][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=5 value="<?=lobas_format($arr_fld[22][5])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=6 value="<?=lobas_format($arr_fld[22][6])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="251" row=22 col=7 value="<?=lobas_format($arr_fld[22][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">252<br>영업권</td>																		     
																																	     
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=1 value="<?=lobas_format($arr_fld[23][1])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=2 value="<?=lobas_format($arr_fld[23][2])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=3 value="<?=lobas_format($arr_fld[23][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=4 value="<?=lobas_format($arr_fld[23][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=5 value="<?=lobas_format($arr_fld[23][5])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=6 value="<?=lobas_format($arr_fld[23][6])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="252" row=23 col=7 value="<?=lobas_format($arr_fld[23][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">253<br>소프트웨어</td>
				
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=1 value="<?=lobas_format($arr_fld[24][1])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=2 value="<?=lobas_format($arr_fld[24][2])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=3 value="<?=lobas_format($arr_fld[24][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=4 value="<?=lobas_format($arr_fld[24][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=5 value="<?=lobas_format($arr_fld[24][5])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=6 value="<?=lobas_format($arr_fld[24][6])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="253" row=24 col=7 value="<?=lobas_format($arr_fld[24][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">254<br>보증금</td>																		     
																																	     
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=1 value="<?=lobas_format($arr_fld[25][1])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=2 value="<?=lobas_format($arr_fld[25][2])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=3 value="<?=lobas_format($arr_fld[25][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=4 value="<?=lobas_format($arr_fld[25][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=5 value="<?=lobas_format($arr_fld[25][5])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=6 value="<?=lobas_format($arr_fld[25][6])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="254" row=25 col=7 value="<?=lobas_format($arr_fld[25][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">259<br>기타비유동자산</td>
				
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=1 value="<?=lobas_format($arr_fld[26][1])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=2 value="<?=lobas_format($arr_fld[26][2])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=3 value="<?=lobas_format($arr_fld[26][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=4 value="<?=lobas_format($arr_fld[26][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=5 value="<?=lobas_format($arr_fld[26][5])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=6 value="<?=lobas_format($arr_fld[26][6])?>"></td>
					<td align=center><input type="text" step="3" parent="250" class="259" row=26 col=7 value="<?=lobas_format($arr_fld[26][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">260<br>비가동설비자산취득</td>
				
					<td align=center><input type="text" step="2" parent="200" class="260" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="260" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="260" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="260" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="260" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="260" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="260" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">261<br>건설중인자산</td>
				
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=1 value="<?=lobas_format($arr_fld[27][1])?>"></td>
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=2 value="<?=lobas_format($arr_fld[27][2])?>"></td>
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=3 value="<?=lobas_format($arr_fld[27][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=4 value="<?=lobas_format($arr_fld[27][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=5 value="<?=lobas_format($arr_fld[27][5])?>"></td>
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=6 value="<?=lobas_format($arr_fld[27][6])?>"></td>
					<td align=center><input type="text" step="3" parent="260" class="261" row=27 col=7 value="<?=lobas_format($arr_fld[27][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">270<br>유동부채상환</td>
				
					<td align=center><input type="text" step="2" parent="200" class="270" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="270" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="270" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="270" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="270" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="270" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="270" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">271<br>선수금상환</td>
				
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=1 value="<?=lobas_format($arr_fld[28][1])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=2 value="<?=lobas_format($arr_fld[28][2])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=3 value="<?=lobas_format($arr_fld[28][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=4 value="<?=lobas_format($arr_fld[28][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=5 value="<?=lobas_format($arr_fld[28][5])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=6 value="<?=lobas_format($arr_fld[28][6])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="271" row=28 col=7 value="<?=lobas_format($arr_fld[28][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">272<br>단기차입금상환</td>																     
																																	     
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=1 value="<?=lobas_format($arr_fld[29][1])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=2 value="<?=lobas_format($arr_fld[29][2])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=3 value="<?=lobas_format($arr_fld[29][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=4 value="<?=lobas_format($arr_fld[29][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=5 value="<?=lobas_format($arr_fld[29][5])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=6 value="<?=lobas_format($arr_fld[29][6])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="272" row=29 col=7 value="<?=lobas_format($arr_fld[29][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">279<br>기타유동부채상환</td>
				
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=1 value="<?=lobas_format($arr_fld[30][1])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=2 value="<?=lobas_format($arr_fld[30][2])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=3 value="<?=lobas_format($arr_fld[30][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=4 value="<?=lobas_format($arr_fld[30][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=5 value="<?=lobas_format($arr_fld[30][5])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=6 value="<?=lobas_format($arr_fld[30][6])?>"></td>
					<td align=center><input type="text" step="3" parent="270" class="279" row=30 col=7 value="<?=lobas_format($arr_fld[30][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">280<br>비유동부채상환</td>
				
					<td align=center><input type="text" step="2" parent="200" class="280" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="280" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="280" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="280" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="280" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="280" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="280" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">282<br>정부차입금원금상환</td>
				
					<td align=center><input type="text" step="3" parent="280" class="282" col=1 none></td>
					<td align=center><input type="text" step="3" parent="280" class="282" col=2 none></td>
					<td align=center><input type="text" step="3" parent="280" class="282" col=3 none></td>
					<td align=center><input type="text" step="3" parent="280" class="282" col=4 none></td>
					<td align=center><input type="text" step="3" parent="280" class="282" col=5 none></td>
					<td align=center><input type="text" step="3" parent="280" class="282" col=6 none></td>
					<td align=center><input type="text" step="3" parent="280" class="282" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">601<br>차입금원금상환</td>
				
					<td align=center><input type="text" step="4" parent="282" class="601" col=1 none></td>
					<td align=center><input type="text" step="4" parent="282" class="601" col=2 none></td>
					<td align=center><input type="text" step="4" parent="282" class="601" col=3 none></td>
					<td align=center><input type="text" step="4" parent="282" class="601" col=4 none></td>
					<td align=center><input type="text" step="4" parent="282" class="601" col=5 none></td>
					<td align=center><input type="text" step="4" parent="282" class="601" col=6 none></td>
					<td align=center><input type="text" step="4" parent="282" class="601" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>시도지역개발기금차입금원금상환</td>
				
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=1 value="<?=lobas_format($arr_fld[31][1])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=2 value="<?=lobas_format($arr_fld[31][2])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=3 value="<?=lobas_format($arr_fld[31][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=4 value="<?=lobas_format($arr_fld[31][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=5 value="<?=lobas_format($arr_fld[31][5])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=6 value="<?=lobas_format($arr_fld[31][6])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=31 col=7 value="<?=lobas_format($arr_fld[31][7])?>"></td>
				</tr>																												    
																																	    
				<tr bgcolor="FFFFFF" height="30">																					    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td class="pd">02<br>시군구지역개발기금차입금원금상환</td>														    
																																	    
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=1 value="<?=lobas_format($arr_fld[32][1])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=2 value="<?=lobas_format($arr_fld[32][2])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=3 value="<?=lobas_format($arr_fld[32][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=4 value="<?=lobas_format($arr_fld[32][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=5 value="<?=lobas_format($arr_fld[32][5])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=6 value="<?=lobas_format($arr_fld[32][6])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=32 col=7 value="<?=lobas_format($arr_fld[32][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>중앙정부차입금원금상환</td>
				
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=1 value="<?=lobas_format($arr_fld[33][1])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=2 value="<?=lobas_format($arr_fld[33][2])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=3 value="<?=lobas_format($arr_fld[33][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=4 value="<?=lobas_format($arr_fld[33][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=5 value="<?=lobas_format($arr_fld[33][5])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=6 value="<?=lobas_format($arr_fld[33][6])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=33 col=7 value="<?=lobas_format($arr_fld[33][7])?>"></td>
				</tr>																												    
																																	    
				<tr bgcolor="FFFFFF" height="30">																					    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td class="pd">09<br>타회계차입금원금상환</td>																	    
																																	    
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=1 value="<?=lobas_format($arr_fld[34][1])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=2 value="<?=lobas_format($arr_fld[34][2])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=3 value="<?=lobas_format($arr_fld[34][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=4 value="<?=lobas_format($arr_fld[34][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=5 value="<?=lobas_format($arr_fld[34][5])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=6 value="<?=lobas_format($arr_fld[34][6])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=34 col=7 value="<?=lobas_format($arr_fld[34][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>자치단체차입금원금상환</td>
				
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=1 value="<?=lobas_format($arr_fld[35][1])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=2 value="<?=lobas_format($arr_fld[35][2])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=3 value="<?=lobas_format($arr_fld[35][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=4 value="<?=lobas_format($arr_fld[35][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=5 value="<?=lobas_format($arr_fld[35][5])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=6 value="<?=lobas_format($arr_fld[35][6])?>"></td>
					<td align=center><input type="text" step="5" parent="280" item="601" row=35 col=7 value="<?=lobas_format($arr_fld[35][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">283<br>금융기관차입금원금상환</td>
				
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=1 value="<?=lobas_format($arr_fld[36][1])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=2 value="<?=lobas_format($arr_fld[36][2])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=3 value="<?=lobas_format($arr_fld[36][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=4 value="<?=lobas_format($arr_fld[36][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=5 value="<?=lobas_format($arr_fld[36][5])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=6 value="<?=lobas_format($arr_fld[36][6])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="283" row=36 col=7 value="<?=lobas_format($arr_fld[36][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">284<br>공사공단채원금상환</td>
				
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=1 value="<?=lobas_format($arr_fld[37][1])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=2 value="<?=lobas_format($arr_fld[37][2])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=3 value="<?=lobas_format($arr_fld[37][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=4 value="<?=lobas_format($arr_fld[37][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=5 value="<?=lobas_format($arr_fld[37][5])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=6 value="<?=lobas_format($arr_fld[37][6])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="284" row=37 col=7 value="<?=lobas_format($arr_fld[37][7])?>"></td>
				</tr>																												     
																																	     
				<tr bgcolor="FFFFFF" height="30">																					     
					<td align=center></td>																							     
					<td align=center></td>																							     
					<td colspan=3 class="pd">285<br>국외차입금원금상환</td>															     
																																	     
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=1 value="<?=lobas_format($arr_fld[38][1])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=2 value="<?=lobas_format($arr_fld[38][2])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=3 value="<?=lobas_format($arr_fld[38][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=4 value="<?=lobas_format($arr_fld[38][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=5 value="<?=lobas_format($arr_fld[38][5])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=6 value="<?=lobas_format($arr_fld[38][6])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="285" row=38 col=7 value="<?=lobas_format($arr_fld[38][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">289<br>기타비유동부채상환</td>
				
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=1 value="<?=lobas_format($arr_fld[39][1])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=2 value="<?=lobas_format($arr_fld[39][2])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=3 value="<?=lobas_format($arr_fld[39][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=4 value="<?=lobas_format($arr_fld[39][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=5 value="<?=lobas_format($arr_fld[39][5])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=6 value="<?=lobas_format($arr_fld[39][6])?>"></td>
					<td align=center><input type="text" step="3" parent="280" class="289" row=39 col=7 value="<?=lobas_format($arr_fld[39][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">290<br>기타자본적지출</td>
				
					<td align=center><input type="text" step="2" parent="200" class="290" col=1 none></td>
					<td align=center><input type="text" step="2" parent="200" class="290" col=2 none></td>
					<td align=center><input type="text" step="2" parent="200" class="290" col=3 none></td>
					<td align=center><input type="text" step="2" parent="200" class="290" col=4 none></td>
					<td align=center><input type="text" step="2" parent="200" class="290" col=5 none></td>
					<td align=center><input type="text" step="2" parent="200" class="290" col=6 none></td>
					<td align=center><input type="text" step="2" parent="200" class="290" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">291<br>정부보조금 반환</td>
				
					<td align=center><input type="text" step="3" parent="290" class="291" col=1 none></td>
					<td align=center><input type="text" step="3" parent="290" class="291" col=2 none></td>
					<td align=center><input type="text" step="3" parent="290" class="291" col=3 none></td>
					<td align=center><input type="text" step="3" parent="290" class="291" col=4 none></td>
					<td align=center><input type="text" step="3" parent="290" class="291" col=5 none></td>
					<td align=center><input type="text" step="3" parent="290" class="291" col=6 none></td>
					<td align=center><input type="text" step="3" parent="290" class="291" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">751<br>반환금</td>
				
					<td align=center><input type="text" step="4" parent="291" class="751" col=1 none></td>
					<td align=center><input type="text" step="4" parent="291" class="751" col=2 none></td>
					<td align=center><input type="text" step="4" parent="291" class="751" col=3 none></td>
					<td align=center><input type="text" step="4" parent="291" class="751" col=4 none></td>
					<td align=center><input type="text" step="4" parent="291" class="751" col=5 none></td>
					<td align=center><input type="text" step="4" parent="291" class="751" col=6 none></td>
					<td align=center><input type="text" step="4" parent="291" class="751" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>국고보조금반환금</td>
				
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=1 value="<?=lobas_format($arr_fld[40][1])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=2 value="<?=lobas_format($arr_fld[40][2])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=3 value="<?=lobas_format($arr_fld[40][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=4 value="<?=lobas_format($arr_fld[40][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=5 value="<?=lobas_format($arr_fld[40][5])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=6 value="<?=lobas_format($arr_fld[40][6])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=40 col=7 value="<?=lobas_format($arr_fld[40][7])?>"></td>
				</tr>																												    
																																	    
				<tr bgcolor="FFFFFF" height="30">																					    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td class="pd">02<br>시도비보조금반환금</td>																	    
																																	    
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=1 value="<?=lobas_format($arr_fld[41][1])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=2 value="<?=lobas_format($arr_fld[41][2])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=3 value="<?=lobas_format($arr_fld[41][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=4 value="<?=lobas_format($arr_fld[41][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=5 value="<?=lobas_format($arr_fld[41][5])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=6 value="<?=lobas_format($arr_fld[41][6])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=41 col=7 value="<?=lobas_format($arr_fld[41][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>보조금반환금</td>
				
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=1 value="<?=lobas_format($arr_fld[42][1])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=2 value="<?=lobas_format($arr_fld[42][2])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=3 value="<?=lobas_format($arr_fld[42][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=4 value="<?=lobas_format($arr_fld[42][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=5 value="<?=lobas_format($arr_fld[42][5])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=6 value="<?=lobas_format($arr_fld[42][6])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=42 col=7 value="<?=lobas_format($arr_fld[42][7])?>"></td>
				</tr>																												    
																																	    
				<tr bgcolor="FFFFFF" height="30">																					    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td align=center></td>																							    
					<td class="pd">04<br>대행사업비반환금</td>																		    
																																	    
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=1 value="<?=lobas_format($arr_fld[43][1])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=2 value="<?=lobas_format($arr_fld[43][2])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=3 value="<?=lobas_format($arr_fld[43][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=4 value="<?=lobas_format($arr_fld[43][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=5 value="<?=lobas_format($arr_fld[43][5])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=6 value="<?=lobas_format($arr_fld[43][6])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=43 col=7 value="<?=lobas_format($arr_fld[43][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09<br>기타반환금</td>
				
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=1 value="<?=lobas_format($arr_fld[44][1])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=2 value="<?=lobas_format($arr_fld[44][2])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=3 value="<?=lobas_format($arr_fld[44][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=4 value="<?=lobas_format($arr_fld[44][4])?>" none></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=5 value="<?=lobas_format($arr_fld[44][5])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=6 value="<?=lobas_format($arr_fld[44][6])?>"></td>
					<td align=center><input type="text" step="5" parent="290" item="751" row=44 col=7 value="<?=lobas_format($arr_fld[44][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">292<br>배당금등</td>
				
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=1 value="<?=lobas_format($arr_fld[45][1])?>"></td>
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=2 value="<?=lobas_format($arr_fld[45][2])?>"></td>
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=3 value="<?=lobas_format($arr_fld[45][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=4 value="<?=lobas_format($arr_fld[45][4])?>" none></td>
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=5 value="<?=lobas_format($arr_fld[45][5])?>"></td>
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=6 value="<?=lobas_format($arr_fld[45][6])?>"></td>
					<td align=center><input type="text" step="3" parent="290" class="292" row=45 col=7 value="<?=lobas_format($arr_fld[45][7])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">299<br>기타자본적지출</td>
				
					<td align=center><input type="text" step="3" parent="290" class="299" col=1 none></td>
					<td align=center><input type="text" step="3" parent="290" class="299" col=2 none></td>
					<td align=center><input type="text" step="3" parent="290" class="299" col=3 none></td>
					<td align=center><input type="text" step="3" parent="290" class="299" col=4 none></td>
					<td align=center><input type="text" step="3" parent="290" class="299" col=5 none></td>
					<td align=center><input type="text" step="3" parent="290" class="299" col=6 none></td>
					<td align=center><input type="text" step="3" parent="290" class="299" col=7 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">800<br>예비비</td>
				
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=1 value="<?=lobas_format($arr_fld[46][1])?>"></td>
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=2 value="<?=lobas_format($arr_fld[46][2])?>"></td>
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=3 value="<?=lobas_format($arr_fld[46][3])?>" none></td>
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=4 value="<?=lobas_format($arr_fld[46][4])?>" none></td>
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=5 value="<?=lobas_format($arr_fld[46][5])?>"></td>
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=6 value="<?=lobas_format($arr_fld[46][6])?>"></td>
					<td align=center><input type="text" step="2" parent="200" class="800" row=46 col=7 value="<?=lobas_format($arr_fld[46][7])?>"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>