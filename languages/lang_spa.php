<?php
//Spanish; Castilian
//ISO 639/2 code: spa
//Translated by Marcos A. Alba at realboutdragon@hotmail.com

$salutearray[] = 'Sr.';
$salutearray[] = 'Srita.';
$salutearray[] = 'Sra.';
$salutearray[] = 'Doc.';
$salutearray[] = 'Rev.';
$salutearray[] = 'Lic.';
$salutearray[] = 'Ing.';

$fc_prompt = array(
'welcome' =>
	'Bienvenido a FishCart, el sistema open source de e-commerce internacional por excelencia. Pudes descargarlo de <a href="http://fishcart.org">fishcart.org</a>.',
'outofservice' =>
	'El carrito de compras se encuentra temporalmente fuera de servicio. Favor de volver a intentarlo más tarde, disculpe las molestias.',
'choosegeo' =>
	'<h2 align=center>Selección Geográfica</h2><i>Seleccione su area de residencia<br>Cálculo de impuestos y gastos de envío.</i>',
'invalidfield' =>
	'</center><p><b>Un campo requerido ha sido dejado en blanco<br>Por favor presione "atrás" en su explorador<br>y asegúrese de que los campos están completos. Gracias.</b><br>',
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

'invalidemail' =>
	'</center><p><b>Parece que su dirección de email es inválida.<br>Por favor presione "atrás" en su explorador<br>y verifique si es la correcta. Gracias.</b><br>',
'invalidoffon' =>
	'</center><p><b>No ha seleccionado un modo de ordenar.<br>Por favor presione "atrás" en su explorador y elija una. Gracias.</b><br>',
'invalidccfld' =>
	'</center><p><b>Un campo requerido de tarjeta de crédito no ha sido llenado. Por favor presione "atrás" en su explorador y verifique que los campos estén completos. Gracias.</b>',
'invalidccard' =>
	'</center><br><b>Parece que tu número de tarjeta de crédito no es válido.<br>Por favor presione "atrás" en su explorador<br>y asegúrese que es el correcto.Para que el número sea de<br>fácil lectura, puede separarlo en grupos<br>con espacios al igual que en los ejemplos siguientes. Gracias.</b><br><pre>1111 2222 3333 4444 (Visa / Mastercard) 1111 222222 333333 (American Express)</pre>',
'invalidcctype' =>
	'</center><p><b>Por favor presione "atrás" en su explorador y seleccione un tipo de tarjeta de crédito. Gracias.</b>',
'invalidccyr' =>
	'</center><p><b>Por favor presione "atrás en su explorador e<br>introduzca el año de expiración correcto. Gracias.</b>',
'invalidccmo' =>
	'</center><p><b>Por favor presione "atrás en su explorador e<br>introduzca el mes de expiración correcto. Gracias.</b>',
'invalidccclr'	=>	'',
'invalidorder' =>
	'<h2 align=center>Orden Inválida o Completa</h2>La orden en curso parece ser inválida o haberse completado exitosamente. Este mensaje aparecerá si ha presionado "atrás" despues de completar su orden. Si ha ordenado en formato Online, sabrá que su orden ha sido completada exitosamente porque le enviaremos un correo de confirmación a su email. No hay email de confirmación para órdenes offline.<p>Para continuar navegando en el sitio de la COMPAÑIA, haga click aqui para <a href="/">regresar al inicio.</a> Gracias!',
'pwexp' =>
	'Esta cuenta de devolución electrónica ha expirado. Por favor contacte nuestro Servicio al Cliente para mayor información. Gracias.',
'orderfinal' =>
	'Su orden ha sido enviada! Un email de confirmación le será enviado a la dirección que dió en el carrito.<p>Por favor no presione "atrás" en su explorador. Para continuar, por favor presione el enlace siguiente para regresar al sitio de la COMPAÑIA. Gracias de Nuevo por su orden!',
'emptysearch' =>
	'No ha seleccionado una categoría/búsqueda correcta, o su búsqueda no ha tenido resultados. Por favor regrese a el área de selección de productos y elija una categoría, o busque de nuevo. Gracias.',
'back2select' =>
	'Haga click aquí para regresar al área de selección de productos.',
'click2select'	=>
	'<i>click2select: to be translated</i>',

'click2prodname'=>
	'<i>click2prodname: to be translated</i>',

'click2select2' =>
	'<i>Click here for more product detail</i>',

'back2cat'=>
	'&#171back2cat: to be translated',

'shipinfo' =>
	'Información de Envio<br><i>(si es diferente a la Información de Cobro)</i>',
'onlinetext' =>
	'Órdenes en modo Online son a través de su tarjeta de crédito. La orden es encriptada para asegurar su información financiera.',
'offlinetext' =>
	'Puede imprimir la orden y enviarla por teléfono, fax o email. Puede pagar con tarjeta de crédito en cualquier modo o enviar un cheque en ordenes por correo.',
'noshipcalc' =>
	'El script calculador de envío no fue encontrado.',
'cartcontents' =>
	'Contenido del Carrito.',
'cartempty' =>
	'Tu Carrito de compras está vacio!',
'cartmodify' =>
	'<i>Para modificar un objeto, elija la nueva cantidad y seleccione "Modifique su Orden".</i><br><i>Para borrar un objeto elija 0 como cantidad y seleccione "Modifique su Orden".</i><br>',
'cartsubmit' =>
	'Modifique su Orden',
'cartinvmax' =>
	'<i>*** Cantidad excede inventorio disponible</i>',
'esdnotrans' =>
	'No eligió producto para descarga.<br>',
'esdnodl' =>
	'La información de descarga es inválida, o la autenticación enviada no coincide.<br>',
'esddlmax' =>
	'Se ha excedido el límite de descargas de este archivo.<br>',
'esdnofile' =>
	'El archivo a descargar no ha sido encontrado.<br>',
'custsvc' =>
	'Por favor contacte el Servicio al Cliente de COMPAÑIA para mayor información <br>',
'donatetext' =>
	'Si desea hacer una contribución a la misión de COMPAÑIA, por favor introduzca la cantidad, esta será añadida al total de su pago con tarjeta de crédito. Gracias.',
'optviolation' =>
	'<b><i>Una opción que requiere este producto no ha sido elegida. Por favor de volver a elegirlo; Las opciones requeridas están marcadas con un <font color="#ff0000"><b>*</b></font>. Gracias.</i></b>',
'optreqtext' =>
	'<font color="#ff0000"><b>*</b> <i>= Opción Requerida</i></font>',
'emptyopt'		=> 'emptyopt: to be translated',
'reqtext' =>
	'<font color="#ff0000"><b>*</b> <i>= Requerido</i></font>',
'reqflag' =>
	'<font color="#ff0000"><b>*</b></font>',
'choosezone' =>
	'Elija un Catálogo',
'chooselang' =>
	'Elija un Lenguaje',
'choosecat' =>
	'Ver la Categoría',
'selectcat' =>
	'[seleccione una categoría]',
'choosekey' =>
	'Búsqueda',
'outstocktemp'	=> 'outstocktemp: to be translated',
'newitems' =>
	'Nuevas Adiciones!',
'dispmultiple'	=> 'dispmultiple: to be translated',
'dispsingle'	=> 'dispsingle: to be translated',
'dispto'	=> 'dispto: to be translated',
'dispof'	=> 'dispof: to be translated',
'closeout'	=> 'closeout: to be translated',
'vieworder' =>
	'Ver Su Orden Actual',
'contactinfo' =>
	'Información de Contacto',
'supportinfo' =>
	'Cuidado al Cliente',
'titletag' =>
	'Compras Online',
'submitgeo' =>
	'Seleccione su area de Cobro',
'prodinfo' =>
	'Compra de Productos',
'orderinfo' =>
	'Información de la Orden del Cliente',
'billinfo' =>
	'Información de Cobro',
'creditinfo' =>
	'Información de Tarjeta de Crédito',
'proddesc' =>
	'Descripción del Producto',
'couponid' =>
	'Cupón No:',
'coupondisc' =>
	'Descuento del Cupón:',
'unitprice' =>
	'Precio Unitario:',
'baseprice' =>
	'Precio Base:',
'option' =>
	'Opción:',
'setup' =>
	'Instalación:',
'basesetup'		=> 'basesetup: to be translated',
'setuptotal'	=> 'setuptotal: to be translated',
'setupfee' =>
	'Costo de Instalación:',
'setupfees' =>
	'Costos de Instalación:',
'shipfee' =>
	'Costo del Envío:',
'salestax' =>
	'Impuestos de Venta:',
'thankyou' =>
	'Gracias!',
'voluntary' =>
	'Donación Voluntaria Adicional:',
'subtotal' =>
	'Subtotal:',
'psubtotal' => 'psubtotal: to be translated',
'product'=>
	'product: to be translated',
'total' =>
	'Total:',
'longadd' =>
	'Añadir a tu Orden',
'shortadd' =>
	'Añadir',
'quantity' =>
	'Cantidad',
'qty' =>
	'Can',
'home' =>
	'Inicio',
'subcats' =>
	'Subcategorías',
'previous' =>
	'Anterior',
'next' =>
	'Siguiente',
'searchresult' =>
	'Resultados de Búsqueda:',
'sku' =>
	'Disponibles #:',
'download' =>
	'Descargar',
'downloadrem' =>
	'descargas restantes',
'downloadmax' =>
	'límite máximo de descargas alcanzado',
'onsale' =>
	'Especial!',
'price' =>
	'Precio:',
'retailprice'	=> 'retailprice: to be translated',
'nocharge' =>
	'N/A',
'audiosample' =>
	'Muestra de Audio:',
'videosample' =>
	'Muestra de Video:',
'homepage' =>
	'Página de Inicio',
'zonehome' =>
	'Inicio del Catálogo',
'returnpage' =>
	'Volver a la Página Anterior',
'returnprod' =>
	'Volver a la Página de Productos',
'selectctry' => 'To be translated: Select Country',
'selectgeo' =>
	'Seleccione Área Geográfica',
'checkout' =>
	'Comprar',
'contribution' =>
	'Donación Online',
'contribamount' =>
	'Cantidad a Donar:',
'emailaddr' =>
	'Dirección E-Mail',
'salutation' =>
	'Título<br><i>opcional</i>',
'saluteopt' =>
	'[título opcional]',
'firstname' =>
	'Nombre',
'miname' =>
	'I.',
'lastname' =>
	'Apellido',
'address' =>
	'Dirección',
'city' =>
	'Ciudad',
'state' =>
	'Estado',
'zip' =>
	'C.P.',
'country' =>
	'País',
'dayphone' =>
	'Teléfono de Día # (código de área, número)',
'ccname' =>
	'Nombre del Propetiario de la Tarjeta de Crédito',
'ccnumber' =>
	'Número de Tarjeta',
'cctype' =>
	'Tipo de Tarjeta',
'ccexpire' =>
	'Fecha de Expiración',
'cvvnumber' =>
	'CVV2',
'termscon'		=> 'termscon: to be translated',
'cvvclosewindow'	=> 'cvvclosewindow: to be translated',
'cvvtext'	=> '<font size="-1">cvvtext: to be translated</font> ',
'month' =>
	'Mes',
'year' =>
	'Año',
'ordermethod' =>
	'Modo de Orden',
'ordersubmit' =>
	'Enviar Orden',
'contribsubmit' =>
	'Enviar Donación',
'clearform' =>
	'Limpiar Campos',
'online' =>
	'Online',
'offline' =>
	'Offline',
'ordersubj' =>
	'Confirmación de la Orden',
'viewcart'		=> 'View Cart: to be translated',
'newitems'		=> 'New Items: to be translated',
'shiploc'		=> 'Shipping Location: to be translated',
'contribsubj' =>
	'Confirmación de la Contribución',
'orderconf' =>
	'Esta es la confirmación de su orden enviada a COMPAÑIA.',
'paymentconf'	=> 
	'paymentconf: to be translated',
'contribconf' =>
	'Esta es la confirmación de su contribución a COMPAÑIA.',
'promoemail'	=> 
	'promoemail: to be translated',
'retain_addr'	=> 
	'retain_addr: to be translated',
'approvetc'	=> 
	'approvetc: to be translated',
'orderorigin'	=> 
	"\nThis order was processed by FishCart(r), FishNet(r)'s Open Source\n".
	"e-commerce software.  For information regarding support, upgrade and\n".
	"feature development services, please visit http://www.fishcart.org/\n".
	"or http://www.fishnetinc.com/.\n",
'jsapprovetc'	=>
	'jsapprovetc: to be translated',
'jsonoff' =>
	'Por favor seleccione Online u Offline. Gracias!',
'jscontrib' =>
	'Por favor introduzca una cantidad a donar. Gracias!',
'jscountry' =>
	'Por favor seleccione un País. Gracias!',
'selectsubz' => 'selectsubz: to be translated',
'jsbcountry' =>
	'Por favor seleccione un País para cobro. Gracias!',
'jsscountry' =>
	'Por favor seleccione un País para envío. Gracias!',
'jsccname' =>
	'Por favor introduzca el nombre del tarjetahabiente. Gracias!',
'jsccnum' =>
	'Por favor introduzca el número de la Tarjeta de Crédito. Gracias!',
'jscctype' =>
	'Por favor elija un tipo de tarjeta de crédito. Gracias!',
'jsccexp' =>
	'Por favor introduzca la fecha de expiración correcta. Gracias!',
'jsbemail' =>
	'Por favor introduzca su dirección de e-mail. Gracias!',
'jsbfname' =>
	'Por favor introduzca el Nombre para cobro. Gracias!',
'jsblname' =>
	'Por favor introduzca el Apellido para cobro. Gracias!',
'jsbaddr' =>
	'Por favor introduzca la dirección para cobro. Gracias!',
'jsbcity' =>
	'Por favor introduzca una ciudad para cobro. Gracias!',
'jsbstate' =>
	'Por favor introduzca un estado para cobro. Gracias!
',
'jsbzip' =>
	'Por favor introduzca un Código Postal para cobro. Gracias!
',
'jspickone' =>
	'Por favor elija una categoría o introduzca las palabras a buscar. Gracias!',
'jsplaced' =>
	'Gracias! Su Orden ha sido enviada; por favor sea paciente, este proceso puede tomar unos cuantos segundos. Gracias de nuevo por su Orden!',
'itemcapfix' =>
	'OBJETO',
'qtycapfix' =>
	'CAN',
'pricecapfix' =>
	'PRECIO',
'productcapfix' =>
	'DESCRIPCIÓN',
'basepricefix' =>
	'Precio Base:',
'optionfix' =>
	'Opción:',
'qtyfix' =>
	'Can:',
'totalfix' =>
	'Total:',
'setupfix' =>
	'Instalación:',
'setuptotalfix' =>
	'Total de Instalación:',
'discountfix' =>
	'Descuento:',
'subtotalfix' =>
	'Subtotal:',
'psubtotalfix' => 'psubtotalfix: to be translated',
'shippingfix' =>
	'Envío:',
'salestaxfix' =>
	'Impuestos de Venta:',
'contributefix' =>
	'Contribución:',
'ordertotalfix' =>
	'Total de la Orden:',
'billinfofix' =>
	'Información del Cobro:',
'emailaddrfix' =>
	'Dirección E-Mail:',
'shipaddrfix' =>
	'Dirección de Envío:',
'dlusernamefix' =>
	'Nombre de Usuario para Descarga:',
'dlpasswordfix' =>
	'Contraseña para Descarga:',
'coupon' =>
	'Cupón:',
'orderid' =>
	'Orden No:',
'phone' =>
	'Teléfono:',
'fax' =>
	'FAX:'
);
?>
