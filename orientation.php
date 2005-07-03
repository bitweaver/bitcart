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
require('./public.php');
// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title>Online Order Orientation</title>
</head>
<body>

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<h2 align="center">Online Order Orientation</h2>
<hr>
<p>

Welcome to 's Online Order Center!  We are
pleased to be able to bring you this comprehensive system to help you
order our various resources.  You can use this Order Center to create an order
whether you will place it electronically or whether you will fax or mail it.
Once you have created an order, you can place it by the following methods as
best fits your situation.  
</p>

<p align="center">

<b>
Fax 
|
Postal Mail 
|
Telephone 
|
Online Secure Credit Card
</b>
</p>
<p>

This Order Center has been designed to work with all Web browsers.  It
is not necessary to update or change your Web browser to use the Order
Center.
</p>

<hr width="30%" />

<h3 align="center">Creating An Order</h3> 

Return to the catalog page and review the various product categories.
You can do this by either clicking the &quot;Back&quot; button on your
browser or by clicking the &quot;Return to Catalog Page&quot; button
at the bottom of this page.
<p>

Select a category of interest, then click &quot;Display&quot;.
As you find products you wish to order, enter the correct quantity and
press the <b><i>Add To Your Order</i></b> button.  That's all that you
have to do!  The items you have selected will be accumulated by our
online order management system.
</p>

<p>

When you have selected all the items that you wish to purchase, click
on the <b><i>View Cart</i></b> button at the bottom of the page, then
click on the <b><i>Check Out</i></b> button.  Then you will select
either <b><i>Order Online</i></b> to purchase with a credit card
online, or <b><i>Order Offline</i></b> to purchase by telephone, fax
or postal mail.
</p>


<hr width="30%" />
<h3 align="center">Resuming An Order In Progress</h3> 

Once you have started an order by adding the first item, to continue
to add to the same order you must stay within the catalog pages.  You
may notice that there are no links in the catalog pages that lead out
of this section.  This is to help insure that you don't inadvertently
lose an order in process.  If you leave the catalog pages and then
reenter the catalog, a new order will be started for you and everything you
have accumulated so far will be lost.

<p>

There is a way to leave the catalog section and then reenter it to
resume an order in process.  Follow the procedure below to do
this.
</p>

<p>
<ul>
<li>Go to the catalog index page.  This has a banner
&quot; Resource Catalog&quot; as the heading, with a list
of the various resources underneath it.</li>
</p>
<p>

<li>Make a bookmark to this page with your browser.  You must create a
new bookmark to this page; a previously created bookmark will not
work.  Note that some browsers call bookmarks by different names.
Some call them &quot;Favorites&quot;, and some call them
&quot;Hotlists&quot;.</li> 
</p>
<p>

<li>You may then leave the catalog pages to browse as you wish.</li>
</p>
<p>

<li>To reenter the order, select the bookmark you created just before
leaving the catalog section.  This will resume processing the order
you started previously.</li>
</p>
<p>
To verify this, click on the <b><i>View
Cart</i></b> button at the bottom of any page in the catalog section;
you will see the contents of the order in progress.
</p>
<p>

<li><b>NOTE: You must resume processing the order within 24 hours of
when it was first created or it will be lost.  Incomplete orders older
than 24 hours are removed each night.</li>
</b>
</p>
<p>

</ul>
<hr width="75%" />

<center>

<font size="-2">
&copy; 1997 <br />
</font></center>

<p>
<center>
<a href="<?php echo $cartdir?>/index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>">
Return to Catalog Page
</a>
</center>
</p>
<?php // END OF ESSENTIAL CART DISPLAY CODE ?>


<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
