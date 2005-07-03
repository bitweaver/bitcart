<?php
// personal salutation array
$salutearray[] = 'Mr.';
$salutearray[] = 'Ms.';
$salutearray[] = 'Mrs.';
$salutearray[] = 'Dr.';
$salutearray[] = 'Rev.';

$fc_prompt = array(

'welcome'		=>
	'Welcome to FishCart, the premier open source multi-national '.
	'e-commerce system.  You can download FishCart from '.
	'<a href="http://fishcart.org">fishcart.org</a>.',

'outofservice'	=>
	'The shopping cart is temporarily out of service. Please try again '.
	'in a few minutes; we apologize for the inconvenience.',

'choosegeo'		=>
	'Geography Selection<br /><br />'.
	'<i>Select area of your residence for state<br>'.
	'sales tax and shipping charge calculation.</i>',

'invalidfield'	=>
	'</center><p><b>A required field has been left blank.<br>'.
	'Please click the &quot;Back&quot; button on your browser<br>'.
	'and make sure all are properly filled in.  Thank you.</b><br>',

'tcnotapproved' =>
        '<p><b>The Terms and Conditions were not approved; your order cannot be '.
        'completed without this approval.  Please click the &quot;Back&quot; button '.
        'on your browser to approve them, or click on the link below to abandon your '.
        'order and return to the front page.  Thank you</b></p>',

'contribblank'  =>
        '<p><b>The contribution amount was left blank.  Please click '.
        'the &quot;Back&quot; button on your browser and enter an amount. '.
        'Thank you!</b></p>',

'payamblank'  =>
        '<p><b>The payment amount was left blank.  Please click '.
        'the &quot;Back&quot; button on your browser and enter an amount. '.
        'Thank you!</b></p>',

'invalidemail'	=>
	'</center><p><b>It appears that your email address as entered is '.
	'invalid.<br>Please click the &quot;Back&quot; button on your '.
	'browser<br>and check to see if it is correct. Thank you.</b><br>',

'invalidoffon'	=>
	'</center><p><b>Neither the Online or Offline order method is '.
	'selected.<br>Please click the &quot;Back&quot; button on your '.
	'browser and choose one.  Thank you.</b><br>',

'invalidccfld'	=>
	'</center><p><b>A required credit card field is blank. '.
	'Please click the &quot;Back&quot; button on your browser '.
	'and make sure they are properly filled in.  Thank you.</b>',

'invalidccard'	=>
	'</center><br><b>'.
	'The credit card number as entered does not appear to be a valid<br>'.
	'number.  Please click the &quot;Back&quot; button on your browser<br>'.
	'and make sure that it is entered correctly.  To help make the number<br>'.
	'more readable, you may separate the credit card number into groups<br>'.
	'with blanks or spaces as shown by the examples below.  Thank you.</b><br>'.
	'<pre>'.
	'1111 2222 3333 4444 (Visa / Mastercard)'.
 	' '.
	'1111 222222 333333 (American Express)'.
	'</pre>',

'invalidcctype'	=>
	'</center><p><b>'.
	'Please click the &quot;Back&quot; button on your browser and '.
	'select the type of credit card.  Thank you.</b>',

'invalidccyr'	=>
	'</center><p><b>'.
	'Please click the &quot;Back&quot; button on your browser and<br>'.
	'enter a valid credit card expiration year.  Thank you.</b>',

'invalidccmo'	=>
	'</center><p><b>'.
	'Please click the &quot;Back&quot; button on your browser and<br>'.
	'enter a valid credit card expiration month.  Thank you.</b>',

'invalidccclr'	=>
	'<center><br>'.
	'This credit card did not pass the online verification phase.<br>'.
	'You may wish to try a different credit card.  You may return to the<br>'.
	'order form by clicking the Back button on your browser.  Thank you.'.
	'<p><a href="'.BIT_ROOT_URL.'"><i> Home Page</i></a></center>',

'invalidorder'	=>
	'<h2 align=center>Invalid or Completed Order</h2>'.
	'The current order appears to either be invalid or '.
	'has been successfully completed.  You will receive '.
	'this message if you clicked the &quot;Back&quot; button '.
	'on your browser after completing your order.  If you '.
	'placed an order online, you will know that it was '.
	'successfully completed because a detailed order confirmation '.
	'will be sent to the email address you gave in the order. '.
	'No confirming e-mail is sent on offline orders.<p>'.
	'To continue browsing the  Web site, please click here to '.
	'<a href="'.BIT_ROOT_URL.'">return to the home page.</a>  Thank you!',

'pwexp'			=>
	'This electronic delivery account has expired.  Please contact '.
	'our Customer Service department for further assistance.  Thank you.',

'orderfinal'	=>
	'Your order has been placed!  A detailed confirmation order will '.
	'be sent to the e-mail address you entered in the cart.<p>'.
	'Please do not click the &quot;Back&quot; button on your browser. '.
	'To continue, please click the link below to return to the '.
	' home page.  Thank you again for your order!',

'emptysearch'	=>
	'Either no product categories and/or search terms were specified, '.
	'or your search returned no results.  Please return to the product '.
	'selection page and choose a category or enter a search term. Thank you.',

'back2select'	=>
	'Click here to return to the product selection page.',

'click2select'	=>
	'<i>Click on the picture for more product detail</i>',

'click2prodname'=>
	'<i>Click on product name for more detail</i>',

'click2select2' =>
        '<i>Click here for more product detail</i>',

'back2cat'=>
	'&#171Back',

'shipinfo'		=>
	'Shipping Information<br><i>(if different than Billing Information)</i>',

'onlinetext'	=>
	'Online orders are placed using your credit card.  The order '.
	'is strongly encrypted to secure your financial information.',
'offlinetext'	=> 
	'You can print the order and send it by phone, fax or mail. '.
	'You can pay by credit card in any mode or send a check with '.
	'mailed orders.',
'noshipcalc'	=> 'The shipping calculation script was not found.',

'cartcontents'	=> 'Shopping Cart Contents',
'cartempty'		=> 'Your shopping basket is empty!',
'cartmodify'	=>
	'<i>To modify an item, enter the new quantity and click '.
	'&quot;Modify Your Order&quot;.</i><br>'.
	'<i>To delete an item, enter a 0 quantity and click '.
	'&quot;Modify Your Order&quot;.</i><br>',
'cartsubmit'	=> 'Modify Your Order',
'cartinvmax'	=> '<i>*** quantity exceeded available inventory</i>',

'esdnotrans'	=>
	'No product was given for download.<br>',
'esdnodl'		=>
	'Either the download information is invalid or the supplied '.
	'authentication did not match this record.<br>',
'esddlmax'		=>
	'The download count on this file has been exceeded.<br>',
'esdnofile'		=>
	'The file to be downloaded could not be found.<br>',
'custsvc'	=>
	'Please contact  Customer Service for further assistance.<br>',

'donatetext'	=>
	'If you would like to make a contribution to the mission of '.
	', please enter the amount below and it will be added '.
	'to the order total charged to your charge card. Thank you.',
 
'optviolation'	=>
	'<b><i>A required option for this product was not chosen. '.
	'Please re-enter your product selection; required options '.
	'are marked with a <font color="#ff0000"><b>*</b></font>. '.
	'Thank you.</i></b>',
'optreqtext'	=> '<font color="#ff0000"><b>*</b> <i>= required option</i></font>',
'emptyopt'		=> '[select an option]',
'reqtext'		=> '<font color="#ff0000"><b>*</b> <i>= required</i></font>',
'reqflag'		=> '<font color="#ff0000"><b>*</b></font>',
'choosezone'	=> 'Choose A Catalog',
'chooselang'	=> 'Choose A Language',
'choosecat'		=> 'View The Category',
'selectcat'		=> '[select a category]',
'choosekey'		=> 'Keyword Search',
'outstocktemp'	=> 'Temporarily Out of Stock',
'newitems'		=> 'New Entries!',
'dispmultiple'	=> 'Displaying products',
'dispsingle'	=> 'Displaying product',
'dispto'	=> 'to',
'dispof'	=> 'of',
'vieworder'		=> 'View Your Current Order',
'contactinfo'	=> 'Contact Information',
'supportinfo'	=> 'Customer Care',
'titletag'		=> 'Online Shopping',
'submitgeo'		=> 'Select Your Billing Area',
'prodinfo'		=> 'Product Purchases',
'orderinfo'		=> 'Customer Order Information',
'billinfo'		=> 'Billing Information',
'creditinfo'	=> 'Credit Card Information',
'proddesc'		=> 'Product Description',
'couponid'		=> 'Coupon ID:',
'coupondisc'	=> 'Coupon Discount:',
'unitprice'		=> 'Unit Price:',
'baseprice'		=> 'Base Price:',
'option'		=> 'Option:',
'setup'			=> 'Setup:',
'basesetup'		=> 'Base&nbsp;Setup:',
'setuptotal'	=> 'Setup:',
'setupfee'		=> 'Setup Fee:',
'setupfees'		=> 'Setup Fees:',
'shipfee'		=> 'Shipping Fee:',
'salestax'		=> 'Sales Tax:',
'psalestax'		=> 'Monthly Sales Tax:',
'thankyou'		=> 'Thank you!',
'voluntary'		=> 'Additional Voluntary Donation:',
'subtotal'		=> 'Subtotal:',
'psubtotal'		=> 'Monthly Total:',
'product'		=> 'Product',
'total'			=> 'Total:',
'longadd'		=> 'Add to Your Order',
'shortadd'		=> 'Add',
'quantity'		=> 'Quantity',
'qty'			=> 'Qty',
'home'			=> 'Home',
'subcats'		=> 'Subcategories:',
'previous'		=> 'Previous',
'next'			=> 'Next',
'searchresult'	=> 'Search Results:',
'sku'			=> 'Product ID:',
'dlheader'		=> 'Downloads for Order ',
'download'		=> 'Download ',
'downloadrem'	=> ' downloads remaining',
'downloadmax'	=> ' maximum download limit reached',
'onsale'		=> 'Special!',
'price'			=> 'Price:',
'retailprice'	=> 'Suggested Retail:',
'periodic'		=> 'per month',
'nocharge'		=> 'N/C',
'audiosample'	=> 'Audio Sample',
'videosample'	=> 'Video Sample',
'homepage'		=> 'Home Page',
'zonehome'		=> 'Catalog Front Page',
'returnpage'	=> 'Previous Page',
'returnprod'	=> 'Previous Product',
'selectctry'	=> 'Select Country',
'selectgeo'		=> 'Select Geographical Area',
'checkout'		=> 'Check Out',
'contribution'	=> 'Online Donation',
'contribamount'	=> 'Donation Amount:',
'payment'		=> 'Online Payment',
'paymentamount'	=> 'Payment Amount:',
'paymentinv'	=> 'Special Comments, Invoices Being Paid:',
'emailaddr'		=> 'E-Mail Address',
'salutation'	=> 'Title<br><i>optional</i>',
'saluteopt'		=> '[optional title]',
'firstname'		=> 'First Name',
'miname'		=> 'M.I.',
'lastname'		=> 'Last Name',
'company'		=> 'Company',
'address'		=> 'Address',
'city'			=> 'City',
'state'			=> 'State',
'zip'			=> 'ZIP',
'country'		=> 'Country',
'dayphone'		=> 'Daytime Phone # (area code, number)',
'ccname'		=> 'Customer Name On Credit Card',
'ccnumber'		=> 'Credit Card Number',
'cctype'		=> 'Credit Card Type',
'ccexpire'		=> 'Credit Card Expiration',
'cvvnumber'		=> 'CVV2',
'termscon'		=> 'Terms and Conditions',
'cvvclosewindow'	=> 'Close This Window',
'cvvtext'		=> '<font size="-1">The CVV2 code is a 3 digit number printed '.
	'on the back of a Visa, Mastercard or Discover credit card '.
	'(usually in the signature field).  On an American Express card, it is '.
	'a 4 digit number on the front.  The CVV2 is not part of the credit card '.
	'number.  Because the CVV2 appears only on the card and not on receipts '.
	'or statements, the CVV2 provides some assurance that the physical card '.
	'is in the possession of the buyer.</font>',
'month'			=> 'Month',
'year'			=> 'Year',
'ordermethod'	=> 'Order Method',
'ordersubmit'	=> 'Submit Your Order',
'contribsubmit'	=> 'Submit Your Donation',
'paymentsubmit'	=> 'Submit Your Payment',
'dlsubmit'		=> 'Send Your Download Username and Password',
'dlusername'	=> 'Username:',
'dlpassword'	=> 'Password:',
'clearform'		=> 'Clear The Form',
'online'		=> 'Online',
'offline'		=> 'Offline',
'ordersubj'		=> 'Order Confirmation',
'viewcart'		=> 'View Cart',
'newitems'		=> 'New Items',
'closeout'		=> 'Closeout Items',
'shiploc'		=> 'Shipping Location',
'contribsubj'	=> 'Contribution Confirmation',
'paymentsubj'	=> 'Payment Confirmation',
'orderconf'	=> 
	"This is the confirmation copy of your order placed to\n.",
'contribconf'	=> 
	"This is the confirmation copy of your contribution to\n.",
'paymentconf'	=> 
	"This is the confirmation copy of your payment to\n.",
'promoemail'	=> 
	'Yes, please send me product announcements and promotions.',
'retain_addr'	=> 
	'Remember the above address information on this computer.',
'approvetc'	=> 
	'Yes, I have read the Terms and Conditions, and I accept them.',
'orderorigin'	=> 
	"\nThis order was processed by FishCart(r), FishNet(r)'s Open Source\n".
	"e-commerce software.  For information regarding support, upgrade and\n".
	"feature development services, please visit http://www.fishcart.org/\n".
	"or http://www.fishnetinc.com/.\n",

'jsapprovetc'	=>
	'Please indicate that you accept the Terms and Conditions.  Thank you!',
'jsonoff'		=> 'Please select Online or Offline.  Thank you!',
'jscontrib'     => 'Please enter a donation amount.  Thank you!',
'jscontriblim'  => 'Online contribution limit is exceeded.  Thank you!',
'jspayment'     => 'Please enter a payment amount.  Thank you!',
'jspaymentlim'  => 'Online payment limit is exceeded.  Thank you!',
'jscountry'		=> 'Please select a country.  Thank you!',
'jsbcountry'	=> 'Please select a billing country.  Thank you!',
'selectsubz'	=> 'Please select a billing area.  Thank you!',
'jsscountry'	=> 'Please select a shipping country.  Thank you!',
'jsccname'		=> 'Please enter the name on the credit card.  Thank you!',
'jsccnum'		=> 'Please enter a credit card number.  Thank you!',
'jscctype'		=> 'Please enter a credit card type.  Thank you!',
'jsccexp'		=> 'Please enter a valid credit card expiration.  Thank you!',
'jsbemail'		=> 'Please enter a billing e-mail address.  Thank you!',
'jsbfname'		=> 'Please enter a billing first name.  Thank you!',
'jsblname'		=> 'Please enter a billing last name.  Thank you!',
'jsbaddr'		=> 'Please enter a billing address.  Thank you!',
'jsbcity'		=> 'Please enter a billing city.  Thank you!',
'jsbstate'		=> 'Please enter a billing state.  Thank you!',
'jsbzip'		=> 'Please enter a billing postal code.  Thank you!',
'jspickone'		=>
	'Please select either a category or enter a search keyword.  Thank you!',
'jsplaced'      => 
	'Thank you!  Your order has been placed; please be patient, as this '.
	'process may take a few seconds.  Thank you again for your order!',

// for proddisp{echo,fixed}.php fixed alignment pages
'itemcapfix'	=> 'ITEM         ',
'qtycapfix'		=> 'QTY      ',
'pricecapfix'	=> 'PRICE     ',
'productcapfix'	=> 'DESCRIPTION',
'basepricefix'	=> '  Base Price: ',
'optionfix'		=> '  Option: ',
'qtyfix'		=> '    Qty: ',
'totalfix'		=> '    Total: ',
'setupfix'		=> '    Setup: ',
'setuptotalfix'	=> '  Setup Total:     ',
'discountfix'	=> '     Discount:     ',
'subtotalfix'	=> '     Products:     ',
'psubtotalfix'	=> '  Monthly Svc:     ',
'shippingfix'	=> '     Shipping:     ',
'salestaxfix'	=> '    Sales Tax:     ',
'psalestaxfix'	=> '  Monthly Tax:     ',
'contributefix'	=> ' Contribution:     ',
'paymentfix'	=> '      Payment:     ',
'ordertotalfix'	=> '  Order Total:     ',
'ptotalfix'		=> 'Monthly Total:     ',
'billinfofix'	=> 'Billing Information:  ',
'emailaddrfix'	=> 'E-Mail Address:       ',
'shipaddrfix'	=> 'Shipping Address:     ',
'dlusernamefix'	=> 'Download Username:    ',
'dlpasswordfix'	=> 'Download Password:    ',
'coupon'		=> 'Coupon: ',
'orderid'		=> 'Order ID: ',
'phone'			=> 'Phone: ',
'fax'			=> 'FAX: '

);
?>
