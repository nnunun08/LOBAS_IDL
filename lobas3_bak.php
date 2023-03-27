
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

		$("input[col='3']").attr("disabled",true);
		$("input[col='3']").css("border","none");
		$("input[col='3']").css("background","white");
		
		$("input").on("keyup", function() {
			let row = $(this).attr("row");
			let col = $(this).attr("col");
			let cls = $(this).attr("class");
			let parent = $(this).attr("parent");

			let val = $(this).val().replace(/[^0-9]/g,"");

			$(this).val(addComma(val));

			if (event.keyCode==13) {
				moveInput(row,col);
			}

			/* ���� tr�� �ε��� */
			var idx = $(this).parent().parent().index();

			tdsu = $("#tbl tr:eq("+idx+") td").length;
			compute(parent,cls,row,col,tdsu);

			if (col=="1" || col=="2") {
				compute(parent,cls,row,"3",tdsu);
			}
		});
	});
	
	function compute(parent,cls,row,col,tdsu) {
		let kap = [];
		let pay = "";
		
		for (i=1;i<=4;i++) {
			kap[i] = 0;

			if (i!=3) {
				if ($("."+cls+"[row='"+row+"'][col='"+i+"']").val()!="") {
					pay = $("."+cls+"[row='"+row+"'][col='"+i+"']").val().replaceAll(",","");
					kap[i] = parseInt(pay);
				}
			}
		}

		/* ���ΰ�� */
		if (col=="1" || col=="2") {
			kap[3] = kap[1]-kap[2];
			$("."+cls+"[row='"+row+"'][col='3']").val(addComma(kap[3]));
		}

		/* ���ΰ�� */
		verticalComp(parent,cls,row,col,tdsu);
	}

	function verticalComp(parent,cls,row,col,tdsu) {
		/* 
			tdsu ( 9:step5, 8:step4, 7:step3 6:step2)
		*/
		
		let pay = 0;
		let tot = 0;
		let val = "";
		
		let selector = "";
		
		selector = "."+cls+"[col='"+col+"'][parent='"+parent+"']";

		$(selector).each(function(i){
			val = $(this).val();
			if (val!="") {
				pay = parseInt(val.replaceAll(",",""));
				tot += pay;
			}
		});

		if (tdsu=="9") {
			/* step4 �� */
			$("td[item='"+cls+"'][col='"+col+"'][parent='"+parent+"']").text(addComma(tot));
		}

		/* step3 �� ���� */
		tot = 0;

		$("td[parent='"+parent+"'][step='4'][col='"+col+"']").each(function(){
			if ($(this).text()!="") {
				pay = parseInt($(this).text().replaceAll(",",""));
				tot += pay;
			}
		});

		$("input[parent='"+parent+"'][step='4'][col='"+col+"']").each(function(){
			if ($(this).val()!="") {
				pay = parseInt($(this).val().replaceAll(",",""));
				tot += pay;
			}
		});

		$("td[class='"+parent+"'][col='"+col+"'][step='3']").text(addComma(tot));

		/* step2 �� ���� */
		tot = 0;
		
		if (tdsu>7) {
			/* �ش�Ǵ� Ŭ���� ������ �����´� */
			parent = $("."+parent+"[col='"+col+"']").attr("parent");
		}

		$("td[parent='"+parent+"'][step='3'][col='"+col+"']").each(function(){
			if ($(this).text()!="") {
				pay = parseInt($(this).text().replaceAll(",",""));
				tot += pay;
			}
		});

		$("input[parent='"+parent+"'][step='3'][col='"+col+"']").each(function(){
			if ($(this).val()!="") {
				pay = parseInt($(this).val().replaceAll(",",""));
				tot += pay;
			}
		});
		
		$("td[class='"+parent+"'][col='"+col+"'][step='2']").text(addComma(tot));
		
		/* step1 �� ���� */
		tot = 0;
		$("td[step='2'][col='"+col+"']").each(function(){
			if ($(this).text()!="") {
				pay = parseInt($(this).text().replaceAll(",",""));
				tot += pay;
			}
		});

		$("input[step='2'][col='"+col+"']").each(function(){
			if ($(this).val()!="") {
				pay = parseInt($(this).val().replaceAll(",",""));
				tot += pay;
			}
		});

		$("td[class='700'][col='"+col+"']").text(addComma(tot));
	}

	function moveInput(row,col) {
		let next = 0;
		
		if (col=="1") {
			next = parseInt(col)+1;
			$("input[col='"+next+"'][row='"+row+"']").focus();
		} else if (col=="2") {
			$("input[col='4'][row='"+row+"']").focus();
		} else if (col=="4") {
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
			<table width="1100" cellpadding="1" cellspacing="1" border="0" bgcolor=#DDDDDD>
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
					<td align=center colspan=5>����</td>
					<td align=center rowspan=2>����<br>(ä��Ȯ����)<br>(C)</td>
					<td align=center rowspan=2>�����<br>(D)</td>
					<td align=center rowspan=2>�����ޱ�<br>(C-D)</td>
					<td align=center rowspan=2>�ͳ⵵����̿���<br>(E)</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td align=center>��</td>
					<td align=center>��</td>
					<td align=center>����</td>
					<td align=center colspan=2>��</td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td colspan=5 class="pd">700<br>������</td>
					<td align=center class="700" col="1"></td>
					<td align=center class="700" col="2"></td>
					<td align=center class="700" col="3"></td>
					<td align=center class="700" col="4"></td>
				</tr>
				<!-- 710 -->
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">710<br>�������</td>
				
					<td align=center class="710" step="2" col="1"></td>
					<td align=center class="710" step="2" col="2"></td>
					<td align=center class="710" step="2" col="3"></td>
					<td align=center class="710" step="2" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">711<br>�����������</td>
				
					<td align=center class="711" step="3" parent="710" col="1"></td>
					<td align=center class="711" step="3" parent="710" col="2"></td>
					<td align=center class="711" step="3" parent="710" col="3"></td>
					<td align=center class="711" step="3" parent="710" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>�ΰ�����</td>
				
					<td align=center parent="711" step="4" item="307" col="1"></td>
					<td align=center parent="711" step="4" item="307" col="2"></td>
					<td align=center parent="711" step="4" item="307" col="3"></td>
					<td align=center parent="711" step="4" item="307" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>�ΰ����������</td>
				
					<td align=center><input type="text" parent="711" class="307" row="1" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="1" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="1" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="1" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>�ΰ���ü���������</td>
				
					<td align=center><input type="text" parent="711" class="307" row="2" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="2" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="2" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="2" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>�ΰ����������</td>
				
					<td align=center><input type="text" parent="711" class="307" row="3" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="3" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="3" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="3" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>�ΰ���Ź��</td>
				
					<td align=center><input type="text" parent="711" class="307" row="4" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="4" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="4" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="4" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">07<br>�������ޱ�</td>
				
					<td align=center><input type="text" parent="711" class="307" row="5" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="5" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="5" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="5" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">08<br>����������</td>
				
					<td align=center><input type="text" parent="711" class="307" row="6" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="6" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="6" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="6" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>�ΰ���ħȰ��������</td>
				
					<td align=center><input type="text" parent="711" class="307" row="7" col="1"></td>
					<td align=center><input type="text" parent="711" class="307" row="7" col="2"></td>
					<td align=center><input type="text" parent="711" class="307" row="7" col="3"></td>
					<td align=center><input type="text" parent="711" class="307" row="7" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>308<br>��ġ��ü������</td>
				
					<td align=center><input type="text" step="4" class="308" parent="711" row="8" col="1"></td>
					<td align=center><input type="text" step="4" class="308" parent="711" row="8" col="2"></td>
					<td align=center><input type="text" step="4" class="308" parent="711" row="8" col="3"></td>
					<td align=center><input type="text" step="4" class="308" parent="711" row="8" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>309<br>�����</td>
				
					<td align=center><input type="text" step="4" class="309" parent="711" row="9" col="1"></td>
					<td align=center><input type="text" step="4" class="309" parent="711" row="9" col="2"></td>
					<td align=center><input type="text" step="4" class="309" parent="711" row="9" col="3"></td>
					<td align=center><input type="text" step="4" class="309" parent="711" row="9" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>310<br>��������</td>
				
					<td align=center><input type="text" step="4" class="310" parent="711" row="10" col="1"></td>
					<td align=center><input type="text" step="4" class="310" parent="711" row="10" col="2"></td>
					<td align=center><input type="text" step="4" class="310" parent="711" row="10" col="3"></td>
					<td align=center><input type="text" step="4" class="310" parent="711" row="10" col="4"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>311<br>���Ա����ڻ�ȯ</td>
				
					<td align=center step="4" item="311" parent="711" col="1"></td>
					<td align=center step="4" item="311" parent="711" col="2"></td>
					<td align=center step="4" item="311" parent="711" col="3"></td>
					<td align=center step="4" item="311" parent="711" col="4"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>�õ��������߱�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="11" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="11" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="11" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="11" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>�ñ����������߱�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="12" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="12" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="12" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="12" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>��ȭ����������Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="13" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="13" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="13" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="13" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>�߾��������Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="14" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="14" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="14" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="14" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06<br>��Ÿ���Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="15" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="15" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="15" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="15" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">10<br>Ÿȸ�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="16" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="16" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="16" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="16" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>���ä�������</td>
				
					<td align=center><input type="text" parent="711" class="311" row="17" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="17" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="17" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="17" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">12<br>��ġ��ü���Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="18" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="18" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="18" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="18" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>�������ä���ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="711" class="311" row="19" col="1"></td>
					<td align=center><input type="text" parent="711" class="311" row="19" col="2"></td>
					<td align=center><input type="text" parent="711" class="311" row="19" col="3"></td>
					<td align=center><input type="text" parent="711" class="311" row="19" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>�������������������</td>
				
					<td align=center><input type="text" step="4" class="316" parent="711" row="20" col="1"></td>
					<td align=center><input type="text" step="4" class="316" parent="711" row="20" col="2"></td>
					<td align=center><input type="text" step="4" class="316" parent="711" row="20" col="3"></td>
					<td align=center><input type="text" step="4" class="316" parent="711" row="20" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>317<br>�����Ư��ȸ�谣�δ��</td>
				
					<td align=center><input type="text" step="4" class="317" parent="711" row="21" col="1"></td>
					<td align=center><input type="text" step="4" class="317" parent="711" row="21" col="2"></td>
					<td align=center><input type="text" step="4" class="317" parent="711" row="21" col="3"></td>
					<td align=center><input type="text" step="4" class="317" parent="711" row="21" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>318<br>��α�</td>
				
					<td align=center><input type="text" step="4" class="318" parent="711" row="22" col="1"></td>
					<td align=center><input type="text" step="4" class="318" parent="711" row="22" col="2"></td>
					<td align=center><input type="text" step="4" class="318" parent="711" row="22" col="3"></td>
					<td align=center><input type="text" step="4" class="318" parent="711" row="22" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>319<br>���������ְ��濵������ü�δ��</td>
				
					<td align=center><input type="text" step="4" class="319" parent="711" row="23" col="1"></td>
					<td align=center><input type="text" step="4" class="319" parent="711" row="23" col="2"></td>
					<td align=center><input type="text" step="4" class="319" parent="711" row="23" col="3"></td>
					<td align=center><input type="text" step="4" class="319" parent="711" row="23" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">712<br>������</td>
				
					<td align=center step="3" class="712" parent="710" col="1"></td>
					<td align=center step="3" class="712" parent="710" col="2"></td>
					<td align=center step="3" class="712" parent="710" col="3"></td>
					<td align=center step="3" class="712" parent="710" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>�ΰ�����</td>
				
					<td align=center step="4" item="307" parent="712" col="1"></td>
					<td align=center step="4" item="307" parent="712" col="2"></td>
					<td align=center step="4" item="307" parent="712" col="3"></td>
					<td align=center step="4" item="307" parent="712" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>�ΰ����������</td>
				
					<td align=center><input type="text" parent="712" class="307" row="24" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="24" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="24" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="24" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>�ΰ���ü���������</td>
				
					<td align=center><input type="text" parent="712" class="307" row="25" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="25" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="25" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="25" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>�ΰ����������</td>
				
					<td align=center><input type="text" parent="712" class="307" row="26" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="26" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="26" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="26" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>�ΰ���Ź��</td>
				
					<td align=center><input type="text" parent="712" class="307" row="27" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="27" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="27" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="27" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">07<br>�������ޱ�</td>
				
					<td align=center><input type="text" parent="712" class="307" row="28" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="28" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="28" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="28" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">08<br>����������</td>
				
					<td align=center><input type="text" parent="712" class="307" row="29" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="29" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="29" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="29" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>�ΰ���ħȰ��������</td>
				
					<td align=center><input type="text" parent="712" class="307" row="30" col="1"></td>
					<td align=center><input type="text" parent="712" class="307" row="30" col="2"></td>
					<td align=center><input type="text" parent="712" class="307" row="30" col="3"></td>
					<td align=center><input type="text" parent="712" class="307" row="30" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>311<br>���Ա����ڻ�ȯ</td>
				
					<td align=center step="4" item="311" parent="712" col="1"></td>
					<td align=center step="4" item="311" parent="712" col="2"></td>
					<td align=center step="4" item="311" parent="712" col="3"></td>
					<td align=center step="4" item="311" parent="712" col="4"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>�õ��������߱�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="31" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="31" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="31" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="31" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>�ñ����������߱�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="32" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="32" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="32" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="32" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>��ȭ����������Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="33" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="33" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="33" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="33" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>�߾��������Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="34" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="34" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="34" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="34" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06<br>��Ÿ���Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="35" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="35" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="35" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="35" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">10<br>Ÿȸ�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="36" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="36" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="36" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="36" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>���ä�������</td>
				
					<td align=center><input type="text" parent="712" class="311" row="37" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="37" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="37" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="37" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">12<br>��ġ��ü���Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="38" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="38" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="38" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="38" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>�������ä���ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="712" class="311" row="39" col="1"></td>
					<td align=center><input type="text" parent="712" class="311" row="39" col="2"></td>
					<td align=center><input type="text" parent="712" class="311" row="39" col="3"></td>
					<td align=center><input type="text" parent="712" class="311" row="39" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>�������������������</td>
				
					<td align=center><input type="text" step="4" parent="712" class="316" row="40" col="1"></td>
					<td align=center><input type="text" step="4" parent="712" class="316" row="40" col="2"></td>
					<td align=center><input type="text" step="4" parent="712" class="316" row="40" col="3"></td>
					<td align=center><input type="text" step="4" parent="712" class="316" row="40" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">713<br>�衤�޼���</td>
				
					<td align=center step="3" class="713" parent="710" col="1"></td>
					<td align=center step="3" class="713" parent="710" col="2"></td>
					<td align=center step="3" class="713" parent="710" col="3"></td>
					<td align=center step="3" class="713" parent="710" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>�ΰ�����</td>
				
					<td align=center step="4" item="307" col="1" parent="713"></td>
					<td align=center step="4" item="307" col="2" parent="713"></td>
					<td align=center step="4" item="307" col="3" parent="713"></td>
					<td align=center step="4" item="307" col="4" parent="713"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>�ΰ����������</td>
				
					<td align=center><input type="text" parent="713" class="307" row="41" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="41" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="41" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="41" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>�ΰ���ü���������</td>
				
					<td align=center><input type="text" parent="713" class="307" row="42" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="42" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="42" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="42" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>�ΰ����������</td>
				
					<td align=center><input type="text" parent="713" class="307" row="43" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="43" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="43" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="43" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>�ΰ���Ź��</td>
				
					<td align=center><input type="text" parent="713" class="307" row="44" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="44" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="44" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="44" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">07<br>�������ޱ�</td>
				
					<td align=center><input type="text" parent="713" class="307" row="45" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="45" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="45" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="45" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">08<br>����������</td>
				
					<td align=center><input type="text" parent="713" class="307" row="46" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="46" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="46" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="46" col="4"></td>
				</tr>
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>�ΰ���ħȰ��������</td>
				
					<td align=center><input type="text" parent="713" class="307" row="47" col="1"></td>
					<td align=center><input type="text" parent="713" class="307" row="47" col="2"></td>
					<td align=center><input type="text" parent="713" class="307" row="47" col="3"></td>
					<td align=center><input type="text" parent="713" class="307" row="47" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>311<br>���Ա����ڻ�ȯ</td>
				
					<td align=center step="4" item="311" col="1" parent="713"></td>
					<td align=center step="4" item="311" col="2" parent="713"></td>
					<td align=center step="4" item="311" col="3" parent="713"></td>
					<td align=center step="4" item="311" col="4" parent="713"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">01<br>�õ��������߱�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="48" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="48" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="48" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="48" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">02<br>�ñ����������߱�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="49" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="49" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="49" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="49" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">03<br>��ȭ����������Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="50" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="50" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="50" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="50" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">04<br>�߾��������Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="51" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="51" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="51" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="51" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">06<br>��Ÿ���Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="52" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="52" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="52" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="52" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">10<br>Ÿȸ�����Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="53" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="53" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="53" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="53" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">11<br>���ä�������</td>
				
					<td align=center><input type="text" parent="713" class="311" row="54" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="54" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="54" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="54" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">12<br>��ġ��ü���Ա����ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="55" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="55" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="55" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="55" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">13<br>�������ä���ڻ�ȯ</td>
				
					<td align=center><input type="text" parent="713" class="311" row="56" col="1"></td>
					<td align=center><input type="text" parent="713" class="311" row="56" col="2"></td>
					<td align=center><input type="text" parent="713" class="311" row="56" col="3"></td>
					<td align=center><input type="text" parent="713" class="311" row="56" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>�������������������</td>
				
					<td align=center><input type="text" step="4" parent="713" class="316" row="57" col="1"></td>
					<td align=center><input type="text" step="4" parent="713" class="316" row="57" col="2"></td>
					<td align=center><input type="text" step="4" parent="713" class="316" row="57" col="3"></td>
					<td align=center><input type="text" step="4" parent="713" class="316" row="57" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">714<br>���ź�</td>
				
					<td align=center step="3" class="714" col="1" parent="710"></td>
					<td align=center step="3" class="714" col="2" parent="710"></td>
					<td align=center step="3" class="714" col="3" parent="710"></td>
					<td align=center step="3" class="714" col="4" parent="710"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>�ΰ�����</td>
				
					<td align=center step="4" item="307" col="1" parent="714"></td>
					<td align=center step="4" item="307" col="2" parent="714"></td>
					<td align=center step="4" item="307" col="3" parent="714"></td>
					<td align=center step="4" item="307" col="4" parent="714"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>�ΰ���Ź��</td>
				
					<td align=center><input type="text" parent="714" class="307" row=58 col=1></td>
					<td align=center><input type="text" parent="714" class="307" row=58 col=2></td>
					<td align=center><input type="text" parent="714" class="307" row=58 col=3></td>
					<td align=center><input type="text" parent="714" class="307" row=58 col=4></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>�������������������</td>
				
					<td align=center><input type="text" step="4" parent="714" class="307" row=59 col=1></td>
					<td align=center><input type="text" step="4" parent="714" class="307" row=59 col=2></td>
					<td align=center><input type="text" step="4" parent="714" class="307" row=59 col=3></td>
					<td align=center><input type="text" step="4" parent="714" class="307" row=59 col=4></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">715<br>�������</td>
				
					<td align=center step="3" parent="710" class="715" col="1"></td>
					<td align=center step="3" parent="710" class="715" col="2"></td>
					<td align=center step="3" parent="710" class="715" col="3"></td>
					<td align=center step="3" parent="710" class="715" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>�ΰ�����</td>
				
					<td align=center step="4" parent="715" item="307" col="1"></td>
					<td align=center step="4" parent="715" item="307" col="2"></td>
					<td align=center step="4" parent="715" item="307" col="3"></td>
					<td align=center step="4" parent="715" item="307" col="4"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>�ΰ���Ź��</td>
				
					<td align=center><input type="text" parent="715" class="307" row="60" col="1"></td>
					<td align=center><input type="text" parent="715" class="307" row="60" col="2"></td>
					<td align=center><input type="text" parent="715" class="307" row="60" col="3"></td>
					<td align=center><input type="text" parent="715" class="307" row="60" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>�������������������</td>
				
					<td align=center><input type="text" step="4" parent="715" class="316" row="61" col="1"></td>
					<td align=center><input type="text" step="4" parent="715" class="316" row="61" col="2"></td>
					<td align=center><input type="text" step="4" parent="715" class="316" row="61" col="3"></td>
					<td align=center><input type="text" step="4" parent="715" class="316" row="61" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">716<br>ó�����</td>
				
					<td align=center step="3" parent="710" class="716" col="1"></td>
					<td align=center step="3" parent="710" class="716" col="2"></td>
					<td align=center step="3" parent="710" class="716" col="3"></td>
					<td align=center step="3" parent="710" class="716" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>307<br>�ΰ�����</td>
				
					<td align=center step="4" parent="716" item="307" col="1"></td>
					<td align=center step="4" parent="716" item="307" col="2"></td>
					<td align=center step="4" parent="716" item="307" col="3"></td>
					<td align=center step="4" parent="716" item="307" col="4"></td>
				</tr>
				
				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd">05<br>�ΰ���Ź��</td>
				
					<td align=center><input type="text" parent="716" class="307" row="62" col="1"></td>
					<td align=center><input type="text" parent="716" class="307" row="62" col="2"></td>
					<td align=center><input type="text" parent="716" class="307" row="62" col="3"></td>
					<td align=center><input type="text" parent="716" class="307" row="62" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td align=center></td>
					<td class="pd" colspan=2>316<br>�������������������</td>
				
					<td align=center><input type="text" step="4" parent="716" class="316" row="63" col="1"></td>
					<td align=center><input type="text" step="4" parent="716" class="316" row="63" col="2"></td>
					<td align=center><input type="text" step="4" parent="716" class="316" row="63" col="3"></td>
					<td align=center><input type="text" step="4" parent="716" class="316" row="63" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">717<br>�ޡ���������</td>
				
					<td align=center><input type="text" step="3" parent="710" class="717" row="64" col="1"></td>
					<td align=center><input type="text" step="3" parent="710" class="717" row="64" col="2"></td>
					<td align=center><input type="text" step="3" parent="710" class="717" row="64" col="3"></td>
					<td align=center><input type="text" step="3" parent="710" class="717" row="64" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">751<br>�Ϲݰ�����</td>
				
					<td align=center><input type="text" step="3" parent="710" class="751" row="65" col="1"></td>
					<td align=center><input type="text" step="3" parent="710" class="751" row="65" col="2"></td>
					<td align=center><input type="text" step="3" parent="710" class="751" row="65" col="3"></td>
					<td align=center><input type="text" step="3" parent="710" class="751" row="65" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">752<br>¡�� �� ���밡������</td>
				
					<td align=center><input type="text" step="3" parent="710" class="752" row="66" col="1"></td>
					<td align=center><input type="text" step="3" parent="710" class="752" row="66" col="2"></td>
					<td align=center><input type="text" step="3" parent="710" class="752" row="66" col="3"></td>
					<td align=center><input type="text" step="3" parent="710" class="752" row="66" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">770<br>�����ܺ��</td>
				
					<td align=center class="770" step="2" col="1"></td>
					<td align=center class="770" step="2" col="2"></td>
					<td align=center class="770" step="2" col="3"></td>
					<td align=center class="770" step="2" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">771<br>�������ڹ��������</td>
				
					<td align=center><input type="text" step="3" parent="770" class="771" row="67" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="771" row="67" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="771" row="67" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="771" row="67" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">772<br>�����ڻ�ó�мս�</td>
				
					<td align=center><input type="text" step="3" parent="770" class="772" row="68" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="772" row="68" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="772" row="68" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="772" row="68" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">773<br>�ڻ�ջ�����</td>
				
					<td align=center><input type="text" step="3" parent="770" class="773" row="69" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="773" row="69" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="773" row="69" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="773" row="69" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">774<br>��ȭȯ��ս�</td>
				
					<td align=center><input type="text" step="3" parent="770" class="774" row="70" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="774" row="70" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="774" row="70" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="774" row="70" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">775<br>������������ս�</td>
				
					<td align=center><input type="text" step="3" parent="770" class="775" row="71" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="775" row="71" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="775" row="71" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="775" row="71" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">776<br>�����ڻ�ó�мս�</td>
				
					<td align=center><input type="text" step="3" parent="770" class="776" row="72" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="776" row="72" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="776" row="72" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="776" row="72" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">779<br>��Ÿ�����ܺ��</td>
				
					<td align=center><input type="text" step="3" parent="770" class="779" row="73" col="1"></td>
					<td align=center><input type="text" step="3" parent="770" class="779" row="73" col="2"></td>
					<td align=center><input type="text" step="3" parent="770" class="779" row="73" col="3"></td>
					<td align=center><input type="text" step="3" parent="770" class="779" row="73" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">780<br>Ư���ս�</td>
				
					<td align=center step="2" class="780" col="1"></td>
					<td align=center step="2" class="780" col="2"></td>
					<td align=center step="2" class="780" col="3"></td>
					<td align=center step="2" class="780" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">781<br>�ӽüս�</td>
				
					<td align=center><input type="text" step="3" parent="780" class="781" row="74" col="1"></td>
					<td align=center><input type="text" step="3" parent="780" class="781" row="74" col="2"></td>
					<td align=center><input type="text" step="3" parent="780" class="781" row="74" col="3"></td>
					<td align=center><input type="text" step="3" parent="780" class="781" row="74" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">782<br>������ͼ����ս�</td>
				
					<td align=center><input type="text" step="3" parent="780" class="782" row="75" col="1"></td>
					<td align=center><input type="text" step="3" parent="780" class="782" row="75" col="2"></td>
					<td align=center><input type="text" step="3" parent="780" class="782" row="75" col="3"></td>
					<td align=center><input type="text" step="3" parent="780" class="782" row="75" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td align=center></td>
					<td colspan=3 class="pd">789<br>��ŸƯ���ս�</td>
				
					<td align=center><input type="text" step="3" parent="780" class="789" row="76" col="1"></td>
					<td align=center><input type="text" step="3" parent="780" class="789" row="76" col="2"></td>
					<td align=center><input type="text" step="3" parent="780" class="789" row="76" col="3"></td>
					<td align=center><input type="text" step="3" parent="780" class="789" row="76" col="4"></td>
				</tr>

				<tr bgcolor="FFFFFF" height="30">
					<td align=center></td>
					<td colspan=4 class="pd">800<br>�����</td>
				
					<td align=center><input type="text" step="2" class="800" row="77" col="1"></td>
					<td align=center><input type="text" step="2" class="800" row="77" col="2"></td>
					<td align=center><input type="text" step="2" class="800" row="77" col="3"></td>
					<td align=center><input type="text" step="2" class="800" row="77" col="4"></td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>