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

// This file calculates shipping on a weight threshold basis.

// pass the weight to do shipping on in $wtotal 
// the shipping amount is given back in $shamt

// $fct is the FC_SQL class instance from the including script
$fcth = new FC_SQL;
$fcth->query("select shipamt from weightthresh where ".
 "shipid=$curshipid and shiplow < $wtotal and shiphigh >= $wtotal");
if( !$fcth->next_record() ) {
 $shamt=0;
}else{
 $shamt=(double)$fcth->f("shipamt");
 $fcth->free_result();
}
?>
