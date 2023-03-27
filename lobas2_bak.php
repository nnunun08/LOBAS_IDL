
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
		$("input:eq(5)").focus();

		$("input").attr("size","19");

		$("input").on("keyup", function() {
			let row = $(this).attr("row");
			let col = $(this).attr("col");
			let cls = $(this).attr("class");
			let val = $(this).val().replace(/[^0-9]/g,"");

			//$(this).val($(this).val().replace(/[^0-9]/g,""));
			
			$(this).val(addComma(val));

			if (event.keyCode==13) {
				moveInput(row,col);
			}

			compute(cls,row,col);
		});
	});

	function comStep3(cls,row,col) {
		let pay = 0;
		let tot = 0;
		let val = "";
		let step = "1";
		let parent = "";

		/* ���ΰ�� step3��� */
		$("."+cls+"[col='"+col+"']").each(function(i){
			if (col=="4") {
				val = $(this).text();
			} else {
				val = $(this).val();
			}
			
			if (val!="") {
				pay = parseInt(val.replaceAll(",",""));
				tot += pay;
			}
		});
		
		/* ���������� �ִ��� ������ */
		if ($("."+cls+"[col='"+col+"']").length == 1) {
			step = "0";

			if (row>="36") {
				step = "1";
			}
		} else {
			step = "1";
		}

		if (step == "0") {
			/* 1���϶��� step3 �Է� �н� */
			if (tot!="0") {
				parent = $("."+cls+"[col='"+col+"']").attr("parent");
				comStep4(parent,row,col);
			}
		} else {
			if (tot=="0") {
				$("td[item='"+cls+"'][col='"+col+"']").text("");
			} else {
				/* ����3 �Է� */
				$("td[item='"+cls+"'][col='"+col+"']").text(addComma(tot));

				parent = $("td[item='"+cls+"'][col='"+col+"']").attr("parent");

				comStep4(parent,row,col);
			}
		}
	}

	function comStep4(parent,row,col) {
		let pay = 0;
		let tot = 0;

		$("td[parent='"+parent+"'][col='"+col+"']").each(function(i){
			if ($(this).text()!="") {
				pay = parseInt($(this).text().replaceAll(",",""));
				tot += pay;
			}
		});

		$("input[parent='"+parent+"'][col='"+col+"']").each(function(i){
			if ($(this).val()!="") {
				pay = parseInt($(this).val().replaceAll(",",""));
				tot += pay;
			}
		});

		$("td[class='"+parent+"'][col='"+col+"']").text(addComma(tot));
		
		/* step5 ��� */
		tot = 0;
		$("td[step='2'][col='"+col+"']").each(function(i){
			if ($(this).text()!="") {
				pay = parseInt($(this).text().replaceAll(",",""));
				tot += pay;
			}
		});

		$("td[class='600'][col='"+col+"']").text(addComma(tot));
	}

	function compute(cls,row,col) {
		let kap = [];
		let pay = "";
		
		for (i=1;i<=5;i++) {
			kap[i] = 0;

			if (i!=4) {
				if ($("."+cls+"[row='"+row+"'][col='"+i+"']").val()!="") {
					pay = $("."+cls+"[row='"+row+"'][col='"+i+"']").val().replaceAll(",","");
					kap[i] = parseInt(pay);
				}
			}
		}
		
		/* ���ΰ�� */
		if (col=="2" || col=="3") {
			if (isNaN(kap[2])) kap[2] = 0;
			if (isNaN(kap[3])) kap[3] = 0;

			kap[4] = kap[2]-kap[3];
			$("."+cls+"[row='"+row+"'][col='4']").text(addComma(kap[4]));
		}

		/* ���� step3 ��� */
		comStep3(cls,row,col);

		if (col=="2" || col=="3") {
			/* 4������ �Է��� �ȵǴϱ� 2,3 �϶� ��� */
			comStep3(cls,row,'4');
		}
	}

	function moveInput(row,col) {
		let next = 0;
		
		if (col=="1" || col=="2") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").focus();
		} else if (col=="3") {
			next = parseInt(col)+1;
			$("input[col='5'][row='"+row+"']").focus();
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
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">������ ����</span>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td class="titLine" height="1" bgcolor="#E7E7E7"></td>
	</tr>
	<tr>
		<td>
			<table width="950" cellpadding="1" cellspacing="1" border="0" bgcolor=#DDDDDD>
				<tr height="28" bgcolor="#FFFFFF">
					<td style="padding: 5px;">
						[�ۼ����]<br>
						1. �� ȭ���� ����� �ιٽ� ����ڷ� �� ������� ��꺸����-������������ �ش� ������ �Է��մϴ�.<br>
						2. ����ڴ� �ιٽ�����  ��������꺸����-������ ���� �ڷḦ �������Ϸ� �ٿ���� �� �ش� ���������� �ݾ��� �Է�(����/�ٿ��ֱ� )�մϴ�.<br>
						3. �ݾ��� ���� ����������  �Է����� �ʰ� ����Ӵϴ�.<br>
						4. �Է��� �������Դϴ�. 
					</td>
				</tr>
			</table>
		</td>
	</tr>
	

	<tr>
		<td>
			<table width=950 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="4%">
					<col width="5%">
					<col width="7%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
					<col width="14%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td align=center colspan=4>����</td>
					<td align=center rowspan=2>����<br>(¡��������)(B)</td>
					<td align=center colspan=3>������</td>
					<td align=center rowspan=2>�̼���<br>(B-C-F)</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td align=center>��</td>
					<td align=center>��</td>
					<td align=center>����</td>
					<td align=center>��</td>
					<td align=center>�� �� ��(D)</td>
					<td align=center>������ȯ�Ҿ�(E)</td>
					<td align=center>����������(C=D-E)</td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td colspan=4 class="pd">600 �������</td>
					<td align=center class="600" col="1"></td>
					<td align=center class="600" col="2"></td>
					<td align=center class="600" col="3"></td>
					<td align=center class="600" col="4"></td>
					<td align=center class="600" col="5"></td>
				</tr>
				
				<!-- 610 -->
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">610 ��������</td>
				
					<td align=center class="610" step="2" col="1"></td>
					<td align=center class="610" step="2" col="2"></td>
					<td align=center class="610" step="2" col="3"></td>
					<td align=center class="610" step="2" col="4"></td>
					<td align=center class="610" step="2" col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">611 �������</td>
				
					<td align=center item="611" parent="610"  col="1"></td>
					<td align=center item="611" parent="610" col="2"></td>
					<td align=center item="611" parent="610" col="3"></td>
					<td align=center item="611" parent="610" col="4"></td>
					<td align=center item="611" parent="610" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 ������</td>
				
					<td align=center><input type="text" class="611" row=1 col="1"></td>
					<td align=center><input type="text" class="611" row=1 col="2"></td>
					<td align=center><input type="text" class="611" row=1 col="3"></td>
					<td align=center class="611" row=1 col="4"></td>
					<td align=center><input type="text" class="611" row=1 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02 ������</td>
				
					<td align=center><input type="text" class="611" row=2 col="1"></td>
					<td align=center><input type="text" class="611" row=2 col="2"></td>
					<td align=center><input type="text" class="611" row=2 col="3"></td>
					<td align=center class="611" row=2 col="4"></td>
					<td align=center><input type="text" class="611" row=2 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03 �Ϲݿ�</td>
				
					<td align=center><input type="text" class="611" row=3 col="1"></td>
					<td align=center><input type="text" class="611" row=3 col="2"></td>
					<td align=center><input type="text" class="611" row=3 col="3"></td>
					<td align=center class="611" row=3 col="4"></td>
					<td align=center><input type="text" class="611" row=3 col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04 ��������</td>
				
					<td align=center><input type="text" class="611" row=4 col="1"></td>
					<td align=center><input type="text" class="611" row=4 col="2"></td>
					<td align=center><input type="text" class="611" row=4 col="3"></td>
					<td align=center class="611" row=4 col="4"></td>
					<td align=center><input type="text" class="611" row=4 col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05 ����װ�����</td>
				
					<td align=center><input type="text" class="611" row=5 col="1"></td>
					<td align=center><input type="text" class="611" row=5 col="2"></td>
					<td align=center><input type="text" class="611" row=5 col="3"></td>
					<td align=center class="611" row=5 col="4"></td>
					<td align=center><input type="text" class="611" row=5 col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">08 ������ �Ǹż���</td>
				
					<td align=center><input type="text" class="611" row=6 col="1"></td>
					<td align=center><input type="text" class="611" row=6 col="2"></td>
					<td align=center><input type="text" class="611" row=6 col="3"></td>
					<td align=center class="611" row=6 col="4"></td>
					<td align=center><input type="text" class="611" row=6 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09 ��Ÿ���ϼ�������</td>
				
					<td align=center><input type="text" class="611" row=7 col="1"></td>
					<td align=center><input type="text" class="611" row=7 col="2"></td>
					<td align=center><input type="text" class="611" row=7 col="3"></td>
					<td align=center class="611" row=7 col="4"></td>
					<td align=center><input type="text" class="611" row=7 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>�����</td>
					<td colspan=2 class="pd">612 �ޡ�����������</td>
				
					<td align=center item="612" parent="610" col="1"></td>
					<td align=center item="612" parent="610" col="2"></td>
					<td align=center item="612" parent="610" col="3"></td>
					<td align=center item="612" parent="610" col="4"></td>
					<td align=center item="612" parent="610" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 �ż��������</td>
				
					<td align=center><input type="text" class="612" row=8 col="1"></td>
					<td align=center><input type="text" class="612" row=8 col="2"></td>
					<td align=center><input type="text" class="612" row=8 col="3"></td>
					<td align=center class="612" row=8 col="4"></td>
					<td align=center><input type="text" class="612" row=8 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02 �����������</td>
				
					<td align=center><input type="text" class="612" row=9 col="1"></td>
					<td align=center><input type="text" class="612" row=9 col="2"></td>
					<td align=center><input type="text" class="612" row=9 col="3"></td>
					<td align=center class="612" row=9 col="4"></td>
					<td align=center><input type="text" class="612" row=9 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>�ϼ���</td>
					<td colspan=2 class="pd">613 ����ó���δ��</td>
				
					<td align=center><input type="text" class="613" parent="610" row=10 col="1"></td>
					<td align=center><input type="text" class="613" parent="610" row=10 col="2"></td>
					<td align=center><input type="text" class="613" parent="610" row=10 col="3"></td>
					<td align=center class="613" parent="610" row=1 col="4"></td>
					<td align=center><input type="text" class="613" parent="610" row=10 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">661 ��Ÿ��������</td>
				
					<td align=center item="661" parent="610" col="1"></td>
					<td align=center item="661" parent="610" col="2"></td>
					<td align=center item="661" parent="610" col="3"></td>
					<td align=center item="661" parent="610" col="4"></td>
					<td align=center item="661" parent="610" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02 ��Ÿ�������</td>
				
					<td align=center><input type="text" class="661" row=11 col="1"></td>
					<td align=center><input type="text" class="661" row=11 col="2"></td>
					<td align=center><input type="text" class="661" row=11 col="3"></td>
					<td align=center class="661" row=11 col="4"></td>
					<td align=center><input type="text" class="661" row=11 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03 ��Ÿ���������</td>
				
					<td align=center><input type="text" class="661" row=12 col="1"></td>
					<td align=center><input type="text" class="661" row=12 col="2"></td>
					<td align=center><input type="text" class="661" row=12 col="3"></td>
					<td align=center class="661" row=12 col="4"></td>
					<td align=center><input type="text" class="661" row=12 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04 ���������</td>
				
					<td align=center><input type="text" class="661" row=13 col="1"></td>
					<td align=center><input type="text" class="661" row=13 col="2"></td>
					<td align=center><input type="text" class="661" row=13 col="3"></td>
					<td align=center class="661" row=13 col="4"></td>
					<td align=center><input type="text" class="661" row=13 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05 �δ�ü��Ӵ����</td>
				
					<td align=center><input type="text" class="661" row=14 col="1"></td>
					<td align=center><input type="text" class="661" row=14 col="2"></td>
					<td align=center><input type="text" class="661" row=14 col="3"></td>
					<td align=center class="661" row=14 col="4"></td>
					<td align=center><input type="text" class="661" row=14 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09 ��Ÿ��������</td>
				
					<td align=center><input type="text" class="661" row=15 col="1"></td>
					<td align=center><input type="text" class="661" row=15 col="2"></td>
					<td align=center><input type="text" class="661" row=15 col="3"></td>
					<td align=center class="661" row=15 col="4"></td>
					<td align=center><input type="text" class="661" row=15 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">670 �����ܼ���</td>
				
					<td align=center class="670" step="2" col="1"></td>
					<td align=center class="670" step="2" col="2"></td>
					<td align=center class="670" step="2" col="3"></td>
					<td align=center class="670" step="2" col="4"></td>
					<td align=center class="670" step="2" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">671 ���ڼ���</td>
				
					<td align=center item="671" parent="670" col="1"></td>
					<td align=center item="671" parent="670" col="2"></td>
					<td align=center item="671" parent="670" col="3"></td>
					<td align=center item="671" parent="670" col="4"></td>
					<td align=center item="671" parent="670" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 �������ڼ���</td>
				
					<td align=center><input type="text" class="671" row=16 col="1"></td>
					<td align=center><input type="text" class="671" row=16 col="2"></td>
					<td align=center><input type="text" class="671" row=16 col="3"></td>
					<td align=center class="671" row=16 col="4"></td>
					<td align=center><input type="text" class="671" row=16 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02 �뿩�����ڼ���</td>
				
					<td align=center><input type="text" class="671" row=17 col="1"></td>
					<td align=center><input type="text" class="671" row=17 col="2"></td>
					<td align=center><input type="text" class="671" row=17 col="3"></td>
					<td align=center class="671" row=17 col="4"></td>
					<td align=center><input type="text" class="671" row=17 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09 ��Ÿ���ڼ���</td>
				
					<td align=center><input type="text" class="671" row=18 col="1"></td>
					<td align=center><input type="text" class="671" row=18 col="2"></td>
					<td align=center><input type="text" class="671" row=18 col="3"></td>
					<td align=center class="671" row=18 col="4"></td>
					<td align=center><input type="text" class="671" row=18 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">672 Ÿȸ�����Աݼ���</td>
				
					<td align=center item="672" parent="670" col="1"></td>
					<td align=center item="672" parent="670" col="2"></td>
					<td align=center item="672" parent="670" col="3"></td>
					<td align=center item="672" parent="670" col="4"></td>
					<td align=center item="672" parent="670" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 �Ϲ�ȸ�����Ա�</td>
				
					<td align=center><input type="text" class="672" row=19 col="1"></td>
					<td align=center><input type="text" class="672" row=19 col="2"></td>
					<td align=center><input type="text" class="672" row=19 col="3"></td>
					<td align=center class="672" row=19 col="4"></td>
					<td align=center><input type="text" class="672" row=19 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02 ŸƯ��ȸ�����Ա�</td>
				
					<td align=center><input type="text" class="672" row=20 col="1"></td>
					<td align=center><input type="text" class="672" row=20 col="2"></td>
					<td align=center><input type="text" class="672" row=20 col="3"></td>
					<td align=center class="672" row=20 col="4"></td>
					<td align=center><input type="text" class="672" row=20 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03 �Ϲ�ȸ��������</td>
				
					<td align=center><input type="text" class="672" row=21 col="1"></td>
					<td align=center><input type="text" class="672" row=21 col="2"></td>
					<td align=center><input type="text" class="672" row=21 col="3"></td>
					<td align=center class="672" row=21 col="4"></td>
					<td align=center><input type="text" class="672" row=21 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04 ŸƯ��ȸ��������</td>
				
					<td align=center><input type="text" class="672" row=22 col="1"></td>
					<td align=center><input type="text" class="672" row=22 col="2"></td>
					<td align=center><input type="text" class="672" row=22 col="3"></td>
					<td align=center class="672" row=22 col="4"></td>
					<td align=center><input type="text" class="672" row=22 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05 �õ�������</td>
				
					<td align=center><input type="text" class="672" row=23 col="1"></td>
					<td align=center><input type="text" class="672" row=23 col="2"></td>
					<td align=center><input type="text" class="672" row=23 col="3"></td>
					<td align=center class="672" row=23 col="4"></td>
					<td align=center><input type="text" class="672" row=23 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06 ����������</td>
				
					<td align=center><input type="text" class="672" row=24 col="1"></td>
					<td align=center><input type="text" class="672" row=24 col="2"></td>
					<td align=center><input type="text" class="672" row=24 col="3"></td>
					<td align=center class="672" row=24 col="4"></td>
					<td align=center><input type="text" class="672" row=24 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09 ��Ÿ���Ա�</td>
				
					<td align=center><input type="text" class="672" row=25 col="1"></td>
					<td align=center><input type="text" class="672" row=25 col="2"></td>
					<td align=center><input type="text" class="672" row=25 col="3"></td>
					<td align=center class="672" row=25 col="4"></td>
					<td align=center><input type="text" class="672" row=25 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">673 ���κ����ݼ���</td>
				
					<td align=center><input type="text" class="673" parent="670" row=26 col="1"></td>
					<td align=center><input type="text" class="673" parent="670" row=26 col="2"></td>
					<td align=center><input type="text" class="673" parent="670" row=26 col="3"></td>
					<td align=center class="673" parent="670" row=26 col="4"></td>
					<td align=center><input type="text" class="673" parent="670" row=26 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">674 �����ڻ�ó������</td>
				
					<td align=center><input type="text" class="674" parent="670" row=27 col="1"></td>
					<td align=center><input type="text" class="674" parent="670" row=27 col="2"></td>
					<td align=center><input type="text" class="674" parent="670" row=27 col="3"></td>
					<td align=center class="674" parent="670" row=27 col="4"></td>
					<td align=center><input type="text" class="674" parent="670" row=27 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">675 ��ȭȯ������</td>
				
					<td align=center><input type="text" class="675" parent="670" row=28 col="1"></td>
					<td align=center><input type="text" class="675" parent="670" row=28 col="2"></td>
					<td align=center><input type="text" class="675" parent="670" row=28 col="3"></td>
					<td align=center class="675" parent="670" row=28 col="4"></td>
					<td align=center><input type="text" class="675" parent="670" row=28 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">676 ���������������</td>
				
					<td align=center><input type="text" class="676" parent="670" row=29 col="1"></td>
					<td align=center><input type="text" class="676" parent="670" row=29 col="2"></td>
					<td align=center><input type="text" class="676" parent="670" row=29 col="3"></td>
					<td align=center class="676" parent="670" row=29 col="4"></td>
					<td align=center><input type="text" class="676" parent="670" row=29 col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">677 ä����������</td>
				
					<td align=center><input type="text" class="677" parent="670" row=30 col="1"></td>
					<td align=center><input type="text" class="677" parent="670" row=30 col="2"></td>
					<td align=center><input type="text" class="677" parent="670" row=30 col="3"></td>
					<td align=center class="677" parent="670" row=30 col="4"></td>
					<td align=center><input type="text" class="677" parent="670" row=30 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center>����</td>
					<td colspan=2 class="pd">678 �����ڻ�ó������</td>
				
					<td align=center><input type="text" class="678" parent="670" row=31 col="1"></td>
					<td align=center><input type="text" class="678" parent="670" row=31 col="2"></td>
					<td align=center><input type="text" class="678" parent="670" row=31 col="3"></td>
					<td align=center class="678" parent="670" row=31 col="4"></td>
					<td align=center><input type="text" class="678" parent="670" row=31 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">679 ��Ÿ�����ܼ���</td>
				
					<td align=center item="679" parent="670" col="1"></td>
					<td align=center item="679" parent="670" col="2"></td>
					<td align=center item="679" parent="670" col="3"></td>
					<td align=center item="679" parent="670" col="4"></td>
					<td align=center item="679" parent="670" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 ��Ÿ�Ӵ����</td>
				
					<td align=center><input type="text" class="679" row=32 col="1"></td>
					<td align=center><input type="text" class="679" row=32 col="2"></td>
					<td align=center><input type="text" class="679" row=32 col="3"></td>
					<td align=center class="679" row=32 col="4"></td>
					<td align=center><input type="text" class="679" row=32 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02 �Ӵ��������</td>
				
					<td align=center><input type="text" class="679" row=33 col="1"></td>
					<td align=center><input type="text" class="679" row=33 col="2"></td>
					<td align=center><input type="text" class="679" row=33 col="3"></td>
					<td align=center class="679" row=33 col="4"></td>
					<td align=center><input type="text" class="679" row=33 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03 �ҿ�ǰ�Ű�����</td>
				
					<td align=center><input type="text" class="679" row=34 col="1"></td>
					<td align=center><input type="text" class="679" row=34 col="2"></td>
					<td align=center><input type="text" class="679" row=34 col="3"></td>
					<td align=center class="679" row=34 col="4"></td>
					<td align=center><input type="text" class="679" row=34 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04 ����ݹ�����ݼ���</td>
				
					<td align=center><input type="text" class="679" row=35 col="1"></td>
					<td align=center><input type="text" class="679" row=35 col="2"></td>
					<td align=center><input type="text" class="679" row=35 col="3"></td>
					<td align=center class="679" row=35 col="4"></td>
					<td align=center><input type="text" class="679" row=35 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=3 class="pd">680 Ư������</td>
				
					<td align=center class="680" step="2" col="1"></td>
					<td align=center class="680" step="2" col="2"></td>
					<td align=center class="680" step="2" col="3"></td>
					<td align=center class="680" step="2" col="4"></td>
					<td align=center class="680" step="2" col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">681 ������ͼ�������</td>
				
					<td align=center item="681" parent="680" col="1"></td>
					<td align=center item="681" parent="680" col="2"></td>
					<td align=center item="681" parent="680" col="3"></td>
					<td align=center item="681" parent="680" col="4"></td>
					<td align=center item="681" parent="680" col="5"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 ������ͼ�������</td>
				
					<td align=center><input type="text" class="681" row=36 col="1"></td>
					<td align=center><input type="text" class="681" row=36 col="2"></td>
					<td align=center><input type="text" class="681" row=36 col="3"></td>
					<td align=center class="681" row=36 col="4"></td>
					<td align=center><input type="text" class="681" row=36 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">682 ä����������</td>
				
					<td align=center item="682" parent="680" col="1"></td>
					<td align=center item="682" parent="680" col="2"></td>
					<td align=center item="682" parent="680" col="3"></td>
					<td align=center item="682" parent="680" col="4"></td>
					<td align=center item="682" parent="680" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01 ä����������</td>
				
					<td align=center><input type="text" class="682" row=37 col="1"></td>
					<td align=center><input type="text" class="682" row=37 col="2"></td>
					<td align=center><input type="text" class="682" row=37 col="3"></td>
					<td align=center class="682" row=37 col="4"></td>
					<td align=center><input type="text" class="682" row=37 col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=2 class="pd">689 ��ŸƯ������</td>
				
					<td align=center item="689" parent="680" col="1"></td>
					<td align=center item="689" parent="680" col="2"></td>
					<td align=center item="689" parent="680" col="3"></td>
					<td align=center item="689" parent="680" col="4"></td>
					<td align=center item="689" parent="680" col="5"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">09 ��ŸƯ������</td>
				
					<td align=center><input type="text" class="689" row=38 col="1"></td>
					<td align=center><input type="text" class="689" row=38 col="2"></td>
					<td align=center><input type="text" class="689" row=38 col="3"></td>
					<td align=center class="689" row=38 col="4"></td>
					<td align=center><input type="text" class="689" row=38 col="5"></td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>