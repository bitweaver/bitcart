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

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

$zid        =   (int)getparam('zid');
$lid        =   (int)getparam('lid');
$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
$val        =   (int)getparam('val');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcc = new FC_SQL;
$fcj = new FC_SQL;
$fcp = new FC_SQL;
$fcs = new FC_SQL;

if(!$zoneid || !$langid){?>
  A zone or language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
<?php exit;
}
?>

<h2 align=center>Product/Category Maintenance</h2>
<hr>
<p>

<?php if(!$val){?>

Please return to the main maintenance page and 
select a category to maintain.<p>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
Return to Central Maintenance</a><br>

<?php exit;}

// category in $val; get the relevant fields from the db

$fcp->query(
	"select count(*) as cnt from prodcat ".
	"where pcatval=$val and pcatzid=$zoneid");
$fcp->next_record();
$pt=(int)$fcp->f("cnt");
$fcp->free_result();

$fcc->query(
    "select catdescr from cat where catval=$val and ".
	"catlid=$langid and catzid=$zoneid"); 
$fcc->next_record();
?>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td valign=top align=center bgcolor=#ffffff>

Category:<br><?php echo $fcc->f("catdescr")?>

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">
<b>Add Product to Category</b>
</td></tr>
<tr><td align=center bgcolor=#ffffff>

<form method="post" action="prodcatupd.php">

<input type=hidden name=val value="<?php echo $val?>">
<input type=hidden name=catval value="<?php echo $val?>">

Product SKU To Add To This Category:<br>

<?php
$fcp->query("select count(*) as ccnt from prodlang ".
            "where prodlzid=$zoneid and prodlid=$langid");
$fcp->next_record();
$pc=(int)$fcp->f("ccnt");
$fcp->free_result();
if( $pc < 100 ){
 $fcp->query("select prodlsku,prodname from prodlang ".
  "where prodlzid=$zoneid and prodlid=$langid order by prodlsku");
?>
<select name=newsku size="<?php echo min(20,$pc); ?>">
 onFocus="helpfocus('skumod');">
<?php
 while( $fcp->next_record() ){
  $tmp=ereg_replace("<[^>]+>"," ",stripslashes($fcp->f("prodname")));?>
<option value="<?php echo $fcp->f("prodlsku")?>"><?php echo
  $fcp->f("prodlsku")?>: <?php echo substr($tmp,0,30)?>
<?php } ?>
</select>
<?php 
  $fcp->free_result();
}else{ ?>
<input name="newsku" size=20 maxsize=20><p>
<?php } ?><br>

Category Sequence Number:<br>
<input name="newseq" size=20 maxsize=20><br>
<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Add Product To Category"><br>

</form>

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">
<b>Delete Product from Category</b>
</td></tr>
<tr><td align=center bgcolor=#ffffff>

<form method="post" action="prodcatupd.php">
<input type=hidden name=val value="<?php echo $val?>">

<i>SKU : Product Description</i><br>
<select name=delsku size="<?php echo min(20,$pt+1)?>">
<option value="" selected>[no change]
<?php 
 $tables="prodlang,prodcat";
 $fields="prodname,pcatsku";
 $pjoin="pcatzid=$zoneid and pcatval=$val and pcatsku=prodlsku ".
  "and prodlid=$langid and prodlzid=pcatzid";
 
 $fcj->query("select $fields from $tables where $pjoin order by pcatsku");
 while( $fcj->next_record() ){
  $prodsku=$fcj->f("pcatsku");
  $tmp=ereg_replace("<[^>]+>"," ",stripslashes($fcj->f("prodname")));
  $tmp=substr($tmp,0,30);?>
<option value="<?php echo $prodsku?>"><?php echo $prodsku?>: <?php echo $tmp?>
<?php }
?>
</select>
<p>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Delete Product From Category">
</form>

</td></tr>
<tr><td align=center bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
