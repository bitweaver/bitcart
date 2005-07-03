<?php
//Italian
//ISO 639/2 code: ita
//Translated by Antonio Capaldo at antonio@ilmac.net

$salutearray[] = 'Sig.';
$salutearray[] = 'Sig.a ';
$salutearray[] = 'Sig.na';
$salutearray[] = 'Dott.';
$salutearray[] = 'Rev.';

$fc_prompt = array(
'welcome' =>
	'Benvenuti in Fishcart, uno dei principali sistemi open-source di commercio elettronico con supporto multinazionale. Puoi scaricare il Fishcart da:<a href="http://fishcart.org">fishcart.org</a>.',
'outofservice' =>
	'Il carrello elettronico e\' temporaneamente non disponibile. Per favore riprova tra pochi minuti. Ti chiediamo scusa per l\'inconveniente.',
'choosegeo' =>
	'<h2 align=center>Scegli la tua posizione geografica</h2><i>Seleziona la tua area di residenza<br>per calcolare l\'IVA e le spese di spedizione.</i>',
'invalidfield' =>
	'</center><p><b>Uno dei campi obbligatori non e\' stato compilato.<br>Per favore premi il pulsante "Back" o "Indietro" sul tuo browser<br>ed assicurati di aver compilato tutti i campi. Grazie.</b><br>',
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
	'</center><p><b>L\'indirizzo email che hai inserito sembra non valido.<br>Per favore premi il pulsante "Back" o "Indietro" sul tuo browser<br>ed assicurati di averlo digitato correttamente. Grazie.</b><br>',
'invalidoffon' =>
	'</center><p><b>Non hai selezionato ne\' la modalita\' offline, ne\' quella online.<br>Per favore premi il pulsante "Back" o "Indietro" sul tuo browser<br>ed assicurati di sceglierne una. Grazie.</b><br>',
'invalidccfld' =>
	'</center><p><b>Uno dei campi obbligatori per la carta di credito non è stato compilato. Per favore premi il pulsante "Back" o "Indietro" sul tuo browser<br>ed assicurati di averli compilati tutti. Grazie.</b><br>',
'invalidccard' =>
	'</center><br><b>Il numero di carta di credito che hai inserito non sembra essere valido.<br> Per favore premi il pulsante "Back" o "Indietro" sul tuo browser<br>ed assicurati di averlo scritto correttamente. Per render il numero piu\' leggibile, potresti separare il numero della carta in gruppi <br> con degli spazi come mostrato negli esempi in basso. Grazie.</b><br> <pre>1111 2222 3333 4444 (Visa / Mastercard) 1111 222222 333333 (American Express)</pre>',
'invalidcctype' =>
	'</center><p><b>Per favore premi il pulsante "Back" o "Indietro" sul tuo browser e scegli il tipo di carta di credito.</b>',
'invalidccyr' =>
	'</center><p><b>Per favore premi il pulsante "Back" o "Indietro" sul tuo browser ed inserisci un anno di scadenza della carta di credito valido. Grazie.</b>',
'invalidccmo' =>
	'</center><p><b>Per favore premi il pulsante "Back" o "Indietro" sul tuo browser ed inserisci un mese di scadenza della carta di credito valido. Grazie.</b>',
'invalidccclr'	=>	'',
'invalidorder' =>
	'<h2 align=center>Ordine non valido o gia\' completato con successo</h2>L\'ordine corrente sembra essere invalido o e\' gia\' stato completato con successo. Si riceve questo messaggio se, dopo aver completato un ordine, si preme il tasto "Back" o "Indietro" nel browser.<br> 
Se hai inviato il tuo ordine utilizzando la modalita\' online, la ricezione dell\' email di conferma con il dettaglio della merce ti garantira\' che sia stato ricevuto correttamente. L\'email non viene inviata per gli ordini completati con la modalità off-line.<p>Per continuare a visitare il sito web  , clicca qui<a href="/">per tornare alla home page.</a> Grazie!',
'pwexp' =>
	'L\'account per la consegna elettronica è scaduto. Per favore contatta il nostro Servizio Clienti per ricevere asssitenza e maggiori informazioni. Grazie.',
'orderfinal' =>
	'Il tuo ordine è stato inviato! Una conferma d\'ordine dettagliata sara\' inviata all\'indirizzo email che hai indicato nel carrello.<p>Per favore non premere il tasto "Back" o "Indietro" sul tuo browser. Per continuare la visita al nostro sito, clicca sul link in baso per tornare alla pagina home di . Grazie ancora per il tuo ordine!',
'emptysearch' =>
	'La ricerca non ha prodotto risultati oppure non sono stati indicati ne\' la categoria nè un termine da ricercare.<br>
Per favore ritorno alla pagina di selezione dei prodotti e scegli una categoria o inserisci un termine da ricercare. Grazie.',
'back2select' =>
	'Clicca qui per tornare alla pagina di selezione dei prodotti.',
'click2select'	=>
	'<i>click2select: to be translated</i>',

'click2prodname'=>
	'<i>click2prodname: to be translated</i>',

'click2select2' =>
        '<i>Click here for more product detail</i>',

'back2cat'=>
	'&#171back2cat: to be translated',

'shipinfo' =>
	'Informazioni per la Spedizione<br><i>(se differenti dalle informazioni per il pagamento)</i>',
'onlinetext' =>
	'Gli ordini online sono inviati inserendo i dati della tua carta di credito. L\'ordine viene criptato per mettere al sicuro i vostri dati.',
'offlinetext' =>
	'E\' possibile stampare l\'ordine ed inviarlo via telefono, fax o per e-mail. Puoi pagare con carta di credito in tutte le modalità oppure inviare un assegno insieme con l\'ordine via posta.',
'noshipcalc' =>
	'Lo script per il calcolo dei costi di spedizione non e\' stato trovato',
'cartcontents' =>
	'Contenuto del Carrello',
'cartempty' =>
	'Il tuo Carrello e\' vuoto!',
'cartmodify' =>
	'<i>Per modificare un prodotto, scegliete la nuova quantita` poi cliccate "Modifica".</i><br><i>Per togliere un prodotto, scegliete la quantita` zero poi cliccate "Modifica".</i><br>',
'cartsubmit' =>
	'Modifica',
'cartinvmax' =>
	'<i>*** la quantita\' inserita eccede quella disponibile</i>',
'esdnotrans' =>
	'Nessun prodotto disponibile per il download.<br>',
'esdnodl' =>
	'Le informazioni per il download non sono corrette oppure l\'autenticazione fornita non è valida per questo prodotto.<br>',
'esddlmax' =>
	'Il numero di download per questo file è stato superato.<br>',
'esdnofile' =>
	'Il file da scaricare non è stato trovato <br>',
'custsvc' =>
	'Per favore contatta il nostro servizio clienti per maggiore assistenza.<br>',
'donatetext' =>
	'Se desideri inviare un contributo per contribuire alla missione di , per favore inserisci l\'importo della tua donazione in basso e questo sara\' aggiunto al totale del tuo ordine. Grazie.',
'optviolation' =>
	'<b><i>Un opzione necessaria per questo prodotto non e\' stata scelta. Per favore reinserisci la tua selezione; le opzioni necessarie sono indicato con un <font color="#ff0000"><b>*</b></font>. Grazie.</i></b>',
'optreqtext' =>
	'<font color="#ff0000"><b>*</b> <i>= opzione necessaria</i></font>',
'emptyopt'		=> 'emptyopt: to be translated',
'reqtext' =>
	'<font color="#ff0000"><b>*</b> <i>= necessario</i></font>',
'reqflag' =>
	'<font color="#ff0000"><b>*</b></font>',
'choosezone' =>
	'Seleziona un catalogo',
'chooselang' =>
	'Seleziona una lingua',
'choosecat' =>
	'Selezionare una categoria',
'selectcat' =>
	'[Scegli una categoria]',
'choosekey' =>
	'Ricerca',
'outstocktemp'	=> 'outstocktemp: to be translated',
'newitems' =>
	'Le novita\'',
'dispmultiple'	=> 'dispmultiple: to be translated',
'dispsingle'	=> 'dispsingle: to be translated',
'dispto'	=> 'dispto: to be translated',
'dispof'	=> 'dispof: to be translated',
'closeout'	=> 'closeout: to be translated',
'vieworder' =>
	'Controlla il carrello',
'contactinfo' =>
	'Informazioni Generali',
'supportinfo' =>
	'Assistenza Clienti',
'titletag' =>
	'Acquisto online',
'submitgeo' =>
	'Seleziona la',
'prodinfo' =>
	'Scelta prodotti',
'orderinfo' =>
	'Informazioni sull\'ordine',
'billinfo' =>
	'Informazioni per il pagamento',
'creditinfo' =>
	'Carta di Credito',
'proddesc' =>
	'Descrizione Prodotti',
'couponid' =>
	'Codice Coupon',
'coupondisc' =>
	'Sconto',
'unitprice' =>
	'Prezzo unitario',
'baseprice' =>
	'Prezzo',
'option' =>
	'Opzione',
'setup' =>
	'Setup',
'basesetup'		=> 'basesetup: to be translated',
'setuptotal'	=> 'setuptotal: to be translated',
'setupfee' =>
	'Costo di setup',
'setupfees' =>
	'Costi di Setup',
'shipfee' =>
	'Spedizione',
'salestax' =>
	'IVA',
'thankyou' =>
	'Grazie!',
'voluntary' =>
	'Aggiungi una donazione volontaria',
'subtotal' =>
	'Subtotale',
'psubtotal' => 'Canone Mensile:',
'product'=>
	'product: to be translated',
'total' =>
	'Totale',
'longadd' =>
	'',
'shortadd' =>
	'',
'quantity' =>
	'Quantita\'',
'qty' =>
	'Q.ta\'',
'home' =>
	'Home',
'subcats' =>
	'Sottocategorie',
'previous' =>
	'Precedente',
'next' =>
	'Successiva',
'searchresult' =>
	'Risultati della Ricerca',
'sku' =>
	'Stock:',
'download' =>
	'Download',
'downloadrem' =>
	'download rimanenti',
'downloadmax' =>
	'limite massimo di download raggiunto',
'onsale' =>
	'Offerta Speciale!',
'price' =>
	'Prezzo',
'retailprice'	=> 'retailprice: to be translated',
'nocharge' =>
	'',
'audiosample' =>
	'Spezzone audio',
'videosample' =>
	'',
'homepage' =>
	'Home Page',
'zonehome' =>
	'Prima pagina del catalogo',
'returnpage' =>
	'Ritorna alla pagina precedente',
'returnprod' =>
	'Ritorna alla pagina dei prodotti',
'selectctry' => 'Seleziona Nazione',
'selectgeo' =>
	'Seleziona l\'area geografica',
'checkout' =>
	'Inoltra l\'ordine',
'contribution' =>
	'Donazione online',
'contribamount' =>
	'Valore della donazione',
'emailaddr' =>
	'Indirizzo email:',
'salutation' =>
	'<br><i>opzionale</i>',
'saluteopt' =>
	'',
'firstname' =>
	'Nome',
'miname' =>
	'',
'lastname' =>
	'Cognome',
'address' =>
	'Indirizzo',
'city' =>
	'Citta\'',
'state' =>
	'Provincia',
'zip' =>
	'CAP',
'country' =>
	'Paese',
'dayphone' =>
	'Numero di telefono',
'ccname' =>
	'Nome sulla Carta di Credito',
'ccnumber' =>
	'Numero della carta',
'cctype' =>
	'Tipo di Carta',
'ccexpire' =>
	'Data scadenza Carta',
'cvvnumber' =>
	'CVV2',
'termscon'		=> 'termscon: to be translated',
'cvvclosewindow'	=> 'cvvclosewindow: to be translated',
'cvvtext'	=> '<font size="-1">cvvtext: to be translated</font> ',
'month' =>
	'Mese',
'year' =>
	'Anno',
'ordermethod' =>
	'Modalità d\'ordine',
'ordersubmit' =>
	'Invia l\'ordine',
'contribsubmit' =>
	'Invia la tua donazione',
'clearform' =>
	'Torna ai valori iniziali',
'online' =>
	'Online',
'offline' =>
	'Offline',
'ordersubj' =>
	'Conferma d\'ordine',
'viewcart'		=> 'View Cart: to be translated',
'newitems'		=> 'New Items: to be translated',
'shiploc'		=> 'Shipping Location: to be translated',
'contribsubj' =>
	'Conferma della donazione',
'orderconf' =>
	':Questa e\' la copia di conferma dell\'ordine inviatoci.',
'contribconf' =>
	': Questa è la copia di conferma della tua donazione.',
'paymentconf'	=> 
	'paymentconf: to be translated',
'promoemail'	=> 
	'promoemail: to be translated',
'retain_addr'	=> 
	'retain_addr: to be translated',
'approvetc'	=> 
	'Yes, I have read the Terms and Conditions, and I accept them.',
'orderorigin'	=> 
	"\nThis order was processed by FishCart(r), FishNet(r)'s Open Source\n".
	"e-commerce software.  For information regarding support, upgrade and\n".
	"feature development services, please visit http://www.fishcart.org/\n".
	"or http://www.fishnetinc.com/.\n",
'jsapprovetc'	=>
	'jsapprovetc: to be translated',
'jsonoff' =>
	'Per favore seleziona la modalita\' Online o Offline. Grazie!',
'jscontrib' =>
	'Per favore inserisci il valore della donazione. Grazie!',
'jscountry' =>
	'Per favore seleziona un Paese. Grazie!',
'selectsubz' => 'selectsubz: to be translated',
'jsbcountry' =>
	'Per favore selezione il paese. Grazie!',
'jsscountry' =>
	'Per favore seleziona il paese di destinazione della merce. Grazie!',
'jsccname' =>
	'Per favore inserisci il nome presente sulla carta di credito. Grazie!',
'jsccnum' =>
	'Per favore inserisci il numero di carta di credito. Grazie!',
'jscctype' =>
	'Per favore seleziona il tipo di carta di credito. Grazie!',
'jsccexp' =>
	'Inserisci una data validadi scadenza della carta di credito. Grazie!',
'jsbemail' =>
	'Per favore inserisci l\'indirizzo email nelle informazioni per il pagamento. Grazie!',
'jsbfname' =>
	'Per favore inserisci il tuo nome nelle informazioni per il pagamento. Grazie!',
'jsblname' =>
	'Per favore inserisci il tuo cognome nelle informazioni per il pagamento. Grazie!',
'jsbaddr' =>
	'Per favore inserisci il tuo indirizzo nelle informazioni per il pagamento. Grazie!',
'jsbcity' =>
	'Per favore inserisci la tua Citta\' nelle informazioni per il pagamento. Grazie!',
'jsbstate' =>
	'Per favore inserisci la tua provincia nelle informazioni per il pagamento. Grazie!',
'jsbzip' =>
	'Per favore inserisci il tuo CAP nelle informazioni per il pagamento. Grazie!',
'jspickone' =>
	'Seleziona una categoria o inserisci un termine da ricercare. Grazie!',
'jsplaced' =>
	'Grazie! Il tuo ordine è stato inviato; per favore pazienta qualche attimo, perchè questo processo potrebbe richiedere qualche secondo. Grazie ancora per il tuo ordine!',
'itemcapfix' =>
	'Prodotto',
'qtycapfix' =>
	'Q.ta\'',
'pricecapfix' =>
	'Prezzo',
'productcapfix' =>
	'Descrizione',
'basepricefix' =>
	'Prezzo base',
'optionfix' =>
	'Opzione',
'qtyfix' =>
	'Q.ta\'',
'totalfix' =>
	'Totale',
'setupfix' =>
	'Setup',
'setuptotalfix' =>
	'Totale Setup',
'discountfix' =>
	'Sconto',
'subtotalfix' =>
	'Subtotale',
'psubtotalfix'	=> 'Canone Mensile:   ',
'shippingfix' =>
	'Spedizione',
'salestaxfix' =>
	'IVA',
'contributefix' =>
	'Donazione',
'ordertotalfix' =>
	'Totale Ordine',
'billinfofix' =>
	'Informazioni per il pagamento',
'emailaddrfix' =>
	'Indirizzo E-mail:',
'shipaddrfix' =>
	'Indirizzo per la spedizione',
'dlusernamefix' =>
	'Download Username:',
'dlpasswordfix' =>
	'Download Password: ',
'coupon' =>
	'Coupon: ',
'orderid' =>
	'Numero Ordine',
'phone' =>
	'Telefono:',
'fax' =>
	'FAX:'
);
?>
