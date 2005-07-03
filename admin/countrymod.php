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

$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
// ==========  end of variable loading  ==========

if( !$zoneid || !$langid ){
?>
<p>Neither a zone or language was selected prior to entry.</p>
<p><a href="index.php">Central Maintenance Page</a></p>
<?php
	exit;
}

require('./admin.php');
require('./header.php');
?>

<h2 align="center">Modify Country List</h2>
<hr />
<p></p>

<?php 
$fcc = new FC_SQL;
$fcl = new FC_SQL;

$fcl->query(
  "select langiso from lang where langzid=$zoneid and langid=$langid");
$fcl->next_record();
$langiso=$fcl->f('langiso');
$fcl->free_result();

$fcc->query(
	"select * from country,countrylang ".
	"where ctryzid=$zoneid and ctrylid=$langid and ".
	"ctryiso=ctrylangciso and ctrylangliso='$langiso' ".
	"order by ctryseq,ctrylangname"); 
?>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" class="text">
<tr><td align="center" bgcolor="#FFFFFF" colspan=6>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a>

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF" colspan=6>

<b>
Modify Country List<br />
</b>

</td></tr>

<form method="post" action="countryupd.php">
<tr>
<td align="center" bgcolor="#FFFFFF"></td>
<td align="center" bgcolor="#FFFFFF">Active</td>
<td align="center" bgcolor="#FFFFFF">Country Name</td>
<td align="center" bgcolor="#FFFFFF">Sequence Number</td>
<td align="center" bgcolor="#FFFFFF">2 Char ISO</td>
<td align="center" bgcolor="#FFFFFF">3 Char ISO</td>
<input type="hidden" name="act" value="add" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid; ?>" />
<input type="hidden" name="langid" value="<?php echo $langid; ?>" />
<input type="hidden" name="langiso" value="<?php echo $langiso?>" />

</td></tr>
<tr><td bgcolor="#FFFFFF">

</td><td align="center" bgcolor="#FFFFFF">
<input type="checkbox" name="active0" value="1" checked />
</td><td align="center" bgcolor="#FFFFFF">
<input type="text" name="countryname0" value="" />
</td><td align="center" bgcolor="#FFFFFF">
<input type="text" name="seq0" value="" size="4" maxlength="5" />
</td><td align="center" bgcolor="#FFFFFF">
<input type="text" name="iso20" value="" size="2" maxlength="2" />
</td><td align="center" bgcolor="#FFFFFF">
<input type="text" name="iso30" value="" size="3" maxlength="3" />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF" colspan=6>
<input type="submit" value="Add Country">
</td></tr>
</form>

<form method="post" action="countryupd.php">
<tr>
<td align="center" bgcolor="#FFFFFF">Delete</td>
<td align="center" bgcolor="#FFFFFF">Active</td>
<td align="center" bgcolor="#FFFFFF">Country Name</td>
<td align="center" bgcolor="#FFFFFF">Sequence Number</td>
<td align="center" bgcolor="#FFFFFF">2 Char ISO</td>
<td align="center" bgcolor="#FFFFFF">3 Char ISO</td>
</tr>

<input type="hidden" name="act" value="update" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid; ?>" />
<input type="hidden" name="langid" value="<?php echo $langid; ?>" />

<?php
$i=1;	// start at 1; 0 is a new record
while ( $fcc->next_record() ){
?>
	<tr>
	<td align="center" bgcolor="#FFFFFF">
	<input type="checkbox" name="delcountry<?php echo $i; ?>" value="1" />

<?php
 if( $fcc->f('ctryactive') >= 1 ){
   $chk = 'checked';
   $bg = 'cccccc';
 }else{
   $chk = '';
   $bg = 'ffffff';
 }
?>
	</td><td align="center" bgcolor="#<?php echo $bg; ?>">
	<input type="checkbox" name="active<?php echo $i; ?>" value="1" <?php echo $chk; ?> />

	</td><td align="center" bgcolor="#FFFFFF">
	<input type="text" name="countryname<?php echo $i; ?>" value="<?php echo $fcc->f('ctrylangname')?>" />

	</td><td align="center" bgcolor="#FFFFFF">
	<input type="text" name="seq<?php echo $i; ?>" value="<?php echo $i*10?>" size="4" maxlength="5" />

	</td><td align="center" bgcolor="#FFFFFF">
	<?php echo $fcc->f('ctrylangciso2')?>
	<input type="text" name="iso2<?php echo $i; ?>" value="<?php echo $fcc->f('ctrylangciso2')?>" size="2" maxlength="2" />
	<input type="hidden" name="oldiso2<?php echo $i; ?>" value="<?php echo $fcc->f('ctrylangciso2')?>" />

	</td><td align="center" bgcolor="#FFFFFF">
	<?php echo $fcc->f('ctrylangciso')?>
	<input type="text" name="iso3<?php echo $i; ?>" value="<?php echo $fcc->f('ctrylangciso')?>" size="3" maxlength="3" />
	<input type="hidden" name="oldiso3<?php echo $i; ?>" value="<?php echo $fcc->f('ctrylangciso')?>" />

	</td></tr>
<?php
	$i++;
}
$fcc->free_result();
?>

<tr><td align="center" bgcolor="#FFFFFF" colspan=6>
<input type="hidden" name="langiso" value="<?php echo $langiso?>" />
<input type="hidden" name="ccount" value="<?php echo $i?>" />
<input type="submit" value="Modify List">
</td></tr>

</form>

<tr><td align="center" bgcolor="#FFFFFF" colspan=6>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
