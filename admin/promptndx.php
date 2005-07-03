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

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$no_lang_iso=1;
require_once( BITCART_PKG_PATH.'languages.php');?>
<table width="650" bgcolor="#666666" cellspacing="1" cellpadding="3" align="center" class="text">
<tr><td align="center" bgcolor="#FFFFFF">
<font face="arial,helvetica" size="3">
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
 Return To Central Maintenance Page</a><br>
 </font>
 </td></tr>
<tr><td valign="top" align="center" bgcolor="#FFFFFF">
<font face="arial,helvetica" size=3><b>
Select Prompt Language to Modify
</b></font>
</td></tr>
<tr><td valign="top" align="center" bgcolor="#FFFFFF">
<form method="post" name="promptmod" action="promptmod.php">
<select name="langiso" size="<?php echo $sz ?>">
<?php
ksort ($language_names);
reset ($language_names);
while ( list($key,$val) = each($language_names)){
	if( !empty( $currl ) && $currl == $key ){
		echo '<option value="'.$key.'" selected>'.$val."\n";
	}else{
		echo '<option value="'.$key.'">'.$val."\n";
	}
}
?>
</select><br>
</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">
  <input type=hidden name=langid value="<?php echo $langid?>">
  <input type=hidden name=zoneid value="<?php echo $zoneid?>">
<input type="submit" value="Select Language">
</form>
</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">
<font face="arial,helvetica" size="3">
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
 Return To Central Maintenance Page</a><br>
 </font>
 </td></tr>
</table>
<?php require('./footer.php');?>
