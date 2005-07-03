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

$fcc = new FC_SQL;

if(!$zoneid||!$langid){?>
  A zone or language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
    <?php exit;
}
?>

<h2 align=center>Product Sequence Maintenance</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center bgcolor=#ffffff colspan=2>

<b>
Modify Product Sequences<br>
</b>

</td></tr>
<tr><td align=center bgcolor=#ffffff>

<form name=catform method="post" action="prodcatseq.php">

To modify the product sequences in an<br>
existing category, select its name from<br>
the list and click <i>Modify Selected Category.</i>
<br>

<?php 
$fcc->query("select count(*) as cnt from cat ".
	"where catzid=$zoneid and catlid=$langid"); 
$fcc->next_record();
$ct=(int)$fcc->f("cnt");
$fcc->free_result();
?>
<select name=catval size="<?php echo $ct+1?>"
 onChange="document.catform.action='prodcatseq.php';submit();">
<option value="" selected>[no change]</option>
<?
$get_cats = new FC_SQL;
$get_scats = new FC_SQL;
$get_cats->query("select catval,catpath from cat ".
	"where catzid=$zoneid and catlid=$langid order by catpath");
while ( $get_cats->next_record() ){
	$patharray = explode(":",$get_cats->f("catpath"));
	$catlst=$get_cats->f("catval");
	print "<option value=\"$catlst\">";
	while (list($key, $val)=each($patharray))
	{
		if ($val != ''){
			$get_scats->query("select catdescr from cat ".
				"where catzid=$zoneid and catlid=$langid and catval=$val");
			if( $get_scats->next_record() ){
				print '/'.$get_scats->f("catdescr");
				$get_scats->free_result();
			}
		}
	}
	print  "</option>\n";
}
$get_cats->free_result();	
?>
</select>
<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Modify Selected Category"><br>

</form>
</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

</td></tr>

</table>
</center>

<?php  require('./footer.php'); ?>
