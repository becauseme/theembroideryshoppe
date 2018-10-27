<?php 
function remove_special_chrs ($string="") {
 
 // PREG_REPLACE REMOVE ALL OTHER CHARACTERS THAT NOT AVAIALABLE IN PREG_REPLACE FIRST
 // PARAMETER YOU CANNOT UNDERSTAND FIRST PARAMETER YOU MUST READ PHP REGULAR EXPRESSION!
 $string = preg_replace('/[^A-Za-z0-9.%-\/]/',' ',$string);
 
 //STRIP_TAGS REMOVE HTML TAGS
 $string=strip_tags($string,"");
 
 //HERE WE REMOVE WHITE SPACES AND RETURN IT
 return trim($string);
}
 
echo remove_special_chrs('Crewneck Sweatshirt 50/50 cotton/poly');
// CALLED THIS FUNCTION ITS ECHO THE "afdas00111rX2GNANOTHERCHARACTERS"
?>