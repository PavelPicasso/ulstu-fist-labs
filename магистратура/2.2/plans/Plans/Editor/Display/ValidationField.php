<script language="JavaScript">
        <!--
            var ArrRecPtr='';
            var ArrDiscipPtr='';
            
            function Validator(theForm,fieldKind,addField) 
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
        // -->
</script>