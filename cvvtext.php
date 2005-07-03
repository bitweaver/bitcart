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

header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-control: No-Cache");

require_once( '../bit_setup_inc.php' );

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape

$lid = (int)getparam('lid');

// ==========  end of variable loading  ==========

require('./public.php');
require('./cartid.php');
require('./languages.php');

?>
<html>
 <head>
 <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
 <title><?php echo fc_text('cvvnumber');?></title>
 <link ID href="style.css" type="text/css" rel="StyleSheet">
 </head>
 <body bgcolor="#FFFFFF" link="#990000" alink="#990000" vlink="#990000" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0">
 <table width="80%" align="center" cellpadding="5" cellspacing="0">
  <tr>
   <td align="left">
    <?php echo fc_text('cvvtext');?><br /><br />
	</td>
  <tr>
   <td align="center">
	<a href="javascript:window.close();"><?php echo fc_text('cvvclosewindow');?></a> 
	</td>
  </tr>
 </table>

<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
