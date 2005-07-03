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

$act = getparam('act');

$zonecc = (int)getparam('zonecc');
$flag_zonecc = (int)getparam('flag_zonecc');
$zonekeepcc = (int)getparam('zonekeepcc');
$flag_zonekeepcc = (int)getparam('flag_zonekeepcc');
$zonesqldel = (int)getparam('zonesqldel');
$flag_zonesqldel = (int)getparam('flag_zonesqldel');
$zonereturn = (int)getparam('zonereturn');
$flag_zonereturn = (int)getparam('flag_zonereturn');
$zonesplitcc = (int)getparam('zonesplitcc');
$flag_zonesplitcc = (int)getparam('flag_zonesplitcc');
$zonegiftorder = (int)getparam('zonegiftorder');
$zonecoupon = (int)getparam('zonecoupon');
$flag_zonecoupon = (int)getparam('flag_zonecoupon');
$zoneproddate = (int)getparam('zoneproddate');
$flag_zoneproddate = (int)getparam('flag_zoneproddate');
$zoneinlinecontrib = (int)getparam('zoneinlinecontrib');
$flag_zoneinlinecontrib = (int)getparam('flag_zoneinlinecontrib');
$zonefishgate = (int)getparam('zonefishgate');
$flag_zonefishgate = (int)getparam('flag_zonefishgate');
$zoneauthorizenet = (int)getparam('zoneauthorizenet');
$flag_zoneauthorizenet = (int)getparam('flag_zoneauthorizenet');
$zonecybercash = (int)getparam('zonecybercash');
$flag_zonecybercash = (int)getparam('flag_zonecybercash');
$zonepmtclear = (int)getparam('zonepmtclear');
$flag_zonepmtclear = (int)getparam('flag_zonepmtclear');
$zonecambist = (int)getparam('zonecambist');
$flag_zonecambist = (int)getparam('flag_zonecambist');
$zonepwcatalog = (int)getparam('zonepwcatalog');
$flag_zonepwcatalog = (int)getparam('flag_zonepwcatalog');
$zonerstrctpwcatalog = (int)getparam('zonerstrctpwcatalog');
$flag_zonerstrctpwcatalog = (int)getparam('flag_zonerstrctpwcatalog');
$zonezipshowgeo = (int)getparam('zonezipshowgeo');
$flag_zonezipshowgeo = (int)getparam('flag_zonezipshowgeo');
$zonezipshowgeo = (int)getparam('zonezipshowgeo');
$flag_zonezipshowgeo = (int)getparam('flag_zonezipshowgeo');
$zonedebug = (int)getparam('zonedebug');
$flag_zonedebug = (int)getparam('flag_zonedebug');
$zonelogaccess = (int)getparam('zonelogaccess');
$flag_zonelogaccess = (int)getparam('flag_zonelogaccess');
$zonetclink = (int)getparam('zonetclink');
$zonetcpage = (int)getparam('zonetcpage');
$zoneseqcartid = (int)getparam('zoneseqcartid');

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$zoneact = (int)getparam('zoneact');
$zonedescr = getparam('zonedescr');
$zonecurrsym = getparam('zonecurrsym');
$zonecurrsym = getparam('zonecurrsym');
$zonedeflid = (int)getparam('zonedeflid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$droot="BITCART_PKG_PATH";

if( strlen($zonedescr)>80 || strlen($zonecurrsym)>10 ){?>
	A field exceeds its maximum length.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}
if( $act != 'delete' && !$zonecurrsym ){?>
	A currency symbol must be entered.
	Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}

$fcz = new FC_SQL;
$fcm = new FC_SQL;

// build up zflag1
$zflag1=0;
if($zonecc){              $zflag1 |= (int)$flag_zonecc; }
if($zonekeepcc){          $zflag1 |= (int)$flag_zonekeepcc; }
if($zonesqldel){          $zflag1 |= (int)$flag_zonesqldel; }
if($zonereturn){          $zflag1 |= (int)$flag_zonereturn; }
if($zonesplitcc){         $zflag1 |= (int)$flag_zonesplitcc; }
if($zonecoupon){          $zflag1 |= (int)$flag_zonecoupon; }
if($zoneproddate){        $zflag1 |= (int)$flag_zoneproddate; }
if($zoneinlinecontrib){   $zflag1 |= (int)$flag_zoneinlinecontrib; }
if($zonefishgate){        $zflag1 |= (int)$flag_zonefishgate; }
if($zoneauthorizenet){    $zflag1 |= (int)$flag_zoneauthorizenet; }
if($zonecybercash){       $zflag1 |= (int)$flag_zonecybercash; }
if($zonepmtclear){        $zflag1 |= (int)$flag_zonepmtclear; }
if($zonepwcatalog){       $zflag1 |= (int)$flag_zonepwcatalog; }
if($zonerstrctpwcatalog){ $zflag1 |= (int)$flag_zonerstrctpwcatalog; }
if($zonezipshowgeo){      $zflag1 |= (int)$flag_zonezipshowgeo; }
if($zonedebug){           $zflag1 |= (int)$flag_zonedebug; }
if($zonegiftorder){       $zflag1 |= (int)$flag_zonegiftorder; }
if($zonelogaccess){       $zflag1 |= (int)$flag_zonelogaccess;}
if($zonetclink){  	      $zflag1 |= (int)$flag_zonetclink;}
if($zonetcpage){  	      $zflag1 |= (int)$flag_zonetcpage;}
if($zoneseqcartid){       $zflag1 |= (int)$flag_zoneseqcartid;}
if($zonecambist){         $zflag1 |= (int)$flag_zonecambist;}


if($act=="update"){

$zonecurrsym=ereg_replace(" *$","",$zonecurrsym);
$res=$fcz->query("update zone set zonedescr='$zonedescr', ".
	"zonecurrsym='$zonecurrsym', zoneact=$zoneact,".
	"zflag1=$zflag1, zonedeflid=$zonedeflid ".
	"where zoneid=$zoneid");

} elseif($act=="new"){

  $fcm->query("select numzone from master");
  $fcm->next_record();
  $numz=(int)$fcm->f("numzone");
  $fcm->free_result();
  $numz+=1;
  $fcm->query("update master set numzone=$numz");

  // on a new zone we cannot yet select a default language 
  // profile as it does not exist yet, so set to 0
  // index.php will see the 0 default, force a new language,
  // and langupd.php will force the new language as the default
  // for this zone when it sees that zonedeflid is 0
 
  if( $databaseeng=='odbc' && $dialect=='solid' ){
   $res=$fcz->query("call zone_ins ".
	"('$zonedescr','$zonecurrsym',$zoneact,$zflag1,0)"); 
  }else{
   $res=$fcz->query("insert into zone ".
    "(zonedescr,zonecurrsym,zoneact,zflag1,zonedeflid)".
	" values ".
	"('$zonedescr','$zonecurrsym',$zoneact,$zflag1,0)"); 
  }

} elseif($act=="delete"){

  $res=$fcz->query("delete from zone where zoneid=$zoneid");

}
if(!$res){
	$fcz->rollback();
	echo "<b>Failure updating zone: $res</b><br>\n";
}else{
	$fcz->commit();
	echo "Work Committed.<br>\n";
}
?>

<p>
<?php if( $act=="new" ){ ?>
You have added a new zone.  When you return to the central maintenance page,
you will immediately be prompted for a minimal set of support profiles for 
this new zone.  This will include a new language profile, web profile, 
vendor profile, at least one subzone profile, and so forth.  Please fill these
in as they are presented.
<p>
<?php } ?>

<form method=post action="index.php">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return to Maintenance">
</form>

<?php require('./footer.php');?>
