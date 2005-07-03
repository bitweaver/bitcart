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

<h2 align="center">Dynamic Link Sequence Maintenance</h2>
<hr />
<p>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>Modify Link Sequences</b>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<form name="linkform" method="post" action="auxlinkupd.php">
<input type="hidden" name="act" value="seq" />

Enter the link sequence numbers in the<br />
order you want, then click &quot;Update&quot;<br />

</td></tr>
<?php 
$i=0;$j=10;
$fcc = new FC_SQL;
$fcc->query("select rid,title,seq from auxlinks order by seq"); 
while( $fcc->next_record() ){
 $rid=$fcc->f("rid");
 $title=ereg_replace("<[^>]+>"," ",stripslashes($fcc->f("title")));?>
<tr><td bgcolor="#FFFFFF">Title:<br /><?php echo substr($title,0,40)?><br /></td><td bgcolor="#FFFFFF">
<input name="lseq<?php echo $i?>" size="16" value="<?php echo $j?>" />
<input type="hidden" name="link<?php echo $i?>" value="<?php echo $rid?>" /><br /></td></tr>
<?php  $i++;  $j+=10;
}
?>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="hidden" name="scount" value="<?php echo $i?>" />
<input type="submit" value="Update Sequence Numbers" /><br />
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
