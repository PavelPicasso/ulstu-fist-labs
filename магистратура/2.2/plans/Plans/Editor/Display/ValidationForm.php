<script language="JavaScript">
        <!--
            function Validator(theForm,fieldKind,addField) { return; }
            function Deleting() { return; }
            function FillArrChange(theForm,fieldKind,addField) { return; }
            function RefreshRecArr() { return; }
        //-->
</script>
<script language="JavaScript">
        <!--
            var ArrRecPtr='';
            var ArrDiscipPtr='';
            
            function Validator(theForm,fieldKind) 
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
                        alert("В данное поле разрешен ввод только цифр");
                        theForm.value=theForm.defaultValue;
                        theForm.focus();
                        return (false);
                    }
                return (true); 
            }

            function FillArrChange(fieldKind,addField) 
            { 
                if (fieldKind=="Discip") {
	                if (ArrDiscipPtr!="") ArrDiscipPtr+=',';
                                ArrDiscipPtr+=addField;
                	document.fed.NumOfChangeDiscip.value=ArrDiscipPtr;
                }
                else {
	                if (ArrRecPtr!="") ArrRecPtr+=',';
                                ArrRecPtr+=addField;
                	document.fed.NumOfChangeRec.value=ArrRecPtr;
		}
                return (true); 
            }

            function RefreshRecArr()
            {
             ArrRecPtr='';
             document.fed.NumOfChangeRec.value=ArrRecPtr;
             ArrDiscipPtr='';
             document.fed.NumOfChangeDiscip.value=ArrRecPtr;
             return (true);
            }

            function Deleting()
            { if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
                {return ('DoDelDisInP.php');
                }
              return('PlanEd.php');
            }
        // -->
</script>