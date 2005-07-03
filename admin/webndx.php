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
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');?>

<h2 align=center>Web Profile Maintenance</h2>
<hr>
<?php 
if($zoneid==""){?>
	A zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select one.  Thank you.<p>
<?php exit;}
if($langid==""){?>
	A language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select one.  Thank you.<p>
<?php exit;}?>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center bgcolor=#ffffff>

<b>Modify An Existing Web Profile</b>

</td><td align=center bgcolor=#ffffff>

<b>Delete An Existing Web Profile</b>

</td></tr>
<tr><td align=center bgcolor=#ffffff>

<?php 
$fcw = new FC_SQL;
?>

<form name=webmod method="post" action="webmod.php">

To modify an existing Web profile,<br>
select its name from the list and<br>
click <i>Modify Selected Profile</i>.
<br>

<?php 
$fcw->query("select count(*) as cnt from web ".
	"where webzid=$zoneid and weblid=$langid"); 
$fcw->next_record();
$wt=(int)$fcw->f('cnt');
$fcw->free_result();
$fcw->query("select * from web ".
	"where webzid=$zoneid and weblid=$langid order by webid"); 
?>
<select name=webid size="<?php echo $wt+1?>"
 onChange="document.webmod.action='webmod.php';submit();">
<option value="" selected>[no change]
<?php 
while( $fcw->next_record() ){?>
 <option value="<?php echo $fcw->f("webid")?>"><?php echo $fcw->f("webdescr")?>
<?php }
$fcw->free_result();
?>
</select>
<p>

<input type=hidden name=act value=update>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Modify Selected Profile"><br>

</form>

</td><td align=center bgcolor=#ffffff>

<form METHOD="POST" action="webupd.php">

To delete an existing Web profile,<br>
select its name from the list and<br>
click <i>Delete Selected Profile.</i>
<br>

<select name=webid size="<?php echo $wt+1?>">
<option value="" selected>[no change]
<?php 
$fcw->query("select * from web ".
	"where webzid=$zoneid and weblid=$langid order by webid"); 
while( $fcw->next_record() ){?>
 <option value="<?php echo $fcw->f("webid")?>"><?php echo $fcw->f("webdescr")?>
<?php }
$fcw->free_result();
?>
</select>
<p>

<input type=hidden name=act value=delete>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Delete Selected Profile">

</form>
</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
