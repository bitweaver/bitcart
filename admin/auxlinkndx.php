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
// ==========  end of variable loading  ==========


require('./admin.php');
require('./header.php');
?>

<h2 align="center">Dynamic Link Maintenance</h2>
<hr />
<p></p>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<b>
Modify An Existing Link<br />
</b>

</td><td align="center" bgcolor="#FFFFFF">

<b>
Delete An Existing Link<br />
</b>

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<form name="linkform" method="post" action="auxlinkmod.php">

To modify an existing link,<br />
select its name from the list and<br />
click <i>Modify Selected Link.</i>
<br />

<select name="link" size="5"
 onChange="document.linkform.action='auxlinkmod.php';submit();">
<option value="" selected>[no change]</option>
<?php 
$get_links = new FC_SQL;
$get_links->query("select * from auxlinks order by seq");
	while ($get_links->next_record()){
		$linklst=$get_links->f("rid");
		print "<option value=\"$linklst\">".$get_links->f("title")."</option>\n";
	}
$get_links->free_result();	
?>
</select>
<p>
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Selected Link"><br />
</p>

</form>

</td><td align="center" bgcolor="#FFFFFF">

<form name="linkdel" method="post" action="auxlinkupd.php">

<input type="hidden" name="act" value="del" />

To delete an existing link,<br />
select its name from the list and<br />
click <i>Delete Selected Link.</i>
<br />

<select name="link" size="5">
<option value="" selected>[no change]</option>
<?php 
$get_links = new FC_SQL;
$get_links->query("select * from auxlinks order by seq");
	while ($get_links->next_record()){
		$linklst=$get_links->f("rid");
		print "<option value=\"$linklst\">".$get_links->f("title")."</option>\n";
	}
$get_links->free_result();	
?>
</select>

<p>

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Delete Selected Link" /><br />

</form>
</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
