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

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$ccount = (int)getparam('ccount');
$langiso = getparam('langiso');
// ==========  end of variable loading  ==========

require('./admin.php');

$fcc = new FC_SQL;

if($act=="update"){

	$i = 1;
	while( $i < $ccount ){
		$seq = (int)getparam( 'seq'.$i );
		$active = (int)getparam( 'active'.$i );
		$iso2 = strtoupper(getparam( 'iso2'.$i ));
		$oldiso2 = getparam( 'oldiso2'.$i );
		$iso3 = strtoupper(getparam( 'iso3'.$i ));
		$oldiso3 = getparam( 'oldiso3'.$i );
		$countryname = getparam( 'countryname'.$i );
		$delcountry = (int)getparam( 'delcountry'.$i );
		if( !$delcountry ){
		  $res=$fcc->query("update country set ".
			"ctryiso='$iso3', ctryactive=$active, ctryseq=$seq ".
			"where ctryiso='$oldiso3' and ctryzid=$zoneid and ctrylid=$langid");
		  $res=$fcc->query("update countrylang set ".
			"ctrylangname='$countryname', ctrylangliso='$langiso', ".
			"ctrylangciso2='$iso2', ctrylangciso='$iso3' ".
			"where ctrylangliso='$langiso' and ctrylangciso='$oldiso3'");
		}else{
		  $res=$fcc->query("delete from country ".
			"where ctryiso='$oldiso3' and ctryzid=$zoneid and ctrylid=$langid");
		  $res=$fcc->query("delete from countrylang ".
			"where ctrylangliso='$langiso' and ctrylangciso='$oldiso3'");
		}
		$i++;
	}

} elseif($act=="add"){

	$iso2 = strtoupper(getparam( 'iso20' ));
	$iso3 = strtoupper(getparam( 'iso30' ));
	$seq = (int)getparam( 'seq0' );
	$active = (int)getparam( 'active0' );
	$countryname = getparam( 'countryname0' );

	$res=$fcc->query('insert into country '.
		'(ctryzid,ctrylid,ctryiso,ctryseq,ctryactive) '.
		' values '.
		"($zoneid,$langid,'$iso3',$seq,$active)");
	$res=$fcc->query('insert into countrylang '.
		'(ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname) '.
		' values '.
		"('$langiso','$iso3','$iso2','$countryname')");

}
if(!$res){
	$fcc->rollback();
	echo "<b>Failure updating country: $res</b><br>\n";
}else{
	$fcc->commit();
	echo "Work Committed.<br>\n";
}
?>

<p><a href="countrymod.php?zoneid=<?php echo $zoneid ?>&langid=<?php echo $langid ?>">Return to Country Maintenance</a></p>
<p><a href="index.php?zoneid=<?php echo $zoneid ?>&langid=<?php echo $langid ?>">Return to Central Maintenance</a></p>
</form>
</p>

<?php require('./footer.php');?>
