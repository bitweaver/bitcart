<?php
//Nederlands
//Translated by Hubert Hoefsloot at hubert@ii.nl
//Rewritten and several translations added by B. van Ouwerkerk bvo  at atz.nl
//currently maintained by B. van Ouwerkerk bvo  at atz.nl

$salutearray[] = 'Mw.';
$salutearray[] = 'Dhr.';
$salutearray[] = 'Mr.';
$salutearray[] = 'Dr.';
$salutearray[] = 'Drs.';
$salutearray[] = 'Da.';

$fc_prompt = array(
'welcome' =>
	'Welkom bij FishCart, een vooraanstaand internationaal open source e-commerce systeem'.
        'U kunt FishCart downloaden van <a href="http://fishcart.org">FishCart.org</a>',
'outofservice' =>
	'Het winkel systeem is tijdelijk buiten gebruik. Probeer het over '.
        'een paar minuten nog eens. Het spijt ons voor het ongemak.',
'choosegeo' =>
	'<h2 align=center>Geografische Selectie</h2><i>Selecteer de locatie '.
        'van uw verblijfplaats t.b.v. <br>het berekenen van btw en vervoerskosten.</i>',
'invalidfield' =>
	'</center><p><b>Een vereist invoerveld is leeg gelaten.<br> Druk a.u.b. op '.
        'de &quot;vorige pagina&quot; knop van uw browser <br>en controleer of alles '.
        'is ingevuld. '.
        'Dank U.</b><br>',
'tcnotapproved' =>
        '<p><b>U bent niet met onze handelsvoorwaarden akkoord gegaan. Uw bestelling '.
        'kan niet afgerond worden als u niet akkoord gaat. Klik s.v.p. op de '.
        '&quot;terug&quot; knop van uw browser om alsnog akkoord te gaan of klik '.
        'op onderstaande link om terug te gaan naar onze site.</b></p>',
'contribblank'  =>
        '<p><b>U heeft vrijwillige bijdrage leeg gelaten. Klik s.v.p. op de &quot;terug&quot; knop '.
        'van uw browser om alsnog een bedrag in te vullen.</b></p>',
'payamblank'  =>
        '<p><b>Het totaalbedrag is leeg. Klik s.v.p. op de &quot;terug&quot; knop '.
        'van uw browser om alsnog een bedrag in te vullen.</b></p>',
'invalidemail' =>
	'</center><p><b>Het door u ingevulde email adres is niet correct.<br> '.
        'Druk s.v.p. op de &quot;vorige pagina&quot; knop van uw browser '.
        'om het adres te corrigeren. Dank u.</b><br>',
'invalidoffon' =>
	'</center><p><b>U heeft online noch offline order methode gekozen. '.
        'Klik s.v.p. op de &quot;vorige pagina&quot; toets van uw browser '.
        'en geef s.v.p. aan voor welke methode u kiest.',
'invalidccfld' =>
	'</center><p><b>Een vereist credit card veld is niet ingevuld. '. 
        'Klik s.v.p. op de &quot;vorige pagina&quot; toets van uw browser '.
        'om het ontbrekende veld in te vullen. Dank u.',
'invalidccard' =>
	'</center><p><b>'.
        'Het door u ingevoerde credit card nummer lijkt ongeldig te zijn '.
        'Klik s.v.p. op de &quot;vorige pagina&quot; toets van uw browser '.
        'en controleer of u het juiste nummer in heeft gevoerd.'.
        'Om het nummer beter leesbaar te maken kunt u het eventueel volgens de '.
        'voorbeelden welke u hieronder aan kunt treffen middels spaties in '.
        'stukken opdelen. Dank u.</b><br>'.
        '<pre>'.
        '1111 2222 3333 4444 (Visa/Mastercard)'.
        ' '.
        '1111 222222 333333 (American Express)'.
        '<pre>',
'invalidcctype' =>
	'</center><p><b>Klik s.v.p. op de &quot;vorige pagina&quot; toets van uw browser '.
        'en selecteer het type credit card wat u gebruikt. Dank u.',
'invalidccyr' =>
	'</center><p><b>Klik s.v.p. op de &quot;vorige pagina&quot; toets van uw browser '.
        'en voer de juiste vervaldatum van uw credit card in. Dank u.',
'invalidccmo' =>
	'</center><p><b>Klik s.v.p. op de &quot;vorige pagina&quot; toets van uw browser '.
        'en voer de juiste maand in waarop uw credit card vervalt. Dank u.',
'invalidccclr'	=>	'<center><br>'.
        'Uw credit card is niet geaccepteerd.<br>'.
        'Eventueel kunt u het met een andere kaart proberen. '.
        'Met de &quot;vorige pagina&quot; toets van uw browser kunt u terug '.
        'naar het orderformulier. Dank u. '.
        '<p><a href="'.BIT_ROOT_URL.'"><i> Home Page</i></a></center>',
'invalidorder' =>
	'<h2 align=center>Ongeldige of voltooide bestelling</h2> De huidige bestelling '.
        'is ongeldig of met succes afgerond. U krijgt dit bericht als u na voltooiing '.
        'van uw bestelling op de &quot;vorige pagina&quot; knop van uw browser heeft '.
        'gedrukt. Als u een bestelling online heeft geplaats zal deze middels een '.
        'uitgebreide email, naar het door u opgegeven adres, worden bevestigd. Er zal '.
        'geen e-mail verzonden worden bij offline bestellingen.<p> Om verder te gaan '.
        'met kijken op deze site kunt u hiet klikken <a href="'.BIT_ROOT_URL.'"> '. 
        'om terug te gaan naar de home page.</a> Bedankt!', 
'pwexp'			=>
	'Dit account voor download is vervallen. Neem s.v.p. contact op met onze '.
        'klantenservice. Dank u.',
'orderfinal' =>
	'Uw bestelling is geplaats! een gedetailleerd overzicht van de bestelling zal per e-mail '.
        'verzonden worden naar het door u ingegeven adres.<p> Druk s.v.p. niet op de '.
        '&quot;vorige pagina&quot; knop an uw browser. Desgewenst kunt u op onderstaande '.
        'link klikken. U komt dan op onze homepagina terecht. '.
        '<br>Bedankt voor het plaatsen van uw bestelling.',
'emptysearch' =>
	'Uw zoekopdracht heeft geen resultaat opgeleverd of u heeft geen product categorie '.
        'gekozen. Ga s.v.p. terug naar de productselectie pagina en kies een categorie '.
        'of voer een zoekopdracht in.',
'back2select' =>
	'Klik hier om terug te gaan naar de productselectie pagina',
'click2select'	=>
	'<i>Klik op het plaatje voor meer details</i>',

'click2prodname'=>
	'<i>Klik op de productnaam voor meer details</i>',

'click2select2' =>
        '<i>Klik hier voor meer productinformatie</i>',

'back2cat'=>
	'&#171terug',

'shipinfo' =>
	'Verzend informatie<i>(mits anders dan factuur adres)</i>',
'onlinetext' =>
	'Online order worden geplaatst en betaald middels uw credit card. Om uw '.
        'gegevens zo goed mogelijk te beschermen gebruiken wij een zeer sterke '.
        'versleuteling.',
'offlinetext' =>
	'U kunt de bestelling uitprinten en verzenden via telefoon, fax of post. '.
        'U kunt ook met credit card betalen of u bestelling met een cheque per post '.
        'aan ons toezenden.',
'noshipcalc' =>
	'Het script om de verzendkosten te berekenen kan niet gevonden worden.',
'cartcontents' =>
	'Inhoud van uw winkelwagen',
'cartempty' =>
	'Uw winkelwagen is leeg!',
'cartmodify' =>
	'<i>Om een artikel aan te passen, verander aantal en klik '.
        '&quot;Pas bestelling aan&quot;.</i><br><i>Verwijder een artikel door hem op '.
        '0 te zetten en klik &quot;Pas bestelling aan&quot;.</i><br>',
'cartsubmit' =>
	'Pas bestelling aan',
'cartinvmax' =>
	'<i>*** aantal is groter dan de voorraad</i>',

'esdnotrans'	=>
	'Geen product beschikbaar voor download.<br>',
'esdnodl'		=>
	'De informatie over de download is onjuist of het door u ingevoerde '.
	'wachtwoord is niet juist.<br>',
'esddlmax'		=>
	'Het maximale aantal downloads van dit bestand is overschreden.<br>',
'esdnofile'		=>
	'Het te downloaden bestand kon niet worden gevonden.<br>',
'custsvc'	=>
	'Neem s.v.p. contact op met de  klantenservice voor assistentie.<br>',

'donatetext' =>
	'Als u een donatie wilt geven t.b.v. de activiteiten van  '.
        'geef dan hieronder s.v.p. het bedrag aan zodat het aan het totale '.
        'orderbedrag toe wordt gevoegd. Dank u.',
'optviolation' =>
	'<b><i>Een vereiste optie voor dit product is niet geselecteerd. '.
        'Voor s.v.p. uw selectie opnieuw in; verieste opties zijn met '.
        '<font color="#ff0000"><b>*</b></font> gemarkeerd. '.
        'Dank u',
'optreqtext' =>
	'<font color="#ff0000"><b>*</b></font> <i>= vereiste optie</i></font>',
'emptyopt'		=> '[selecteer een optie]',
'reqtext' =>
	'<font color="#ff0000"><b>*</b></font> <i>= vereist</i></font>',
'reqflag' =>
	'<font color="#ff0000"><b>*</b></font>',
'choosezone' =>
	'Selecteer een catalogus',
'chooselang' =>
	'Selecteer een taal',
'choosecat' =>
	'Bekijk de catalogus',
'selectcat' =>
	'[selecteer een catalogus]',
'choosekey' =>
	'zoek op trefwoord',
'outstocktemp'	=> 'Tijdelijk niet op voorraad',
'newitems' =>
	'Nieuwe toevoegingen!',
'dispmultiple'	=> 'Toont producten',
'dispsingle'	=> 'Toont product',
'dispto'	=> 'tot ',
'dispof'	=> 'van',
'closeout'	=> 'Uitverkoop',
'vieworder' =>
	'Bekijk uw bestelling',
'contactinfo' =>
	'Contact informatie',
'supportinfo' =>
	'Klanten service',
'titletag' =>
	'Online winkelen',
'submitgeo' =>
	'Selecteer de regio waar u woont.',
'prodinfo' =>
	'Bestelde producten',
'orderinfo' =>
	'Informatie over uw order',
'billinfo' =>
	'Factuur gegevens',
'creditinfo' =>
	'Credit Card informatie',
'proddesc' =>
	'Product omschrijving:',
'couponid' =>
	'Coupon ID:',
'coupondisc' =>
	'Coupon korting:',
'unitprice' =>
	'Stuks prijs:',
'baseprice' =>
	'Basis prijs:',
'option' =>
	'Optie:',
'setup' =>
	'Setup:',
'basesetup'		=> 'basisproduct setup:',
'setuptotal'	=> 'totale setup kosten:',
'setupfee' =>
	'Setup kosten:',
'setupfees' =>
	'Setup kosten:',
'shipfee' =>
	'Verzend kosten:',
'salestax' =>
	'Belasting:',
'psalestax' =>
	'Belasting maand:',
'thankyou' =>
	'Dank U!',
'voluntary' =>
	'Donatie:',
'subtotal' =>
	'Subtotaal:',
'psubtotal' =>
	'Subtotaal maand:',
'product'=>
	'Product',
'total' =>
	'Totaal:',
'longadd' =>
	'Toevoegen aan bestelling',
'shortadd' =>
	'Toevoegen',
'quantity' =>
	'Aantal:',
'qty' =>
	'Aantal:',
'home' =>
	'Home',
'subcats' =>
	'Subcategorie',
'previous' =>
	'Vorige',
'next' =>
	'Volgende',
'searchresult' =>
	'Zoek resultaat',
'sku' =>
	'Product ID:',

'dlheader'	=> 'Download voor bestelling ',

'download' =>
	'Download ',
'downloadrem' =>
	'Resterende downloads',
'downloadmax' =>
	'Limiet maximale download bereikt',
'onsale' =>
	'Actie!',
'price' =>
	'Prijs',
'retailprice'	=> 'adviesprijs:',
'periodic' =>
	'per maand',
'nocharge' =>
	'gratis',
'audiosample' =>
	'Audio Sample',
'videosample' =>
	'Video Sample',
'homepage' =>
	'Home Page',
'zonehome' =>
	'Voorpagina van catalogus',
'returnpage' =>
	'terug naar vorige pagina',
'returnprod' =>
	'terug naar product pagina',
'selectctry' => 'selecteer land',
'selectgeo' =>
	'Selecteer geografische lokatie',
'checkout' =>
	'Kassa',
'contribution' =>
	'Online donatie',
'contribamount' =>
	'Bedrag van donatie:',
'emailaddr' =>
	'E-Mail adres',
'salutation' =>
	'Titel<br><i>optioneel</i>',
'saluteopt' =>
	'[optionele titel]',
'firstname' =>
	'Voornaam',
'miname' =>
	'Tussenvoegsel',
'lastname' =>
	'Achternaam',
'company' =>
	'Bedrijf',
'address' =>
	'Adres',
'city' =>
	'Stad',
'state' =>
	'Provincie',
'zip' =>
	'Postcode',
'country' =>
	'Land',
'dayphone' =>
	'Telefoon overdag (incl. netnummer)',
'ccname' =>
	'Naam op Credit Card',
'ccnumber' =>
	'Credit Card nummer',
'cctype' =>
	'Soort Credit Card',
'ccexpire' =>
	'Credit Card vervaldatum',
'cvvnumber' =>
	'CVV2',
'termscon'		=> 'Leveringsvoorwaarden',
'cvvclosewindow'	=> 'Dit venster sluiten.',
'cvvtext'   => '<font size="-1">De CVV2 code is een 3 cijferig nummer '.
        'wat op de achterkant van Visa, Mastercard of Discovery credit card staat '.
        'vermeld. Over het algemeen in het handtekening venster. Op een '.
        'American Express kaart vind u het nummer op de voorkant en is het 4 cijferig. '.
        'Omdat de CVV2 code alleen op de kaart staat en op geen enkele andere wijze '.
        'elders vermeld is (overzicht of bon) geeft de CVV2 code enige zekerheid '.
        'dat de kaart in handen van de bezoeker van de website is.</font>',
'month' =>
	'Maand',
'year' =>
	'Jaar',
'ordermethod' =>
	'Wijze van bestellen',
'ordersubmit' =>
	'Plaats uw bestelling',
'contribsubmit' =>
	'Geef uw donatie',

'dlsubmit'		=> 'Uw gebruikersnaam en wachtwoord voor download '.
                           'verzenden',
'dlusername'	=> 'Gebruikersnaam:',
'dlpassword'	=> 'Wachtwoord: ',

'clearform' =>
	'Wis Formulier',
'online' =>
	'Online',
'offline' =>
	'Offline',
'ordersubj' =>
	'Bevestiging van bestelling',
'viewcart'		=> 'winkelwagen',
'newitems'		=> 'nieuwe producten',
'shiploc'		=> 'Geografische locatie',
'contribsubj' =>
	'Bevestiging van contributie',
'orderconf' =>
	'Dit is de bevestiging van uw bestelling geplaatst bij\n .',
'contribconf' =>
	'Dit is de bevestiging van uw contributie bij\n .',
'paymentconf'	=> 
	'Dit is de bevestiging van uw betaling',
'promoemail'	=> 
	'Hou mij op de hoogte van nieuwe producten en aanbiedingen',
'retain_addr'	=> 
	'Bewaar mijn adresgegevens op deze computer',
'approvetc'	=> 
	'Ja. Ik heb de leveringsvoorwaarden gelezen en ik accepteer ze.',
'orderorigin'	=> 
	"\nThis order was processed by FishCart(r), FishNet(r)'s Open Source\n".
	"e-commerce software.  For information regarding support, upgrade and\n".
	"feature development services, please visit http://www.fishcart.org/\n".
	"or http://www.fishnetinc.com/.\n",
'jsapprovetc'	=>
	'U heeft nog niet aangegeven akkoord te gaan met onze leveringsvoorwaarden',
'jsonoff' =>
	'A.u.b. online of offline selecteren. Dank U!',
'jscontrib' =>
	'A.u.b. bedrag van uw donatie invullen. Dank U!',
'jscountry' =>
	'Selecteer a.u.b. een land. Dank U!',
'selectsubz' => 'Selecteer uw geografische locatie',
'jsbcountry' =>
	'Selecteer a.u.b. het land voor het factuuradres. Dank U!',
'jsscountry' =>
	'Selecteer a.u.b. het land voor het aflever adres. Dank U!',
'jsccname' =>
	'A.u.b. de naam die op de credit card staat. Dank U!',
'jsccnum' =>
	'Voer s.v.p. het nummer van uw credit card in. Dank u.',
'jscctype' =>
	'Voer s.v.p. het type credit card in. Dank u.',
'jsccexp' =>
	'Selecteer s.v.p. een geldige credit card vervaldatum. Dank u.',
'jsbemail' =>
	'Voer s.v.p. een geldig e-mail adres in bij het factuuradres. Dank u.',
'jsbfname' =>
	'Voer s.v.p. een voornaam in bij het factuuradres. Dank u.',
'jsblname' =>
	'Voer s.v.p. een achternaam in bij het factuuradres. Dank u',
'jsbaddr' =>
	'Voer s.v.p. een adres in bij het factuuradres. Dank u',
'jsbcity' =>
	'voer s.v.p. een stad in bij het factuuradres. Dank u.',
'jsbstate' =>
	'Voer s.v.p. een staat of provincie in bij het factuuradres. Dank u.',
'jsbzip' =>
	'Voer s.v.p. een postcode in bij het factuuradres. Dank u.',
'jspickone' =>
	'Selecteer s.v.p. een categorie of voer een trefwoord in om op '.
        'te zoeken. Dank u.',
'jsplaced' =>
	'Dank u. Uw order is geplaats; even geduld a.u.b. deze verwerking '.
        'kan even duren. Nogmaals onze dank voor uw bestelling.',
'itemcapfix' =>
	'   ARTIKEL   ',
'qtycapfix' =>
	'AANTAL    ',
'pricecapfix' =>
	'PRIJS   ',
'productcapfix' =>
	' OMSCHRIJVING    ',
'basepricefix' =>
	'Advies  Prijs:',
'optionfix' =>
	'Optie:',
'qtyfix' =>
	'Aantal:        ',
'totalfix' =>
	'Totaal:',
'setupfix' =>
	'Setup:',
'setuptotalfix' =>
	'Setup:',
'discountfix' =>
	'Korting:',
'subtotalfix' =>
	'Subtotaal:',
'psubtotalfix' =>
	'Subtotaal per maand:',
'shippingfix' =>
	'Verzendkosten:',
'salestaxfix' =>
	'Belasting:',
'psalestaxfix' =>
	'Belasting per maand:',
'contributefix' =>
	'Contributie:',
'ordertotalfix' =>
	'Totaal order:',
'ptotalfix' =>
	'Totaal per maand:',
'billinfofix' =>
	'Factuur adres:',
'emailaddrfix' =>
	'E-Mail adres:',
'shipaddrfix' =>
	'Afleveradres:',
'dlusernamefix'	=>
	'Loginnaam t.b.v. download',
'dlpasswordfix'	=>
	'Wachtwoord t.b.v. download',
'coupon' =>
	'Coupon:',
'orderid' =>
	'Bestel ID:',
'phone' =>
	'Telefoon:',
'fax' =>
	'Fax:'
);
?>
