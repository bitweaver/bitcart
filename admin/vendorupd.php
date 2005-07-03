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

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$vendid = (int)getparam('vendid');

$act = getparam('act');
$vendname = getparam('vendname');
$vendaddr1 = getparam('vendaddr1');
$vendaddr2 = getparam('vendaddr2');
$vendcity = getparam('vendcity');
$vendstate = getparam('vendstate');
$vendzip = getparam('vendzip');
$vendphone = getparam('vendphone');
$vendfax = getparam('vendfax');
$vendemail = getparam('vendemail');
$vsvcname = getparam('vsvcname');
$vsvcaddr1 = getparam('vsvcaddr1');
$vsvcaddr2 = getparam('vsvcaddr2');
$vsvccity = getparam('vsvccity');
$vsvcstate = getparam('vsvcstate');
$vsvczip = getparam('vsvczip');
$vsvcphone = getparam('vsvcphone');
$vsvcfax = getparam('vsvcfax');
$vsvcemail = getparam('vsvcemail');
$vendnatl = getparam('vendnatl');
$vsvcnatl = getparam('vsvcnatl');
$vendonline = getparam('vendonline');
$vendofline = getparam('vendofline');
$vendoemail = getparam('vendoemail');
$vendconfirm = getparam('vendconfirm');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');   // includes flags.php

$fcv = new FC_SQL;

if($zoneid==""){?>
  A zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
    <?php exit;
}

if((strlen($vendname)>80 || strlen($vendaddr1)>80) ||
   (strlen($vendaddr2)>80|| strlen($vendcity)>40)  ||
   (strlen($vendstate)>3 || strlen($vendzip)>12)   ||
   (strlen($vendphone)>20|| strlen($vendfax)>20)   ||
   (strlen($vendemail)>40|| strlen($vsvcemail)>40) ||
   (strlen($vsvcname)>80 || strlen($vsvcaddr1)>80) ||
   (strlen($vsvcaddr2)>80|| strlen($vsvccity)>40)  ||
   (strlen($vsvcstate)>3 || strlen($vsvczip)>12)   ||
   (strlen($vsvcphone)>20|| strlen($vsvcfax)>20))  {?>
A field exceeds its maximum length.
<p>Please click the &quot;Back&quot; button on your browser
and correct the errors.  Thank you.
  <?php exit;
}
if($zoneid==""){?>
  A Zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
    <?php exit;
}

// build up vflag1
$vflag1=0;

if ($act=="update") {

   $fcv->query("update vend set ".
   "vendname='$vendname',   vendaddr1='$vendaddr1', vendaddr2='$vendaddr2', ".
   "vendcity='$vendcity',   vendstate='$vendstate', vendzip='$vendzip', ".
   "vendphone='$vendphone', vendfax='$vendfax',     vendemail='$vendemail', ".
   "vsvcname='$vsvcname',   vsvcaddr1='$vsvcaddr1', vsvcaddr2='$vsvcaddr2', ".
   "vsvccity='$vsvccity',   vsvcstate='$vsvcstate', vsvczip='$vsvczip', ".
   "vsvcphone='$vsvcphone', vsvcfax='$vsvcfax',     vsvcemail='$vsvcemail',".
   "vendnatl='$vendnatl',   vsvcnatl='$vsvcnatl',   vendonline='$vendonline',".
   "vendofline='$vendofline',vendoemail='$vendoemail',".
   "vendconfirm='$vendconfirm',vflag1=$vflag1 ".
   "where vendid=$vendid"); 
	 
} elseif ($act=="new") {

 if( $databaseeng=='odbc' && $dialect=='solid' ){
	$fcv->query("call vendor_ins ($zoneid,".
     "'$vendname' ,'$vendaddr1','$vendaddr2','$vendcity', ".
	 "'$vendstate','$vendzip'  ,'$vendnatl', '$vendphone',".
	 "'$vendfax'  ,'$vendemail','$vsvcname' ,'$vsvcaddr1',".
	 "'$vsvcaddr2','$vsvccity' ,'$vsvcstate','$vsvczip'  ,".
	 "'$vsvcnatl' ,'$vsvcphone','$vsvcfax'  ,'$vsvcemail',".
	 "'$vendonline','$vendofline','$vendoemail','$vendconfirm',$vflag1)"); 
 }else{
	$fcv->query("insert into vend (vendzid,".
	 "vendname,vendaddr1,vendaddr2,vendcity,vendstate,vendzip,".
	 "vendnatl,vendphone,vendfax,vendemail,".
	 "vsvcname,vsvcaddr1,vsvcaddr2,vsvccity,vsvcstate,vsvczip,".
	 "vsvcnatl,vsvcphone,vsvcfax,vsvcemail,".
	 "vendonline,vendofline,vendoemail,vendconfirm,vflag1)".
	 " values ".
	 "($zoneid,".
     "'$vendname' ,'$vendaddr1','$vendaddr2','$vendcity', ".
	 "'$vendstate','$vendzip'  ,'$vendnatl', '$vendphone',".
	 "'$vendfax'  ,'$vendemail','$vsvcname' ,'$vsvcaddr1',".
	 "'$vsvcaddr2','$vsvccity' ,'$vsvcstate','$vsvczip'  ,".
	 "'$vsvcnatl' ,'$vsvcphone','$vsvcfax'  ,'$vsvcemail',".
	 "'$vendonline','$vendofline','$vendoemail','$vendconfirm',$vflag1)"); 
 }

} elseif ($act=="delete") {
   $fcv->query("delete from vend where vendid=$vendid");
}

$fcv->commit();
echo "Work committed.<br>\n";
?>

<p>

<form method=post action="index.php">
<input type=hidden name=zoneid value="<?php echo $zoneid?>">
<input type=hidden name=langid value="<?php echo $langid?>">
<input type=submit value="Return to Maintenance">
</form>

<?php require('./footer.php');?>
