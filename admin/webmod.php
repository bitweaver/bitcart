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
$webid  = (int)getparam('webid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcw = new FC_SQL;
$fcz = new FC_SQL;
?>

<h2 align=center>Modify A Web Profile</h2>
<hr>
<p>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<b>
Modify A Web Profile<br>
</b>

</td></tr>
<tr><td colspan=1 bgcolor="#FFFFFF">

<?php 
$fcw->query("select * from web where webid=$webid"); 
$fcw->next_record();
?>

<form method="post" action="webupd.php">

<input type=hidden name=act value=update>

<input type=hidden name=webid value="<?php echo $webid?>">

Zone:<br>
<?php 
$vz=$fcw->f("webzid");
$fcz->query(
	"select zonedescr from zone where zoneid=$vz"); 
$fcz->next_record();
echo $fcz->f("zonedescr");
$fcz->free_result()
?>

</td><td colspan=1 bgcolor="#FFFFFF">

Language:<br>
<?php 
$vl=$fcw->f("weblid");
$fcz->query(
	"select langdescr from lang where langid=$vl"); 
$fcz->next_record();
echo $fcz->f("langdescr");
$fcz->free_result()
?>

</td></tr>
<tr><td colspan=2 bgcolor="#FFFFFF">

Web Profile Description: <i>max 80 characters</i><br>
<input name="webdescr" size=50 onFocus="currfield='descr'"
 value="<?php echo $fcw->f("webdescr")?>"><br>
<?php
/*
Home Page URL: <i>max 80 characters</i><br>
<input name="realhome" size=50 onFocus="currfield='homeurl'"
 value="<?php echo $fcw->f("realhome")?>"><br>

Cart Home Page: <i>max 80 characters</i><br>
<input name="carthome" size=50 onFocus="currfield='carturl'"
 value="<?php echo $fcw->f("carthome")?>"><br>

Web Page Title:<br>
<input name="webtitle" size=50 onFocus="currfield='webtitle'"
 value="<?php echo $fcw->f("webtitle")?>">
*/
?>
<p><i>
Graphic paths should be either relative to the installed cart (<b>./...</b>)<br>
or absolute with respect to the top of the Web site (<b>/fishcart/...</b>).</i>
<p>

Background Graphic URI: <i>max 40 characters</i><br>
<input name="back" size=50 onFocus="currfield='back'"
 value="<?php echo $fcw->f("webback")?>"><br>

Masthead Logo Graphic URI: <i>max 40 characters</i><br>
<input name="logo" size=50 onFocus="currfield='logo'"
 value="<?php echo $fcw->f("weblogo")?>">
<p>

<a href="colors.html">Hexadecimal Color Chart</a>
<p>

<table border=0 cellpadding=3 bgcolor="#FFFFFF" class="text">
<tr><td align=left valign=top bgcolor="#FFFFFF">

Web Page Background Color:<br>
<input name="webbg" size=8 onFocus="currfield='color'"
 value="<?php echo $fcw->f("webbg")?>"><br>

</td><td align=left valign=top bgcolor="#FFFFFF">

Web Page Text Color:<br>
<input name="webtext" size=8 onFocus="currfield='color'"
 value="<?php echo $fcw->f("webtext")?>"><br>

</td></tr><tr><td align=left valign=top bgcolor="#FFFFFF">

Web Page Link Color:<br>
<input name="weblink" size=8 onFocus="currfield='color'"
 value="<?php echo $fcw->f("weblink")?>"><br>

</td><td align=left valign=top bgcolor="#FFFFFF">

Web Page Visited Link Color:<br>
<input name="webvlink" size=8 onFocus="currfield='color'"
 value="<?php echo $fcw->f("webvlink")?>"><br>

</td></tr><tr><td align=left valign=top bgcolor="#FFFFFF">

Web Page Active Link Color:<br>
<input name="webalink" size=8 onFocus="currfield='color'"
 value="<?php echo $fcw->f("webalink")?>"><br>

</td><td align=left valign=top bgcolor="#FFFFFF">

<?php $tmp=$fcw->f("websort");?>
Product Display Sort Field:<br>
<select name="websort" size=3 onFocus="currfield='websort'">
<option value="prodsku"<?php if($tmp=="prodsku"){echo " selected";}?>>Product SKU
<option value="prodname"<?php if($tmp=="prodname"){echo " selected";}?>>Product Name
<option value="prodseq"<?php if($tmp=="prodseq"){echo " selected";}?>>Product Sequence
</select><br>

</td></tr>
</table>

<tr><td colspan=2 bgcolor="#FFFFFF">

Header Product SKU:<br>
<input name="headsku" size=40 onFocus="currfield='headsku'"
 value="<?php echo $fcw->f("webhdsku")?>"><br>

Header Product Text:<br>
<textarea name="headtext" wrap=virtual rows=4 cols=32
 onFocus="currfield='headtext'"><?php echo $fcw->f("webhdtext")?></textarea><br>

Header Product Banner Graphic URI:<br>
<input name="headgraph" size=40 onFocus="currfield='headbanr'"
 value="<?php echo $fcw->f("webhdgraph")?>"><br>

</td></tr>
<tr><td colspan=2 bgcolor="#FFFFFF">

Footer Product SKU:<br>
<input name="footsku" size=40 onFocus="currfield='footsku'"
 value="<?php echo $fcw->f("webftsku")?>"><br>

Foot Product Text:<br>
<textarea name="foottext" wrap=virtual rows=4 cols=32
 onFocus="currfield='foottext'"><?php echo $fcw->f("webfttext")?></textarea><br>

Footer Product Banner Graphic URI:<br>
<input name="footgraph" size=40 onFocus="currfield='footbanr'"
 value="<?php echo $fcw->f("webftgraph")?>"><br>

</td></tr>
<tr><td align=left colspan=2 bgcolor="#FFFFFF">

Category Header Text:<br>
<textarea name="cattext" wrap=virtual rows=4 cols=32
 onFocus="currfield='cattext'"><?php echo $fcw->f("webcattext")?></textarea><br>

</td></tr>
<tr><td align=left valign=center colspan=1 bgcolor="#FFFFFF">

Duration In Days of New Products in the New Table:<br>
<input name="daysinnew" size=10 onFocus="currfield='days'"
 value="<?php echo $fcw->f("webdaysinnew")?>"><br>

</td><td colspan=1 bgcolor="#FFFFFF">

Products to Display per Page:<br>
<input name="prodpage" size=5 onFocus="currfield='prodpage'"
 value="<?php echo $fcw->f("webprodpage")?>"><br>

</td></tr>
<?php /*
<tr><td colspan=2 bgcolor="#FFFFFF">

New Items Icon Graphic URI:<br>
<input name="newlogo" size=40 onFocus="currfield='newlogo'"
 value="<?php echo $fcw->f("webnewlogo")?>"><br>

</td></tr>
*/ ?>
<tr><td colspan=2 bgcolor="#FFFFFF">

New Items Masthead Graphic URI:<br>
<input name="newmast" size=40 onFocus="currfield='newmast'"
 value="<?php echo $fcw->f("webnewmast")?>"><br>

</td></tr>
<?php /*
<tr><td colspan=2 bgcolor="#FFFFFF">

Special Items Icon Graphic URI:<br>
<input name="speclogo" size=40 onFocus="currfield='speclogo'"
 value="<?php echo $fcw->f("webspeclogo")?>"><br>

</td></tr>
*/ ?>
<tr><td colspan=2 bgcolor="#FFFFFF">

Special Items Masthead Graphic URI:<br>
<input name="specmast" size=40 onFocus="currfield='specmast'"
 value="<?php echo $fcw->f("webspecmast")?>"><br>

</td></tr>
<?php /*
<tr><td colspan=2 bgcolor="#FFFFFF">

View Cart Graphic URI:<br>
<input name="viewlogo" size=40 onFocus="currfield='viewlogo'"
 value="<?php echo $fcw->f("webviewlogo")?>"><br>

</td></tr>
*/ ?>
<tr><td colspan=2 bgcolor="#FFFFFF">

<?php 
$tmp=ereg_replace("\"","&quot;",$fcw->f("webfree"));
?>
Zero Price Alternate Text Display: <i>example<br>
&lt;b&gt;&lt;font color=&quot;#ff0000&quot;&gt;Free!&lt;/font&gt;&lt;/b&gt; is <b><font color="#ff0000">Free!</font></b><br></i>
<input name="webfree" size=40 onFocus="currfield='webfree'"
 value="<?php echo $tmp?>"><br>

</td></tr>
<tr><td colspan=2 bgcolor="#FFFFFF">

New Product Description Template:<br>
<textarea name="webdesctmpl" wrap=virtual rows=6 cols=40
 onFocus="currfield='webdesctmpl'"><?php echo $fcw->f("webdesctmpl")?></textarea><br>

</td></tr>
<tr><td align=left valign=top bgcolor="#FFFFFF">


</td><td align=left valign=top bgcolor="#FFFFFF">

<?php $autodom=$fcw->f("webautodom");?>
<input type=checkbox name="autodom" 
 value="1" <?php if($autodom=="1"){echo"checked";}?>>
Automatically track viewer's domain?<br>

</td></tr>
<tr><td align=left valign=top bgcolor="#FFFFFF">

<input type=checkbox name=webshowqty value=1 <?php if($fcw->f("webflags1") & $flag_webshowqty){?> checked<?php }?>>
Show Qty on Order in display.php?<br>

<input type=checkbox name=webshowpreview value=1 <?php if($fcw->f("webflags1") & $flag_webshowpreview){?> checked<?php }?>>
Show order preview in index.php/display.php?<br>

<?php /*
<input type=radio name=webshowtopbot value=0<?php if( !($fcw->f("webflags1") & $flag_webshowtopbot) ){?> checked<?php } ?>>
Show order preview at top of nav bar<br>
<input type=radio name=webshowtopbot value=1<?php if( $fcw->f("webflags1") & $flag_webshowtopbot ){?> checked<?php } ?>>
Show order preview at bottom of nav bar<br>
 */ ?>

<input type=checkbox name=webusenlbr value=1 <?php if($fcw->f("webflags1") & $flag_webusenlbr){?> checked<?php }?>>
Format output to browser like it has been entered in productadd/productmod pages?<br>


</td><td align=left valign=top bgcolor="#FFFFFF">

<br>

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Modify" onClick="closehelp()">
<input type="reset" value="Clear Form">

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
