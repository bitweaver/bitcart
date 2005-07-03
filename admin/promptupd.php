<?php

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$show   = (int)getparam('show');
$srch   = (int)getparam('srch');

$langiso = getparam('langiso');

$salcnt  = (int)getparam('salcnt');
$promptcnt = (int)getparam('promptcnt');

//array fc_prompt is handled below
//array salutearray is handled below
// ==========  end of variable loading  ==========

require("../public.php");
$fd = fopen ("BITCART_PKG_PATH$cartdir/languages/lang_$langiso.php", "w");
fwrite($fd,"<?php\n");

$cnt=$salcnt;
for ($i=0; $i<$cnt; $i++) {
 $fcsal=getparam('salutearray');
 $tmp=$fcsal[$i];
 $tmp=ereg_replace("'","\'",$tmp);
 if( strlen($tmp) ){
  fwrite($fd,"\$salutearray[] = '$tmp';\n");
 }
}

fwrite($fd,"\n\$fc_prompt = array(\n");

$cnt=$promptcnt;
for ($i=0; $i<$cnt; $i++) {
 $fc_promp=getparam('fc_prompt');
 $tmp=$fc_promp[$i];
 $txt=getparam($tmp);
 $txt=stripslashes($txt);
 $txt=ereg_replace("'","\'",$txt);
 // echo "txt for $tmp: $txt<br>'$tmp' =>\n\t'$txt',\n";
 if( $cnt == $i+1 ){
  fwrite($fd,"'$tmp' =>\n\t'$txt'\n");
 }else{
  fwrite($fd,"'$tmp' =>\n\t'$txt',\n");
 }
}

fwrite($fd,");\n?>\n");

fclose ($fd);

 header("Location: $nsecurl/$maintdir/promptndx.php?langid=$langid&zoneid=$zoneid");
?>
