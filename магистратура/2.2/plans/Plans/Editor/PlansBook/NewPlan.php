<?php
	session_start();
	if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
		Header ("Location: ../Unreg.html");
		exit;
	}
	if ($REQUEST_METHOD=='GET') {
	  Header ("Location: ChoiseP.php");
	  exit;
	}
?>
<HTML>
<HEAD>
<TITLE>�������� ������ �������� �����</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<script language="JavaScript">
<!--
	function Validator(theForm) { return; }
	function IsEmpty(theForm) { return; }
	function ValidCount(theForm) { return;}
	function ValidFirst(theForm) { return;}
	function ChangeCount(theForm,theForm) { return; }
	function ChekDayCount(theForm) { return; }
	function ChekMonCount(theForm) { return; }
	function ChekYearCount(theForm) { return; }
//-->
</script>
<script language="JavaScript1.1">
<!--
function IsEmpty(theForm)
{
	 if (plData.PlanName.value == ""){
		 alert("������� �������� �����!");
		 return ("NewPlan.php");
	 }
	 return ("schedules.php"); 
}
function Validator(theForm) 
{ 
	 var checkOK="0123456789";
	 var checkStr = theForm.value;
	 var allValid = true;
	 for (i=0; i<checkStr.length; i++)
	 {
		  ch = checkStr.charAt(i);
		  for(j=0;j<checkOK.length;j++)
				if (ch == checkOK.charAt(j)) break;
		  if (j == checkOK.length)
		  {
				allValid = false;
				break;
		  }
	 }
		  if (allValid == false)
		  {
				alert("� ������ ���� �������� ���� ������ ����");
				theForm.value=theForm.defaultValue;
				theForm.focus();
				return (false);
		  }
	 return (true); 
}

function ChangeCount(A,B) 
{ 
	 document.fed.yCnt.value=A;
	 document.fed.FirstSm.value=B;
	 return (true); 
}

function ChekDayCount(theForm) 
{ 
	if (Validator(theForm))
	{	if (theForm.value>31)
		  	{
						  alert("�������� ������� ���� �� ����� ��������� 31");
						  theForm.value=theForm.defaultValue;
						  theForm.focus();
								return (false);
						  }
			}
				return (true); 
}

function ChekMonCount(theForm) 
{ 
	 if (Validator(theForm))
	 {
		 	 if (theForm.value>12)
			{
				  alert("�������� ���� '�����' �� ����� ��������� 12");
				  theForm.value=theForm.defaultValue;
				  theForm.focus();
				  return (false);
			}
	 }		
	 return (true); 
}

function ValidCount(theForm) 
{ 
	if (Validator(theForm))
	{
		  if (theForm.value>6)
		  {
						  alert("���� �������� �� ����� ��������� 6 ���");
						  theForm.value=theForm.defaultValue;
						  theForm.focus();
						  return (false);
		  }
		  if (theForm.value<1)
		  {
					 alert("���� �������� �� ����� ���� ������ 1 ����");
					 theForm.value=theForm.defaultValue;
					 theForm.focus();
					 return (false);
		  }
	 }		
	 return (true); 
}
		

function ValidFirst(theForm) 
{ 
	if (Validator(theForm))
	{
		  	if (theForm.value<1)
				{
						  alert("����� ���������� ����� �� ����� ���� ������ 1");
						  theForm.value=theForm.defaultValue;
						  theForm.focus();
						  return (false);
				}
	}		
	 return (true); 
}
		function ChekYearCount(theForm) 
{ 
		  if (Validator(theForm))
			{	 		
			  	if ((theForm.value>2078) || (theForm.value<1900))
					 {
					 alert("�������� ���� '���' �� ����� ���� ������ 1900 � ������ 2078");
						theForm.value=theForm.defaultValue;
							  theForm.focus();
						 return (false);
			  }
			}
	 return (true); 
}
// -->
	 </script>
</HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=plData method=post action="">
<script  language="JavaScript">
<!--
			top.LeftDown.location='../downNewP.html';	
//-->
			</script>
<em class='h1'>
<center>�������� ������ �������� �����</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<h2>��������� ����&nbsp;&nbsp;&nbsp;</h2>
<h3>�������� ������ �����&nbsp;&nbsp;&nbsp;<INPUT TYPE=TEXT NAME='PlanName' SIZE=35 MAXLENGTH=35 </INPUT></h3>
<h3>����� ��������&nbsp;&nbsp;&nbsp;
<?php
	//����� ����� ��������
	$keys = array('�����','�������','��������','�������������','���������');
	$form = array('classroom','correspondence','night','distance','external');
	echo	"<SELECT NAME='TchForm'>\n";
	while (($F = each($form)) && ($K = each($keys))){  
		echo "<OPTION VALUE='".$F[1]."' >".$K[1]."\n"; 
	}
	echo "</SELECT></h3>\n";

	$Direct = $_POST['Dir'];
	$Spec = $_POST['Spec'];
	$Spz = $_POST['Spz'];

	include("../PlanCalculatFunc.php");
	CreateConnection();

	$Srok = 0;
	$Years = 0;
	if (!empty($Spz)){
		$result = mysql_query("select degrees.Apprenticeship, degrees.FirstYear from degrees, specializations where specializations.CodeOfDegree=degrees.CodeOfDegree and specializations.CodeOfSpecialization =".$Spz, $Connection)
			 or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
		if ($row = mysql_fetch_object($result)){
			$Srok = $row->Apprenticeship;
			$Years = $row->FirstYear;
		}
	} 
	else{
		if (!empty($Spec)) {
			$result = mysql_query("select degrees.Apprenticeship, degrees.FirstYear from degrees, specials where specials.CodeOfDegree=degrees.CodeOfDegree and specials.CodeOfSpecial =".$Spec, $Connection)
				 or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
			if ($row = mysql_fetch_object($result)){
				$Srok = $row->Apprenticeship;
				$Years = $row->FirstYear;
			}
		}
		else{
			$result = mysql_query("select degrees.Apprenticeship, degrees.FirstYear from degrees, directions where directions.CodeOfDegree=degrees.CodeOfDegree and directions.CodeOfDirect =".$Dir, $Connection)
				 or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
			if ($row = mysql_fetch_object($result)){
				$Srok = $row->Apprenticeship;
				$Years = $row->FirstYear;
			}
		}
	}
	//��������� ������ � �������������

	echo "<h3>���� ��������:&nbsp;<INPUT TYPE=TEXT NAME='yCnt' SIZE=2 MAXLENGTH=10 VALUE=\"".$Srok."\"onChange=\"ValidCount(this)\"></INPUT>&nbsp;���\n";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����� ���������� �����:&nbsp;<INPUT TYPE=TEXT NAME='FirstSm' SIZE=2 MAXLENGTH=10 VALUE=\"".$Years."\" onChange=\"ValidFirst(this)\"></INPUT>&nbsp;</h3>\n";

	mysql_free_result($result);
	mysql_close($Connection);
	echo "<input type='hidden' name='Dir' value=$Direct>";
	echo "<input type='hidden' name='Spec' value=$Spec>";
	echo "<input type='hidden' name='Spz' value=$Spz>";
	echo "<input type='hidden' name='new' value=1>\n";
?>
<center><input type="submit" name="subm" value="������"  onClick="plData.action=IsEmpty(this)"></center></form><hr /></body></html>