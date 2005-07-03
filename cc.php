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

function cc_check($cctype,$cc_number,$ccexp_year,$ccexp_month) {
	global $ccexp_years;
	if( !$cctype ){
		echo fc_text('invalidcctype');
		return(1);
	}
	// verify the cc; non numerics must already be removed
	$rv=cc_mod10($cctype,$cc_number);
	if($rv==0){
		echo fc_text('invalidccard');
		return(1);
	}
	$ccexp_year=(int)trim($ccexp_year);
	$lower=(int)date("Y");
	$upper=$lower + (int)$ccexp_years;
	if($ccexp_year < $lower || $ccexp_year > $upper){
		echo fc_text('invalidccyr');
		return(1);
	}
	$ccexp_month=(int)trim($ccexp_month);
	if($ccexp_month < 1 || $ccexp_month > 12){
		echo fc_text('invalidccmo');
		return(1);
	}
	return(0);
}

// Based on Melvyn Myers program. Rewritten in PHP by Wojciech Tryc
// Many thanks to Joe Ghaby joseph@kanatek.ca for help in debugging.
// Person to blame wojtek@tryc.on.ca. I am not responsible for any loss
// or damage which may be effect of using this program.

function cc_mod10($card,$card_no) {
 // non-numeric characters must already be removed

 $length = strlen($card_no);

 if( $card == "Mastercard" &&
    ((int)substr($card_no,0,2) < 51 ||
	 (int)substr($card_no,0,2) > 55 ||
	 $length != 16) ){
  return(0);
  exit;
 }
 if( $card == "Visa" &&
    (substr($card_no,0,1) != '4' ||
	 ($length != 13  &&
	  $length != 16)) ){
  return(0);
  exit;
 }
 if( $card == "Discover" &&
    (substr($card_no,0,4) != '6011' ||
	 $length != 16) ){
  return(0);
  exit;
 }
 if( $card == "American Express" &&
    ((substr($card_no,0,2) != '34' &&
	  substr($card_no,0,2) != '37') ||
	 $length != 15) ){
  return(0);
  exit;
 }

 if($card=="Visa" && $length==13){
  $cc0  = (int)substr($card_no,0,1);
  $cc1  = (int)substr($card_no,1,1);
  $cc2  = (int)substr($card_no,2,1);
  $cc3  = (int)substr($card_no,3,1);
  $cc4  = (int)substr($card_no,4,1);
  $cc5  = (int)substr($card_no,5,1);
  $cc6  = (int)substr($card_no,6,1);
  $cc7  = (int)substr($card_no,7,1);
  $cc8  = (int)substr($card_no,8,1);
  $cc9  = (int)substr($card_no,9,1);
  $cc10 = (int)substr($card_no,10,1);
  $cc11 = (int)substr($card_no,11,1);
  $cc12 = (int)substr($card_no,12,1);	

  $cc1a  = $cc1 * 2;
  $cc3a  = $cc3 * 2;
  $cc5a  = $cc5 * 2;
  $cc7a  = $cc7 * 2;
  $cc9a  = $cc9 * 2;
  $cc11a = $cc11 * 2;

  if($cc1a >= 10){
   $cc1b = (int)substr($cc1a,0,1);
   $cc1c = (int)substr($cc1a,1,1);
   $cc1  = $cc1b+$cc1c;
  }else{
   $cc1  = (int)$cc1a;
  }
  if($cc3a >= 10){
   $cc3b = (int)substr($cc3a,0,1);
   $cc3c = (int)substr($cc3a,1,1);
   $cc3  = $cc3b+$cc3c;
  }else{
   $cc3  = (int)$cc3a;
  }
  if($cc5a >= 10){
   $cc5b = (int)substr($cc5a,0,1);
   $cc5c = (int)substr($cc5a,1,1);
   $cc5  = $cc5b+$cc5c;
  }else{
   $cc5  = (int)$cc5a;
  }
  if($cc7a >= 10){
   $cc7b = (int)substr($cc7a,0,1);
   $cc7c = (int)substr($cc7a,1,1);
   $cc7  = $cc7b+$cc7c;
  }else{
   $cc7  = (int)$cc7a;
  }
  if($cc9a >= 10){
   $cc9b = (int)substr($cc9a,0,1);
   $cc9c = (int)substr($cc9a,1,1);
   $cc9  = $cc9b+$cc9c;
  }else{
   $cc9  = (int)$cc9a;
  }
  if($cc11a >= 10){
   $cc11b = (int)substr($cc11a,0,1);
   $cc11c = (int)substr($cc11a,1,1);
   $cc11  = $cc11b+$cc11c;
  }else{
   $cc11  = (int)$cc11a;
  }
  $val = $cc0+$cc1+$cc2+$cc3+$cc4+$cc5+$cc6+$cc7+$cc8+$cc9+$cc10+$cc11+$cc12;
  if(substr($val,1,1) != 0){
   return(0);
  }else{
   return(1);
  }
  exit;
 }elseif(($card=="Visa" && $length==16) ||
         ($card=="Mastercard" && $length==16) ||
         ($card=="Discover" && $length==16)){
  $cc0  = (int)substr($card_no,0,1);
  $cc1  = (int)substr($card_no,1,1);
  $cc2  = (int)substr($card_no,2,1);
  $cc3  = (int)substr($card_no,3,1);
  $cc4  = (int)substr($card_no,4,1);
  $cc5  = (int)substr($card_no,5,1);
  $cc6  = (int)substr($card_no,6,1);
  $cc7  = (int)substr($card_no,7,1);
  $cc8  = (int)substr($card_no,8,1);
  $cc9  = (int)substr($card_no,9,1);
  $cc10 = (int)substr($card_no,10,1);
  $cc11 = (int)substr($card_no,11,1);
  $cc12 = (int)substr($card_no,12,1);
  $cc13 = (int)substr($card_no,13,1);
  $cc14 = (int)substr($card_no,14,1);
  $cc15 = (int)substr($card_no,15,1);
				
  $cc0a  = $cc0 * 2;
  $cc2a  = $cc2 * 2;
  $cc4a  = $cc4 * 2;
  $cc6a  = $cc6 * 2;
  $cc8a  = $cc8 * 2;
  $cc10a = $cc10 * 2;
  $cc12a = $cc12 * 2;
  $cc14a = $cc14 * 2;

  if($cc0a >= 10){
   $cc0b = (int)substr($cc0a,0,1);
   $cc0c = (int)substr($cc0a,1,1);
   $cc0  = $cc0b+$cc0c;
  }else{
   $cc0  = (int)$cc0a;
  }
  if($cc2a >= 10){
   $cc2b = (int)substr($cc2a,0,1);
   $cc2c = (int)substr($cc2a,1,1);
   $cc2  = $cc2b+$cc2c;
  }else{
   $cc2  = (int)$cc2a;
  }
  if($cc4a >= 10){
   $cc4b = (int)substr($cc4a,0,1);
   $cc4c = (int)substr($cc4a,1,1);
   $cc4  = $cc4b+$cc4c;
  }else{
   $cc4  = (int)$cc4a;
  }
  if($cc6a >= 10){
   $cc6b = (int)substr($cc6a,0,1);
   $cc6c = (int)substr($cc6a,1,1);
   $cc6  = $cc6b+$cc6c;
  }else{
   $cc6  = (int)$cc6a;
  }
  if($cc8a >= 10){
   $cc8b = (int)substr($cc8a,0,1);
   $cc8c = (int)substr($cc8a,1,1);
   $cc8  = $cc8b+$cc8c;
  }else{
   $cc8  = (int)$cc8a;
  }
  if($cc10a >= 10){
   $cc10b = (int)substr($cc10a,0,1);
   $cc10c = (int)substr($cc10a,1,1);
   $cc10  = $cc10b+$cc10c;
  }else{
   $cc10  = (int)$cc10a;
  }
  if($cc12a >= 10){
   $cc12b = (int)substr($cc12a,0,1);
   $cc12c = (int)substr($cc12a,1,1);
   $cc12  = $cc12b+$cc12c;
  }else{
   $cc12  = (int)$cc12a;
  }
  if($cc14a >= 10){
   $cc14b = (int)substr($cc14a,0,1);
   $cc14c = (int)substr($cc14a,1,1);
   $cc14  = $cc14b+$cc14c;
  }else{
   $cc14  = (int)$cc14a;
  }
  $val=$cc0+$cc1+$cc2+$cc3+$cc4+$cc5+$cc6+$cc7+$cc8+$cc9+$cc10+$cc11+$cc12+$cc13+$cc14+$cc15;
  if(substr($val,1,1) != 0){
   return(0);
  }else{
   return(1);
  }
  exit;
 }elseif($card=="American Express" && $length==15){
  $cc0  = (int)substr($card_no,0,1);
  $cc1  = (int)substr($card_no,1,1);
  $cc2  = (int)substr($card_no,2,1);
  $cc3  = (int)substr($card_no,3,1);
  $cc4  = (int)substr($card_no,4,1);
  $cc5  = (int)substr($card_no,5,1);
  $cc6  = (int)substr($card_no,6,1);
  $cc7  = (int)substr($card_no,7,1);
  $cc8  = (int)substr($card_no,8,1);
  $cc9  = (int)substr($card_no,9,1);
  $cc10 = (int)substr($card_no,10,1);
  $cc11 = (int)substr($card_no,11,1);
  $cc12 = (int)substr($card_no,12,1);
  $cc13 = (int)substr($card_no,13,1);
  $cc14 = (int)substr($card_no,14,1);
   
  $cc1a  = $cc1  * 2;
  $cc3a  = $cc3  * 2;
  $cc5a  = $cc5  * 2;
  $cc7a  = $cc7  * 2;
  $cc9a  = $cc9  * 2;
  $cc11a = $cc11 * 2;
  $cc13a = $cc13 * 2;

  if($cc1a >= 10){
   $cc1b = (int)substr($cc1a,0,1);
   $cc1c = (int)substr($cc1a,1,1);
   $cc1  = $cc1b+$cc1c;
  }else{
   $cc1  = (int)$cc1a;
  }
  if($cc3a >= 10){
   $cc3b = (int)substr($cc3a,0,1);
   $cc3c = (int)substr($cc3a,1,1);
   $cc3  = $cc3b+$cc3c;
  }else{
   $cc3  = (int)$cc3a;
  }
  if($cc5a >= 10){
   $cc5b = (int)substr($cc5a,0,1);
   $cc5c = (int)substr($cc5a,1,1);
   $cc5  = $cc5b+$cc5c;
  }else{
   $cc5  = (int)$cc5a;
  }
  if($cc7a >= 10){
   $cc7b = (int)substr($cc7a,0,1);
   $cc7c = (int)substr($cc7a,1,1);
   $cc7  = $cc7b+$cc7c;
  }else{
   $cc7  = (int)$cc7a;
  }
  if($cc9a >= 10){
   $cc9b = (int)substr($cc9a,0,1);
   $cc9c = (int)substr($cc9a,1,1);
   $cc9  = $cc9b+$cc9c;
  }else{
   $cc9  = (int)$cc9a;
  }
  if($cc11a >= 10){
   $cc11b = (int)substr($cc11a,0,1);
   $cc11c = (int)substr($cc11a,1,1);
   $cc11  = $cc11b+$cc11c;
  }else{
   $cc11  = (int)$cc11a;
  }
  if($cc13a >= 10){
   $cc13b = (int)substr($cc13a,0,1);
   $cc13c = (int)substr($cc13a,1,1);
   $cc13  = $cc13b+$cc13c;
  }else{
   $cc13  = (int)$cc13a;
  }
  $val=$cc0+$cc1+$cc2+$cc3+$cc4+$cc5+$cc6+$cc7+$cc8+$cc9+$cc10+$cc11+$cc12+$cc13+$cc14;
   if(substr($val,1,1) != 0){
    return(0);
   }else{
    return(1);
   }
   exit;
  }
  return(0);
  exit;
 }
?>
