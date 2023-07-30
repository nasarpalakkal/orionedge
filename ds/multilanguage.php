<?php
error_reporting(E_ALL | E_STRICT);
$RELanguage=$_REQUEST['lanR'];
if($RELanguage=="")
{
$RELanguage=$_SESSION['lang'];
}
else
{
$RELanguage=$_REQUEST['lanR'];
}
$_SESSION['lang']=$RELanguage;

/*$locale = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : $RELanguage;
if( substr(php_uname(), 0, strpos(php_uname()," ") ) == "Windows" ) {
 $locale = str_replace('_','-',$locale ); }
$encoding = 'UTF-8'; 
if( substr(php_uname(), 0, strpos(php_uname()," ") ) == "Windows" ) {
 putenv("LC_ALL=$locale");
 T_setlocale(LC_MESSAGES, $locale); } 
else {
 putenv("LC_ALL=$locale" . "." . "$encoding"); 
 T_setlocale(LC_MESSAGES, $locale . "." . $encoding); };

$LOCALE_DIR='lang/locale';
$domain = 'messages';
T_bindtextdomain($domain, $LOCALE_DIR);
T_bind_textdomain_codeset($domain, $encoding);
T_textdomain($domain);
*/
// define constants
define('PROJECT_DIR', realpath('./'));
define('LOCALE_DIR', 'D:/wamp64/www/websoft/ds/lang');
define('DEFAULT_LOCALE', $RELanguage);

require_once('lang/gettext.inc');

$supported_locales = array('en_US', 'sr_CS', 'de_CH','es_ES','ar_SA');
$encoding = 'UTF-8';

$locale = (isset($_GET['lang']))? $_GET['lang'] : $RELanguage;

//var_dump($locale);die();

// gettext setup
T_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'
$domain = 'messages';
bindtextdomain($domain, LOCALE_DIR);
// bind_textdomain_codeset is supported only in PHP 4.2.0+
if (function_exists('bind_textdomain_codeset'))
bind_textdomain_codeset($domain, $encoding);
textdomain($domain);
?>