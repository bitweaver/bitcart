<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2003  FishNet, Inc.

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


This module is the interface to FishNet's credit card clearing gateway.

TRANSACTION TYPES (only 'S' or 'A' should be used for clearing)
 S: Immediate Sale
 A: Authorization Only
 P: Post Authorization
 C: Credit
 R: Partial Reversal
 V: Void
*/

 $trans_type  = 'set_this_to_S_or_A';
 $merchant_id = 'YOU_MUST_SET_THIS';

 // NOTHING BELOW THIS POINT SHOULD NEED TO BE CHANGED

 // set this non 0 to see all the transaction details
 $debug=0;
 
 $ccm=sprintf("%02d",$ccexp_month);
 $ccy=sprintf("%02d",substr($ccexp_year,2,2));
 $tax_total=$taxsubtotal + $ptaxsubtotal;

 $tax_total = $tps + $tpn;	// sum of shipping and non-shipping tax

 $tmp ="x_merchid=$merchant_id&".
       "x_ttype=$trans_type&".
	   "x_cc_number=$cc_number&".
	   "x_cc_mon=$ccm&".
	   "x_cc_yr=$ccy&".
	   "x_cc_cvv=$cc_cvv&".
	   "x_tax_amount=".sprintf("%.2f",$tax_total)."&".
	   "x_trans_amount=".sprintf("%.2f",$ttotal)."&".
	   "x_invoice=".urlencode($cartid)."&".
	   "x_merch_ref=".urlencode($cartid)."&".
	   "x_fname=".urlencode($billing_first)."&".
	   "x_lname=".urlencode($billing_last)."&".
	   "x_avs_addr=".urlencode($billing_address1)."&".
	   "x_avs_city=".urlencode($billing_city)."&".
	   "x_avs_state=".urlencode($billing_state)."&".
	   "x_avs_zip=".urlencode($billing_zip)."&".
	   "x_avs_country=".urlencode($billing_country);

 exec('curl -d '.$tmp.' https://accgate.fishnet.us/apps/acc_xml_gate',
      $resp, $ret);

 // split out the response into discrete fields
 list($status,
 	  $authresp,
	  $avsresp,
	  $cvv2resp,
	  $dupresp,
	  $uniqid,
	  $refnumber,
	  $errtext,
	  $reqact) = split("\,",$resp[0]);

 if($debug){
  echo "response: $resp<br>\n".
  	   "status: $status<br>\n".
  	   "authresp: $authresp<br>\n".
  	   "avsresp: $avsresp<br>\n".
  	   "cvv2resp: $cvv2resp<br>\n".
  	   "dupresp: $dupresp<br>\n".
  	   "uniqid: $uniqid<br>\n".
  	   "refnumber: $refnumber<br>\n".
  	   "errtext: $errtext<br>\n".
  	   "reqact: $reqact<br>\n";
 }

 if($status){
  global $gBitSystem;
  echo fc_text('invalidccclr');
  mail($gBitSystem->getErrorEmail(),"Online Payment Failure",$res[0]);
  exit;
 }
?>
