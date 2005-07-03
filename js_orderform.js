<!--
function mod10_verify(cc)
{
	var ccnum;
	ccnum =    cc.replace(/[^0-9]*/g, "");
	var digits = ccnum.length;
	var oe = digits & 1;
	var sum = 0;
	var digit;
	for(var i=0; i<digits; i++) {
		digit = parseInt(ccnum.charAt(i));
		if (!((i & 1) ^ oe)) {
  			digit *=2;
  			if (digit > 9) {
    			digit -= 9;
  			}
		}//end if
		sum += digit;
	}//end for
	if (sum % 10 == 0) { return true; }
	else { return false; }
}//end function mod10_verify

function aba_verify ( route ) {
	var no_digit = route.length;
	var k2 = 0;
	var dig = 0;
	var nsum = 0;
	// loop through the first 8 digits and sum their weighted values
	for (var i=0; i < 8; i++) {
    	dig = parseInt( route.charAt(i) );
    	switch (i) {
        	// for digits 0, 3 and 6 multiply by 3
        	case 0:
        	case 3:
        	case 6:
            	nsum += dig * 3;
            	break;
        	// for digits 1, 4 and 7 multiply by 7
        	case 1:
        	case 4:
        	case 7:
            	nsum += dig * 7;
            	break;
        	// for digits 2 and 5 multiply by 1
        	case 2:
        	case 5:
            	nsum += dig;
            	break;
    	}
	}
	// get the 10 modulus and subtract it from 10
	// i.e. if the sum is 58, the modulus is 8 and K2 = 2
	k2 = 10 - (nsum % 10);

	// k2 has the correct check digit, compare it to the 9th digit
	if ( parseInt(route.charAt(8)) != k2 ){ // routing code is invalid
    	return 0;
	}else{ // routing code is valid
    	return 1;
	}
}
function rs(n,u,w,h,x) {
 args="width="+w+",height="+h+",resizeable=no,scrollbars=yes,status=0";
 remote=window.open(u,n,args);
  if (remote != null) {
   if (remote.opener == null)
	remote.opener = self;
	} 
    if (x == 1) { return remote; }
	} 
//-->

