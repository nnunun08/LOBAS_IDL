<?
	if (empty($yy)) $yy = date("Y")-1;
?>
<style>
	input {
		text-align:center;
	}

	.tot1 {
		background-color: white;
	}

	.tot2 {
		background-color: white;
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
		
		$("input[col='7'],input[col='8']").each(function(){
			if ($(this).attr("sub")!="1") {
				$(this).attr("disabled",true);
				$(this).css("border","none");
				$(this).css("background","white");
			}
		});

		/* 이월금 */
		$(".suib[col='9'][row='15']").attr("disabled",true);
		$(".suib[col='9'][row='15']").css("border","none");
		$(".suib[col='9'][row='15']").css("background","white");
		
		/* 이월예산지출 */
		$(".jichul[col='9'][row='15']").attr("disabled",true);
		$(".jichul[col='9'][row='15']").css("border","none");
		$(".jichul[col='9'][row='15']").css("background","white");


		$(".adm1[col='2']").css("background","#EEECE1");
		$(".adm2[col='1']").css("background","#EEECE1");
		
		$("input").on("focus",function(){
			$(this).select();
		});
		
		$("input").keypress(function(e) {
			//$("input").trigger("keyup");
		});

		$("input").keydown(function(e) {
			/*
			let val = $(this).val();
			
			if (e.keyCode==9) {
				if (val=="") {
					$(this).val("0");
					$("input").trigger("keyup");
				}
			}
			*/
		});

		$("input").keyup(function(e) {
			let val = $(this).val();
			let col = $(this).attr("col");
			let row = $(this).attr("row");
			let cls = $(this).attr("class");
			let next = 0;
			let kap = 0;
			let tot = 0;
			
			//if (e.keyCode==13 && val=="") {
			//	val = "0";
			//}

			$(this).val(addComma(val));
			
			/* 1,7 2,8 3,7 4,8 */
				
			/* 자동입력 부분 */
			if (col=="1" || col=="4") {
				$("."+cls+"[col='7'][row='"+row+"']").val(addComma(val));
			} else if (col=="2" || col=="5") {
				$("."+cls+"[col='8'][row='"+row+"']").val(addComma(val));
			} else if (col=="7") {
				if ($("."+cls+"[col='1'][row='"+row+"']").length) {
					$("."+cls+"[col='1'][row='"+row+"']").val(addComma(val));
				}

				if ($("."+cls+"[col='4'][row='"+row+"']").length) {
					$("."+cls+"[col='4'][row='"+row+"']").val(addComma(val));
				}
			} else if (col=="8") {
				if ($("."+cls+"[col='2'][row='"+row+"']").length) {
					$("."+cls+"[col='2'][row='"+row+"']").val(addComma(val));
				}

				if ($("."+cls+"[col='5'][row='"+row+"']").length) {
					$("."+cls+"[col='5'][row='"+row+"']").val(addComma(val));
				}
			}
			
			//if (e.keyCode==13) {
				if (cls=="iwol") {
					next = getNextRow(cls,row,col);
					
					if (next==0) {
						$("."+cls+"[col='9'][row='1']").focus();
					} else {
						$("."+cls+"[col='9'][row='"+next+"']").focus();
					}

					compute(cls);
				} else {
					if (e.keyCode==13) {
						moveInput(cls,row,col);
					}

					if ($(this).attr("sub")) {
						if (col=="7" || col=="8" || col=="9") {
							if (row==12 || row==13) {
								if (e.keyCode==13) {
									subchk(cls,row,col);
								}
							} else {
								subchk(cls,row,col);
							}
						}
					}

					compute(cls);
				}
			//}
		});
	});

	/* 서브체크 */
	function subchk(cls,row,col) {
		let kap1 = 0;
		let kap2 = 0;
		let kap3 = 0;
		let kap4 = 0;

		if (cls=="suib") {
			if (row=="12" || row=="13") {
				kap1 = parseInt($(".suib[row='11'][col='"+col+"']").val().replaceAll(",",""));
				kap2 = parseInt($(".suib[row='12'][col='"+col+"']").val().replaceAll(",",""));
				kap3 = parseInt($(".suib[row='13'][col='"+col+"']").val().replaceAll(",",""));
				
				if (kap2>0 && kap3>0) {
					if (kap1!=(kap2+kap3)) {
						alert("입력한 값이 잘못되었습니다.");
						$(".suib[row='"+row+"'][col='"+col+"']").focus();
					}
				}
			}

			if (row=="16" || row=="17") {
				kap1 = parseInt($(".suib[row='15'][col='"+col+"']").val().replaceAll(",",""));
				kap2 = parseInt($(".suib[row='16'][col='"+col+"']").val().replaceAll(",",""));
				kap3 = parseInt($(".suib[row='17'][col='"+col+"']").val().replaceAll(",",""));
				
				if (isNaN(kap2)) kap2 = 0;
				if (isNaN(kap3)) kap3 = 0;

				$(".suib[row='15'][col='"+col+"']").val(addComma(kap2+kap3));

				//if (kap1!=(kap2+kap3)) {
				//	alert("이월금 자금내역이 잘못되었습니다");
				//	$(".suib[row='"+row+"'][col='"+col+"']").focus();
				//}
			}
		} else {
				
			if (row=="16" || row=="17" || row=="18") {
				kap1 = parseInt($(".jichul[row='15'][col='"+col+"']").val().replaceAll(",",""));
				kap2 = parseInt($(".jichul[row='16'][col='"+col+"']").val().replaceAll(",",""));
				kap3 = parseInt($(".jichul[row='17'][col='"+col+"']").val().replaceAll(",",""));
				kap4 = parseInt($(".jichul[row='18'][col='"+col+"']").val().replaceAll(",",""));
				
				if (isNaN(kap2)) kap2 = 0;
				if (isNaN(kap3)) kap3 = 0;
				if (isNaN(kap4)) kap4 = 0;

				$(".jichul[row='15'][col='"+col+"']").val(addComma(kap2+kap3+kap4));

				//if (kap1!=(kap2+kap3+kap4)) {
				//	alert("이월예산지출 자금내역이 잘못되었습니다");
				//	$(".jichul[row='"+row+"'][col='"+col+"']").focus();
				//}
			}
		}
	}
	
	/* 금액계산하기 */
	function compute(cls) {
		/* 수입계산 */
		let maxRow = $("."+cls+"[col='9']").length;
		
		let kap1 = 0;
		let kap2 = 0;
		let kap3 = 0;

		let tot = [0,0,0,0,0,0,0,0,0,0,0];
		
		//alert("123");

		if (cls=="iwol") {
			for (i=1;i<=maxRow ;i++ ) {
				kap1 = parseInt($("."+cls+"[col='9'][row='"+i+"']").val().replaceAll(",", ""));
				if (isNaN(kap1)) kap1 = 0;

				kap2 += kap1;
			}

			$(".tot3:eq(6)").text(addComma(kap2));

			return;
		}
		
		for (i=1;i<=maxRow ;i++) {
			/* 첫번째행이 있으면 */
			if ($("."+cls+"[col='1'][row='"+i+"']").length) {
				kap1 = parseInt($("."+cls+"[col='1'][row='"+i+"']").val().replaceAll(",", ""));
				kap2 = parseInt($("."+cls+"[col='2'][row='"+i+"']").val().replaceAll(",", ""));
				
				if (isNaN(kap1)) kap1 = 0;
				if (isNaN(kap2)) kap2 = 0;
				
				kap3 = kap1 - kap2;

				tot[1] += kap1;
				tot[2] += kap2;
				
				$("."+cls+"[col='3'][row='"+i+"']").val(addComma(kap3));
			}
			
			if ($("."+cls+"[col='4'][row='"+i+"']").length) {
				kap1 = parseInt($("."+cls+"[col='4'][row='"+i+"']").val().replaceAll(",", ""));
				kap2 = parseInt($("."+cls+"[col='5'][row='"+i+"']").val().replaceAll(",", ""));
				
				if (isNaN(kap1)) kap1 = 0;
				if (isNaN(kap2)) kap2 = 0;

				kap3 = kap1 - kap2;

				tot[4] += kap1;
				tot[5] += kap2;
				
				$("."+cls+"[col='6'][row='"+i+"']").val(addComma(kap3));
			}
			
			if ($("."+cls+"[col='7'][row='"+i+"']").length) {
				kap1 = parseInt($("."+cls+"[col='7'][row='"+i+"']").val().replaceAll(",", ""));
				kap2 = parseInt($("."+cls+"[col='8'][row='"+i+"']").val().replaceAll(",", ""));
				
				if (isNaN(kap1)) kap1 = 0;
				if (isNaN(kap2)) kap2 = 0;

				if ($("."+cls+"[col='7'][row='"+i+"']").attr("sub")=="1") {
					/* 하위단은 더하지 않는다 */
				} else {
					tot[7] += kap1;
					tot[8] += kap2;
				}
			}

			if ($("."+cls+"[col='9'][row='"+i+"']").length) {
				kap1 = parseInt($("."+cls+"[col='9'][row='"+i+"']").val().replaceAll(",", ""));
				kap2 = parseInt($("."+cls+"[col='8'][row='"+i+"']").val().replaceAll(",", ""));
				
				if (isNaN(kap1)) kap1 = 0;
				if (isNaN(kap2)) kap2 = 0;

				if ($("."+cls+"[col='7'][row='"+i+"']").attr("sub")=="1") {
				} else {
					tot[9] += kap1;
				}
				
				kap3 = kap2 - kap1;
					
				if (cls=="suib") {
					$(".adm1[col='1'][row='"+i+"']").text(addComma(kap3));
				} else {
					$(".adm2[col='2'][row='"+i+"']").text(addComma(kap3));
				}
			}
		}
		
		tot[3] = tot[1] - tot[2];
		tot[6] = tot[4] - tot[5];

		for (i=1;i<=9 ;i++) {
			if (cls=="suib") {
				$(".tot1:eq("+(i-1)+")").val(addComma(tot[i]));
			} else {
				$(".tot2:eq("+(i-1)+")").val(addComma(tot[i]));
			}
		}
		
		for (i=0;i<6 ;i++) {
			kap1 = parseInt($(".tot1:eq("+i+")").val().replaceAll(",", ""));
			kap2 = parseInt($(".tot2:eq("+i+")").val().replaceAll(",", ""));
			
			if (isNaN(kap1)) kap1 = 0;
			if (isNaN(kap2)) kap2 = 0;

			kap3 = kap1 - kap2;
			
			$(".tot3:eq("+i+")").text(addComma(kap3));
		}

		kap3 = tot[8] - tot[9];

		if (cls=="suib") {
			$(".adm1[col='1'][row='0']").text(addComma(kap3));
		} else {
			$(".adm2[col='2'][row='0']").text(addComma(kap3));
		}
	}

	function moveInput(cls,row,col) {
		let next = 0;
		
		/* 가로이동 */
		if (col=="1" || col=="4" || col=="7" || col=="8") {
			next = parseInt(col)+1;
			$("."+cls+"[col='"+next+"'][row='"+row+"']").focus();
		}

		/* 세로이동 */
		if (col=="2") {
			/* 해당클레스 다음 로우가 있는지 체크 */
			next = getNextRow(cls,row,col);
			
			if (next==0) {
				if (cls=="suib") {
					$("."+cls+"[col='4'][row='4']").focus();
				} else {
					$("."+cls+"[col='4'][row='6']").focus();
				}
			} else {
				$("."+cls+"[col='1'][row='"+next+"']").focus();
			}
		} else if (col=="5") {
			/* 해당클레스 다음 로우가 있는지 체크 */
			next = getNextRow(cls,row,col);

			if (next==0) {
				$("."+cls+"[col='7'][row='1']").focus();
			} else {
				$("."+cls+"[col='4'][row='"+next+"']").focus();
			}
		} else if (col=="9") {
			/* 해당클레스 다음 로우가 있는지 체크 */
			next = getNextRow(cls,row,col);
			
			if (cls=="suib") {
				if (next==12 || next==13 || next==16 || next==17) {
					$("."+cls+"[col='7'][row='"+next+"']").focus();
				} else if (next==15) {
					$("."+cls+"[col='7'][row='16']").focus();
				} else if (next==0) {
					$("."+cls+"[col='9'][row='1']").focus();
				} else {
					$("."+cls+"[col='9'][row='"+next+"']").focus();
				}
			} else if (cls=="jichul") {
				if (next==16 || next==17 || next==18) {
					$("."+cls+"[col='7'][row='"+next+"']").focus();
				} else if (next==15) {
					$("."+cls+"[col='7'][row='16']").focus();
				} else if (next==0) {
					$("."+cls+"[col='9'][row='1']").focus();
				} else {
					$("."+cls+"[col='9'][row='"+next+"']").focus();
				}
			}
		}
	}

	function getNextRow(cls,row,col) {
		var max = $("."+cls+"[col='9']").length;
		var ret = 0;
		row = parseInt(row)+1;
		
		for (i=row;i<=max ;i++ ) {
			if ($("."+cls+"[col='"+col+"'][row='"+i+"']").length) {
				ret = i;
				break;
			}
		}

		return ret;
	}
//-->
</SCRIPT>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form name="form1" method="post">
	<input type="hidden" name="sw" value="<?=$sw?>">
	<input type="hidden" name="flag" value="<?=$flag?>">
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">1. 총괄표</span>
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
						1. 본 화면은 당기의 로바스 출력자료 중 총괄표의 해당 내용을 입력합니다.<br>
						2. 사용자는 로바스에서  총괄표를 엑셀파일로 다운받은 후 해당 계정과목의 금액을 입력(복사/붙여넣기 )합니다.<br>
						3. 금액은 원단위로 입력합니다.<br>
						4. 금액 앱력후 Enter key 누르면 자동 저장됩니다.
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width=1900 cellspacing="1" cellpadding="0">
				<tr>
					<td>
						<table width=1600 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
							<colgroup>
								<col width="4%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
								<col width="8%">
							</colgroup>
							
							<tr bgcolor="f6f6f6" height="25">
								<td align=center rowspan=2>구분</td>
								<td align=center colspan=4>사업예산결산</td>
								<td align=center colspan=4>자본예산결산</td>
								<td align=center colspan=4>자금결산</td>
							</tr>	
							
							<tr bgcolor="f6f6f6" height="25">
								<td align=center>계정과목</td>
								<td align=center>예산액(A)</td>
								<td align=center>결산액(B)</td>
								<td align=center>증감(A-B)</td>

								<td align=center>계정과목</td>
								<td align=center>예산액(A)</td>
								<td align=center>결산액(B)</td>
								<td align=center>증감(A-B)</td>

								<td align=center>계정과목</td>
								<td align=center>예산액</td>
								<td align=center>결산액</td>
								<td align=center>수납액.지출액</td>
							</tr>

							<?
								$arr_fld = array();

								$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=1 and gubun=1";
								$row = T_select($sql);
								
								for ($i=0;$i<count($row);$i++) {
									for ($j=1;$j<=9;$j++) {
										if (trim($row[$i]["fld".$j])) {
											$arr_fld[$row[$i][rnum]][$j] = trim($row[$i]["fld".$j]);
										}
									}
								}
							?>
							
							<tr bgcolor="FFFFFF" height="25">
								<td align=center rowspan=18>수입(I)</td>
								<td align=center>계</td>
								<td align=center><input type="text" size=16 col=1 row=0 class="tot1" disabled style="border:none"></td>
								<td align=center><input type="text" size=10 col=2 row=0 class="tot1" disabled style="border:none"></td>
								<td align=center><input type="text" size=10 col=3 row=0 class="tot1" disabled style="border:none"></td>

								<td align=center>계</td>
								<td align=center><input type="text" size=10 col=4 row=0 class="tot1" disabled style="border:none"></td>
								<td align=center><input type="text" size=10 col=5 row=0 class="tot1" disabled style="border:none"></td>
								<td align=center><input type="text" size=10 col=6 row=0 class="tot1" disabled style="border:none"></td>

								<td align=center>계</td>
								<td align=center><input type="text" size=10 col=7 row=0 class="tot1" disabled style="border:none"></td>
								<td align=center><input type="text" size=10 col=8 row=0 class="tot1" disabled style="border:none"></td>
								<td align=center><input type="text" size=10 col=9 row=0 class="tot1" disabled style="border:none"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>영업수익</td>
								<td align=center><input type="text" class="suib" col=1 row=1 size=10 value="<?=lobas_format($arr_fld[1][1])?>"></td>
								<td align=center><input type="text" class="suib" col=2 row=1 size=10 value="<?=lobas_format($arr_fld[1][2])?>"></td>
								<td align=center><input type="text" class="suib" col=3 row=1 size=10 value="<?=lobas_format($arr_fld[1][3])?>" none></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>영업수익</td>
								<td align=center><input type="text" class="suib" col=7 row=1 size=10 value="<?=lobas_format($arr_fld[1][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=1 size=10 value="<?=lobas_format($arr_fld[1][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=1 size=10 value="<?=lobas_format($arr_fld[1][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>영업외수익</td>
								<td align=center><input type="text" class="suib" col=1 row=2 size=10 value="<?=lobas_format($arr_fld[2][1])?>"></td>
								<td align=center><input type="text" class="suib" col=2 row=2 size=10 value="<?=lobas_format($arr_fld[2][2])?>"></td>
								<td align=center><input type="text" class="suib" col=3 row=2 size=10 value="<?=lobas_format($arr_fld[2][3])?>" none></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>영업외수익</td>
								<td align=center><input type="text" class="suib" col=7 row=2 size=10 value="<?=lobas_format($arr_fld[2][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=2 size=10 value="<?=lobas_format($arr_fld[2][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=2 size=10 value="<?=lobas_format($arr_fld[2][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>특별이익</td>
								<td align=center><input type="text" class="suib" col=1 row=3 size=10 value="<?=lobas_format($arr_fld[3][1])?>"></td>
								<td align=center><input type="text" class="suib" col=2 row=3 size=10 value="<?=lobas_format($arr_fld[3][2])?>"></td>
								<td align=center><input type="text" class="suib" col=3 row=3 size=10 value="<?=lobas_format($arr_fld[3][3])?>" none></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>특별이익</td>
								<td align=center><input type="text" class="suib" col=7 row=3 size=10 value="<?=lobas_format($arr_fld[3][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=3 size=10 value="<?=lobas_format($arr_fld[3][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=3 size=10 value="<?=lobas_format($arr_fld[3][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>투자자산처분</td>
								<td align=center><input type="text" class="suib" col=4 row=4 size=10 value="<?=lobas_format($arr_fld[4][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=4 size=10 value="<?=lobas_format($arr_fld[4][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=4 size=10 value="<?=lobas_format($arr_fld[4][6])?>" none></td>

								<td align=center>투자자산처분</td>
								<td align=center><input type="text" class="suib" col=7 row=4 size=10 value="<?=lobas_format($arr_fld[4][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=4 size=10 value="<?=lobas_format($arr_fld[4][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=4 size=10 value="<?=lobas_format($arr_fld[4][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>유형자산처분</td>
								<td align=center><input type="text" class="suib" col=4 row=5 size=10 value="<?=lobas_format($arr_fld[5][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=5 size=10 value="<?=lobas_format($arr_fld[5][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=5 size=10 value="<?=lobas_format($arr_fld[5][6])?>" none></td>
																                            		                                          
								<td align=center>유형자산처분</td>                          		                                          
								<td align=center><input type="text" class="suib" col=7 row=5 size=10 value="<?=lobas_format($arr_fld[5][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=5 size=10 value="<?=lobas_format($arr_fld[5][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=5 size=10 value="<?=lobas_format($arr_fld[5][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>기타비유동자산처분</td>
								<td align=center><input type="text" class="suib" col=4 row=6 size=10 value="<?=lobas_format($arr_fld[6][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=6 size=10 value="<?=lobas_format($arr_fld[6][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=6 size=10 value="<?=lobas_format($arr_fld[6][6])?>" none></td>
																									                                 	        
								<td align=center>기타비유동자산처분</td>							                                 	        
								<td align=center><input type="text" class="suib" col=7 row=6 size=10 value="<?=lobas_format($arr_fld[6][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=6 size=10 value="<?=lobas_format($arr_fld[6][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=6 size=10 value="<?=lobas_format($arr_fld[6][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>유동부채수입</td>
								<td align=center><input type="text" class="suib" col=4 row=7 size=10 value="<?=lobas_format($arr_fld[7][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=7 size=10 value="<?=lobas_format($arr_fld[7][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=7 size=10 value="<?=lobas_format($arr_fld[7][6])?>" none></td>
																									                                 	      
								<td align=center>유동부채수입</td>									                                 	      
								<td align=center><input type="text" class="suib" col=7 row=7 size=10 value="<?=lobas_format($arr_fld[7][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=7 size=10 value="<?=lobas_format($arr_fld[7][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=7 size=10 value="<?=lobas_format($arr_fld[7][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>비유동부채수입</td>
								<td align=center><input type="text" class="suib" col=4 row=8 size=10 value="<?=lobas_format($arr_fld[8][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=8 size=10 value="<?=lobas_format($arr_fld[8][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=8 size=10 value="<?=lobas_format($arr_fld[8][6])?>" none></td>
																									                                 	      
								<td align=center>비유동부채수입</td>								                                 	      
								<td align=center><input type="text" class="suib" col=7 row=8 size=10 value="<?=lobas_format($arr_fld[8][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=8 size=10 value="<?=lobas_format($arr_fld[8][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=8 size=10 value="<?=lobas_format($arr_fld[8][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>자본금수입</td>
								<td align=center><input type="text" class="suib" col=4 row=9 size=10 value="<?=lobas_format($arr_fld[9][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=9 size=10 value="<?=lobas_format($arr_fld[9][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=9 size=10 value="<?=lobas_format($arr_fld[9][6])?>" none></td>
																									                                 	      
								<td align=center>자본금수입</td>									                                 	      
								<td align=center><input type="text" class="suib" col=7 row=9 size=10 value="<?=lobas_format($arr_fld[9][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=9 size=10 value="<?=lobas_format($arr_fld[9][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=9 size=10 value="<?=lobas_format($arr_fld[9][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>자본잉여금수입</td>
								<td align=center><input type="text" class="suib" col=4 row=10 size=10 value="<?=lobas_format($arr_fld[10][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=10 size=10 value="<?=lobas_format($arr_fld[10][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=10 size=10 value="<?=lobas_format($arr_fld[10][6])?>" none></td>
																									                                  	      
								<td align=center>자본잉여금수입</td>								                                  	      
								<td align=center><input type="text" class="suib" col=7 row=10 size=10 value="<?=lobas_format($arr_fld[10][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=10 size=10 value="<?=lobas_format($arr_fld[10][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=10 size=10 value="<?=lobas_format($arr_fld[10][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>유보자금</td>
								<td align=center><input type="text" class="suib" col=4 row=11 size=10 value="<?=lobas_format($arr_fld[11][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=11 size=10 value="<?=lobas_format($arr_fld[11][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=11 size=10 value="<?=lobas_format($arr_fld[11][6])?>" none></td>
																									                                  	        
								<td align=center>유보자금</td>										                                  	        
								<td align=center><input type="text" class="suib" col=7 row=11 size=10 value="<?=lobas_format($arr_fld[11][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=11 size=10 value="<?=lobas_format($arr_fld[11][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=11 size=10 value="<?=lobas_format($arr_fld[11][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>- 순세계잉여금</td>
								<td align=center><input type="text" class="suib" sub="1" col=7 row=12 value="<?=lobas_format($arr_fld[12][7])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=8 row=12 value="<?=lobas_format($arr_fld[12][8])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=9 row=12 value="<?=lobas_format($arr_fld[12][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>- 미수금</td>
								<td align=center><input type="text" class="suib" sub="1" col=7 row=13 size=10 value="<?=lobas_format($arr_fld[13][7])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=8 row=13 size=10 value="<?=lobas_format($arr_fld[13][8])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=9 row=13 size=10 value="<?=lobas_format($arr_fld[13][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>기타자본적수입</td>
								<td align=center><input type="text" class="suib" col=4 row=14 size=10 value="<?=lobas_format($arr_fld[14][4])?>"></td>
								<td align=center><input type="text" class="suib" col=5 row=14 size=10 value="<?=lobas_format($arr_fld[14][5])?>"></td>
								<td align=center><input type="text" class="suib" col=6 row=14 size=10 value="<?=lobas_format($arr_fld[14][6])?>" none></td>

								<td align=center>기타자본적수입</td>
								<td align=center><input type="text" class="suib" col=7 row=14 size=10 value="<?=lobas_format($arr_fld[14][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=14 size=10 value="<?=lobas_format($arr_fld[14][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=14 size=10 value="<?=lobas_format($arr_fld[14][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>이월금</td>
								<td align=center><input type="text" class="suib" col=7 row=15 size=10 value="<?=lobas_format($arr_fld[15][7])?>"></td>
								<td align=center><input type="text" class="suib" col=8 row=15 size=10 value="<?=lobas_format($arr_fld[15][8])?>"></td>
								<td align=center><input type="text" class="suib" col=9 row=15 size=10 value="<?=lobas_format($arr_fld[15][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>- 이월재원충당액</td>
								<td align=center><input type="text" class="suib" sub="1" col=7 row=16 size=10 value="<?=lobas_format($arr_fld[16][7])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=8 row=16 size=10 value="<?=lobas_format($arr_fld[16][8])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=9 row=16 size=10 value="<?=lobas_format($arr_fld[16][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>-미지급이월액</td>
								<td align=center><input type="text" class="suib" sub="1" col=7 row=17 size=10 value="<?=lobas_format($arr_fld[17][7])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=8 row=17 size=10 value="<?=lobas_format($arr_fld[17][8])?>"></td>
								<td align=center><input type="text" class="suib" sub="1" col=9 row=17 size=10 value="<?=lobas_format($arr_fld[17][9])?>"></td>
							</tr>
							<!-- 수입 종료 ------------------------------------ -->
							
							<?
								$arr_fld = array();

								$sql = "select * from admin_lobas where company=$adm_com and yy=$yy and cate=1 and gubun=2";
								$row = T_select($sql);
								
								for ($i=0;$i<count($row);$i++) {
									for ($j=1;$j<=9;$j++) {
										if (trim($row[$i]["fld".$j])) {
											$arr_fld[$row[$i][rnum]][$j] = trim($row[$i]["fld".$j]);
										}
									}
								}
							?>

							<!-- 지출 시작 ------------------------------------ -->
							<tr bgcolor="FFFFFF" height="25">
								<td align=center rowspan=19>지출(II)</td>
								<td align=center>계</td>
								<td align=center><input type="text" col=1 row=0 class="tot2" disabled style="border:none"></td>
								<td align=center><input type="text" col=2 row=0 class="tot2" disabled style="border:none"></td>
								<td align=center><input type="text" col=3 row=0 class="tot2" disabled style="border:none"></td>

								<td align=center>계</td>
								<td align=center><input type="text" col=4 row=0 class="tot2" disabled style="border:none"></td>
								<td align=center><input type="text" col=5 row=0 class="tot2" disabled style="border:none"></td>
								<td align=center><input type="text" col=6 row=0 class="tot2" disabled style="border:none"></td>

								<td align=center>계</td>
								<td align=center><input type="text" col=7 row=0 class="tot2" disabled style="border:none"></td>
								<td align=center><input type="text" col=8 row=0 class="tot2" disabled style="border:none"></td>
								<td align=center><input type="text" col=9 row=0 class="tot2" disabled style="border:none"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>영업비용</td>
								<td align=center><input type="text" class="jichul" col=1 row=1 value="<?=lobas_format($arr_fld[1][1])?>"></td>
								<td align=center><input type="text" class="jichul" col=2 row=1 value="<?=lobas_format($arr_fld[1][2])?>"></td>
								<td align=center><input type="text" class="jichul" col=3 row=1 value="<?=lobas_format($arr_fld[1][3])?>" none></td>

								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>영업비용</td>
								<td align=center><input type="text" class="jichul" col=7 row=1 value="<?=lobas_format($arr_fld[1][7])?>"></td>
								<td align=center><input type="text" class="jichul" col=8 row=1 value="<?=lobas_format($arr_fld[1][8])?>"></td>
								<td align=center><input type="text" class="jichul" col=9 row=1 value="<?=lobas_format($arr_fld[1][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>영업외비용</td>
								<td align=center><input type="text" class="jichul" col=1 row=2 value="<?=lobas_format($arr_fld[2][1])?>"></td>
								<td align=center><input type="text" class="jichul" col=2 row=2 value="<?=lobas_format($arr_fld[2][2])?>"></td>
								<td align=center><input type="text" class="jichul" col=3 row=2 value="<?=lobas_format($arr_fld[2][3])?>" none></td>
																									                                   	        
								<td align=center></td>												                                   	        
								<td align=center></td>												                                   	        
								<td align=center></td>												                                   	        
								<td align=center></td>												                                   	        
																									                                   	        
								<td align=center>영업외비용</td>									                                   	        
								<td align=center><input type="text" class="jichul" col=7 row=2 value="<?=lobas_format($arr_fld[2][7])?>"></td>
								<td align=center><input type="text" class="jichul" col=8 row=2 value="<?=lobas_format($arr_fld[2][8])?>"></td>
								<td align=center><input type="text" class="jichul" col=9 row=2 value="<?=lobas_format($arr_fld[2][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>특별손실</td>
								<td align=center><input type="text" class="jichul" col=1 row=3 value="<?=lobas_format($arr_fld[3][1])?>"></td>
								<td align=center><input type="text" class="jichul" col=2 row=3 value="<?=lobas_format($arr_fld[3][2])?>"></td>
								<td align=center><input type="text" class="jichul" col=3 row=3 value="<?=lobas_format($arr_fld[3][3])?>" none></td>
																																	    
								<td align=center></td>																				    
								<td align=center></td>																				    
								<td align=center></td>																				    
								<td align=center></td>																				    
																																	    
								<td align=center>특별손실</td>																		    
								<td align=center><input type="text" class="jichul" col=7 row=3 value="<?=lobas_format($arr_fld[3][7])?>"></td>
								<td align=center><input type="text" class="jichul" col=8 row=3 value="<?=lobas_format($arr_fld[3][8])?>"></td>
								<td align=center><input type="text" class="jichul" col=9 row=3 value="<?=lobas_format($arr_fld[3][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>법인세</td>
								<td align=center><input type="text" class="jichul" col=1 row=4 value="<?=lobas_format($arr_fld[4][1])?>"></td>
								<td align=center><input type="text" class="jichul" col=2 row=4 value="<?=lobas_format($arr_fld[4][2])?>"></td>
								<td align=center><input type="text" class="jichul" col=3 row=4 value="<?=lobas_format($arr_fld[4][3])?>" none></td>
																									  							       	    
								<td align=center></td>												  							       	    
								<td align=center></td>												  							       	    
								<td align=center></td>												  							       	    
								<td align=center></td>												  							       	    
																									  							       	    
								<td align=center>법인세</td>										  							       	    
								<td align=center><input type="text" class="jichul" col=7 row=4 value="<?=lobas_format($arr_fld[4][7])?>"></td>
								<td align=center><input type="text" class="jichul" col=8 row=4 value="<?=lobas_format($arr_fld[4][8])?>"></td>
								<td align=center><input type="text" class="jichul" col=9 row=4 value="<?=lobas_format($arr_fld[4][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center>예비비</td>
								<td align=center><input type="text" class="jichul" col=1 row=5 value="<?=lobas_format($arr_fld[5][1])?>"></td>
								<td align=center><input type="text" class="jichul" col=2 row=5 value="<?=lobas_format($arr_fld[5][2])?>"></td>
								<td align=center><input type="text" class="jichul" col=3 row=5 value="<?=lobas_format($arr_fld[5][3])?>" none></td>
																									  							       	        
								<td align=center></td>												  							       	        
								<td align=center></td>												  							       	        
								<td align=center></td>												  							       	        
								<td align=center></td>												  							       	        
																									  							       	        
								<td align=center>예비비</td>										  							       	        
								<td align=center><input type="text" class="jichul" col=7 row=5 value="<?=lobas_format($arr_fld[5][7])?>"></td>
								<td align=center><input type="text" class="jichul" col=8 row=5 value="<?=lobas_format($arr_fld[5][8])?>"></td>
								<td align=center><input type="text" class="jichul" col=9 row=5 value="<?=lobas_format($arr_fld[5][9])?>"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>재고자산취득</td>
								<td align=center><input type="text" class="jichul" col=4 row=6 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=6 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=6 size=10></td>

								<td align=center>재고자산취득</td>
								<td align=center><input type="text" class="jichul" col=7 row=6 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=6 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=6 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>투자자산취득</td>
								<td align=center><input type="text" class="jichul" col=4 row=7 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=7 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=7 size=10></td>
																                              
								<td align=center>투자자산취득</td>                            
								<td align=center><input type="text" class="jichul" col=7 row=7 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=7 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=7 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>유형자산취득</td>
								<td align=center><input type="text" class="jichul" col=4 row=8 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=8 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=8 size=10></td>
																                              
								<td align=center>유형자산취득</td>                            
								<td align=center><input type="text" class="jichul" col=7 row=8 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=8 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=8 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>무형자산취득</td>
								<td align=center><input type="text" class="jichul" col=4 row=9 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=9 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=9 size=10></td>
																                              
								<td align=center>무형자산취득</td>                            
								<td align=center><input type="text" class="jichul" col=7 row=9 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=9 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=9 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>비가동설비자산취득</td>
								<td align=center><input type="text" size=10 class="jichul" col=4 row=10></td>
								<td align=center><input type="text" size=10 class="jichul" col=5 row=10></td>
								<td align=center><input type="text" size=10 class="jichul" col=6 row=10></td>
																		                              
								<td align=center>비가동설비자산취득</td>                              
								<td align=center><input type="text" size=10 class="jichul" col=7 row=10></td>
								<td align=center><input type="text" size=10 class="jichul" col=8 row=10></td>
								<td align=center><input type="text" size=10 class="jichul" col=9 row=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>유동부채상환</td>
								<td align=center><input type="text" class="jichul" col=4 row=11 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=11 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=11 size=10></td>
																                               
								<td align=center>유동부채상환</td>                             
								<td align=center><input type="text" class="jichul" col=7 row=11 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=11 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=11 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>비유동부채상환</td>
								<td align=center><input type="text" class="jichul" col=4 row=12 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=12 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=12 size=10></td>
																                               
								<td align=center>비유동부채상환</td>
								<td align=center><input type="text" class="jichul" col=7 row=12 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=12 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=12 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>기타자본적지출</td>
								<td align=center><input type="text" class="jichul" col=4 row=13 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=13 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=13 size=10></td>
																                               
								<td align=center>기타자본적지출</td>
								<td align=center><input type="text" class="jichul" col=7 row=13 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=13 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=13 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center>예비비</td>
								<td align=center><input type="text" class="jichul" col=4 row=14 size=10></td>
								<td align=center><input type="text" class="jichul" col=5 row=14 size=10></td>
								<td align=center><input type="text" class="jichul" col=6 row=14 size=10></td>
																                               
								<td align=center>예비비</td>	                               
								<td align=center><input type="text" class="jichul" col=7 row=14 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=14 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=14 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>이월예산지출</td>
								<td align=center><input type="text" class="jichul" col=7 row=15 size=10></td>
								<td align=center><input type="text" class="jichul" col=8 row=15 size=10></td>
								<td align=center><input type="text" class="jichul" col=9 row=15 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>- 자본적지출</td>
								<td align=center><input type="text" class="jichul" sub=1 col=7 row=16 size=10></td>
								<td align=center><input type="text" class="jichul" sub=1 col=8 row=16 size=10></td>
								<td align=center><input type="text" class="jichul" sub=1 col=9 row=16 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>- 수익적지출</td>
								<td align=center><input type="text" class="jichul" sub=1 col=7 row=17 size=10></td>
								<td align=center><input type="text" class="jichul" sub=1 col=8 row=17 size=10></td>
								<td align=center><input type="text" class="jichul" sub=1 col=9 row=17 size=10></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>
								<td align=center></td>

								<td align=center>-미지급비용.금</td>
								<td align=center><input type="text" class="jichul" sub=1 col=7 row=18 size=10></td>
								<td align=center><input type="text" class="jichul" sub=1 col=8 row=18 size=10></td>
								<td align=center><input type="text" class="jichul" sub=1 col=9 row=18 size=10></td>
							</tr>
							<!-- 지출 종료 ------------------------------------ -->


							<tr bgcolor="FFFFFF" height="25">
								<td align=center rowspan=6>차액<br>(I - II)</td>

								<td align=center rowspan=6>당기순이익<br>(자본형성)</td>
								<td align=center rowspan=6 class="tot3"></td>
								<td align=center rowspan=6 class="tot3"></td>
								<td align=center rowspan=6 class="tot3"></td>

								<td align=center rowspan=6>과부족<br>(자본형성)</td>
								<td align=center rowspan=6 class="tot3"></td>
								<td align=center rowspan=6 class="tot3"></td>
								<td align=center rowspan=6 class="tot3"></td>

								<td align=center rowspan=6>차기이월</td>
								<td align=center colspan=2>계</td>
								<td align=center class="tot3"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center colspan=2>순세계잉여금</td>
								<td align=center><input class="iwol" type="text" col=9 row=1 size=11></td>
							</tr>
							<tr bgcolor="FFFFFF" height="25">
								<td align=center colspan=2>사고이월금</td>
								<td align=center><input class="iwol" type="text" col=9 row=2 size=11></td>
							</tr>
							<tr bgcolor="FFFFFF" height="25">
								<td align=center colspan=2>건설개량이월금</td>
								<td align=center><input class="iwol" type="text" col=9 row=3 size=11></td>
							</tr>
							<tr bgcolor="FFFFFF" height="25">
								<td align=center colspan=2>계속비이월금</td>
								<td align=center><input class="iwol" type="text" col=9 row=4 size=11></td>
							</tr>
							<tr bgcolor="FFFFFF" height="25">
								<td align=center colspan=2>미지급비용 ㆍ 금</td>
								<td align=center><input class="iwol" type="text" col=9 row=5 size=11></td>
							</tr>
						</table>
					</td>
					<td width=20>&nbsp;</td>
					<td valign=top align=center>
						<table width=280 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
							<colgroup>
								<col width="50%">
								<col width="50%">
							</colgroup>
							
							<tr bgcolor="f6f6f6" height="50">
								<td align=center>미수금/미수수익</td>
								<td align=center>미지급금/미지급비용</td>
							</tr>	
							
							<tr bgcolor="FFFFFF" height="24">
								<td align=center class="adm1" row="0" col="1"></td>
								<td align=center class="adm1" row="0" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="24">
								<td align=center class="adm1" row="1" col="1"></td>
								<td align=center class="adm1" row="1" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="24">
								<td align=center class="adm1" row="2" col="1"></td>
								<td align=center class="adm1" row="2" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="3" col="1"></td>
								<td align=center class="adm1" row="3" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="4" col="1"></td>
								<td align=center class="adm1" row="4" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="5" col="1"></td>
								<td align=center class="adm1" row="5" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="6" col="1"></td>
								<td align=center class="adm1" row="6" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="7" col="1"></td>
								<td align=center class="adm1" row="7" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="8" col="1"></td>
								<td align=center class="adm1" row="8" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="9" col="1"></td>
								<td align=center class="adm1" row="9" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="10" col="1"></td>
								<td align=center class="adm1" row="10" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="11" col="1"></td>
								<td align=center class="adm1" row="11" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="12" col="1"></td>
								<td align=center class="adm1" row="12" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="13" col="1"></td>
								<td align=center class="adm1" row="13" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="14" col="1"></td>
								<td align=center class="adm1" row="14" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="15" col="1"></td>
								<td align=center class="adm1" row="15" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="16" col="1"></td>
								<td align=center class="adm1" row="16" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm1" row="17" col="1"></td>
								<td align=center class="adm1" row="17" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="0" col="1"></td>
								<td align=center class="adm2" row="0" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="1" col="1"></td>
								<td align=center class="adm2" row="1" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="2" col="1"></td>
								<td align=center class="adm2" row="2" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="3" col="1"></td>
								<td align=center class="adm2" row="3" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="4" col="1"></td>
								<td align=center class="adm2" row="4" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="5" col="1"></td>
								<td align=center class="adm2" row="5" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="6" col="1"></td>
								<td align=center class="adm2" row="6" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="7" col="1"></td>
								<td align=center class="adm2" row="7" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="8" col="1"></td>
								<td align=center class="adm2" row="8" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="9" col="1"></td>
								<td align=center class="adm2" row="9" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="10" col="1"></td>
								<td align=center class="adm2" row="10" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="11" col="1"></td>
								<td align=center class="adm2" row="11" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="12" col="1"></td>
								<td align=center class="adm2" row="12" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="13" col="1"></td>
								<td align=center class="adm2" row="13" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="14" col="1"></td>
								<td align=center class="adm2" row="14" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="15" col="1"></td>
								<td align=center class="adm2" row="15" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="16" col="1"></td>
								<td align=center class="adm2" row="16" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="25">
								<td align=center class="adm2" row="17" col="1"></td>
								<td align=center class="adm2" row="17" col="2"></td>
							</tr>

							<tr bgcolor="FFFFFF" height="24">
								<td align=center class="adm2" row="18" col="1"></td>
								<td align=center class="adm2" row="18" col="2"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>