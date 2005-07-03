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

header("Last-Modified: ". gmdate("D, d M Y H:i:s",time()) . " GMT");

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

$orderid = getparam('orderid');
$ototal  = (int)getparam('ototal');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

if(!$zoneid || !$langid){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}

if(!$orderid){
echo 'Please click Back and select an orderid or type one. Thank you.';
exit;
}
?>

<h2 align=center>Order Record Display</h2>
<hr>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align=center valign=middle colspan=4 bgcolor="#FFFFFF">

<a href="orderdetail.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Order Detail Query Page</a>
<br>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>

</td></tr>

<?php
$fcohead = new FC_SQL;
$fcoline = new FC_SQL;
$fccust = new FC_SQL;

$ototal=(int)$ototal;

$notfound = 0;
if( $ototal ){
 $fcohead->query("select * from ohead where orderid='$orderid'");
 if( !$fcohead->next_record() ){
	$notfound = 1;
 }
}else{
	$notfound = 1;
}

if( $notfound ){

 echo '<tr><td align=center valign=middle colspan=4 bgcolor="#FFFFFF">'.
      'Order record '.$orderid.' not found<br>'.
      '</td></tr>';
	  
}else{	// notfound

 $purchid = $fcohead->f("purchid");

 $fccust->query("select * from cust where custid=$purchid");
 if( !$fccust->next_record() ){
  echo '<tr><td align=center valign=middle colspan=4 bgcolor="#FFFFFF">'.
       'Customer record '.$purchid.' not found<p>'.
      "</td></tr>\n";
 }else{
  // show the customer record

  echo "<tr><td valign=top colspan=2 bgcolor=\"#FFFFFF\"><b>Billing Information</b><p>\n";

  echo $fccust->f("custbsal").' '.$fccust->f("custbfname").' '.
       $fccust->f("custbmname").' '.$fccust->f("custblname")."<br>\n".
       $fccust->f("custbaddr1")."<br>\n".
	   $fccust->f("custbaddr2")."<br>\n".
       $fccust->f("custbcity").', '.$fccust->f("custbstate").' '.
       $fccust->f("custbzip").'-'.$fccust->f("custbzip4").' '.
       $fccust->f("custbnatl")."<br>\n".
       $fccust->f("custbacode").' '.$fccust->f("custbphone")."<br>\n".
       $fccust->f("custbemail")."<br>\n";

  echo "</td><td valign=top colspan=2 bgcolor=\"#FFFFFF\"><b>Shipping Information</b><p>\n";

  echo $fccust->f("custssal").' '.$fccust->f("custsfname").' '.
       $fccust->f("custsmname").' '.$fccust->f("custslname")."<br>\n".
       $fccust->f("custsaddr1")."<br>\n".
	   $fccust->f("custsaddr2")."<br>\n".
       $fccust->f("custscity").', '.$fccust->f("custsstate").' '.
       $fccust->f("custszip").'-'.$fccust->f("custszip4").' '.
       $fccust->f("custsnatl")."<br>\n".
       $fccust->f("custsacode").' '.$fccust->f("custsphone")."<br>\n".
       $fccust->f("custsemail")."<br>\n";

  echo "<tr><td valign=top colspan=2 bgcolor=\"#FFFFFF\"><b>IP Address:</b>\n";
  echo "</td><td valign=top colspan=2 bgcolor=\"#FFFFFF\">".$fcohead->f('oheadcustip')."\n";
  echo "</td></tr>\n";

  echo "</td></tr>\n";
  echo "<tr><td valign=top colspan=4 align=center bgcolor=\"#FFFFFF\"><b>Credit Information</b></td></tr>\n";
  echo "<tr><td valign=top colspan=2 bgcolor=\"#FFFFFF\">\n";

       echo '<b>CC Name</b><br>'.
            '<b>CC Number</b><br>'.
            '<b>CC Type</b><br>'.
            '<b>CC Expiration</b><br>';

  echo "</td><td valign=top colspan=2 bgcolor=\"#FFFFFF\">\n";

       echo $fccust->f("custccname")."<br>\n".
            $fccust->f("custccnumber")."<br>\n".
            $fccust->f("custcctype")."<br>\n".
            $fccust->f("custccexpmo").'/'.$fccust->f("custccexpyr")."<br>\n";
  
  echo "</td></tr>\n";
  $fccust->free_result();
 }

 $fcoline->query("select * from oline where orderid='$orderid'");
 if( !$fcoline->next_record() ){
  echo '<tr><td align=center valign=middle colspan=4 bgcolor="#FFFFFF">'.
       'No order detail records found.<p>'.
      "</td></tr>\n";
 }else{
  // show the order detail items

  echo "<tr><td bgcolor=\"#FFFFFF\"><b>SKU</b>".
       "</td><td bgcolor=\"#FFFFFF\"><b>Composite SKU</b>".
       "</td><td bgcolor=\"#FFFFFF\"><b>Quantity</b>".
       "</td><td bgcolor=\"#FFFFFF\"><b>Price</b>".
       "</td></tr>\n";
  do {
    echo "<tr><td bgcolor=\"#FFFFFF\">".
       $fcoline->f("sku").
       "</td><td bgcolor=\"#FFFFFF\">".
       $fcoline->f("compsku").
       "</td><td align=right bgcolor=\"#FFFFFF\">".
       $fcoline->f("qty").
       "</td><td align=right bgcolor=\"#FFFFFF\">".
       sprintf("%.2f",$fcoline->f("olprice")).
       "</td></tr>\n";
  } while( $fcoline->next_record() );

  $fcoline->free_result();
 }

 echo "<tr><td valign=top bgcolor=\"#FFFFFF\">".
      '<b>Product Subtotal</b><br></td>'.
      '<td valign=top align=right bgcolor="#FFFFFF">'.
      sprintf("%.2f",(double)$fcohead->f("pstotal")).'<br></td>'.
      '<td valign=top bgcolor="#FFFFFF"><b>Shipping</b><br></td>'.
      '<td valign=top align=right bgcolor="#FFFFFF">'.
      sprintf("%.2f",(double)$fcohead->f("shamt")).'<br>'.
      "</td></tr>\n";

 echo "<tr><td valign=top bgcolor=\"#FFFFFF\">".
      '<b>Donation</b><br></td>'.
      '<td valign=top align=right bgcolor="#FFFFFF">'.
      sprintf("%.2f",(double)$fcohead->f("contrib")).'<br></td>'.
      '<td valign=top bgcolor="#FFFFFF"><b>Total</b><br></td>'.
      '<td valign=top align=right bgcolor="#FFFFFF">'.
      sprintf("%.2f",(double)$fcohead->f("ototal")).'<br>'.
      "</td></tr>\n";

 $fcohead->free_result();

}  // notfound
?>

<tr><td align=center valign=top colspan=4 bgcolor="#FFFFFF">

<a href="orderdetail.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Order Detail Query Page</a>
<br>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
