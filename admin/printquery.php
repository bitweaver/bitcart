<?
 /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2001  FishNet, Inc.

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

############################################################################
             Reporting Program for Fishcart by Web Synergy
                     Change Log
2001/04/07 - Initial Public Beta Release Version 1.0
             This software has been tested on a Unix server running
			 Apache and php 3.0.15
             Although, it should work in php 3.0.9+
             Created By Wayne T. Ethier, Web Synergy Internet Services.
			 Feel free to modify, please forward any changes to
			 wte@websynergyinternet.com

2001/05/18 - Modified index.php, printquery.php and showpricereport.php
			 so that all files are loaded to the cart at build time.
			 Modified by Glenn Antoine eSystems Design, Inc.
			 Email - rantoine@esysdesign.com

2002/02/09 - Integrated into the FishCart source.
             Michael Brennen
############################################################################
*/

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
Header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

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

require('./admin.php');
require('./header.php');

if(!$zoneid||!$langid){?>
  A zone or language ID was not selected.
        <p>Please click the <a href='self.history.back()'>Back</a> button on your browser
        and select a zone.  Thank you.
    <?php exit;
}

$fcp = new FC_SQL;
$fcp->query("select zonedescr from zone where zoneid='$zoneid' and zonedeflid='$langid'");
if($fcp->next_record()){
  $zone_name = $fcp->f("zonedescr")."<br>";
  $fcp->free_result();
}
?>

<table align=center cellspacing=0 cellpadding=4 bgcolor=#666666 width=650 class="text">
  <tr valign="middle" align="center">
    <td bgcolor=#ffffff>
      <h2>FishCart Reporting Module</h2>
    </td>
  </tr>
</table>
<hr>
  <table align=center class="text">
  <tr>
  <td>
  <a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp();">
   Return To Central Maintenance Page</a><br>
  </td>
  </tr>
  </table>
<form method="post" action='./showpricereport.php' name="sortMethod">
<p align=center>
<input type="radio" name="report[0]" value="price"
 onClick="javascript: sortMethod.action='./showpricereport.php';" checked>
Product Price Report
 </p>

<input type=hidden name=langid value=<?php echo $langid ?>>
<input type=hidden name=zoneid value=<?php echo $zoneid ?>>
  <table width="650" border="0" cellpadding=4 cellspacing=1 bgcolor=#666666 align="center" class="text">
    <tr><td bgcolor=#ffffff>

        <table width=100% border=0 align=center class="text">
          <tr>
            <td bgcolor=#ffffff>
              Sort Records By:
            </td>
          </tr>
          <tr>
            <td bgcolor=#ffffff>

              <p align="center">
                <select name="order" size="1">
                  <option value="prodlsku">Sku #</option>
                  <option value="prodname">Product Name</option>
                  <option value="prodstart">Product Begin Date</option>
                  <option value="prodstop">Product End Date</option>
                  <option value="prodsalebeg">Sale Begin Date</option>
                  <option value="prodsaleend">Sale End Date</option>
                  <option value="prodsaleprice">Sale Price</option>
                  <option value="prodprice">Product Price</option>
                  <option value="prodrtlprice">Cost</option>
                  <option value="invqty">Inventory Quantity</option>
                </select>
              </p>
            </td>
          </tr>
          <tr>
            <td bgcolor=#ffffff>
			<table bgcolor=#ffffff class="text">
			 <tr>
			    <td bgcolor=#ffffff>
               
                <input type="radio" name="descend" value="0" checked>
				</td><td bgcolor=#ffffff>
                Ascending (A-Z)
				</td>
			 </tr>
			 <tr>
			    <td bgcolor=#ffffff>
                <input type="radio" name="descend" value="1">
				</td><td bgcolor=#ffffff>
                Descending (Z-A)
				</td>
			 </tr>
			 <tr>
			    <td colspan=2 align=center bgcolor=#ffffff>
                <hr width="100%">
				</td>
			 </tr>
			 <tr>
			    <td colspan=2 align=center bgcolor=#ffffff>
                <b>Show Optional Fields:</b>
				</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkProdBegin value='1' checked></td>
			  <td bgcolor=#ffffff>Product Begin Date</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkProdEnd value='1' checked></td>
			  <td bgcolor=#ffffff>Product End Date</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkSaleBegin value='1' checked></td>
			  <td bgcolor=#ffffff>Sale Begin Date<br>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkSaleEnd value='1' checked></td>
			  <td bgcolor=#ffffff>Sale End Date</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkCost value='1' checked></td>
			  <td bgcolor=#ffffff>Cost</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkProdPrice value='1' checked></td>
			  <td bgcolor=#ffffff>Product Price</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkSalePrice value='1' checked></td>
			  <td bgcolor=#ffffff>Sale Price</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkSetupPrice value='1' checked></td>
			  <td bgcolor=#ffffff>Setup Price</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkMargin value='1' checked></td>
			  <td bgcolor=#ffffff>Profit Margin</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkInvQty value='1' checked></td>
			  <td bgcolor=#ffffff>Inventory Quantity</td>
			 </tr>
			 <tr>
			  <td bgcolor=#ffffff><input type=checkbox name=chkCogs value='1' checked></td>
			  <td bgcolor=#ffffff>COGS (Cost of Goods Sold)</td>                
			 </tr>
			</table>
            </td>
          </tr>
          <tr>
            <td bgcolor=#ffffff>
              <p>&nbsp;</p>
              <p align="center">
                <input type="submit" name="Submit" value="View Records" onClick="javascript: sortMethod.action='./showpricereport.php';">
                <input type="button" name="quit" value="Quit" onClick="javascript: sortMethod.action='./index.php'; sortMethod.submit();">
              </p>
            </td>
          </tr>
        </table>
</td></tr>
  </table>
  <table align=center class="text">
  <tr>
  <td>
  <a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp();">
   Return To Central Maintenance Page</a><br>
  </td>
  </tr>
  </table>
</form>

<?php include ('./footer.php'); ?>
