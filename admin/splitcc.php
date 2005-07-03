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
$act = getparam('act');
$fetched = getparam('fetched');
$delday = (int)getparam('delday');
$showall = (int)getparam('showall');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fccc = new FC_SQL;
$fccc->User = '';
$fccc->Password = '';

if($act=="update"){
 $i=0;
 $tsi=count($fetched);
 while($i<$tsi){
  if($fetched[$i]){
   $tst=$fetched[$i];
   if( $databaseeng=='odbc' && $dialect=='solid' ){
    $fccc->query("update _ccnum set fetched='1' where tstamp=$tst");
   }else{
    $fccc->query("update ${instid}_ccnums set fetched='1' where tstamp=$tst");
   }
  }
  $i++;
 }
 $fccc->commit();
}elseif($act=="delete"){
 // delday contains the number of days past to purge; 0 means all unpurged
 // start by finding midnight just past
 if($delday){
  $yr=date("Y");
  $mn=date("m");
  $dy=date("d");
  $delstamp=mktime(0,0,0,$mn,$dy,$yr)-($delday*86400);
 }else{
  $delstamp=time();
 }
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $fccc->query(
   "delete from _ccnum where tstamp<$delstamp and fetched='1'");
 }else{
  $fccc->query(
   "delete from ${instid}_ccnums where tstamp<$delstamp and fetched='1'");
 }
 $fccc->commit();
}
?>

<h2 align=center>
FishCart Online CC Number Collection
</h2>
<hr>
<p>

<center><table border=0 width=650 cellpadding=4 cellspacing=1 bgcolor=#666666 class="text">

<?php 
if($showall){
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $fccc->query("select count(*) as cnt from _ccnum");
 }else{
  $fccc->query("select count(*) as cnt from ${instid}_ccnums");
 }
}else{
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $fccc->query(
		"select count(*) as cnt from _ccnum where fetched='0'");
 }else{
  $fccc->query(
		"select count(*) as cnt from ${instid}_ccnums ".
		"where fetched='0'");
 }
}
$fccc->next_record();
$cnt=$fccc->f("cnt");
$fccc->free_result();

if( $databaseeng=='odbc' && $dialect=='solid' ){
 $fccc->query("select count(*) as cnt from _ccnum");
}else{
 $fccc->query("select count(*) as cnt from ${instid}_ccnums");
}
$fccc->next_record();
$tot=$fccc->f("cnt");
$fccc->free_result();
?>

<?php if($cnt){?>
<tr><td align=center colspan=4 bgcolor=#ffffff>
<?php }else{?>
<tr><td align=center bgcolor=#ffffff colspan=2>
<?php }?>
<a href="index.php">Return to Central Maintenance Page</a>
</td></tr>
<?php if($cnt){?>
<tr><td colspan=4 align=center bgcolor=#ffffff>
<?php }else{?>
<tr><td align=center bgcolor=#ffffff>
<?php }?>
<?php if($showall){?>
<b>All Orders</b>
<?php }else{?>
<b>Uncollected Orders</b>
<?php }?>
<form method=post name=occnum action="splitcc.php">
</td></tr>


<?php if($cnt){?>

<tr>
<td align=center bgcolor=#ffffff><b>Date / Time</b><br></td>
<td align=center bgcolor=#ffffff><b>Order ID</b><br></td>
<td align=center bgcolor=#ffffff><b>Last 6 CC Digits</b><br></td>
<td align=center bgcolor=#ffffff><b>Collected</b><br></td>
</tr>

<?php 
if($showall){
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $selstr="select userid,tstamp,fetched,orderid,cc6 from _ccnum ".
   "order by tstamp";
 }else{
  $selstr="select userid,tstamp,fetched,orderid,cc6 from ${instid}_ccnums ".
   "order by tstamp";
 }
}else{
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $selstr="select userid,tstamp,fetched,orderid,cc6 from _ccnum ".
   "where fetched='0' order by tstamp";
 }else{
  $selstr="select userid,tstamp,fetched,orderid,cc6 from ${instid}_ccnums ".
   "where fetched='0' order by tstamp";
 }
}
$fccc->query($selstr);
while ( $fccc->next_record() ) {
	$dt=date("M d, Y  H:i",$fccc->f("tstamp"));
?>

<tr>
<td bgcolor=#ffffff><?php echo $dt?><br></td>
<td bgcolor=#ffffff><?php $oid=ereg_replace("[ \t\n\r]*$","",$fccc->f("orderid"));echo $oid;?><br></td>
<td align=center bgcolor=#ffffff><?php echo $fccc->f("cc6")?><br></td>
<td align=center bgcolor=#ffffff><input type=checkbox name=fetched[]<?php if($fccc->f("fetched")){?> checked<?php }?> value="<?php echo $fccc->f("tstamp")?>"><br></td>
</tr>

<?php }
 $fccc->free_result();
}else{?>
<tr><td align=center bgcolor=#ffffff>
<?php if($tot){?>
There are no outstanding uncollected orders.
<?php }else{?>
There are no available orders.
<?php }?>
</td></tr>
<?php }
?>

<?php if($tot){
if($cnt){?>
<tr><td align=center colspan=4 bgcolor=#ffffff>
<?php }else{?>
<tr><td align=center bgcolor=#ffffff>
<?php }
if($showall){?>
<b>Show uncollected orders</b>
<input type=checkbox name=showall value="0" onClick="submit();return true">
<?php }elseif($tot!=$cnt){?>
<b>Show all orders</b>
<input type=checkbox name=showall value="1" onClick="submit();return true">
<?php }?>
<?php if(!$cnt){?>
</form>
<?php }else{?>
</td></tr>
<tr><td align=center colspan=4 bgcolor=#ffffff>
<input type=hidden name=act value="update">
<input type=submit value="Update Marked Orders As Collected">
</form>
<?php }?>
<p>
<i>
Click the &quot;Collected&quot; box beside each order as you collect the
last six digits of the credit card.  When finished, click the 
&quot;Update Marked Orders As Collected&quot; button.  Collected orders will
no longer be visible but are still in the database; they can be reshown
by clicking the &quot;Show all orders&quot; button.
</i>
</td></tr>

<?php if($cnt){?>
<tr><td align=center colspan=4 bgcolor=#ffffff>
<?php }else{?>
<tr><td align=center bgcolor=#ffffff>
<?php }

if( $databaseeng=='odbc' && $dialect=='solid' ){
 $fccc->query("select count(*) as cnt from _ccnum where fetched='1'");
}else{
 $fccc->query("select count(*) as cnt from ${instid}_ccnums where fetched='1'");
}
$fccc->next_record();
$cnt=$fccc->f("cnt");
$fccc->free_result();

?>
<form method=post name=occact action="splitcc.php">
<input type=hidden name=act value="delete">
Purge collected orders over <select name=delday>
<option value="5">5
<option value="4">4
<option value="3">3
<option value="2">2
<option value="1">1
<option value="0">ALL
</select> days old
<input type=submit value="Purge"></form>
<i>
There are <?php echo $cnt ?> orders that are collected but not deleted.
<p>
Periodically collected and processed orders should be deleted from the
database.  Collected orders are not deleted from the database until
manually purged.
</i>
</td></tr>

<?php } // tot ?>

<?php if($cnt){?>
<tr><td align=center colspan=4 bgcolor=#ffffff>
<?php }else{?>
<tr><td align=center bgcolor=#ffffff>
<?php }?>
<a href="index.php">Central Maintenance Page</a>
</td></tr>

</table></center>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
