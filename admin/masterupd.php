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
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$show   = (int)getparam('show');
$srch   = (int)getparam('srch');

$act    = getparam('act');
$oldzid = (int)getparam('oldzid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$droot="BITCART_PKG_PATH";

if($zoneid==0){?>
	Please click the &quot;Back&quot; button on your browser
	and select a default zone.  Thank you.
    <?php exit;
}

$fcm = new FC_SQL;
$fcm->Auto_commit = 0;
$fcm->query("select count(*) as cnt from master");
$fcm->next_record();
if( $fcm->f("cnt") > 0 ){
 $fcm->free_result();
 $res=$fcm->query(
  "update master set zoneid=$zoneid");
}else{
 $res=$fcm->query(
  "insert into master (zoneid) values ($zoneid)");
}
if(!$res){
 $fcm->rollback();
 echo "<b>Failure updating master: $res</b><br>\n";
}else{
 $fcm->commit();
 echo "Work Committed.<br>\n";
}?>

<p>

<?php if($zoneid!=$oldzid){?>

The default zone/catalog has changed. Click below to enter 
language profile modification to select the default language for
this zone.

<p>

<form method=post action="langndx.php">
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return to Master Update">
</form>

<?php }else{?>

<form method=post action="index.php">
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return to Maintenance">
</form>

<?php }?>

<?php require('./footer.php');?>
