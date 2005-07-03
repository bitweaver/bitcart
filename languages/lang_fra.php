<?php
/* French prompts translation.
My cart is a little bit personalized and I use different prompts
than these, so double check everything before going on line. 

Many thanks to Michael Brennen and all contributors for sharing
their programming skills with the world.

-- Ronald Labont�
Qu�bec, Canada, 31 Jan 2001.
*/

// personal salutation array
$salutearray[] = 'M.';
$salutearray[] = 'Mme.';
$salutearray[] = 'Madame';
$salutearray[] = 'Md.';
$salutearray[] = 'Rev.';

$fc_prompt = array(

'welcome'	=>
	'<h4>Fishcart</h4>'.
	'<b>Scripts php pour commerce en-ligne</b>.<p>'.
	'Les scripts sont libres &rsquo;Open Source&rsquo;'.
	' avec capacit�e multi-nationale.<p>'.
	'Vous pouvez t�l�charger fishcart � cette adresse:'.
	'<a href="http://fishcart.org">fishcart.org</a>',
		

'outofservice'	=>
	'Le logiciel est pr�sentement hors d&rsquo;usages.'.
	' Essayez de nouveau dans quelques minutes; '.
	' nous sommes d�sol� des inconv�nients caus�s.',

'choosegeo'		=>
	'<h2 align=center>S�lection G�eographique</h2>'.
	'<i>S�lectionez la r�gion de votre r�sidence pour<br>'.
	'les calculs de taxes de vente et de transport.</i>',

'invalidfield'  =>
    '</center><p><b>'.
	'Un champ requis &agrave; &eacute;t&eacute; laiss&eacute; blanc.<br>'.
    'S.V.P. cliquer le bouton &quot;Retour&quot; de votre navigateur<br>'.
    'et assurez-vous d&rsquo;emplir toutes les cases.  Merci.</b><br>',

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

'invalidemail'  =>
    '</center><p><b>'.
    'Il semble y avoir un probl&egrave;me avec votre adresse courriel. '.
    '<br>S.V.P. Utiliser le bouton &quot;Retour&quot; de votre navigateur '.
    '<br>et v&eacute;rifiez-en la syntaxe. Merci.</b><br>',

'invalidoffon'  =>
    '</center><p><b>Vous devez faire la s&eacute;lection de la '.
	'm&eacute;thode (En-Ligne ou Hors-Ligne)<br>'.
	'S.V.P. Utilisez le bouton &quot;Retour&quot; de votre navigateur '.
    'pour choisir la m&eacute;thode.  Merci.</b><br>',

'invalidccfld'  =>
    '</center><p><b>'.
	'Un champ de carte de cr&eacute;dit &agrave; &eacute;t&eacute;'.
    'laiss&eacute; blanc.  S.V.P. Utilisez le bouton &quot;Retour&quot; '.
	'de votre navigateur pour en faire la v&eacute;rification.  Merci.</b>',

'invalidccard'  =>
    '</center><br><b>Le num&eacute;ro '.
	'de carte de cr&eacute;dit semble &ecirc;tre non valide<br>'.
    'S.V.P. Utilisez le bouton &quot;Retour&quot; de votre navigateur<br>'.
    'et v&eacute;rifier le num&eacute;ro.  Pour vous aider vous pouvez '.
	's&eacute;parer le num&eacute;ro<br>'.
    'en groupes par des espaces blancs (voir les exemples plus bas).<br>'.
    'Merci.</b><br>'.
    '<pre>'.
    '1111 2222 3333 4444 (Visa / Mastercard)'.
    ' '.
    '1111 222222 333333 (American Express)'.
    '</pre>',

'invalidcctype' =>
    '</center><p><b>'.
    'S.V.P. Utilisez le bouton &quot;Retour&quot; de votre navigateur et '.
    's&eacute;lectionner un type de carte de cr&eacute;dit.  Merci.</b>',

'invalidccyr'   =>
    '</center><p><b>'.
    'S.V.P. Utilisez le bouton &quot;Retour&quot; de votre navigateur et<br>'.
    'entrer une date d\'expiration valide pour la carte de cr&eacute;dit. '.
	'Merci.</b>',

'invalidccmo'   =>
    '</center><p><b>'.
    'S.V.P. Utiliser le bouton &quot;Retour&quot; de votre navigateur et<br>'.
    'entrer un mois d&rsquo;expiration valide pour la carte de '.
	'cr&eacute;dit.  Merci.</b>',

'invalidccclr'	=>	'',

'invalidorder'	=>
	'<h2 align=center>Commande non valide ou d�ja compl�t�e</h2>'.
	'La commande courante semble �tre non valide ou d�ja '.
	'compl�t�e avec succ�s.  Vous allez re�evoir ce '.
	'message si vous cliquer le bouton &quot;Retour&quot; de'.
	'votre navigateur apr�s avoir �ffectu� une commande.'.
	'Vous re�evrez un message de confirmation apr�s toute'.
	'commande compl�t�e avec succ�s si cette commande �tait'.
	' de type &quot;En Ligne&quot;'.
	'Les commandes &quot;Hors Ligne&quot ne sont pas '.
	'confirm�es par courriel.<br>'.
	'Pour continuer votre navigation sur notre site,'.
	' S.V.P. cliquer ici '.
	'<a href="'.$homeurl.'/">retour � la page d&rsquo;cceuiile.</a> Merci!',

'pwexp'			=>
	'',

'orderfinal'	=>
	'Votre commande est plac�e!  Un message d�taill� vous sera '.
        'envoyer par courriel � l&rsquo;adresse vous nous avez laisser.'.
        '<p>'.
	'S.V.P. Ne cliquer pas sur le bouton &quot;Retour&quot; '.
        'de votre navigateur. Pour continuer, utilisez le lien '.
        'ci-dessous pour retourner � la page principale. '.
	'<p>Merci encore pour avoir utiliser nos services!',

'emptysearch'	=>
	'Votre recherche ne retourne aucun r�sultat, assurez-vous '.
        'd&rsquo;avoir bien sp�cifi� un mot cl� et une cat�gorie appropri�e. ',

'back2select'	=>
	'Cliquez ici pour retourner � la page de s�lection des produits.',

'click2select'	=>
	'<i>click2select: to be translated</i>',

'click2prodname'=>
	'<i>click2prodname: to be translated</i>',

'click2select2' =>
        '<i>Click here for more product detail</i>',

'back2cat'=>
	'&#171back2cat: to be translated',

'shipinfo'		=>
	'Information sur la livraison<br><i>(si diff�rent des infos'.
	' de facturation)</i>',

'onlinetext'	=>
	'Les commandes en ligne sont �ffectu�es avec votre carte de '.
         'cr�dit.  La commande est encrypt�e.',
'offlinetext'	=> 
	'Vous pouvez imprimer votre commande et la transmettre par '.
        'courrier, fax ou t�l�phone. '.
	'Vous pouvez payer par carte de cr�dit ou mandat postal',
	
'noshipcalc'	=> 'Le script pour calculs de transport est manquant.',

'cartcontents'	=> 'Contenu de votre Commande',
'cartempty'	=> 'Aucun Item est en commande!',
'cartmodify'	=>
	'<i>Pour modifier un item, entrer la nouvelle quantit�e et '.
        'cliquez<br>'.
	'&quot;Modifier la Commande&quot;.</i><br>'.
	'<i>Pour enlever un item, entrer une quantit�e 0 et cliquez<br>'.
	'&quot;Modifier la Commande&quot;.</i><br>',
'cartsubmit'	=> 'Modifier la Commande',
'cartinvmax'	=> '<i>*** quantit�e exc�de inventaire</i>',

'esdnotrans'	=>
	'<br>',
'esdnodl'		=>
	'<br>',
'esddlmax'		=>
	'<br>',
'esdnofile'		=>
	'<br>',
'custsvc'	=>
	'<br>',

'donatetext'	=>
	'Si vous d�sirez apporter une contribution volantaire '.
	', S.V.P. entrer le montant ci-dessous et il sera'.
	'ajout� au montant total de votre commande. Merci.',
 
'optviolation'	=>
	'<b><i>Une option requise pour ce produit est manquante. '.
	'S.V.P. V�rifier votre s�lection de produits;'.
	'les options requises '.
	'sont identifi�s par un <font color="#ff0000"><b>*</b></font>. '.
	'Merci.</i></b>',
'optreqtext'	=> 'optreqtext: to be translated',
'emptyopt'		=> 'emptyopt: to be translated',
'reqtext'		=> '<font color="#ff0000"><b>*</b> <i>= requis</i></font>',
'reqflag'		=> '<font color="#ff0000"><b>*</b></font>',
'choosezone'	=> 'Choix du Catalogue',
'chooselang'	=> 'Choix de la Langue',
'choosecat'		=> 'Choix de la Cat�gorie',
'selectcat'		=> '[Votre Choix]',
'choosekey'		=> 'Recherche dans cette cat�gorie',
'outstocktemp'	=> 'outstocktemp: to be translated',
'newitems'		=> 'Du Nouveau!',
'dispmultiple'	=> 'dispmultiple: to be translated',
'dispsingle'	=> 'dispsingle: to be translated',
'dispto'	=> 'dispto: to be translated',
'dispof'	=> 'dispof: to be translated',
'closeout'		=> 'closeout: to be translated',
'vieworder'		=> 'Afficher votre Commande',
'contactinfo'	=> 'Contact pour Informations',
'supportinfo'	=> 'Contact pour Support',
'titletag'		=> 'Commerce en Ligne',
'submitgeo'		=> 'S�lection de Votre R�gion',
'prodinfo'		=> 'Achat de Produits',
'orderinfo'		=> 'Information sur la Commande',
'billinfo'		=> 'Information sur la Facturation: ',
'creditinfo'	=> 'Information sur la Carte de Cr�dit',
'proddesc'		=> 'Description du Produit',
'couponid'		=> 'ID Coupon:',
'coupondisc'	=> 'Coupon Escompte:',
'unitprice'		=> 'Prix Unitaire:',
'baseprice'		=> 'Prix de Base:',
'option'		=> 'Option:',
'setup'			=> 'Activation:',
'basesetup'		=> 'basesetup: to be translated',
'setuptotal'	=> 'setuptotal: to be translated',
'setupfee'		=> 'Frais D&rsquo;activation:',
'setupfees'		=> 'Frais D&rsquo;activations:',
'shipfee'		=> 'Transport & Manutention:',
'salestax'		=> 'Taxes de Ventes:',
'psalestax'		=> 'Taxes par mois:',
'thankyou'		=> 'Merci!',
'voluntary'		=> 'Dons volontaire additionnels:',
'subtotal'		=> 'Sous-total:',
'psubtotal'		=> 'Total par mois:',
'product'		=> 'product: to be translated',
'total'			=> 'Total:',
'longadd'		=> 'Ajouter � la commande',
'shortadd'		=> 'J&rsquo;ach�te &radic;',
'quantity'		=> 'Quantit�',
'qty'			=> 'Qt�e',
'home'			=> 'Acceuil',
'subcats'		=> 'Sous-cat�gories:',
'previous'		=> 'Pr�c�dent',
'next'			=> 'Suivant',
'searchresult'	=> 'R�sultas de la recherche de:',
'sku'			=> 'Inventaire #:',
'dlheader'		=> 'Liens pour Commande ',
'download'		=> 'Liens',
'downloadrem'	=> ' ',
'downloadmax'	=> ' ',
'onsale'		=> 'Special!',
'price'			=> 'Prix:',
'retailprice'	=> 'retailprice: to be translated',
'periodic'		=> 'par mois',
'nocharge'		=> 'S/F',
'audiosample'	=> 'Extrait Audio',
'videosample'	=> 'Extrait Vid�o',
'homepage'		=> '-> Page D&rsquo;acceuil',
'zonehome'		=> 'Page Principale du Catalogue',
'returnpage'	=> 'Retour � la page pr�c�dente',
'returnprod'	=> 'Retour � la Page Produits',
'selectctry'	=> 'S�lection de Pays',
'selectgeo'		=> 'S�lection de la R�gion G�ographique',
'checkout'		=> 'Soumettre La Commande',
'contribution'	=> 'Dons En-Ligne',
'contribamount'	=> 'Montant du Don:',
'emailaddr'		=> 'Adresse Courriel',
'salutation'	=> 'Titre<br><i>optionnel</i>',
'saluteopt'		=> '[titre optionnel]',
'firstname'		=> 'Pr�nom',
'miname'		=> 'I.',
'lastname'		=> 'Nom',
'company'		=> 'Soci�t�',
'address'		=> 'Adresse',
'city'			=> 'Ville',
'state'			=> 'Province',
'zip'			=> 'Code Postal',
'country'		=> 'Pays',
'dayphone'		=> 'T�l�phone de jour (code, num�ro)',
'ccname'		=> 'Nom sur la carte de Cr�dit',
'ccnumber'		=> 'Num�ro de la carte de Cr�dit',
'cctype'		=> 'Type de Carte de Cr�dit',
'ccexpire'		=> 'Date d&rsquo;xpiration',
'cvvnumber'		=> 'CVV2',
'termscon'		=> 'termscon: to be translated',
'cvvclosewindow'	=> 'cvvclosewindow: to be translated',
'cvvtext'		=> '<font size="-1">cvvtext yet to be specified</font> ',
'month'			=> 'Mois',
'year'			=> 'Ann�e',
'ordermethod'	=> 'M�thode de Commande',
'ordersubmit'	=> 'Soumettre Votre Commande',
'contribsubmit'	=> 'Soumettre Votre Don',
'dlsubmit'		=> 'Nom e Mot de Passe',
'dlusername'	=> 'Nom:',
'dlpassword'	=> 'Mot de Passe:',
'clearform'		=> '�ffacer le Formulaire',
'online'		=> 'En Ligne',
'offline'		=> 'Hors Ligne',
'ordersubj'		=> 'Confirmation de la Commande',
'viewcart'		=> 'View Cart',
'newitems'		=> 'New Items',
'shiploc'		=> 'Shipping Location',
'contribsubj'	=> 'Confirmation de la Contribution',
'orderconf'	=> 
	"Ceci est une copie de confirmation de votre commande chez\n ".
	'COMPAGNIE.', 
'contribconf'	=> 
	"Ceci est une copie de confirmation de votre contribution\n chez COMPAGNIE.",
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
'jsonoff'    => 'S�lectionnez En Ligne ou Hors Ligne.  Merci!',
'jscontrib'  => 'S.V.P. S�lectionner un montant pour le don.  Merci!',
'jscountry'  => 'S.V.P. S�lectionnez un pays.  Merci!',
'selectsubz' => 'selectsubz: to be translated',
'jsbcountry' => 'S.V.P. S�lectionnez un pays pour la facturation.  Merci!',
'jsscountry' => 'S.V.P. S�lectionnez un pays pour la livraison.  Merci!',
'jsccname'   => 'S.V.P. Entrez le nom qui est sur la carte de cr�dit.  Merci!',
'jsccnum'    => 'S.V.P. Entrez le num�ro de la carte de cr�dit.   Merci!',
'jscctype'   => 'S.V.P. Entrez le type de la carte de cr�dit.  Merci!',
'jsccexp'    => 'S.V.P. Entrez la date d&rsquo;expiration de la carte '.
                'de cr�dit.  Merci!',
'jsbemail'   => 'S.V.P. Entrez votre adresse courriel pour la facturation.  Merci!',
'jsbfname'   => 'S.V.P. Entrez votre pr�nom pour la facturation.  Merci!',
'jsblname'   => 'S.V.P. Entrez votre nom pour la facturation.  Merci!',
'jsbaddr'    => 'S.V.P. Entrez une adresse de facturation.  Merci!',
'jsbcity'    => 'S.V.P. Entrez la ville de facturation.  Merci!',
'jsbstate'   => 'S.V.P. Entrez une province de facturation.  Merci!',
'jsbzip'     => 'S.V.P. Entrez le code postal pour la facturation.  Merci!',
'jspickone'  =>	'S.V.P. Entrez une cat�gorie ou un mot cl� pour la recherche'.
                '  Merci!',
'jsplaced'		=> 'Merci!  Votre commande est plac�e!',

// for proddisp{echo,fixed}.php fixed alignment pages
'itemcapfix'	=> 'ITEM         ',
'qtycapfix'		=> 'QTEE      ',
'pricecapfix'	=> 'PRIX     ',
'productcapfix'	=> 'DESCRIPTION',
'basepricefix'	=> '     Prix de Base: ',
'optionfix'		=> '           Option: ',
'qtyfix'		=> '             Qt�e: ',
'totalfix'		=> '            Total: ',
'setupfix'		=> '       Activation: ',
'setuptotalfix'	=> ' Total Activation:     ',
'discountfix'	=> '         Escompte:     ',
'subtotalfix'	=> '       Sous-Total:     ',
'psubtotalfix'	=> '         Par Mois:     ',
'shippingfix'	=> '        Transport:     ',
'salestaxfix'	=> '  Taxes de Ventes:     ',
'psalestaxfix'	=> '   Taxes par Mois:     ',
'contributefix'	=> '     Contribution:     ',
'ordertotalfix'	=> ' Total de la Commande: ',
'ptotalfix'		=> '   Total par Mois:     ',
'billinfofix'	=> 'Information de Facturation:  ',
'emailaddrfix'	=> 'Adresse Courriel:       ',
'shipaddrfix'	=> 'Adresse de Livraison:     ',
'dlusernamefix'	=> '',
'dlpasswordfix'	=> '',
'coupon'		=> 'Coupon: ',
'orderid'		=> 'Commande #: ',
'phone'			=> 'T�l�phone: ',
'fax'			=> 'FAX: '

);
?>
