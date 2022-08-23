<?php
//Функция переводит символ в верхний регистр
Function ToUpper($char){
   if (strcmp ($char,"а")==0){ return "А";}
   if (strcmp ($char,"б")==0){ return "Б";}
   if (strcmp ($char,"в")==0){ return "В";}
   if (strcmp ($char,"г")==0){ return "Г";}
   if (strcmp ($char,"д")==0){ return "Д";}
   if (strcmp ($char,"е")==0){ return "Е";}
   if (strcmp ($char,"ё")==0){ return "Ё";}
   if (strcmp ($char,"ж")==0){ return "Ж";}
   if (strcmp ($char,"з")==0){ return "З";}
   if (strcmp ($char,"и")==0){ return "И";}
   if (strcmp ($char,"й")==0){ return "Й";}
   if (strcmp ($char,"к")==0){ return "К";}
   if (strcmp ($char,"л")==0){ return "Л";}
   if (strcmp ($char,"м")==0){ return "М";}
   if (strcmp ($char,"н")==0){ return "Н";}
   if (strcmp ($char,"о")==0){ return "О";}
   if (strcmp ($char,"п")==0){ return "П";}
   if (strcmp ($char,"р")==0){ return "Р";}
   if (strcmp ($char,"с")==0){ return "С";}
   if (strcmp ($char,"т")==0){ return "Т";}
   if (strcmp ($char,"у")==0){ return "У";}
   if (strcmp ($char,"ф")==0){ return "Ф";}
   if (strcmp ($char,"х")==0){ return "Х";}
   if (strcmp ($char,"ц")==0){ return "Ц";}
   if (strcmp ($char,"ч")==0){ return "Ч";}
   if (strcmp ($char,"ш")==0){ return "Ш";}
   if (strcmp ($char,"щ")==0){ return "Щ";}
   if (strcmp ($char,"ъ")==0){ return "Ъ";}
   if (strcmp ($char,"ы")==0){ return "Ы";}
   if (strcmp ($char,"ь")==0){ return "Ь";}
   if (strcmp ($char,"э")==0){ return "Э";}
   if (strcmp ($char,"ю")==0){ return "Ю";}
   if (strcmp ($char,"я")==0){ return "Я";}

   if (strcmp ($char,"a")==0){ return "А";}
   if (strcmp ($char,"b")==0){ return "B";}
   if (strcmp ($char,"c")==0){ return "C";}
   if (strcmp ($char,"d")==0){ return "D";}
   if (strcmp ($char,"e")==0){ return "E";}
   if (strcmp ($char,"f")==0){ return "F";}
   if (strcmp ($char,"g")==0){ return "G";}
   if (strcmp ($char,"h")==0){ return "H";}
   if (strcmp ($char,"i")==0){ return "I";}
   if (strcmp ($char,"j")==0){ return "J";}
   if (strcmp ($char,"k")==0){ return "K";}
   if (strcmp ($char,"l")==0){ return "L";}
   if (strcmp ($char,"m")==0){ return "M";}
   if (strcmp ($char,"n")==0){ return "N";}
   if (strcmp ($char,"o")==0){ return "O";}
   if (strcmp ($char,"p")==0){ return "P";}
   if (strcmp ($char,"q")==0){ return "Q";}
   if (strcmp ($char,"r")==0){ return "R";}
   if (strcmp ($char,"s")==0){ return "S";}
   if (strcmp ($char,"t")==0){ return "T";}
   if (strcmp ($char,"u")==0){ return "U";}
   if (strcmp ($char,"v")==0){ return "V";}
   if (strcmp ($char,"w")==0){ return "W";}
   if (strcmp ($char,"x")==0){ return "X";}
   if (strcmp ($char,"y")==0){ return "Y";}
   if (strcmp ($char,"z")==0){ return "Z";}
   return $char;
}
?>