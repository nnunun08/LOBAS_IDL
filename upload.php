<?
	if (empty($gubun)) $gubun="1";
?>
<head>
 <style>
	#addBtn {
		display: block;
		padding: 5px;
		margin-left: 825px;
	}

	input:text {
		height:18px;
		width:100px;
	}

	button {
		width:100px;
	}

	.file {
		position: relative;
		top: 1px;
	}
 </style>
 <SCRIPT LANGUAGE="JavaScript">
 <!--
	$(function(){
		$("input:radio").click(function(){
			var gubun = $("input:radio").index(this)+1;

			form1.gubun.value = gubun;

			form1.action = "index.php";
			form1.submit();
		});

		$("button").click(function(){
			var idx = $("button").index(this)+1;

			if ($("input[name=lobas"+idx+"]").val()=="") {
				alert("엑셀 파일을 선택해 주세요");
				return;
			}

			form1.action = "upload_ok"+idx+".php";
			form1.submit();
		});
	});
 //-->
 </SCRIPT>
</head>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form name="form1" method="post" enctype="multipart/form-data">
	<input type="hidden" name="sw" value="<?=$sw?>">
	<input type="hidden" name="flag" value="<?=$flag?>">
	<input type="hidden" name="gubun" value="<?=$gubun?>">
	<tr>
		<td width="100%" height="50" align="left" class="title">
			<span id="jTit">당기 로바스 엑셀파일업로드</span>
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
						1. 로바스에서 다운받은 엑셀파일의 저장위치를 확인하여 등록합니다.<br>
						2. 파일을 여러 번 등록 한 경우에는 마지막으로 등록 한 파일만 저장됩니다.
					</td>
				</tr>
			</table>
			<!--
			<table width="800" cellpadding="1" cellspacing="1" border="0" bgcolor=#FFFFFF>
				<tr height="28" bgcolor="#FFFFFF">
					<td align=right style="padding: 5px;">
					</td>
				</tr>
			</table>
			-->
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-left:20px;text-align:left">
			<table width=800 cellspacing="1" cellpadding="0" bgcolor=#DDDDDD>
				<colgroup>
					<col width="35%">
					<col width="65%">
				</colgroup>
				
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">총괄표 엑셀파일</td>
					<td class="w lt">
						<span class="file"><input type="file" name="lobas1" style="width:300px;"></span>
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">사업예산 결산보고서(수익적 수입) 엑셀파일</td>
					<td class="w lt">
						<span class="file"><input type="file" name="lobas2" style="width:300px;"></span>
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">사업예산 결산보고서(수익적 지출) 엑셀파일</td>
					<td class="w lt">
						<span class="file"><input type="file" name="lobas3" style="width:300px;"></span>
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">자본예산 결산보고서(자본적 수입) 엑셀파일</td>
					<td class="w lt">
						<span class="file"><input type="file" name="lobas4" style="width:300px;"></span>
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">자본예산 결산보고서(자본적 지출) 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas5" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">이월예산 결산보고서(이월재원 수입결산) 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas6" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">이월예산 결산보고서(지출) 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas7" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">수입지출외 현금 현재액 조서 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas8" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">자금수입기록부 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas9" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">자금지출기록부 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas10" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
				<tr bgcolor="f6f6f6" height="30">
					<td class="lt">지출예산 성질별 결산(세목별) 엑셀파일</td>
					<td class="w lt">
						<input type="file" name="lobas11" style="width:300px;">
						<button>업로드</button>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>