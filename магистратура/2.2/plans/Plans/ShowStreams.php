<HTML>
<HEAD>
<TITLE>Посеместровая нагрузка студентов. 220100&nbsp;Вычислительные машины, комплексы, системы и сети</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>
<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<center>
<?php 
   include("cfg.php");
   $Connection=mysql_connect($data_source , $dbi_user , $dbi_password) 
      or die ("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   if ($REQUEST_METHOD=='GET'){
      $q = "";
      if (isset($spz)){
         $result=mysql_query("select * from specializations where CodeOfSpecialization=".$spz,$Connection) 
            or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
         if($res=mysql_fetch_object($result)){
            echo "<h1>Потоки специализации</h1>";
            echo "<h2>$res->MinistryCode";
            echo "&nbsp;&nbsp;&nbsp;";
            echo "$res->SpzName</h2>";
            echo "</center>";
         }
         $q = "select distinct streams.StreamName from streams, plans, specializations where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfSpecialization =".$spz." order by streams.StreamName";
      }else{
         if (isset($spc)){
            $result=mysql_query("select * from specials where CodeOfSpecial=".$spc,$Connection) 
               or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
            if($res=mysql_fetch_object($result)){
               echo "<h1>Потоки специальности</h1>";
               echo "<h2>$res->MinistryCode";
               echo "&nbsp;&nbsp;&nbsp;";
               echo "$res->SpcName</h2>";
               echo "</center>";
            }
            $q = "select distinct streams.StreamName from streams, plans, specials where streams.CodeOfPlan=plans.CodeOfPlan and (plans.CodeOfSpecial =".$spc." and (plans.CodeOfSpecialization is NULL)) order by streams.StreamName ";
         }else{
            if (isset ($dir)){
               $result=mysql_query("select * from directions where CodeOfDirect=".$dir,$Connection) 
                  or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
               if($res=mysql_fetch_object($result)){
                  echo "<h1>Потоки направления</h1>";
                  echo "<h2>$res->MinistryCode";
                  echo "&nbsp;&nbsp;&nbsp;";
                  echo "$res->DirName</h2>";
                  echo "</center>";
               }
               $q = "select distinct streams.StreamName from streams, plans, directions where streams.CodeOfPlan=plans.CodeOfPlan and (plans.CodeOfDirect =".$dir." and (plans.CodeOfSpecial is NULL) and (plans.CodeOfSpecialization is NULL)) order by streams.StreamName ";
            }else{
               echo "</body></html>\n";
               exit;
            }
         }
      }
  
      $result=mysql_query($q, $Connection) 
         or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
      $i = 0;
      while($res=mysql_fetch_object($result)){
         echo  "<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
         echo  "<a href=\"StreamPlan.php?strNm=$res->StreamName";
         if (isset($spz)){
            echo "&spz=$spz\">";
         }else{
            if (isset($spc)){
               echo "&spc=$spc\">";
            }else{
               if (isset($dir)){
                  echo "&dir=$dir\">";
               }
            }
         }
         echo  "$res->StreamName</strong></a><P>";
         $i++;
      }
      if ($i == 0){echo "<h1>неопределены</h1>";}
      /* Освобождение resultset */
      mysql_free_result($result);
      /* Закрытие соединения */
      mysql_close($Connection);
  }
?>
</TABLE>
</tr></table></body>
</html>
