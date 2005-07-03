<?php
//Portuguese Brazil
//ISO 639/2 code: por
//Translated by Andres E.L. Reyes at aelreyes@ciagri.usp.br

$salutearray[] = 'Sr.';
$salutearray[] = 'Sra.';
$salutearray[] = 'Senhorita';
$salutearray[] = 'Dr.';
$salutearray[] = 'Rev.';
$salutearray[] = 'Eng.';
$salutearray[] = 'Prof.';

$fc_prompt = array(
'welcome' =>
	'Bem-vindo ao FishCart, o primeiro sistema de multi-nacional de e-commerce. Voc� p�de fazer o 
Download do FishCart de <a href="http://fishcart.org">fishcart.org</a>.',
'outofservice' =>
	'O carrinho de compras est� momentaneamente fora de servi�o. Por favor tente novamente em alguns minutos, receba as nossas desculpas pelo inconveniente.',
'choosegeo' =>
	'<h2 align=center>Sele��o Geogr�fica </h2><i>Selecione a �rea da sua resid�ncia para  <br>calcular impostos estaduais e o custo de envio.</i>',
'invalidfield' =>
	'</center><p><b>Um campo obrigat�rio n�o foi preenchido.<br>Por favor clique no bot�o "Voltar" do seu navegador<br>e tenha certeza de que todos os campos foram preenchidos corretamente. Muito Obrigado.</b><br>',
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
	'</center><p><b>O seu endere�o de email aparece como n�o v�lido.<br>Por favor clique no bot�o "Voltar" do seu navegador<br> e verifique se est� correto. Muito obrigado.</b><br>',
'invalidoffon' =>
	'</center><p><b>Nenhum m�todo de ordem (Online ou Offline) foi selecionado.<br>Por favor clique no bot�o "Voltar" do seu navegador e escolha uma das op��es. Muito obrigado.</b><br>',
'invalidccfld' =>
	'</center><p><b>O campo de Cart�o de Cr�dito est� em branco. Clique no bot�o voltar do seu navegador e confira que todos os campos estejam  preenchidos. Muito Obrigado.</b>',
'invalidccard' =>
	'</center><br><b>O n�mero do cart�o de cr�dito 
n�o � v�lido.<br> Por favor clique no bot�o "Voltar" do seu navegador <br>e confira se est� inteiramente correto. Para deixar o n�mero mais leg�vel, <br> voc� p�de separar os n�mero do 
cart�o em grupos <br>com espa�os em branco como 
mostrado a seguir. Muito Obrigado.</b><br><pre>
1111 2222 3333 4444 (Visa / Mastercard) 1111 222222 333333 (American Express)</pre>',
'invalidcctype' =>
	'</center><p><b>Por favor clique no bot�o "Voltar" do seu navegador e selecione o tipo de cart�o de cr�dito. Muito obrigado.</b>',
'invalidccyr' =>
	'</center><p><b>Por favor clique no bot�o "Voltar" do seu navegador e<br> digite um ano de validade correto para o cart�o de cr�dito. Muito obrigado.</b>',
'invalidccmo' =>
	'</center><p><b>Por favor clique no bot�o "Voltar" do seu navegador e<br> digite um m�s de validade correto para o cart�o de cr�dito. Muito obrigado.</b>',
'invalidccclr'	=>	'',
'invalidorder' =>
	'<h2 align=center>Pedido Completado ou Invalido</h2> O pedido atual parece ser invalido ou foi completado com sucesso. Voc� vai receber esta mensagem se clicar no bot�o "Voltar" do seu navegador depois de completar o seu pedido. Se voc� optou por um pedido <b>online</b>, saber� que este foi completado com sucesso porque ser� enviada uma mensagem ao email que voc� indicou no pedido contendo o pedido detalhado e pedindo uma confirma��o. Email sem confirma��o � enviado em pedidos <b>offline</b>. <p> Para continuar navegando no site, por favor clique <a href="/">aqui para retornar � p�gina  principal.</a> Muito obrigado!',
'pwexp' =>
	'Esta conta para envio (correspond�ncia) j� expirou. Por favor entre em contato com nosso servi�o de 
atendimento ao cliente para maiores informa��es.',
'orderfinal' =>
	'o seu pedido foi enviado! Uma mensagem com o  pedido de confirma��o detalhado ser� enviada ao email indicado.<p>Por favor no clique no bot�o 
"Voltar" do seu navegador. Para continuar, por  favor clique no link abaixo e retorne � p�gina 
principal da empresa. Muito obrigado mais uma vez pelo seu pedido!',
'emptysearch' =>
	'Nenhuma categoria de produto e/ou termo de para busca foi especificado, ou a sua busca n�o encontrou nenhum resultado. por favor retorne � 
p�gina de sele��o de produtos e escolha uma categoria ou entre com um termo de busca. Muito 
obrigado.',
'back2select' =>
	'Clique aqui para retornar � p�gina de sele��o 
de produto.',
'click2select'	=>
	'<i>click2select: to be translated<i>',

'click2prodname'=>
	'<i>click2prodname: to be translated</i>',

'click2select2' =>
        '<i>Click here for more product detail</i>',

'back2cat'=>
	'&#171back2cat: to be translated',

'shipinfo' =>
	'Informa��es para envio<br><i>(se diferentes das 
informa��es da fatura)',
'onlinetext' =>
	'Pedidos <b>online</b> s�o efetuados utilizando 
o seu cart�o de cr�dito. Os pedidos s�o encriptados para garantir a seguran�a das informa��es financeiras.',
'offlinetext' =>
	'Voc� p�de imprimir o pedido e envi�-lo por telefone, fax ou correio. Voc� p�de efetuar o 
pagamento com cart�o de cr�dito de qualquer 
maneira ou enviar um cheque junto com o pedido.',
'noshipcalc' =>
	'O programa que c�lcula o custo de envio n�o foi 
encontrado. ',
'cartcontents' =>
	'Conte�do do Carrinho de Compras',
'cartempty' =>
	'A sua cesta de compras est� vazia!',
'cartmodify' =>
	'<i>Para modificar um item, entre com a nova quantidade e clique em "Modificar o  Pedido".</i><br><i>Para  excluir um item, entre com uma quantidade 0 e clique em  "Modificar o  Pedido".</i><br>',
'cartsubmit' =>
	'Modificar o Pedido',
'cartinvmax' =>
	'<i>*** a quantidade excede o estoque dispon�vel</i>',
'esdnotrans' =>
	'Nenhum produto foi escolhido para fazer o doewnload.<br>',
'esdnodl' =>
	'A informa��o para <b>download</b> n�o � v�lida ou a autentica��o fornecida n�o combina com este 
registro. <br>',
'esddlmax' =>
	'A conta de <b>download</b> deste arquivo foi excedida.<br>',
'esdnofile' =>
	'O arquivo para <b>download</b> n�o foi encontrado.
<br>',
'custsvc' =>
	'Por favor entre em contato com o Servi�o de Atendomento ao cliente da EMPRESA para uma melhor assist�ncia. <br>',
'donatetext' =>
	'Se deseja fazer alguma contribui��o na miss�o da EMPRESA, por favor entre com a quantia abaixo e ela ser� adicionada ao valor total do seu pedido. Muito obrigado.',
'optviolation' =>
	'<b><i>A op��o requerida para este produto n�o 
foi escolhida. por favor selecione novamente o 
produto; as op��es requeridas est�o marcadas com um <font color="#ff0000"><b>*</b></font>. Muito obrigado.</i></b>',
'optreqtext' =>
	'<font color="#ff0000"><b>*</b> <i>= op��o requerida</i></font>',
'emptyopt'		=> 'emptyopt: to be translated',
'reqtext' =>
	'<font color="#ff0000"><b>*</b> <i>= requerido</i></font>',
'reqflag' =>
	'<font color="#ff0000"><b>*</b></font>',
'choosezone' =>
	'Escolha um Cat�logo',
'chooselang' =>
	'Escolha o Idioma',
'choosecat' =>
	'Veja A Categoria',
'selectcat' =>
	'[selecione uma categoria]',
'choosekey' =>
	'Palavra-chave de busca',
'outstocktemp'	=> 'outstocktemp: to be translated',
'newitems' =>
	'Nova entrada!',
'dispmultiple'	=> 'dispmultiple: to be translated',
'dispsingle'	=> 'dispsingle: to be translated',
'dispto'	=> 'dispto: to be translated',
'dispof'	=> 'dispof: to be translated',
'closeout'	=> 'closeout: to be translated',
'vieworder' =>
	'Veja Seu Pedido Atual',
'contactinfo' =>
	'Informa��o de Contato',
'supportinfo' =>
	'Gosto do Cliente',
'titletag' =>
	'Compra <b>Online</b>',
'submitgeo' =>
	'Selecione a �rea da Fatura',
'prodinfo' =>
	'Produtos Comprados',
'orderinfo' =>
	'Informa��es do Pedido do Cliente',
'billinfo' =>
	'Informa��es da Fatura',
'creditinfo' =>
	'Informa��es do Cart�o de Cr�dito',
'proddesc' =>
	'Descri��o do Produto',
'couponid' =>
	'C�d. do Cupom:',
'coupondisc' =>
	'Desconto de Cupom:',
'unitprice' =>
	'Pre�o Unit�rio:',
'baseprice' =>
	'Pre�o Base:',
'option' =>
	'Op��o:',
'setup' =>
	'Configurar:',
'basesetup'		=> 'basesetup: to be translated',
'setuptotal'	=> 'setuptotal: to be translated',
'setupfee' =>
	'Configurar Custo:',
'setupfees' =>
	'Configurar Custos:',
'shipfee' =>
	'Custo de Entrega:',
'salestax' =>
	'Imposto de venda:',
'thankyou' =>
	'Muito obrigado!',
'voluntary' =>
	'Doa��o Volunt�ria Adicional:',
'subtotal' =>
	'Subtotal:',
'psubtotal' => 'psubtotal: to be translated',
'product'=>
	'product: to be translated',
'total' =>
	'Total:',
'longadd' =>
	'Adicionar ao Pedido',
'shortadd' =>
	'Adicionar',
'quantity' =>
	'Quantidade',
'qty' =>
	'Qde.',
'home' =>
	'Home',
'subcats' =>
	'Subcategorias:',
'previous' =>
	'Anterior',
'next' =>
	'Pr�ximo',
'searchresult' =>
	'Resultados da Busca:',
'sku' =>
	'Estoque #:',
'download' =>
	'<b>Download</b>',
'downloadrem' =>
	'arquivos por fazer <b>download</b>',
'downloadmax' =>
	'o limite m�ximo de <b>download</b> foi ultrapassado ',
'onsale' =>
	'Especial!',
'price' =>
	'Pre�o:',
'retailprice'	=> 'retailprice: to be translated',
'nocharge' =>
	'N/C',
'audiosample' =>
	'Amostra de Som',
'videosample' =>
	'Amostra de V�deo',
'homepage' =>
	'P�gina Principal',
'zonehome' =>
	'P�gina Principal do Cat�logo',
'returnpage' =>
	'Retornar � P�gina Anterior',
'returnprod' =>
	'Retornar � P�gina de Produtos',
'selectctry' => 'To be translated: Select Country',
'selectgeo' =>
	'Selecionar Area Geogr�fica',
'checkout' =>
	'Veja o seu Pedido',
'contribution' =>
	'Doa��o Online',
'contribamount' =>
	'Quantia de Doa��o:',
'emailaddr' =>
	'Endere�o Email',
'salutation' =>
	'T�tulo<br><i>opcional</i>',
'saluteopt' =>
	'[t�tulo opconal]',
'firstname' =>
	'Nome',
'miname' =>
	'M.I.',
'lastname' =>
	'Sobrenome',
'address' =>
	'Endere�o',
'city' =>
	'Cidade',
'state' =>
	'Estado',
'zip' =>
	'C�digo Postal ',
'country' =>
	'Pa�s',
'dayphone' =>
	'Telefone durante o dia # (c�digo de �rea, n�mero)',
'ccname' =>
	'Nome do Cliente no Cart�o de Cr�dito',
'ccnumber' =>
	'N�mero do Cart�o de Cr�dito',
'cctype' =>
	'Tipo de Cart�o de Cr�dito',
'ccexpire' =>
	'Expira��o do Cart�o de Cr�dito',
'cvvnumber' =>
	'CVV2',
'termscon'		=> 'termscon: to be translated',
'cvvclosewindow'	=> 'cvvclosewindow: to be translated',
'cvvtext'	=> '<font size="-1">cvvtext: to be translated</font> ',
'month' =>
	'M�s',
'year' =>
	'Ano',
'ordermethod' =>
	'M�todo de Pedido',
'ordersubmit' =>
	'Enviar Seu Pedido',
'contribsubmit' =>
	'Enviar Sua Doa��o',
'clearform' =>
	'Limpar o Formul�rio',
'online' =>
	'<b>Online</b>',
'offline' =>
	'<b>Offline</b>',
'ordersubj' =>
	'Confirma��o de Pedido ',
'viewcart'		=> 'View Cart: to be translated',
'newitems'		=> 'New Items: to be translated',
'shiploc'		=> 'Shipping Location: to be translated',
'contribsubj' =>
	'Confirma��o de Contribui��o',
'orderconf' =>
	'Esta � a c�pia da confirma��o do seu pedido de compra � EMPRESA.',
'contribconf' =>
	'Esta � a c�pia da confirma��o de sua contribui��o � EMPRESA.',
'paymentconf'	=> 
	'paymentconf: to be translated',
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
	'Por favor selecione <b>Online</b> ou <b>Offline</b>. Muito obrigado!',
'jscontrib' =>
	'Por favor entre com uma quantia de doa��o. Muito obrigado!  ',
'jscountry' =>
	'Por favor selecione um pa�s. Muito obrigado!',
'selectsubz' => 'selectsubz: to be translated',
'jsbcountry' =>
	'Por favor entre com o pa�s da fatura. Muito obrigado!',
'jsscountry' =>
	'Por favor entre com o pa�s de entrega. Muito obrigado!',
'jsccname' =>
	'Por favor entre com o nome no cart�o de cr�dito. Muito obrigado!',
'jsccnum' =>
	'Por favor entre com o n�mero do cart�o de cr�dito. Muito obrigado!',
'jscctype' =>
	'Por favor entre com o tipo de cart�o de cr�dito. Muito obrigado!',
'jsccexp' =>
	'Por favor entre com uma expira��o v�lida do cart�o de cr�dito. Muito obrigado!',
'jsbemail' =>
	'Por favor entre com o endere�o email da fatura. Muito obrigado!',
'jsbfname' =>
	'Por favor entre com o nome da fatura. Muito obrigado!',
'jsblname' =>
	'Por favor entre com o sobrenome da fatura. Muito obrigado!',
'jsbaddr' =>
	'Por favor entre com o endere�o da fatura. Muito obrigado!',
'jsbcity' =>
	'Por favor entre com a cidade da fatura. Muito obrigado!',
'jsbstate' =>
	'Por favor entre com o estado do endere�o da fatura. Muito obrigado!',
'jsbzip' =>
	'Por favor entre com o c�digo postal do endere�o da fatura. Muito obrigado!',
'jspickone' =>
	'por favor selecione uma categoria ou entre com uma palavra-chave para busca. Muito obrigado!',
'jsplaced' =>
	'Muito obrigado! O seu pedido foi enviado; por favor seja paciente, o processo p�de demorar alguns segundos. Mai uma vez muito obrigado pelo seu pedido!',
'itemcapfix' =>
	'ITEM',
'qtycapfix' =>
	'QDE.',
'pricecapfix' =>
	'PRE�O',
'productcapfix' =>
	'DESCRI��O',
'basepricefix' =>
	'Pre�o Base:',
'optionfix' =>
	'Op��o:',
'qtyfix' =>
	'Qde.:',
'totalfix' =>
	'Total:',
'setupfix' =>
	'Configura��o:',
'setuptotalfix' =>
	'Configura��o Total:',
'discountfix' =>
	'Desconto:',
'subtotalfix' =>
	'Subtotal:',
'psubtotalfix' => 'psubtotalfix: to be translated',
'shippingfix' =>
	'Envio:',
'salestaxfix' =>
	'Imposto de Venda:',
'contributefix' =>
	'Contribui��o:',
'ordertotalfix' =>
	'Pedido Total:',
'billinfofix' =>
	'Informa��o de Fatura:',
'emailaddrfix' =>
	'Endere�o Email:',
'shipaddrfix' =>
	'Endere�o para Entrega:',
'dlusernamefix' =>
	'Nome de Usu�rio para Download:',
'dlpasswordfix' =>
	'Senha para Download:',
'coupon' =>
	'Cupom:',
'orderid' =>
	'C�d. Pedido:',
'phone' =>
	'Telefone:',
'fax' =>
	'Fax:'
);
?>
