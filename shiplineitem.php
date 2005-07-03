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

This method calculates shipping by applying the line items
charges on a per line item basis.  The per item shipping 
costs have already been chosen from ship.

In preparation, showcart.php and orderform.php accumulate two
counters, $first_items and $second_items.  first_items is the
number of items that have at least a quantity of one, and
second_items is an accumulation of the total quantity of items
of two or more.  The algorithm:
   if($qty==1){
    $first_items++;
   }
   elseif($qty>1){
    $first_items++;
    $second_items += ($qty-1);
   }
Thus with two items of quantity 1 and 3 each, these variables are
as follows:
   $first_items  = 2
   $second_items = 2

pass the amount to do shipping on in $shipsubtotal 
the shipping amount is given back in $shamt

$fct is the FC_SQL class instance from the including script
*/

// first and second item costs apply to each line item
if( $first_items < 1 ){
  $shamt=0;
}else{
  $shamt = rnd(
  ((double)$fct->f("shipitem")  * (double)$first_items) +
  ((double)$fct->f("shipitem2") * (double)$second_items) );
}
?>
