<?
$lang_inc=1;
$fc_functions=!empty($fc_functions) ? (int)$fc_functions : 0;

// include this file after cartid.php, as cartid.php sets $lang_iso
$language_files = array(
	'eng'  =>  'lang_eng.php',
	'fra'  =>  'lang_fra.php',
	'ita'  =>  'lang_ita.php',
	'ger'  =>  'lang_ger.php',
	'nld'  =>  'lang_nld.php',
	'nor'  =>  'lang_nor.php',
	'pol'  =>  'lang_pol.php',
	'por'  =>  'lang_por.php',
	'spa'  =>  'lang_spa.php'
);

$language_names = array(
	'eng'  =>  'English',
	'fra'  =>  'French',
	'ita'  =>  'Italian',
	'ger'  =>  'German',
	'nld'  =>  'Dutch',
	'nor'  =>  'Norwegian',
	'pol'  =>  'Polish',
	'por'  =>  'Portuguese',
	'spa'  =>  'Castillian Spanish'
);

// function to return the indicated prompt
function fc_text($msg){
	global $fc_prompt;
	return $fc_prompt[$msg];
}

// set the default language if undefined by langtable
if( empty($lang_iso) ){
	$lang_iso='eng';
}

$lang_file = '';
if( empty($no_lang_iso) && $fc_functions ){
	$lang_file = 'BITCART_PKG_PATHlanguages/'.$language_files["$lang_iso"];
}elseif( empty($no_lang_iso) ){
	$lang_file = './languages/'.$language_files["$lang_iso"];
}
if( $lang_file ){
	include($lang_file);
}
?>
