<?php
/* French prompts translation.
My cart is a little bit personalized and I use different prompts
than these, so double check everything before going on line. 

Many thanks to Michael Brennen and all contributors for sharing
their programming skills with the world.

-- Ronald Labonté
Québec, Canada, 31 Jan 2001.
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
	' avec capacitée multi-nationale.<p>'.
	'Vous pouvez télécharger fishcart à cette adresse:'.
	'<a href="http://fishcart.org">fishcart.org</a>',
		

'outofservice'	=>
	'Le logiciel est présentement hors d&rsquo;usages.'.
	' Essayez de nouveau dans quelques minutes; '.
	' nous sommes désolé des inconvénients causés.',

'choosegeo'		=>
	'<h2 align=center>Sélection Géeographique</h2>'.
	'<i>Sélectionez la région de votre résidence pour<br>'.
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
	'<h2 align=center>Commande non valide ou déja complètée</h2>'.
	'La commande courante semble être non valide ou déja '.
	'complétée avec succès.  Vous allez reçevoir ce '.
	'message si vous cliquer le bouton &quot;Retour&quot; de'.
	'votre navigateur après avoir éffectué une commande.'.
	'Vous reçevrez un message de confirmation après toute'.
	'commande complétée avec succés si cette commande était'.
	' de type &quot;En Ligne&quot;'.
	'Les commandes &quot;Hors Ligne&quot ne sont pas '.
	'confirmées par courriel.<br>'.
	'Pour continuer votre navigation sur notre site,'.
	' S.V.P. cliquer ici '.
	'<a href="'.$homeurl.'/">retour à la page d&rsquo;cceuiile.</a> Merci!',

'pwexp'			=>
	'',

'orderfinal'	=>
	'Votre commande est placée!  Un message détaillé vous sera '.
        'envoyer par courriel à l&rsquo;adresse vous nous avez laisser.'.
        '<p>'.
	'S.V.P. Ne cliquer pas sur le bouton &quot;Retour&quot; '.
        'de votre navigateur. Pour continuer, utilisez le lien '.
        'ci-dessous pour retourner à la page principale. '.
	'<p>Merci encore pour avoir utiliser nos services!',

'emptysearch'	=>
	'Votre recherche ne retourne aucun résultat, assurez-vous '.
        'd&rsquo;avoir bien spécifié un mot clé et une catégorie appropriée. ',

'back2select'	=>
	'Cliquez ici pour retourner à la page de sélection des produits.',

'click2select'	=>
	'<i>click2select: to be translated</i>',

'click2prodname'=>
	'<i>click2prodname: to be translated</i>',

'click2select2' =>
        '<i>Click here for more product detail</i>',

'back2cat'=>
	'&#171back2cat: to be translated',

'shipinfo'		=>
	'Information sur la livraison<br><i>(si différent des infos'.
	' de facturation)</i>',

'onlinetext'	=>
	'Les commandes en ligne sont éffectuées avec votre carte de '.
         'crédit.  La commande est encryptée.',
'offlinetext'	=> 
	'Vous pouvez imprimer votre commande et la transmettre par '.
        'courrier, fax ou téléphone. '.
	'Vous pouvez payer par carte de crédit ou mandat postal',
	
'noshipcalc'	=> 'Le script pour calculs de transport est manquant.',

'cartcontents'	=> 'Contenu de votre Commande',
'cartempty'	=> 'Aucun Item est en commande!',
'cartmodify'	=>
	'<i>Pour modifier un item, entrer la nouvelle quantitée et '.
        'cliquez<br>'.
	'&quot;Modifier la Commande&quot;.</i><br>'.
	'<i>Pour enlever un item, entrer une quantitée 0 et cliquez<br>'.
	'&quot;Modifier la Commande&quot;.</i><br>',
'cartsubmit'	=> 'Modifier la Commande',
'cartinvmax'	=> '<i>*** quantitée excède inventaire</i>',

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
	'Si vous désirez apporter une contribution volantaire '.
	', S.V.P. entrer le montant ci-dessous et il sera'.
	'ajouté au montant total de votre commande. Merci.',
 
'optviolation'	=>
	'<b><i>Une option requise pour ce produit est manquante. '.
	'S.V.P. Vérifier votre sélection de produits;'.
	'les options requises '.
	'sont identifiés par un <font color="#ff0000"><b>*</b></font>. '.
	'Merci.</i></b>',
'optreqtext'	=> 'optreqtext: to be translated',
'emptyopt'		=> 'emptyopt: to be translated',
'reqtext'		=> '<font color="#ff0000"><b>*</b> <i>= requis</i></font>',
'reqflag'		=> '<font color="#ff0000"><b>*</b></font>',
'choosezone'	=> 'Choix du Catalogue',
'chooselang'	=> 'Choix de la Langue',
'choosecat'		=> 'Choix de la Catégorie',
'selectcat'		=> '[Votre Choix]',
'choosekey'		=> 'Recherche dans cette catégorie',
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
'submitgeo'		=> 'Sélection de Votre Région',
'prodinfo'		=> 'Achat de Produits',
'orderinfo'		=> 'Information sur la Commande',
'billinfo'		=> 'Information sur la Facturation: ',
'creditinfo'	=> 'Information sur la Carte de Crédit',
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
'longadd'		=> 'Ajouter à la commande',
'shortadd'		=> 'J&rsquo;achète &radic;',
'quantity'		=> 'Quantité',
'qty'			=> 'Qtée',
'home'			=> 'Acceuil',
'subcats'		=> 'Sous-catégories:',
'previous'		=> 'Précédent',
'next'			=> 'Suivant',
'searchresult'	=> 'Résultas de la recherche de:',
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
'videosample'	=> 'Extrait Vidéo',
'homepage'		=> '-> Page D&rsquo;acceuil',
'zonehome'		=> 'Page Principale du Catalogue',
'returnpage'	=> 'Retour à la page précédente',
'returnprod'	=> 'Retour à la Page Produits',
'selectctry'	=> 'Sélection de Pays',
'selectgeo'		=> 'Sélection de la Région Géographique',
'checkout'		=> 'Soumettre La Commande',
'contribution'	=> 'Dons En-Ligne',
'contribamount'	=> 'Montant du Don:',
'emailaddr'		=> 'Adresse Courriel',
'salutation'	=> 'Titre<br><i>optionnel</i>',
'saluteopt'		=> '[titre optionnel]',
'firstname'		=> 'Prénom',
'miname'		=> 'I.',
'lastname'		=> 'Nom',
'company'		=> 'Société',
'address'		=> 'Adresse',
'city'			=> 'Ville',
'state'			=> 'Province',
'zip'			=> 'Code Postal',
'country'		=> 'Pays',
'dayphone'		=> 'Téléphone de jour (code, numéro)',
'ccname'		=> 'Nom sur la carte de Crédit',
'ccnumber'		=> 'Numéro de la carte de Crédit',
'cctype'		=> 'Type de Carte de Crédit',
'ccexpire'		=> 'Date d&rsquo;xpiration',
'cvvnumber'		=> 'CVV2',
'termscon'		=> 'termscon: to be translated',
'cvvclosewindow'	=> 'cvvclosewindow: to be translated',
'cvvtext'		=> '<font size="-1">cvvtext yet to be specified</font> ',
'month'			=> 'Mois',
'year'			=> 'Année',
'ordermethod'	=> 'Méthode de Commande',
'ordersubmit'	=> 'Soumettre Votre Commande',
'contribsubmit'	=> 'Soumettre Votre Don',
'dlsubmit'		=> 'Nom e Mot de Passe',
'dlusername'	=> 'Nom:',
'dlpassword'	=> 'Mot de Passe:',
'clearform'		=> 'Éffacer le Formulaire',
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
'jsonoff'    => 'Sélectionnez En Ligne ou Hors Ligne.  Merci!',
'jscontrib'  => 'S.V.P. Sélectionner un montant pour le don.  Merci!',
'jscountry'  => 'S.V.P. Sélectionnez un pays.  Merci!',
'selectsubz' => 'selectsubz: to be translated',
'jsbcountry' => 'S.V.P. Sélectionnez un pays pour la facturation.  Merci!',
'jsscountry' => 'S.V.P. Sélectionnez un pays pour la livraison.  Merci!',
'jsccname'   => 'S.V.P. Entrez le nom qui est sur la carte de crédit.  Merci!',
'jsccnum'    => 'S.V.P. Entrez le numéro de la carte de crédit.   Merci!',
'jscctype'   => 'S.V.P. Entrez le type de la carte de crédit.  Merci!',
'jsccexp'    => 'S.V.P. Entrez la date d&rsquo;expiration de la carte '.
                'de crédit.  Merci!',
'jsbemail'   => 'S.V.P. Entrez votre adresse courriel pour la facturation.  Merci!',
'jsbfname'   => 'S.V.P. Entrez votre prénom pour la facturation.  Merci!',
'jsblname'   => 'S.V.P. Entrez votre nom pour la facturation.  Merci!',
'jsbaddr'    => 'S.V.P. Entrez une adresse de facturation.  Merci!',
'jsbcity'    => 'S.V.P. Entrez la ville de facturation.  Merci!',
'jsbstate'   => 'S.V.P. Entrez une province de facturation.  Merci!',
'jsbzip'     => 'S.V.P. Entrez le code postal pour la facturation.  Merci!',
'jspickone'  =>	'S.V.P. Entrez une catégorie ou un mot clé pour la recherche'.
                '  Merci!',
'jsplaced'		=> 'Merci!  Votre commande est placée!',

// for proddisp{echo,fixed}.php fixed alignment pages
'itemcapfix'	=> 'ITEM         ',
'qtycapfix'		=> 'QTEE      ',
'pricecapfix'	=> 'PRIX     ',
'productcapfix'	=> 'DESCRIPTION',
'basepricefix'	=> '     Prix de Base: ',
'optionfix'		=> '           Option: ',
'qtyfix'		=> '             Qtée: ',
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
'phone'			=> 'Téléphone: ',
'fax'			=> 'FAX: '

);
?>
