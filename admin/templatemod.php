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
$act = getparam('act');
$tidx = (int)getparam('tidx');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fct = new FC_SQL;
?>

<body bgcolor="#ffffec">

<h2 align=center>Template Maintenance</h2>

<?php 
$fct->query("select * from templates where tidx=$tidx");
if( !$fct->next_record() ){?>
<p>This template was not found; please contact FishNet support for further
assistance at <a href="mailto:support@fni.com">support@fni.com</a>
<?php exit;}
?>

<form method="post" action="templateupd.php">

<b>Template Name:</b> <i>maxsize=128</i><br>
<input name=tname size=20 value="<?php echo stripslashes($fct->f("tname"))?>"><br>

<b>Template Description:</b> <i>maxsize=255</i><br>
<input name=tdesc size=65 value="<?php echo stripslashes($fct->f("tdesc"))?>"><br>

<b>Template Text:</b> <i>maxsize=65,000 bytes</i><br>
<i>text substitution formula:</i> &lt;?eval ( '?&gt;' . $txt . '&lt;?' );?&gt;<br>
<font size="-1">
<textarea name=ttxt rows=20 cols=80 wrap=virtual><?php echo stripslashes($fct->f("ttxt"))?></textarea><br>
</font>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=tidx value="<?php echo $fct->f("tidx")?>">
<input type=hidden name=act value="MT">
<input type=submit value="Update">
<input type=reset  value="Previous Values">

</form>
<p>

<a href="templateadd.php">Template Maintenance Page</a>

<?php  require('./footer.php'); ?>
