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
// addslashes() for non-numbers, no exceptions

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

$zoneid=(int)getparam('zoneid');
$langid=(int)getparam('langid');
$act=getparam('act');

$cpnid=getparam('cpnid');
$oldcpnid=getparam('oldcpnid');
$cpnminqty=(int)getparam('cpnminqty');
$cpnminamt=(int)getparam('cpnminamt');
$cpnredeem=(int)getparam('cpnredeem');
$cpnmaximum=(int)getparam('cpnmaximum');
$discount=(double)getparam('discount');
$cpnsku=getparam('cpnsku');

$ssy = (int)getparam('ssy');
$ssm = (int)getparam('ssm');
$ssd = (int)getparam('ssd');

$sey = (int)getparam('sey');
$sem = (int)getparam('sem');
$sed = (int)getparam('sed');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$droot="BITCART_PKG_PATH";

if( $cpnid=='' ){?>
  A coupon ID was not entered.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.</p>
    <?php exit;
} // end if

$coupon = new FC_SQL;

// convert strings to date
if($ssm && $ssd && $ssy){
	$ssdate=mktime(0,0,0,$ssm,$ssd,$ssy);
}else{
	$ssdate=0;
} // end if
if($sem && $sed && $sey){
	$sedate=mktime(23,59,59,$sem,$sed,$sey);
}else{
	$sedate=0;
} // end if


if($act=="update"){

	$res = $coupon->query("update coupon set ".
	"cpnid='$cpnid',     cpnsku='$cpnsku',     cpnstart=$ssdate, ".
	"cpnstop=$sedate,    cpnminqty=$cpnminqty, cpnminamt=$cpnminamt, ".
	"discount=$discount, cpnredeem=$cpnredeem, cpnmaximum=$cpnmaximum ".
	"where cpnid='$oldcpnid'"); 

} elseif($act=="new"){

  $res = $coupon->query("insert into coupon ".
    "(cpnid,cpnsku,cpnstart,cpnstop,cpnminqty,cpnminamt,discount,".
	"cpnredeem,cpnmaximum)".
	" values ".
	"('$cpnid','$cpnsku',$ssdate,$sedate,$cpnminqty,$cpnminamt,".
	"$discount,0,$cpnmaximum)");

} elseif($act=="delete"){

  $res=$coupon->query("delete from coupon where cpnid='$cpnid'");

} // end update & insert queries 

if(!$res){
	$coupon->rollback();
	echo "<b>failure updating coupon: $res</b><br />\n";
}else{
	$coupon->commit();
	echo "Work committed.<br />\n";
} // end if
?>

<p>

<form method="post" action="couponndx.php">
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Return to Coupon Maintenance" />
</form>

</p>
<?php require('./footer.php');?>
