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

$rid    = (int)getparam('rid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
?>

<h2 align="center">Modify A Dynamic Text Area</h2>
<hr />
<p></p>

<?php 
$fcc = new FC_SQL;

$fcc->query("select * from auxtext where rid=$rid"); 
if( !$fcc->next_record() ){?>
  The selected text area could not be found.  This may reflect an
  inconsistent database; please check with your system
  administrator.
  <?php $fcc->free_result();
  exit;
}?>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="auxtextndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Dynamic Text Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Modify A Link<br />
</b>

</td></tr>

<form method="post" action="auxtextupd.php">
<input type="hidden" name="act" value="mod" />
<input type="hidden" name="rid" value="<?php echo $rid?>" />

<tr><td align="right" bgcolor="#FFFFFF">
Title
</td><td align="left" bgcolor="#FFFFFF">
<input name="title" size="40"
value="<?php echo stripslashes($fcc->f("title"))?>" />
</td></tr>
<tr><td align="right" bgcolor="#FFFFFF">
Text
</td><td align="left" bgcolor="#FFFFFF">
<textarea name="text" rows="5" cols="50">
<?php echo stripslashes($fcc->f("text"))?>
</textarea>
</td></tr>
<tr><td align="right" bgcolor="#FFFFFF">
Location for Text
</td><td align="left" bgcolor="#FFFFFF">
<select name="loc">
<?php if ($fcc->f("loc")==2){?>
<option value="2" selected>Cart</option>
<?php }else{ ?>
<option value="2">Cart</option>
<?php } ?>
</select>
</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF" align="center">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Text"
 onSubmit="closehelp();" />
<input type="reset" value="Clear Field" />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="auxtextndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Dynamic Text Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
