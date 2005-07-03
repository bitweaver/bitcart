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

$langiso = getparam('langiso');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
?>
<div align=center>
<table width="650" cellspacing="1" cellpadding="3" bgcolor="#666666" class="text">
 <tr>
  <td colspan="3" align="center" bgcolor="#FFFFFF">
   <a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
    onClick="closehelp()">
    Return To Central Maintenance Page</a><br>
  </td>
 </tr>
  <form method=post name="promptupd" action="promptupd.php">
 <tr>
  <td align="center" colspan="3" bgcolor="#FFFFFF">
  <font face="arial,helvetica" size="3">
  <b>FishCart Prompt Modification Form</b>
  </font>
  <br>
  </td>
 </tr>
 <tr>
  <td colspan=3 bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
   Please duplicate any HTML markup in the prompts. Change all wording desired, then click Submit Modifications at the bottom of this page.
   </td>
   </font>
 </tr>
 <tr>
  <td valign=top align=left bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
   <b>Prompt Name</b>
   </font>
   </td>
  <td valign=top align=left bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
   <b>Old Prompt</b>
   </font>
   </td>
  <td valign=top align=left bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
   <b>New Prompt</b>
   </font>
   </td>
 </tr>

<?php
require("../languages/lang_$langiso.php");
reset($fc_prompt);
while ( list($keyname,$txt) = each($fc_prompt) ){
 $txt=trim($txt);
 $nrows = (int)(strlen($txt)/25) + 2;
 $txt=ereg_replace("<","&lt;",$txt);
 $txt=ereg_replace(">","&gt;",$txt);
?>
 <tr>
  <td valign=top bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
   <?php echo $keyname;?>
   </font>
   </td>
   <td valign=top bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
   <?php echo  $txt ?>
   </font>
   </td>
   <td bgcolor=#FFFFFF>
   <font face="arial,helvetica" size=2>
<?php if (!$txt){
	$txt='Undefined Prompt';
} ?>
<textarea name="<?php echo $keyname?>" rows=<?php echo $nrows?> cols=50 wrap=virtual><?php echo $txt?></textarea>
 <input type=hidden name="fc_prompt[]" value="<?php echo $keyname?>">
   </font>
  </td>
 </tr>
<?php
}
?>

 <tr>
  <td colspan=3 align=center bgcolor=#FFFFFF><b>Salutations:</b><br>
  </td>
 </tr>

<?php
$i=0;
$cnt=count($salutearray);
while ( $i < $cnt ){
?>
 <tr>
  <td bgcolor=#FFFFFF>
  </td>
  <td bgcolor=#FFFFFF>
  <?php echo  $salutearray[$i] ?>
  </td>
  <td bgcolor=#FFFFFF>
  <input name="salutearray[]" value="<?php echo  $salutearray[$i] ?>" size=50>
  </td>
 </tr>
<?php
 $i++;
}
?>
 <tr>
  <td colspan=3 align=center bgcolor=#FFFFFF><b>Other salutations you may desire:</b><br>
  </td>
 </tr>
 <tr>
  <td bgcolor=#FFFFFF colspan=3 align=center>
  <input name="salutearray[]" size=50>
  </td>
 </tr>
 <tr>
  <td bgcolor=#FFFFFF colspan=3 align=center><br>
  <input name="salutearray[]" size=50>
  </td>
 </tr>
 <tr>
  <td colspan=3 align=center bgcolor=#FFFFFF>
  <?php
  $salcnt=count($salutearray);
  $promptcnt=count($fc_prompt);
  ?>
  <input type=hidden name=langiso value="<?php echo $langiso?>">
  <input type=hidden name=salcnt value="<?php echo $salcnt?>">
  <input type=hidden name=promptcnt value="<?php echo $promptcnt?>">
  <input type=hidden name=langid value="<?php echo $langid?>">
  <input type=hidden name=zoneid value="<?php echo $zoneid?>">
  <input type=submit value="Submit Modifications">
  </td>
 </tr>
  </form>
 <tr>
  <td colspan=3 align=center bgcolor=#FFFFFF>
  <a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
   onClick="closehelp()">
   Return To Central Maintenance Page</a><br>
  </td>
 </tr>
</table>
</div>
<?php require('./footer.php');?>
