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
require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

// ==========  end of variable loading  ==========

$fcs = new FC_SQL;
$fcz = new FC_SQL;
?>

<h2 align="center">SubZone Maintenance</h2>
<hr />

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td colspan="2" align="center" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp()">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#ffffff">
<b>Modify SubZone</b>
<td align="center" bgcolor="#ffffff">
<b>Delete SubZone</b>
</td></tr>
<tr><td align="center" valign="top" bgcolor="#ffffff">

<form method="post" action="subzonemod.php">

<input type="hidden" name="act" value="update" />

To modify an existing subzone,<br />
select the vendor and subzone from the<br />
list and click <i>Modify SubZone.</i><br />

<p></p>

<?php 
$fcs->query("select count(*) as cnt from subzone where subzid=$zoneid"); 
$fcs->next_record();
$st=(int)$fcs->f('cnt')+1;
$fcs->free_result();
?>
<select name="subzone" size="<?php echo $st; ?>" onfocus="currfield='subzsid';"
 onChange="submit();">
<option value="" selected="selected">[no change]</option>
<?php 
$fcs->query(
	"select subzsid,subzdescr,subzvendid from subzone ".
	"where subzid=$zoneid order by subzsid"); 
while ( $fcs->next_record() ) {
    $vendor=$fcs->f('subzvendid');
    $fcz->query("select vendid,vendname from vend where vendid=$vendor");
    $fcz->next_record();
    echo '<option value="'.$fcz->f('vendid').":".$fcs->f('subzsid').'">';
    echo $fcz->f('vendname')." : ".substr($fcs->f('subzdescr'),0,30).'</option>';
    $fcz->free_result();
}
$fcs->free_result(); ?>
</select>

<p></p>

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify SubZone"
    onclick="closehelp()" /><br />

</form>

</td><td align="center" valign="top" bgcolor="#ffffff">

<form method="post" action="subzoneupd.php">

<input type="hidden" name="act" value="delete" />

To delete an existing subzone,<br />
select the vendor and subzone from the<br />
list and click <i>Delete SubZone.</i><br />

<p></p>

<select name="subzone" size="<?php echo $st; ?>">
<option value="" selected="selected">[no change]</option>
<?php 

$fcs->query("select subzsid,subzdescr,subzvendid from subzone order by subzsid");
while ($fcs->next_record()) {
    $vendor=$fcs->f('subzvendid');
    $fcz->query("select vendid,vendname from vend where vendid=$vendor");
    $fcz->next_record();
    echo '<option value="'.$fcz->f('vendid').":".$fcs->f('subzsid').'">';
    echo $fcz->f('vendname')." : ".substr($fcs->f('subzdescr'),0,30).'</option>';
    $fcz->free_result();
} 
$fcs->free_result(); ?>
</select>

<p></p>

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Delete SubZone" onclick="closehelp()" /><br />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp()">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
