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

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$subzcount = (int)getparam('subzcount');

// values of subzseq is an array handled below

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcc = new FC_SQL;

if( !$zoneid || !$langid ){?>
  A zone or language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
    <?php exit;
}

$i=0;
while ( $i < $subzcount ) {
 $seq = (int)getparam('subzseq'.$i);
 $sid = (int)getparam('subzid'.$i);
 $fcc->query(
	"update subzone set subzseq=$seq ".
	"where subzid=$zoneid and subzsid=$sid");
 $i++;
}
?>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

<?php require('./footer.php');?>
