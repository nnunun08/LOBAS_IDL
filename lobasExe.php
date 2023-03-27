<?
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/lib.php";
	include $_SERVER["DOCUMENT_ROOT"]."/jnc/etc.php";

	if (!$connect) $connect=dbConn();
	
	$sql = "select count(*) from admin_lobas where cate=$cate and company=$adm_com and rnum=$row";
	$cnt = T_read($sql);

	if ($cnt=="0") {
		$fld = "yy,cate,company,fldsu,rnum";
		$val = "$yy,$cate,$adm_com,$fldsu,$row";

		$sql = "insert into admin_lobas ($fld) values ($val)";
		T_insert($sql);
	}

	$sql = "update admin_lobas set fld".$col."='".$kap."' where cate=$cate and yy=$yy and company=$adm_com and rnum=$row";
	T_update($sql);

	if ($connect) @mysql_close($connect);
	unset($connect);
?>
<?=$sql?>