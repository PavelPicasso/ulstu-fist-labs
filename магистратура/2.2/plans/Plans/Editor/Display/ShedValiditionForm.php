<SCRIPT language="JavaScript">
<!--
var ArrKursPtr='';
var ArrPerPtr='';
function Changing(theForm)
{	
    if(confirm("При удалениии курсов будут удалены соответствующие дисциплины из учебных планов. Внести изменения?"))
    {
       return ('schedules.php');	
    }  
	window.event.returnValue = false;
}

function IsNumber(theForm) 
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
        alert("В данное поле разрешен ввод только чисел");
        theForm.value=theForm.defaultValue;
        theForm.focus();
        return (false);
    }
    return (true); 
}

function Validator(theForm) 
{ 
    if (IsNumber(theForm)) {
        r=FillArrChenge(theForm);
        return (true); 
    } 
    else
        return false;
}

function ValidCount(theForm) 
{ 
	if (IsNumber(theForm))
	{
        	if (theForm.value>6)
            {
                    alert("Срок обучения не может превышать 6 лет");
                    theForm.value=theForm.defaultValue;
                    theForm.focus();
                    return (false);
            }
        	if (theForm.value<1)
            {
                    alert("Срок обучения не может быть меньше 1 года");
                    theForm.value=theForm.defaultValue;
                    theForm.focus();
                    return (false);
            }
            return (true); 
        }
        else
            return (false); 
}
		

function ValidFirst(theForm) 
{ 
	if (IsNumber(theForm))
	{
        	if (theForm.value<1)
            {
                    alert("Номер начального курса не может быть меньше 1");
                    theForm.value=theForm.defaultValue;
                    theForm.focus();
                    return (false);
            }
            return (true); 
	}
	else
	    return (false);
}

function FillArrChenge(theForm) { 
    var checkOK="0123456789";
    var checkStr = theForm.name;
    var flag=true;
    var KursPtr=0;
    var PerPtr=0;
    i=checkStr.length-1;
    while (flag && i>=0)
    {
      ch = checkStr.charAt(i);
      j=0;
      while( (ch != checkOK.charAt(j)) && j<checkOK.length)
       {    j++;   }                
      if (j == checkOK.length)  flag = false;
      else 
      {     mnogitel=1;
        for (q=0;q<checkStr.length-1-i;q++)
        { 
         mnogitel*=10;  
        }
        KursPtr+=(ch*mnogitel);
      }
      i--;
    }
    if (ArrKursPtr!="") ArrKursPtr+=',';
                    ArrKursPtr+=KursPtr;
    document.fed.NumOfChangeKurs.value=ArrKursPtr;
    
    len=i;
    flag=true;              
    while (flag && i>=0)
    {
      ch = checkStr.charAt(i);
      j=0;
      while( (ch != checkOK.charAt(j)) && j<checkOK.length)
       {    j++;   }                
      if (j == checkOK.length)  flag = false;
      else 
      {     mnogitel=1;
        for (q=0;q<len-i;q++)
        { 
         mnogitel*=10;  
        }
        PerPtr+=(ch*mnogitel);
      }
      i--;
    }

    if (ArrPerPtr!="") ArrPerPtr+=',';
                    ArrPerPtr+=PerPtr;
    document.fed.NumOfChangePer.value=ArrPerPtr;
                    
    return (true); 
}

function RefreshRecArr(theForm)
{
   ArrKursPtr='';
   ArrPerPtr='';
   document.fed.NumOfChangeKurs.value=ArrKursPtr;
   document.fed.NumOfChangePer.value=ArrPerPtr;
   return (true);
}
function Deleting(theForm){  
	if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
		return(true);
	return(false);
}
// -->
</script>