<?
	if (empty($yy)) $yy = date("Y")-1;

	$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=3";
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
		text-align:center;
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
			let parent = $(this).attr("parent");

			let val = $(this).val().replace(/[^0-9]/g,"");
			let yy = $("select[name='yy']").val();

			$(this).val(addComma(val));
			
			if (event.keyCode==13) {
				$.get("lobasExe.php",{"yy" : yy,"row" : row,"col" : col,"kap" : val,"cate" : "3","fldsu" : "4"},function(json){
					if(json != null) {
						$(this).val(addComma(val));
						moveInput(row,col);
					} 
				});
			}

			compute(step,parent,row,col);
		});

		/* 초기 계산 */
		{		
			let col = "0";
			let row = "0";
			let step = "0";
			let parent = "0";

			for (let i=1;i<=4 ;i++) {
				$("input[col='"+i+"']").each(function(){
					if (i!=3) {
						if ($(this).val()!="") {
							col = i;
							row = $(this).attr("row");
							step = $(this).attr("step");
							parent = $(this).attr("parent");
							
							compute(step,parent,row,col);
						}
					}
				});
			}
		}
		
		/*
		$("input[step='4']").each(function(){
			let row = $(this).attr("row");
			let cls = $(this).attr("class");
			
			if(typeof cls == "undefined"){
				alert(row);
			}
		});
		*/
	});

	function compute(step,parent,row,col) {
		let kap = [];
		let pay = 0;

		for (i=1;i<=4;i++) {
			kap[i] = 0;
			
			if (i!=3) {
				if ($("input[row='"+row+"'][col='"+i+"']").val()!="") {
					pay = $("input[row='"+row+"'][col='"+i+"']").val().replaceAll(",","");
					if (isNaN(pay)) pay = 0;
					kap[i] = parseInt(pay);
				}
			}
		}

		if (col=="1" || col=="2") {
			kap[3] = kap[1]-kap[2];
			$("input[row='"+row+"'][col='3']").val(addComma(kap[3]));
		}
		
		verticalComp(step,parent,row,col);
	}

	function verticalComp(step,parent,row,col) {
		let pay = 0;
		let tot = 0;
		let key = "";
		let tmp = "";
		
		if (col=="4") {
			tmp = String(col).split(",");
		} else {
			tmp = (col+",3").split(",");
		}
		
		if (step=="5") {
			key = $("input[row='"+row+"'][col='"+col+"']").attr("item");
			
			for (i=0;i<tmp.length ;i++) {
				tot = 0;
				$("input[parent='"+parent+"'][item='"+key+"'][col='"+tmp[i]+"']").each(function(i){
					pay = parseInt($(this).val().replaceAll(",",""));
					if (isNaN(pay)) pay = 0;
					tot += pay;
				});

				$("input[parent='"+parent+"'][class='"+key+"'][col='"+tmp[i]+"']").val(addComma(tot));
			}
		}

		if (step=="3") {
			for (i=0;i<tmp.length ;i++) {
				tot = 0;
				$("input[step='3'][parent='"+parent+"'][col='"+tmp[i]+"']").each(function(){
					pay = parseInt($(this).val().replaceAll(",",""));
					if (isNaN(pay)) pay = 0;
					tot += pay;
				});
				
				$("input[class='"+parent+"'][col='"+tmp[i]+"']").val(addComma(tot));
			}
		}
		
		for (i=0;i<tmp.length ;i++) {
			tot = 0;
			
			$("input[step='2'][col='"+tmp[i]+"']").each(function(){
				pay = parseInt($(this).val().replaceAll(",",""));
				if (isNaN(pay)) pay = 0;
				tot += pay;
			});
			
			$("input[class='700'][col='"+tmp[i]+"']").val(addComma(tot));
		}
	}
	
	function moveInput(row,col) {
		let next = 0;
		
		if (col=="1") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").select();
		} else if (col=="2") {
			$("input[col='4'][row='"+row+"']").select();
		} else if (col=="4") {
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
			<span id="jTit">수익적 지출</span>
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
						1. 본 화면은 당기의 로바스 출력자료 중 사업예산 결산보고서-수익적지출의 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  사업예산결산보고서-수익적 수입 자료를 엑셀파일로 다운받은 후 해당 계정과목의 금액을 입력(복사/붙여넣기 )합니다.<br>
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
			<table width=1100 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD id="tbl">
				<colgroup>
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="8%">
					<col width="21%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center colspan=5>과목</td>
					<td align=center rowspan=2>결산액<br>(채무확정액)<br>(C)</td>
					<td align=center rowspan=2>지출액<br>(D)</td>
					<td align=center rowspan=2>미지급금<br>(C-D)</td>
					<td align=center rowspan=2>익년도사고이월액<br>(E)</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td align=center>관</td>
					<td align=center>항</td>
					<td align=center>세항</td>
					<td align=center colspan=2>목</td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td colspan=5 class="pd">700<br>사업비용</td>
					<td align=center><input type="text" class="700" col="1" none></td>
					<td align=center><input type="text" class="700" col="2" none></td>
					<td align=center><input type="text" class="700" col="3" none></td>
					<td align=center><input type="text" class="700" col="4" none></td>
				</tr>
				<!-- 710 -->
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">710<br>영업비용</td>
				
					<td align=center><input type="text" step=2 parent=700 class=710 col=1 none></td>
					<td align=center><input type="text" step=2 parent=700 class=710 col=2 none></td>
					<td align=center><input type="text" step=2 parent=700 class=710 col=3 none></td>
					<td align=center><input type="text" step=2 parent=700 class=710 col=4 none></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">711<br>원수및취수비</td>
				
					<td align=center><input type="text" step=3 parent=710 class=711 row=1 col=1 value="<?=lobas_format($arr_fld[1][1])?>"></td>
					<td align=center><input type="text" step=3 parent=710 class=711 row=1 col=2 value="<?=lobas_format($arr_fld[1][2])?>"></td>
					<td align=center><input type="text" step=3 parent=710 class=711 row=1 col=3 value="<?=lobas_format($arr_fld[1][3])?>" none></td>
					<td align=center><input type="text" step=3 parent=710 class=711 row=1 col=4 value="<?=lobas_format($arr_fld[1][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>민간이전</td>
				
					<td align=center><input type="text" step=4 parent=711 class=307 col=1 none></td>
					<td align=center><input type="text" step=4 parent=711 class=307 col=2 none></td>
					<td align=center><input type="text" step=4 parent=711 class=307 col=3 none></td>
					<td align=center><input type="text" step=4 parent=711 class=307 col=4 none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>민간경상사업보조</td>
				
					<td align=center><input type="text" step="5" parent="711" item="307" row="2" col="1" value="<?=lobas_format($arr_fld[2][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="2" col="2" value="<?=lobas_format($arr_fld[2][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="2" col="3" value="<?=lobas_format($arr_fld[2][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="2" col="4" value="<?=lobas_format($arr_fld[2][4])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>민간단체법정운영비보조</td>
				
					<td align=center><input type="text" step="5" parent="711" item="307" row="3" col="1" value="<?=lobas_format($arr_fld[3][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="3" col="2" value="<?=lobas_format($arr_fld[3][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="3" col="3" value="<?=lobas_format($arr_fld[3][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="3" col="4" value="<?=lobas_format($arr_fld[3][4])?>"></td>
				</tr>																													  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">04<br>민간행사사업보조</td>																			  
																																		  
					<td align=center><input type="text" step="5" parent="711" item="307" row="4" col="1" value="<?=lobas_format($arr_fld[4][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="4" col="2" value="<?=lobas_format($arr_fld[4][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="4" col="3" value="<?=lobas_format($arr_fld[4][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="4" col="4" value="<?=lobas_format($arr_fld[4][4])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>민간위탁금</td>
				
					<td align=center><input type="text" step="5" parent="711" item="307" row="5" col="1" value="<?=lobas_format($arr_fld[5][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="5" col="2" value="<?=lobas_format($arr_fld[5][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="5" col="3" value="<?=lobas_format($arr_fld[5][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="5" col="4" value="<?=lobas_format($arr_fld[5][4])?>"></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">07<br>연금지급금</td>																				  
																																		  
					<td align=center><input type="text" step="5" parent="711" item="307" row="6" col="1" value="<?=lobas_format($arr_fld[6][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="6" col="2" value="<?=lobas_format($arr_fld[6][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="6" col="3" value="<?=lobas_format($arr_fld[6][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="6" col="4" value="<?=lobas_format($arr_fld[6][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">08<br>이차보전금</td>
				
					<td align=center><input type="text" step="5" parent="711" item="307" row="7" col="1" value="<?=lobas_format($arr_fld[7][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="7" col="2" value="<?=lobas_format($arr_fld[7][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="7" col="3" value="<?=lobas_format($arr_fld[7][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="7" col="4" value="<?=lobas_format($arr_fld[7][4])?>"></td>
				</tr>																													  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">13<br>민간검침활동지원비</td>																		  
																																		  
					<td align=center><input type="text" step="5" parent="711" item="307" row="8" col="1" value="<?=lobas_format($arr_fld[8][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="8" col="2" value="<?=lobas_format($arr_fld[8][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="8" col="3" value="<?=lobas_format($arr_fld[8][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="307" row="8" col="4" value="<?=lobas_format($arr_fld[8][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>308<br>자치단체등이전</td>
				
					<td align=center><input type="text" step="4" parent="711" class="308" row="9" col="1" value="<?=lobas_format($arr_fld[9][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="308" row="9" col="2" value="<?=lobas_format($arr_fld[9][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="308" row="9" col="3" value="<?=lobas_format($arr_fld[9][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="308" row="9" col="4" value="<?=lobas_format($arr_fld[9][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>309<br>전출금</td>
				
					<td align=center><input type="text" step="4" parent="711" class="309" row="10" col="1" value="<?=lobas_format($arr_fld[10][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="309" row="10" col="2" value="<?=lobas_format($arr_fld[10][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="309" row="10" col="3" value="<?=lobas_format($arr_fld[10][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="309" row="10" col="4" value="<?=lobas_format($arr_fld[10][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>310<br>국외이전</td>
				
					<td align=center><input type="text" step="4" parent="711" class="310" row="11" col="1" value="<?=lobas_format($arr_fld[11][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="310" row="11" col="2" value="<?=lobas_format($arr_fld[11][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="310" row="11" col="3" value="<?=lobas_format($arr_fld[11][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="310" row="11" col="4" value="<?=lobas_format($arr_fld[11][4])?>"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>311<br>차입금이자상환</td>
				
					<td align=center><input type="text" step="4" parent="711" class="311" col="1" none></td>
					<td align=center><input type="text" step="4" parent="711" class="311" col="2" none></td>
					<td align=center><input type="text" step="4" parent="711" class="311" col="3" none></td>
					<td align=center><input type="text" step="4" parent="711" class="311" col="4" none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>시도지역개발기금차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="711" item="311" row="12" col="1" value="<?=lobas_format($arr_fld[12][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="12" col="2" value="<?=lobas_format($arr_fld[12][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="12" col="3" value="<?=lobas_format($arr_fld[12][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="12" col="4" value="<?=lobas_format($arr_fld[12][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">02<br>시군구지역개발기금차입금이자상환</td>															    
																																		    
					<td align=center><input type="text" step="5" parent="711" item="311" row="13" col="1" value="<?=lobas_format($arr_fld[13][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="13" col="2" value="<?=lobas_format($arr_fld[13][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="13" col="3" value="<?=lobas_format($arr_fld[13][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="13" col="4" value="<?=lobas_format($arr_fld[13][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>통화금융기관차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="711" item="311" row="14" col="1" value="<?=lobas_format($arr_fld[14][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="14" col="2" value="<?=lobas_format($arr_fld[14][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="14" col="3" value="<?=lobas_format($arr_fld[14][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="14" col="4" value="<?=lobas_format($arr_fld[14][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">04<br>중앙정부차입금이자상환</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="711" item="311" row="15" col="1" value="<?=lobas_format($arr_fld[15][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="15" col="2" value="<?=lobas_format($arr_fld[15][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="15" col="3" value="<?=lobas_format($arr_fld[15][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="15" col="4" value="<?=lobas_format($arr_fld[15][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06<br>기타차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="711" item="311" row="16" col="1" value="<?=lobas_format($arr_fld[16][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="16" col="2" value="<?=lobas_format($arr_fld[16][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="16" col="3" value="<?=lobas_format($arr_fld[16][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="16" col="4" value="<?=lobas_format($arr_fld[16][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">10<br>타회계차입금이자상환</td>																		    
																																		    
					<td align=center><input type="text" step="5" parent="711" item="311" row="17" col="1" value="<?=lobas_format($arr_fld[17][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="17" col="2" value="<?=lobas_format($arr_fld[17][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="17" col="3" value="<?=lobas_format($arr_fld[17][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="17" col="4" value="<?=lobas_format($arr_fld[17][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>기업채취급제비</td>
				
					<td align=center><input type="text" step="5" parent="711" item="311" row="18" col="1" value="<?=lobas_format($arr_fld[18][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="18" col="2" value="<?=lobas_format($arr_fld[18][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="18" col="3" value="<?=lobas_format($arr_fld[18][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="18" col="4" value="<?=lobas_format($arr_fld[18][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">12<br>자치단체차입금이자상환</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="711" item="311" row="19" col="1" value="<?=lobas_format($arr_fld[19][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="19" col="2" value="<?=lobas_format($arr_fld[19][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="19" col="3" value="<?=lobas_format($arr_fld[19][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="19" col="4" value="<?=lobas_format($arr_fld[19][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>공사공단채이자상환</td>
				
					<td align=center><input type="text" step="5" parent="711" item="311" row="20" col="1" value="<?=lobas_format($arr_fld[20][1])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="20" col="2" value="<?=lobas_format($arr_fld[20][2])?>"></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="20" col="3" value="<?=lobas_format($arr_fld[20][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="711" item="311" row="20" col="4" value="<?=lobas_format($arr_fld[20][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>공기관등경상적대행사업비</td>
				
					<td align=center><input type="text" step="4" parent="711" class="316" row="21" col="1" value="<?=lobas_format($arr_fld[21][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="316" row="21" col="2" value="<?=lobas_format($arr_fld[21][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="316" row="21" col="3" value="<?=lobas_format($arr_fld[21][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="316" row="21" col="4" value="<?=lobas_format($arr_fld[21][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>317<br>공기업특별회계간부담금</td>
				
					<td align=center><input type="text" step="4" parent="711" class="317" row="22" col="1" value="<?=lobas_format($arr_fld[22][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="317" row="22" col="2" value="<?=lobas_format($arr_fld[22][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="317" row="22" col="3" value="<?=lobas_format($arr_fld[22][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="317" row="22" col="4" value="<?=lobas_format($arr_fld[22][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td class="pd" colspan=2>318<br>기부금</td>																			     
																																		     
					<td align=center><input type="text" step="4" parent="711" class="318" row="23" col="1" value="<?=lobas_format($arr_fld[23][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="318" row="23" col="2" value="<?=lobas_format($arr_fld[23][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="318" row="23" col="3" value="<?=lobas_format($arr_fld[23][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="318" row="23" col="4" value="<?=lobas_format($arr_fld[23][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>319<br>지방공기업최고경영자협의체부담금</td>
				
					<td align=center><input type="text" step="4" parent="711" class="319" row="24" col="1" value="<?=lobas_format($arr_fld[24][1])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="319" row="24" col="2" value="<?=lobas_format($arr_fld[24][2])?>"></td>
					<td align=center><input type="text" step="4" parent="711" class="319" row="24" col="3" value="<?=lobas_format($arr_fld[24][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="711" class="319" row="24" col="4" value="<?=lobas_format($arr_fld[24][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">712<br>정수비</td>
				
					<td align=center><input type="text" step=3 parent=710 class=712 row="25" col="1" value="<?=lobas_format($arr_fld[25][1])?>"></td>
					<td align=center><input type="text" step=3 parent=710 class=712 row="25" col="2" value="<?=lobas_format($arr_fld[25][2])?>"></td>
					<td align=center><input type="text" step=3 parent=710 class=712 row="25" col="3" value="<?=lobas_format($arr_fld[25][3])?>" none></td>
					<td align=center><input type="text" step=3 parent=710 class=712 row="25" col="4" value="<?=lobas_format($arr_fld[25][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>민간이전</td>
				
					<td align=center><input type="text" step=4 parent=712 class=307 col="1" none></td>
					<td align=center><input type="text" step=4 parent=712 class=307 col="2" none></td>
					<td align=center><input type="text" step=4 parent=712 class=307 col="3" none></td>
					<td align=center><input type="text" step=4 parent=712 class=307 col="4" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>민간경상사업보조</td>
				
					<td align=center><input type="text" step=5 parent="712" item="307" row="26" col="1" value="<?=lobas_format($arr_fld[26][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="26" col="2" value="<?=lobas_format($arr_fld[26][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="26" col="3" value="<?=lobas_format($arr_fld[26][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="26" col="4" value="<?=lobas_format($arr_fld[26][4])?>"></td>
				</tr>																														
				<tr bgcolor="FFFFFF" height="30">																							
					<td align=center></td>																									
					<td align=center></td>																									
					<td align=center></td>																									
					<td align=center></td>																									
					<td class="pd">03<br>민간단체법정운영비보조</td>																		
																																			
					<td align=center><input type="text" step=5 parent="712" item="307" row="27" col="1" value="<?=lobas_format($arr_fld[27][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="27" col="2" value="<?=lobas_format($arr_fld[27][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="27" col="3" value="<?=lobas_format($arr_fld[27][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="27" col="4" value="<?=lobas_format($arr_fld[27][4])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>민간행사사업보조</td>
				
					<td align=center><input type="text" step=5 parent="712" item="307" row="28" col="1" value="<?=lobas_format($arr_fld[28][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="28" col="2" value="<?=lobas_format($arr_fld[28][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="28" col="3" value="<?=lobas_format($arr_fld[28][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="28" col="4" value="<?=lobas_format($arr_fld[28][4])?>"></td>
				</tr>																													  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">05<br>민간위탁금</td>																				  
																																		  
					<td align=center><input type="text" step=5 parent="712" item="307" row="29" col="1" value="<?=lobas_format($arr_fld[29][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="29" col="2" value="<?=lobas_format($arr_fld[29][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="29" col="3" value="<?=lobas_format($arr_fld[29][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="29" col="4" value="<?=lobas_format($arr_fld[29][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">07<br>연금지급금</td>
				
					<td align=center><input type="text" step=5 parent="712" item="307" row="30" col="1" value="<?=lobas_format($arr_fld[30][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="30" col="2" value="<?=lobas_format($arr_fld[30][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="30" col="3" value="<?=lobas_format($arr_fld[30][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="30" col="4" value="<?=lobas_format($arr_fld[30][4])?>"></td>
				</tr>																													  
																																		  
				<tr bgcolor="FFFFFF" height="30">																						  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td align=center></td>																								  
					<td class="pd">08<br>이차보전금</td>																				  
																																		  
					<td align=center><input type="text" step=5 parent="712" item="307" row="31" col="1" value="<?=lobas_format($arr_fld[31][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="31" col="2" value="<?=lobas_format($arr_fld[31][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="31" col="3" value="<?=lobas_format($arr_fld[31][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="31" col="4" value="<?=lobas_format($arr_fld[31][4])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>민간검침활동지원비</td>
				
					<td align=center><input type="text" step=5 parent="712" item="307" row="32" col="1" value="<?=lobas_format($arr_fld[32][1])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="32" col="2" value="<?=lobas_format($arr_fld[32][2])?>"></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="32" col="3" value="<?=lobas_format($arr_fld[32][3])?>" none></td>
					<td align=center><input type="text" step=5 parent="712" item="307" row="32" col="4" value="<?=lobas_format($arr_fld[32][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>311<br>차입금이자상환</td>
				
					<td align=center><input step="4" parent="712" class="311" col="1" none></td>
					<td align=center><input step="4" parent="712" class="311" col="2" none></td>
					<td align=center><input step="4" parent="712" class="311" col="3" none></td>
					<td align=center><input step="4" parent="712" class="311" col="4" none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>시도지역개발기금차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="712" item="311" row="33" col="1" value="<?=lobas_format($arr_fld[33][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="33" col="2" value="<?=lobas_format($arr_fld[33][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="33" col="3" value="<?=lobas_format($arr_fld[33][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="33" col="4" value="<?=lobas_format($arr_fld[33][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">02<br>시군구지역개발기금차입금이자상환</td>															    
																																		    
					<td align=center><input type="text" step="5" parent="712" item="311" row="34" col="1" value="<?=lobas_format($arr_fld[34][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="34" col="2" value="<?=lobas_format($arr_fld[34][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="34" col="3" value="<?=lobas_format($arr_fld[34][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="34" col="4" value="<?=lobas_format($arr_fld[34][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>통화금융기관차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="712" item="311" row="35" col="1" value="<?=lobas_format($arr_fld[35][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="35" col="2" value="<?=lobas_format($arr_fld[35][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="35" col="3" value="<?=lobas_format($arr_fld[35][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="35" col="4" value="<?=lobas_format($arr_fld[35][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">04<br>중앙정부차입금이자상환</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="712" item="311" row="36" col="1" value="<?=lobas_format($arr_fld[36][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="36" col="2" value="<?=lobas_format($arr_fld[36][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="36" col="3" value="<?=lobas_format($arr_fld[36][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="36" col="4" value="<?=lobas_format($arr_fld[36][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06<br>기타차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="712" item="311" row="37" col="1" value="<?=lobas_format($arr_fld[37][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="37" col="2" value="<?=lobas_format($arr_fld[37][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="37" col="3" value="<?=lobas_format($arr_fld[37][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="37" col="4" value="<?=lobas_format($arr_fld[37][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">10<br>타회계차입금이자상환</td>																		    
																																		    
					<td align=center><input type="text" step="5" parent="712" item="311" row="38" col="1" value="<?=lobas_format($arr_fld[38][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="38" col="2" value="<?=lobas_format($arr_fld[38][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="38" col="3" value="<?=lobas_format($arr_fld[38][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="38" col="4" value="<?=lobas_format($arr_fld[38][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>기업채취급제비</td>
				
					<td align=center><input type="text" step="5" parent="712" item="311" row="39" col="1" value="<?=lobas_format($arr_fld[39][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="39" col="2" value="<?=lobas_format($arr_fld[39][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="39" col="3" value="<?=lobas_format($arr_fld[39][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="39" col="4" value="<?=lobas_format($arr_fld[39][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">12<br>자치단체차입금이자상환</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="712" item="311" row="40" col="1" value="<?=lobas_format($arr_fld[40][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="40" col="2" value="<?=lobas_format($arr_fld[40][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="40" col="3" value="<?=lobas_format($arr_fld[40][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="40" col="4" value="<?=lobas_format($arr_fld[40][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>공사공단채이자상환</td>
				
					<td align=center><input type="text" step="5" parent="712" item="311" row="41" col="1" value="<?=lobas_format($arr_fld[41][1])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="41" col="2" value="<?=lobas_format($arr_fld[41][2])?>"></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="41" col="3" value="<?=lobas_format($arr_fld[41][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="712" item="311" row="41" col="4" value="<?=lobas_format($arr_fld[41][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>공기관등경상적대행사업비</td>
				
					<td align=center><input type="text" step="4" parent="712" class="316" row="42" col="1" value="<?=lobas_format($arr_fld[42][1])?>"></td>
					<td align=center><input type="text" step="4" parent="712" class="316" row="42" col="2" value="<?=lobas_format($arr_fld[42][2])?>"></td>
					<td align=center><input type="text" step="4" parent="712" class="316" row="42" col="3" value="<?=lobas_format($arr_fld[42][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="712" class="316" row="42" col="4" value="<?=lobas_format($arr_fld[42][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">713<br>배·급수비</td>
				
					<td align=center><input step="3" parent="710" class="713" row="43" col="1" value="<?=lobas_format($arr_fld[43][1])?>"></td>
					<td align=center><input step="3" parent="710" class="713" row="43" col="2" value="<?=lobas_format($arr_fld[43][2])?>"></td>
					<td align=center><input step="3" parent="710" class="713" row="43" col="3" value="<?=lobas_format($arr_fld[43][3])?>" none></td>
					<td align=center><input step="3" parent="710" class="713" row="43" col="4" value="<?=lobas_format($arr_fld[43][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>민간이전</td>
				
					<td align=center><input step="4" parent="713" class="307" col="1" none></td>
					<td align=center><input step="4" parent="713" class="307" col="2" none></td>
					<td align=center><input step="4" parent="713" class="307" col="3" none></td>
					<td align=center><input step="4" parent="713" class="307" col="4" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>민간경상사업보조</td>
				
					<td align=center><input type="text" step="5" parent="713" item="307" row="44" col="1" value="<?=lobas_format($arr_fld[44][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="44" col="2" value="<?=lobas_format($arr_fld[44][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="44" col="3" value="<?=lobas_format($arr_fld[44][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="44" col="4" value="<?=lobas_format($arr_fld[44][4])?>"></td>
				</tr>																													    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">03<br>민간단체법정운영비보조</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="307" row="45" col="1" value="<?=lobas_format($arr_fld[45][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="45" col="2" value="<?=lobas_format($arr_fld[45][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="45" col="3" value="<?=lobas_format($arr_fld[45][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="45" col="4" value="<?=lobas_format($arr_fld[45][4])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>민간행사사업보조</td>
				
					<td align=center><input type="text" step="5" parent="713" item="307" row="46" col="1" value="<?=lobas_format($arr_fld[46][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="46" col="2" value="<?=lobas_format($arr_fld[46][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="46" col="3" value="<?=lobas_format($arr_fld[46][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="46" col="4" value="<?=lobas_format($arr_fld[46][4])?>"></td>
				</tr>																													    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">05<br>민간위탁금</td>																				    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="307" row="47" col="1" value="<?=lobas_format($arr_fld[47][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="47" col="2" value="<?=lobas_format($arr_fld[47][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="47" col="3" value="<?=lobas_format($arr_fld[47][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="47" col="4" value="<?=lobas_format($arr_fld[47][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">07<br>연금지급금</td>
				
					<td align=center><input type="text" step="5" parent="713" item="307" row="48" col="1" value="<?=lobas_format($arr_fld[48][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="48" col="2" value="<?=lobas_format($arr_fld[48][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="48" col="3" value="<?=lobas_format($arr_fld[48][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="48" col="4" value="<?=lobas_format($arr_fld[48][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">08<br>이차보전금</td>																				    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="307" row="49" col="1" value="<?=lobas_format($arr_fld[49][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="49" col="2" value="<?=lobas_format($arr_fld[49][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="49" col="3" value="<?=lobas_format($arr_fld[49][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="49" col="4" value="<?=lobas_format($arr_fld[49][4])?>"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>민간검침활동지원비</td>
				
					<td align=center><input type="text" step="5" parent="713" item="307" row="50" col="1" value="<?=lobas_format($arr_fld[50][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="50" col="2" value="<?=lobas_format($arr_fld[50][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="50" col="3" value="<?=lobas_format($arr_fld[50][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="307" row="50" col="4" value="<?=lobas_format($arr_fld[50][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>311<br>차입금이자상환</td>
				
					<td align=center><input step="4" parent="713" class="311" col="1" none></td>
					<td align=center><input step="4" parent="713" class="311" col="2" none></td>
					<td align=center><input step="4" parent="713" class="311" col="3" none></td>
					<td align=center><input step="4" parent="713" class="311" col="4" none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>시도지역개발기금차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="713" item="311" row="51" col="1" value="<?=lobas_format($arr_fld[51][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="51" col="2" value="<?=lobas_format($arr_fld[51][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="51" col="3" value="<?=lobas_format($arr_fld[51][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="51" col="4" value="<?=lobas_format($arr_fld[51][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">02<br>시군구지역개발기금차입금이자상환</td>															    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="311" row="52" col="1" value="<?=lobas_format($arr_fld[52][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="52" col="2" value="<?=lobas_format($arr_fld[52][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="52" col="3" value="<?=lobas_format($arr_fld[52][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="52" col="4" value="<?=lobas_format($arr_fld[52][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>통화금융기관차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="713" item="311" row="53" col="1" value="<?=lobas_format($arr_fld[53][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="53" col="2" value="<?=lobas_format($arr_fld[53][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="53" col="3" value="<?=lobas_format($arr_fld[53][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="53" col="4" value="<?=lobas_format($arr_fld[53][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">04<br>중앙정부차입금이자상환</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="311" row="54" col="1" value="<?=lobas_format($arr_fld[54][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="54" col="2" value="<?=lobas_format($arr_fld[54][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="54" col="3" value="<?=lobas_format($arr_fld[54][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="54" col="4" value="<?=lobas_format($arr_fld[54][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06<br>기타차입금이자상환</td>
				
					<td align=center><input type="text" step="5" parent="713" item="311" row="55" col="1" value="<?=lobas_format($arr_fld[55][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="55" col="2" value="<?=lobas_format($arr_fld[55][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="55" col="3" value="<?=lobas_format($arr_fld[55][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="55" col="4" value="<?=lobas_format($arr_fld[55][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">10<br>타회계차입금이자상환</td>																		    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="311" row="56" col="1" value="<?=lobas_format($arr_fld[56][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="56" col="2" value="<?=lobas_format($arr_fld[56][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="56" col="3" value="<?=lobas_format($arr_fld[56][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="56" col="4" value="<?=lobas_format($arr_fld[56][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>기업채취급제비</td>
				
					<td align=center><input type="text" step="5" parent="713" item="311" row="57" col="1" value="<?=lobas_format($arr_fld[57][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="57" col="2" value="<?=lobas_format($arr_fld[57][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="57" col="3" value="<?=lobas_format($arr_fld[57][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="57" col="4" value="<?=lobas_format($arr_fld[57][4])?>"></td>
				</tr>																													    
																																		    
				<tr bgcolor="FFFFFF" height="30">																						    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td align=center></td>																								    
					<td class="pd">12<br>자치단체차입금이자상환</td>																	    
																																		    
					<td align=center><input type="text" step="5" parent="713" item="311" row="58" col="1" value="<?=lobas_format($arr_fld[58][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="58" col="2" value="<?=lobas_format($arr_fld[58][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="58" col="3" value="<?=lobas_format($arr_fld[58][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="58" col="4" value="<?=lobas_format($arr_fld[58][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>공사공단채이자상환</td>
				
					<td align=center><input type="text" step="5" parent="713" item="311" row="59" col="1" value="<?=lobas_format($arr_fld[59][1])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="59" col="2" value="<?=lobas_format($arr_fld[59][2])?>"></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="59" col="3" value="<?=lobas_format($arr_fld[59][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="713" item="311" row="59" col="4" value="<?=lobas_format($arr_fld[59][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>공기관등경상적대행사업비</td>
				
					<td align=center><input type="text" step="4" parent="713" class="316" row="60" col="1" value="<?=lobas_format($arr_fld[60][1])?>"></td>
					<td align=center><input type="text" step="4" parent="713" class="316" row="60" col="2" value="<?=lobas_format($arr_fld[60][2])?>"></td>
					<td align=center><input type="text" step="4" parent="713" class="316" row="60" col="3" value="<?=lobas_format($arr_fld[60][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="713" class="316" row="60" col="4" value="<?=lobas_format($arr_fld[60][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">714<br>관거비</td>
				
					<td align=center><input step="3" parent="710" class="714" row=61 col="1" value="<?=lobas_format($arr_fld[61][1])?>"></td>
					<td align=center><input step="3" parent="710" class="714" row=61 col="2" value="<?=lobas_format($arr_fld[61][2])?>"></td>
					<td align=center><input step="3" parent="710" class="714" row=61 col="3" value="<?=lobas_format($arr_fld[61][3])?>" none></td>
					<td align=center><input step="3" parent="710" class="714" row=61 col="4" value="<?=lobas_format($arr_fld[61][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>민간이전</td>
				
					<td align=center><input step="4" parent="714" class="307" col="1" none></td>
					<td align=center><input step="4" parent="714" class="307" col="2" none></td>
					<td align=center><input step="4" parent="714" class="307" col="3" none></td>
					<td align=center><input step="4" parent="714" class="307" col="4" none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>민간위탁금</td>
				
					<td align=center><input step="4" parent="714" class="307" row=62 col=1 value="<?=lobas_format($arr_fld[62][1])?>"></td>
					<td align=center><input step="4" parent="714" class="307" row=62 col=2 value="<?=lobas_format($arr_fld[62][2])?>"></td>
					<td align=center><input step="4" parent="714" class="307" row=62 col=3 value="<?=lobas_format($arr_fld[62][3])?>" none></td>
					<td align=center><input step="4" parent="714" class="307" row=62 col=4 value="<?=lobas_format($arr_fld[62][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>공기관등경상적대행사업비</td>
				
					<td align=center><input type="text" step="4" parent="714" class="307" row=63 col=1 value="<?=lobas_format($arr_fld[63][1])?>"></td>
					<td align=center><input type="text" step="4" parent="714" class="307" row=63 col=2 value="<?=lobas_format($arr_fld[63][2])?>"></td>
					<td align=center><input type="text" step="4" parent="714" class="307" row=63 col=3 value="<?=lobas_format($arr_fld[63][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="714" class="307" row=63 col=4 value="<?=lobas_format($arr_fld[63][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">715<br>펌프장비</td>
				
					<td align=center><input step="3" parent="710" class="715" row=64 col="1" value="<?=lobas_format($arr_fld[64][1])?>"></td>
					<td align=center><input step="3" parent="710" class="715" row=64 col="2" value="<?=lobas_format($arr_fld[64][2])?>"></td>
					<td align=center><input step="3" parent="710" class="715" row=64 col="3" value="<?=lobas_format($arr_fld[64][3])?>" none></td>
					<td align=center><input step="3" parent="710" class="715" row=64 col="4" value="<?=lobas_format($arr_fld[64][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>민간이전</td>
				
					<td align=center><input step="4" parent="715" class="307" col="1" none></td>
					<td align=center><input step="4" parent="715" class="307" col="2" none></td>
					<td align=center><input step="4" parent="715" class="307" col="3" none></td>
					<td align=center><input step="4" parent="715" class="307" col="4" none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>민간위탁금</td>
				
					<td align=center><input type="text" step="5" parent="715" item="307" row="65" col="1" value="<?=lobas_format($arr_fld[65][1])?>"></td>
					<td align=center><input type="text" step="5" parent="715" item="307" row="65" col="2" value="<?=lobas_format($arr_fld[65][2])?>"></td>
					<td align=center><input type="text" step="5" parent="715" item="307" row="65" col="3" value="<?=lobas_format($arr_fld[65][3])?>" none></td>
					<td align=center><input type="text" step="5" parent="715" item="307" row="65" col="4" value="<?=lobas_format($arr_fld[65][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>공기관등경상적대행사업비</td>
				
					<td align=center><input type="text" step="4" parent="715" class="316" row="66" col="1" value="<?=lobas_format($arr_fld[66][1])?>"></td>
					<td align=center><input type="text" step="4" parent="715" class="316" row="66" col="2" value="<?=lobas_format($arr_fld[66][2])?>"></td>
					<td align=center><input type="text" step="4" parent="715" class="316" row="66" col="3" value="<?=lobas_format($arr_fld[66][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="715" class="316" row="66" col="4" value="<?=lobas_format($arr_fld[66][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">716<br>처리장비</td>
				
					<td align=center><input step="3" parent="710" class="716" row="67" col="1" value="<?=lobas_format($arr_fld[67][1])?>"></td>
					<td align=center><input step="3" parent="710" class="716" row="67" col="2" value="<?=lobas_format($arr_fld[67][2])?>"></td>
					<td align=center><input step="3" parent="710" class="716" row="67" col="3" value="<?=lobas_format($arr_fld[67][3])?>" none></td>
					<td align=center><input step="3" parent="710" class="716" row="67" col="4" value="<?=lobas_format($arr_fld[67][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>민간이전</td>
				
					<td align=center><input step="4" parent="716" class="307" col="1" none></td>
					<td align=center><input step="4" parent="716" class="307" col="2" none></td>
					<td align=center><input step="4" parent="716" class="307" col="3" none></td>
					<td align=center><input step="4" parent="716" class="307" col="4" none></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>민간위탁금</td>
				
					<td align=center><input step="5" parent="716" item="307" row="68" col="1" value="<?=lobas_format($arr_fld[68][1])?>"></td>
					<td align=center><input step="5" parent="716" item="307" row="68" col="2" value="<?=lobas_format($arr_fld[68][2])?>"></td>
					<td align=center><input step="5" parent="716" item="307" row="68" col="3" value="<?=lobas_format($arr_fld[68][3])?>" none></td>
					<td align=center><input step="5" parent="716" item="307" row="68" col="4" value="<?=lobas_format($arr_fld[68][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>공기관등경상적대행사업비</td>
				
					<td align=center><input type="text" step="4" parent="716" class="316" row="69" col="1" value="<?=lobas_format($arr_fld[69][1])?>"></td>
					<td align=center><input type="text" step="4" parent="716" class="316" row="69" col="2" value="<?=lobas_format($arr_fld[69][2])?>"></td>
					<td align=center><input type="text" step="4" parent="716" class="316" row="69" col="3" value="<?=lobas_format($arr_fld[69][3])?>" none></td>
					<td align=center><input type="text" step="4" parent="716" class="316" row="69" col="4" value="<?=lobas_format($arr_fld[69][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=3 class="pd">717<br>급·배수공사비</td>																	     
																																		     
					<td align=center><input type="text" step="3" parent="710" class="717" row="70" col="1" value="<?=lobas_format($arr_fld[70][1])?>"></td>
					<td align=center><input type="text" step="3" parent="710" class="717" row="70" col="2" value="<?=lobas_format($arr_fld[70][2])?>"></td>
					<td align=center><input type="text" step="3" parent="710" class="717" row="70" col="3" value="<?=lobas_format($arr_fld[70][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="710" class="717" row="70" col="4" value="<?=lobas_format($arr_fld[70][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">751<br>일반관리비</td>
				
					<td align=center><input type="text" step="3" parent="710" class="751" row="71" col="1" value="<?=lobas_format($arr_fld[71][1])?>"></td>
					<td align=center><input type="text" step="3" parent="710" class="751" row="71" col="2" value="<?=lobas_format($arr_fld[71][2])?>"></td>
					<td align=center><input type="text" step="3" parent="710" class="751" row="71" col="3" value="<?=lobas_format($arr_fld[71][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="710" class="751" row="71" col="4" value="<?=lobas_format($arr_fld[71][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=3 class="pd">752<br>징수 및 수용가관리비</td>															     
																																		     
					<td align=center><input type="text" step="3" parent="710" class="752" row="72" col="1" value="<?=lobas_format($arr_fld[72][1])?>"></td>
					<td align=center><input type="text" step="3" parent="710" class="752" row="72" col="2" value="<?=lobas_format($arr_fld[72][2])?>"></td>
					<td align=center><input type="text" step="3" parent="710" class="752" row="72" col="3" value="<?=lobas_format($arr_fld[72][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="710" class="752" row="72" col="4" value="<?=lobas_format($arr_fld[72][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">770<br>영업외비용</td>
				
					<td align=center><input step="2" parent="700" class="770" col="1" none></td>
					<td align=center><input step="2" parent="700" class="770" col="2" none></td>
					<td align=center><input step="2" parent="700" class="770" col="3" none></td>
					<td align=center><input step="2" parent="700" class="770" col="4" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">771<br>지급이자및취급제비</td>
				
					<td align=center><input type="text" step="3" parent="770" class="771" row="73" col="1" value="<?=lobas_format($arr_fld[73][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="771" row="73" col="2" value="<?=lobas_format($arr_fld[73][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="771" row="73" col="3" value="<?=lobas_format($arr_fld[73][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="771" row="73" col="4" value="<?=lobas_format($arr_fld[73][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=3 class="pd">772<br>유형자산처분손실</td>																     
																																		     
					<td align=center><input type="text" step="3" parent="770" class="772" row="74" col="1" value="<?=lobas_format($arr_fld[74][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="772" row="74" col="2" value="<?=lobas_format($arr_fld[74][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="772" row="74" col="3" value="<?=lobas_format($arr_fld[74][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="772" row="74" col="4" value="<?=lobas_format($arr_fld[74][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">773<br>자산손상차손</td>
				
					<td align=center><input type="text" step="3" parent="770" class="773" row="75" col="1" value="<?=lobas_format($arr_fld[75][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="773" row="75" col="2" value="<?=lobas_format($arr_fld[75][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="773" row="75" col="3" value="<?=lobas_format($arr_fld[75][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="773" row="75" col="4" value="<?=lobas_format($arr_fld[75][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=3 class="pd">774<br>외화환산손실</td>																	     
																																		     
					<td align=center><input type="text" step="3" parent="770" class="774" row="76" col="1" value="<?=lobas_format($arr_fld[76][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="774" row="76" col="2" value="<?=lobas_format($arr_fld[76][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="774" row="76" col="3" value="<?=lobas_format($arr_fld[76][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="774" row="76" col="4" value="<?=lobas_format($arr_fld[76][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">775<br>전기오류수정손실</td>
				
					<td align=center><input type="text" step="3" parent="770" class="775" row="77" col="1" value="<?=lobas_format($arr_fld[77][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="775" row="77" col="2" value="<?=lobas_format($arr_fld[77][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="775" row="77" col="3" value="<?=lobas_format($arr_fld[77][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="775" row="77" col="4" value="<?=lobas_format($arr_fld[77][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=3 class="pd">776<br>투자자산처분손실</td>																     
																																		     
					<td align=center><input type="text" step="3" parent="770" class="776" row="78" col="1" value="<?=lobas_format($arr_fld[78][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="776" row="78" col="2" value="<?=lobas_format($arr_fld[78][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="776" row="78" col="3" value="<?=lobas_format($arr_fld[78][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="776" row="78" col="4" value="<?=lobas_format($arr_fld[78][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">779<br>기타영업외비용</td>
				
					<td align=center><input type="text" step="3" parent="770" class="779" row="79" col="1" value="<?=lobas_format($arr_fld[79][1])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="779" row="79" col="2" value="<?=lobas_format($arr_fld[79][2])?>"></td>
					<td align=center><input type="text" step="3" parent="770" class="779" row="79" col="3" value="<?=lobas_format($arr_fld[79][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="770" class="779" row="79" col="4" value="<?=lobas_format($arr_fld[79][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">780<br>특별손실</td>
				
					<td align=center><input step="2" parent=700 class="780" col="1" none></td>
					<td align=center><input step="2" parent=700 class="780" col="2" none></td>
					<td align=center><input step="2" parent=700 class="780" col="3" none></td>
					<td align=center><input step="2" parent=700 class="780" col="4" none></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">781<br>임시손실</td>
				
					<td align=center><input type="text" step="3" parent="780" class="781" row="80" col="1" value="<?=lobas_format($arr_fld[80][1])?>"></td>
					<td align=center><input type="text" step="3" parent="780" class="781" row="80" col="2" value="<?=lobas_format($arr_fld[80][2])?>"></td>
					<td align=center><input type="text" step="3" parent="780" class="781" row="80" col="3" value="<?=lobas_format($arr_fld[80][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="780" class="781" row="80" col="4" value="<?=lobas_format($arr_fld[80][4])?>"></td>
				</tr>																													     
																																		     
				<tr bgcolor="FFFFFF" height="30">																						     
					<td align=center></td>																								     
					<td align=center></td>																								     
					<td colspan=3 class="pd">782<br>전기손익수정손실</td>																     
																																		     
					<td align=center><input type="text" step="3" parent="780" class="782" row="81" col="1" value="<?=lobas_format($arr_fld[81][1])?>"></td>
					<td align=center><input type="text" step="3" parent="780" class="782" row="81" col="2" value="<?=lobas_format($arr_fld[81][2])?>"></td>
					<td align=center><input type="text" step="3" parent="780" class="782" row="81" col="3" value="<?=lobas_format($arr_fld[81][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="780" class="782" row="81" col="4" value="<?=lobas_format($arr_fld[81][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">789<br>기타특별손실</td>
				
					<td align=center><input type="text" step="3" parent="780" class="789" row="82" col="1" value="<?=lobas_format($arr_fld[82][1])?>"></td>
					<td align=center><input type="text" step="3" parent="780" class="789" row="82" col="2" value="<?=lobas_format($arr_fld[82][2])?>"></td>
					<td align=center><input type="text" step="3" parent="780" class="789" row="82" col="3" value="<?=lobas_format($arr_fld[82][3])?>" none></td>
					<td align=center><input type="text" step="3" parent="780" class="789" row="82" col="4" value="<?=lobas_format($arr_fld[82][4])?>"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">800<br>예비비</td>
				
					<td align=center><input type="text" step="2" parent=700 class="800" row="83" col="1" value="<?=lobas_format($arr_fld[83][1])?>"></td>
					<td align=center><input type="text" step="2" parent=700 class="800" row="83" col="2" value="<?=lobas_format($arr_fld[83][2])?>"></td>
					<td align=center><input type="text" step="2" parent=700 class="800" row="83" col="3" value="<?=lobas_format($arr_fld[83][3])?>" none></td>
					<td align=center><input type="text" step="2" parent=700 class="800" row="83" col="4" value="<?=lobas_format($arr_fld[83][4])?>"></td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>