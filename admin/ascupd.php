<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972
*/

header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");


require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

$act    = getparam('act');

$ascname = getparam('ascname');
$ascaddr1 = getparam('ascaddr1');
$ascaddr2 = getparam('ascaddr2');
$ascphone = getparam('ascphone');
$ascfax = getparam('ascfax');
$ascemail = getparam('ascemail');
$ascsvcemail = getparam('ascsvcemail');
$asczip = getparam('asczip');
$asccity = getparam('asccity');
$ascstate = getparam('ascstate');
$ascsvcname = getparam('ascsvcname');
$ascsvcaddr1 = getparam('ascsvcaddr1');
$ascsvcaddr2 = getparam('ascsvcaddr2');
$ascsvcstate = getparam('ascsvcstate');
$ascsvczip = getparam('ascsvczip');
$ascsvcphone = getparam('ascsvcphone');
$ascsvcfax = getparam('ascsvcfax');
$asconline = getparam('asconline');
$ascofline = getparam('ascofline');
$ascconfirm = getparam('ascconfirm');
$ascoemail = getparam('ascoemail');
$ascnatl = getparam('ascnatl');
$ascsvcnatl = getparam('ascsvcnatl');
$ascsvccity = getparam('ascsvccity');

$ascwebid = getparam('ascwebid');
$ascid = getparam('ascid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');   // includes flags.php

$fcv = new FC_SQL;

if($zoneid==""){?>
  A zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.</p>
    <?php exit;
}

if((strlen($ascname)>80 || strlen($ascaddr1)>80) ||
   (strlen($ascaddr2)>80|| strlen($asccity)>40)  ||
   (strlen($ascstate)>3 || strlen($asczip)>12)   ||
   (strlen($ascphone)>20|| strlen($ascfax)>20)   ||
   (strlen($ascemail)>40|| strlen($ascsvcemail)>40) ||
   (strlen($ascsvcname)>80 || strlen($ascsvcaddr1)>80) ||
   (strlen($ascsvcaddr2)>80|| strlen($ascsvccity)>40)  ||
   (strlen($ascsvcstate)>3 || strlen($ascsvczip)>12)   ||
   (strlen($ascsvcphone)>20|| strlen($ascsvcfax)>20))  {?>
A field exceeds its maximum length.
<p>Please click the &quot;Back&quot; button on your browser
and correct the errors.  Thank you.</p>
  <?php exit;
}
if($zoneid==""){?>
  A Zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.</p>
    <?php exit;
}


if ($act=="update") {

   $fcv->query("update associate set ".
   "ascname='$ascname',   ascaddr1='$ascaddr1', ascaddr2='$ascaddr2', ".
   "asccity='$asccity',   ascstate='$ascstate', asczip='$asczip', ".
   "ascphone='$ascphone', ascfax='$ascfax',     ascemail='$ascemail', ".
   "ascsvcname='$ascsvcname',   ascsvcaddr1='$ascsvcaddr1', ascsvcaddr2='$ascsvcaddr2', ".
   "ascsvccity='$ascsvccity',   ascsvcstate='$ascsvcstate', ascsvczip='$ascsvczip', ".
   "ascsvcphone='$ascsvcphone', ascsvcfax='$ascsvcfax',     ascsvcemail='$ascsvcemail',".
   "ascnatl='$ascnatl',   ascsvcnatl='$ascsvcnatl',   asconline='$asconline',".
   "ascofline='$ascofline',ascoemail='$ascoemail',".
   "ascconfirm='$ascconfirm',ascwebid = '$ascwebid'".
   "where ascid=$ascid"); 
	 
} elseif ($act=="new") {

 if( $databaseeng=='odbc' && $dialect=='solid' ){
	$fcv->query("call asc_ins ($zoneid,".
     "'$ascname' ,'$ascaddr1','$ascaddr2','$asccity', ".
	 "'$ascstate','$asczip'  ,'$ascnatl', '$ascphone',".
	 "'$ascfax'  ,'$ascemail','$ascsvcname' ,'$ascsvcaddr1',".
	 "'$ascsvcaddr2','$ascsvccity' ,'$ascsvcstate','$ascsvczip'  ,".
	 "'$ascsvcnatl' ,'$ascsvcphone','$ascsvcfax'  ,'$ascsvcemail',".
	 "'$asconline','$ascofline','$ascoemail','$ascconfirm','$ascwebid')"); 
 }else{
	$fcv->query("insert into associate (asczid,".
	 "ascname,ascaddr1,ascaddr2,asccity,ascstate,asczip,".
	 "ascnatl,ascphone,ascfax,ascemail,".
	 "ascsvcname,ascsvcaddr1,ascsvcaddr2,ascsvccity,ascsvcstate,ascsvczip,".
	 "ascsvcnatl,ascsvcphone,ascsvcfax,ascsvcemail,".
	 "asconline,ascofline,ascoemail,ascconfirm,ascwebid)".
	 " values ".
	 "($zoneid,".
     "'$ascname' ,'$ascaddr1','$ascaddr2','$asccity', ".
	 "'$ascstate','$asczip'  ,'$ascnatl', '$ascphone',".
	 "'$ascfax'  ,'$ascemail','$ascsvcname' ,'$ascsvcaddr1',".
	 "'$ascsvcaddr2','$ascsvccity' ,'$ascsvcstate','$ascsvczip'  ,".
	 "'$ascsvcnatl' ,'$ascsvcphone','$ascsvcfax'  ,'$ascsvcemail',".
	 "'$asconline','$ascofline','$ascoemail','$ascconfirm','$ascwebid')"); 
 }

} elseif ($act=="delete") {
   $fcv->query("delete from associate where ascid=$ascid");
}

$fcv->commit();
echo "Work committed.<br />\n";
?>

<p>

<form method="post" action="index.php">
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Return to Maintenance" />
</form>

<?php require('./footer.php');?>
