<?
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/lib.php";
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/etc.php";

	if (!$connect) $connect=dbConn();
	
	$sql = "select * from admin_lobas9_file where company=$adm_com";
	$row = T_select($sql);

	$filePath = "/opt/apache/userdir/pws.withidl.com/lobas/data/";
	
	for ($i=0;$i<count($row);$i++) {
		$file = $row[$i][file];

		$filename = $filePath.$file;
		delFile($filename);	
	}

	$sql = "delete from admin_lobas9_file where company=$adm_com";
	T_delete($sql);

	$sql = "delete from admin_lobas9_year where company=$adm_com";
	T_delete($sql);

	$sql = "delete from admin_lobas9 where company=$adm_com";
	T_delete($sql);

	if ($connect) @mysql_close($connect);
	unset($connect);
?>
<form name="form1" method="post">
<input type="hidden" name="sw" value="<?=$sw?>">
<input type="hidden" name="flag" value="<?=$flag?>">
<input type="hidden" name="mseq" value="<?=$mseq?>">
</form>
<SCRIPT LANGUAGE="JavaScript">
<!--
	alert("처리되었습니다");

	form1.action = "index.php";
	form1.submit();
//-->
</SCRIPT>