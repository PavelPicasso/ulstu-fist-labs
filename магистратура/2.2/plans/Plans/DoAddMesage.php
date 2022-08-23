<?php 
  $filename="Editor/GuestBook.html";
  $fill="Editor/fill.html";
  if ($REQUEST_METHOD=='POST' && $_POST['Mesage']) {
    $msg = $_POST['Mesage'];
    $Dt = date("H:i l d.m.Y");
    //замена ангийских названий дней недели русскими
    $Dt = str_replace("Monday", "Понедельник", $Dt);
    $Dt = str_replace("Tuesday", "Вторник", $Dt);
    $Dt = str_replace("Wednesday", "Среда", $Dt);
    $Dt = str_replace("Thursday", "Четверг", $Dt);
    $Dt = str_replace("Friday", "Пятница", $Dt);
    $Dt = str_replace("Saturday", "Суббота", $Dt);
    $Dt = str_replace("Sunday", "Воскресенье", $Dt);
    $author=$_POST['Author'];

    include("cfg.php");
    $Connection=mysql_connect($data_source , $dbi_user , $dbi_password) 
       or die ("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_select_db("plans") 
       or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

    $result=mysql_query("SELECT MAX(mesage_id) from mesages",$Connection) 
       or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
    $res=mysql_fetch_row($result);
    $i=$res[0];
    if ($i)
      $i+=1;
    else
      $i=1; 
    if (mysql_query("INSERT INTO mesages (mesage_id, Mesage, Author, Date)
       VALUES ($i, '$msg', '$author', '$Dt')",$Connection)){
       Header ("Location: Editor/GuestBook.php");
    }
    /* Освобождение resultset */
    mysql_free_result($result);
    /* Закрытие соединения */
    mysql_close($Connection);
  }
  else {
       Header ("Location: $fill");
  }
?>