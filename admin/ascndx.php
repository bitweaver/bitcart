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

$fcv = new FC_SQL;
?>

<h2 align="center">Associate Profile Maintenance</h2>
<hr />
<?php 
if($zoneid==""){?>
	A zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select one.  Thank you.</p>
<?php exit;}
if($langid==""){?>
	A language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select one.  Thank you.</p>
<?php exit;}?>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<b>
Modify An Existing Associate Profile<br />
</b>

</td><td align="center" bgcolor="#FFFFFF">

Delete An Existing Associate Profile<br />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<form name="ascmod" method="post" action="ascmod.php">

To modify an existing Associate profile,<br />
select its name from the list and<br />
click <i>Modify Selected Profile</i>.
<br />

<select name="ascid" size="1"
 onChange="document.ascmod.action='ascmod.php';submit();">
<option value="" selected>[no change]</option>
<?php 
$fcv->query("select ascid,ascname from associate ".
	"where asczid=$zoneid order by ascid"); 
while( $fcv->next_record() ){?>
 <option value="<?php echo $fcv->f("ascid")?>"><?php echo $fcv->f("ascname")?>
 </option>
<?php }?>
</select>
<p>

<input type="hidden" name="act" value="update" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Selected Profile" /><br />
</p>
</form>

</td><td align="center" bgcolor="#FFFFFF">

<form METHOD="POST" action="ascupd.php">

To delete an existing Asscoaite profile,<br />
select its name from the list and<br />
click <i>Delete Selected Profile.</i>
<br />

<select name="ascid" size="1">
<option value="" selected>[no change]</option>
<?php 
$fcv->query("select * from associate ".
	"where asczid=$zoneid order by ascid"); 
while( $fcv->next_record() ){?>
<option value="<?php echo $fcv->f("ascid")?>"><?php echo $fcv->f("ascname")?>
</option>
<?php }?>
</select>
<p>

<input type="hidden" name="act" value="delete" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Delete Selected Profile" /><br />
</p>

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
