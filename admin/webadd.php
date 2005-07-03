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
require('./header.php');

$fcz = new FC_SQL;
?>

<h2 align=center>Add A Web Profile</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<b>
Add A Web Profile<br>
</b>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<form method="post" action="webupd.php">

<input type=hidden name=act value=new>

Selected Zone: <?php 
$fcz->query("select zonedescr from zone ".
	"where zoneid=$zoneid order by zoneid"); 
$fcz->next_record();
echo $fcz->f("zonedescr");
$fcz->free_result();
?><br>

Selected Language: <?php 
$fcz->query("select langdescr from lang ".
	"where langzid=$zoneid and langid=$langid order by langid"); 
$fcz->next_record();
echo $fcz->f("langdescr");
$fcz->free_result();
?><br>

</td></tr>
<tr><td colspan=2 bgcolor=#ffffff>

Web Profile Description: <i>max 80 characters</i><br>
<input name="webdescr" size=50 onFocus="currfield='descr'"><br>
<?php
/*
Home Page URL: <i>max 80 characters</i><br>
<input name="realhome" size=50 onFocus="currfield='homeurl'"><br>

Cart Home Page: <i>max 80 characters</i><br>
<input name="carthome" size=50 onFocus="currfield='carturl'"><br>

Web Page Title:<br>
<input name="webtitle" size=50 onFocus="currfield='webtitle'">
*/
?>
<p><i>
Graphic paths should be either relative to the installed cart (<b>./...</b>)<br>
or absolute with respect to the top of the Web site (<b>//fishcart/...</b>).</i>
<p>

Background Graphic URI: <i>max 40 characters</i><br>
<Input name="back" size=50 onFocus="currfield='back'"><br>

Masthead Logo Graphic URI: <i>max 40 characters</i><br>
<input name="logo" size=50 onFocus="currfield='logo'">
<p>

<a href="colors.html">Hexadecimal Color Chart</a>
<p>

<table border=0 cellpadding=3 class="text">
<tr><td align=left valign=top bgcolor=#ffffff>

Web Page Background Color:<br>
<input name="webbg" size=8 onFocus="currfield='color'"><br>

</td><td align=left valign=top bgcolor=#ffffff>

Web Page Text Color:<br>
<input name="webtext" size=8 onFocus="currfield='color'"><br>

</td></tr><tr><td align=left valign=top bgcolor=#ffffff>

Web Page Link Color:<br>
<input name="weblink" size=8 onFocus="currfield='color'"><br>

</td><td align=left valign=top bgcolor=#ffffff>

Web Page Visited Link Color:<br>
<input name="webvlink" size=8 onFocus="currfield='color'"><br>

</td></tr><tr><td align=left valign=top bgcolor=#ffffff>

Web Page Active Link Color:<br>
<input name="webalink" size=8 onFocus="currfield='color'"><br>

</td><td align=left valign=top bgcolor=#ffffff>

Product Display Sort Field:<br>
<select name="websort" size=3 onFocus="currfield='websort'">
<option value="prodsku" selected>Product SKU
<option value="prodname">Product Name
<option value="prodseq">Product Sequence
</select><br>

</td></tr>
</table>

<tr><td colspan=2 bgcolor=#ffffff>

Header Product SKU:<br>
<input name="headsku" size=40 onFocus="currfield='headsku'"><br>

Header Product Text:<br>
<textarea name="headtext" wrap=virtual rows=4 cols=32
 onFocus="currfield='headtext'">
</textarea><br>

Header Product Banner Graphic URI:<br>
<input name="headgraph" size=40 onFocus="currfield='headbanr'"><br>

</td></tr>
<tr><td colspan=2 bgcolor=#ffffff>

Footer Product SKU:<br>
<input name="footsku" size=40 onFocus="currfield='footsku'"><br>

Footer Product Text:<br>
<textarea name="foottext" wrap=virtual rows=4 cols=32
 onFocus="currfield='foottext'">
</textarea><br>

Footer Product Banner Graphic URI:<br>
<input name="footgraph" size=40 onFocus="currfield='footbanr'"><br>

</td></tr>
<tr><td align=left colspan=2 bgcolor=#ffffff>

Category Header Text:<br>
<textarea name="cattext" wrap=virtual rows=4 cols=32
 onFocus="currfield='cattext'">
</textarea><br>

</td></tr>
<tr><td align=left valign=center colspan=1 bgcolor=#ffffff>

Duration In Days of New Products in the New Table:<br>
<input name="daysinnew" size=5 onFocus="currfield='days'"><br>

</td><td colspan=1 bgcolor=#ffffff>

Products to Display per Page:<br>
<input name="prodpage" size=5 onFocus="currfield='prodpage'"
 value=5><br>

</td></tr>
<?php /*
<tr><td colspan=2 bgcolor=#ffffff>

New Items Icon Graphic URI:<br>
<input name="newlogo" size=40 onFocus="currfield='newlogo'"><br>

</td></tr>
*/ ?>
<tr><td colspan=2 bgcolor=#ffffff>

New Items Masthead URI:<br>
<input name="newmast" size=40 onFocus="currfield='newmast'"><br>

</td></tr>
<?php /*
<tr><td colspan=2 bgcolor=#ffffff>

Special Items Icon Graphic URI:<br>
<input name="speclogo" size=40 onFocus="currfield='speclogo'"><br>

</td></tr>
*/ ?>
<tr><td colspan=2 bgcolor=#ffffff>

Special Items Masthead URI:<br>
<input name="specmast" size=40 onFocus="currfield='specmast'"><br>

</td></tr>
<?php /*
<tr><td colspan=2 bgcolor=#ffffff>

View Cart Icon Graphic URI:<br>
<input name="viewlogo" size=40 onFocus="currfield='viewlogo'"><br>

</td></tr>
*/ ?>
<tr><td colspan=2 bgcolor=#ffffff>

Zero Price Alternate Text Display:<br>
<i>example: &lt;b&gt;&lt;i&gt;&lt;font color=&quot;#ff0000&quot;&gt;Free!&lt;/font&gt;&lt;/i&gt;&lt;/b&gt; is <b><font color="#ff0000">Free!</font></b><br>
<input name="webfree" size=40 onFocus="currfield='webfree'"><br>

</td></tr>
<tr><td colspan=2 bgcolor=#ffffff>

New Product Description Template:<br>
<textarea name="webdesctmpl" wrap=virtual rows=6 cols=40
 onFocus="currfield='webdesctmpl'">
</textarea><br>

</td></tr>
<tr><td align=left valign=top bgcolor=#ffffff>

<input type=checkbox name=webshowqty value=1 checked>
Show Qty on Order in display.php?<br>

<input type=checkbox name=webshowpreview value=1 checked>
Show order preview in index.php/display.php?<br />


<?php /*
<input type=radio name=webshowtopbot value=0>
Show order preview at top of nav bar<br>
<input type=radio name=webshowtopbot value=1 checked>
Sacthow order preview at bottom of nav bar<br>
 */ ?>

<input type=checkbox name=webusenlbr value=1>
Format output to browser like it has been entered in productadd/productmod pages?<br />

<p>
</td><td align=left valign=top bgcolor=#ffffff>

<input type=checkbox name=autodom value="1">
Automatically track viewer's domain:?

</td></tr>
<tr><td colspan="2"align="center" bgcolor="#ffffff">

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Add" onClick="closehelp()">
<input type="reset" value="Clear Form">

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
