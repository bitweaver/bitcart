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
require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// addslashes() for non-numbers, no exceptions

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

$zid        =   (int)getparam('zid');
$lid        =   (int)getparam('lid');
$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
$cctype     =   getparam('cctype');
$cc_number  =   getparam('cc_number');
// ==========  end of variable loading  ==========

if($cc_number==0 || $cctype==""){
  echo "</center><p><b>A required field has been left blank.</p> ";
  echo "Please click the &quot;Back&quot; button on your browser ";
  echo "and make sure they are properly filled in.  Thank you.</b>\n";
  exit;
}
require_once( BITCART_PKG_PATH.'cc.php');
$cc_number = preg_replace( "/\D/", '', $cc_number );
$rv=cc_mod10($cctype,$cc_number);
if($rv==0){
  echo "The CC number $cc_number did not verify.\n";
}else{
  echo "The CC number $cc_number verified.\n";
}
?>
<p></p>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return To Central Maintenance Page</a><br />
