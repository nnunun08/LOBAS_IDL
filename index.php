<?
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/lib.php";
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/etc.php";
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/jtsClass.php";
	include $_SERVER["DOCUMENT_ROOT"]."/adminChk.php";

	if (!$connect) $connect=dbConn();
	
	if (empty($flag)) $flag = "upload";
	
	$sw = "3";
?>
<!DOCTYPE html>
<html lang="ko">
<head>
 <? include $_SERVER["DOCUMENT_ROOT"]."/jnc/head.php"; ?>
 <style>
	.lt { padding-left:5px; }
	.pl3 { padding-left:3px; }
	.pt3 { padding-top:3px; }
	
	.w { background-color:#fff; }
	.y { background-color:yellow; }
	.g { background-color:green; }
	.bl { background-color:blue; }
	
	.f { color:#fff; }

	.b { 
		font-weight: bold;
	}

	.bb { 
		color : #094CA2;
		font-weight: bold;
	}

	.br { 
		color : red;
		font-weight: bold;
	}

	#red { color:red; }

	.t {
		border-top : 0px solid white;
	}

	.l {
		border-left : 0px solid white;
	}

	.r {
		border-right : 0px solid white;
	}

	.b {
		border-bottom : 0px solid white;
	}

	.page {page-break-before:always}
 </style>
 <SCRIPT LANGUAGE="JavaScript">
 <!--
	var adm_com = "<?=$adm_com?>";
	
	$(window).on('load', function(){   // 이미지를 불러온 후
		var docH = $("body").height();
		var gnbH = $(".gnbWrap").height();
		var secH = $(".section").height()+200;
		
		docH = docH-gnbH-7;
		if (secH < docH) {
			secH = docH;
		}

		$(".lnbWrap").height(secH);
		
		if (form1.flag.value == "companyW") {
			if (adm_com!="0") {
				$("#tTit").text("사업자관리"); 
			} else {
				$("#tTit").text($("#jTit").text()); 
			}
		} else {
			$("#tTit").text($("#jTit").text()); 
		}
	});
 //-->
 </SCRIPT>
</head>
<body>
	<div class="mainWrap" >
		<!-- top area -->
		<div class="gnbWrap">
			<a href="/"><h1>IDL</h1></a>
			<? include $_SERVER["DOCUMENT_ROOT"]."/top.php"; ?>   
		</div>
		<!-- //top area -->

		<!-- container -->
		<div class="conWrap">
			<!-- lnb area -->
			<div class="lnbWrap">
				<? include $_SERVER["DOCUMENT_ROOT"]."/left.php"; ?>
			</div>
			<!-- //lnb area -->
			<!-- section area -->
			<div class="secWrap">
				<h3><span><?=convSw($sw)?></span> &gt; <span id="tTit"></span></h3>
				<div class="section">
					접속아이디 : [<?=$adm_id?>]
					<?=$flag?>
					<? include $flag.".php"; ?>
				</div>
			</div>
			<!-- //section area -->
		</div>
		<!-- //container -->
	</div>
</body>
</html>



<?
	if ($connect) @mysql_close($connect);
	unset($connect);
?>