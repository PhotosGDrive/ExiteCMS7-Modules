<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| wikkawiki, integrated into ExiteCMS by WanWizard[at]exitecms.org     |
+----------------------------------------------------------------------+
| Released under the terms & conditions of v2 of the GNU General Public|
| License. For details refer to the included gpl.txt file or visit     |
| http://gnu.org                                                       |
+----------------------------------------------------------------------+
| $Id::                                                               $|
+----------------------------------------------------------------------+
| Last modified by $Author::                                          $|
| Revision number $Rev::                                              $|
+---------------------------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

/**
 * The Wikka mainscript.
 *
 * This file is called each time a request is made from the browser.
 * Most of the core methods used by the engine are located in the Wakka class.
 * @see Wakka
 * This file was originally written by Hendrik Mans for WakkaWiki
 * and released under the terms of the modified BSD license
 * @see /docs/WakkaWiki.LICENSE
 *
 * @package Wikka
 * @subpackage Core
 * @version 1.1.6.3
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @see /docs/Wikka.LICENSE
 * @filesource
 *
 * @author Hendrik Mans <hendrik@mans.de>
 * @author Jason Tourtelotte <wikka-admin@jsnx.com>
 * @author {@link http://wikkawiki.org/JavaWoman Marjolein Katsma}
 * @author {@link http://wikkawiki.org/NilsLindenberg Nils Lindenberg}
 * @author {@link http://wikkawiki.org/DotMG Mahefa Randimbisoa}
 * @author {@link http://wikkawiki.org/DarTar Dario Taraborelli}
 *
 * @copyright Copyright 2002-2003, Hendrik Mans <hendrik@mans.de>
 * @copyright Copyright 2004-2005, Jason Tourtelotte <wikka-admin@jsnx.com>
 * @copyright Copyright 2006, {@link http://wikkawiki.org/CreditsPage Wikka Development Team}
 *
 * @todo use templating class for page generation;
 * @todo add phpdoc documentation for configuration array elements;
 * @todo	replace $_REQUEST with either $_GET or $_POST (or both if really
 * 			necessary) - #312
 */

// If you need to use this installation with a configuration file outside the
// installation directory uncomment the following line and adapt it to reflect
// the (filesystem) path to where your configuration file is located.
// This would make it possible to store the configuration file outside of the
// webroot, or to share one configuration file between several Wikka Wiki
// installations.
// This replaces the use of the environment variable WAKKA_CONFIG for security
// reasons. [SEC]
#if (!defined('WAKKA_CONFIG')) define('WAKKA_CONFIG','path/to/your/wikka.config.php');

error_reporting (E_ALL ^ E_NOTICE);

if(!defined('ERROR_WAKKA_LIBRARY_MISSING')) define ('ERROR_WAKKA_LIBRARY_MISSING','The necessary file "libs/Wakka.class.php" could not be found. To run Wikka, please make sure the file exists and is placed in the right directory!');
if(!defined('ERROR_WRONG_PHP_VERSION')) define ('ERROR_WRONG_PHP_VERSION', '$_REQUEST[] not found. Wakka requires PHP 4.1.0 or higher!');
if(!defined('ERROR_SETUP_FILE_MISSING')) define ('ERROR_SETUP_FILE_MISSING', 'A file of the installer/ upgrader was not found. Please install Wikka again!');
if(!defined('ERROR_SETUP_HEADER_MISSING')) define ('ERROR_SETUP_HEADER_MISSING', 'The file "setup/header.php" was not found. Please install Wikka again!');
if(!defined('ERROR_SETUP_FOOTER_MISSING')) define ('ERROR_SETUP_FOOTER_MISSING', 'The file "setup/footer.php" was not found. Please install Wikka again!');
if(!defined('ERROR_NO_DB_ACCESS')) define ('ERROR_NO_DB_ACCESS', 'The wiki is currently unavailable.');
if(!defined('PAGE_GENERATION_TIME')) define ('PAGE_GENERATION_TIME', 'Page was generated in %.4f seconds'); // %.4f - generation time in seconds with 4 digits after the dot
if(!defined('WIKI_UPGRADE_NOTICE')) define ('WIKI_UPGRADE_NOTICE', 'This site is currently being upgraded. Please try again later.');

ob_start();

/**
 * Defines the current Wikka version. Do not change the version number or you will have problems upgrading.
 */
if (!defined('WAKKA_VERSION')) define('WAKKA_VERSION', '1.1.6.3');
/**
 * Defines the default cookie name.
 */
if(!defined('BASIC_COOKIE_NAME')) define('BASIC_COOKIE_NAME', 'Wikkawiki');

/**
 * Calculate page generation time.
 */
function getmicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

$tstart = getmicrotime();

/**
 * Include main library if it exists.
 * @see /libs/Wakka.class.php
 */
if (file_exists('libs/Wakka.class.php')) require_once('libs/Wakka.class.php');
else die(ERROR_WAKKA_LIBRARY_MISSING);

// stupid version check
if (!isset($_REQUEST)) die(ERROR_WRONG_PHP_VERSION); // TODO replace with php version_compare

/**
 * Default configuration.
 */
// attempt to derive base URL fragments and whether rewrite mode is enabled (#438)
$t_domain	= $_SERVER['SERVER_NAME'];
$t_port		= $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
$t_request = $_SERVER['REQUEST_URI'];
if (preg_match('@\.php$@', $t_request) && !preg_match('@index\.php$@', $t_request))
{
	$t_request = preg_replace('@/[^.]+\.php@', '/index.php', $t_request);	// handle "overridden" redirect from index.php (or plain wrong file name!)
}
if ( !preg_match('@wakka=@',$_SERVER['REQUEST_URI']) && isset($_SERVER['QUERY_STRING']) && preg_match('@wakka=@',$_SERVER['QUERY_STRING']))
{
	// looks like we got a rewritten request via .htaccess
	$t_query = '';
	$t_request = preg_replace('@'.preg_quote('index.php').'@', '', $t_request);
	$t_rewrite_mode = 1;
}
else
{
	// no rewritten request apparent
	$t_query = '?wakka=';
	$t_rewrite_mode = 0;
}
$wakkaDefaultConfig = array(
	'mysql_host'				=> 'localhost',
	'mysql_database'			=> 'wikka',
	'mysql_user'				=> 'wikka',
	'table_prefix'			=> 'wikka_',

	'root_page'				=> 'HomePage',
	'wakka_name'				=> 'MyWikkaSite',
#	'base_url'				=> 'http://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '').$_SERVER['REQUEST_URI'].(preg_match('/'.preg_quote('wikka.php').'$/', $_SERVER['REQUEST_URI']) ? '?wakka=' : ''),
#	'rewrite_mode'			=> (preg_match('/'.preg_quote('wikka.php').'$/', $_SERVER['REQUEST_URI']) ? '0' : '1'),
	'base_url'				=> 'http://'.$t_domain.$t_port.$t_request.$t_query,
	'rewrite_mode'			=> $t_rewrite_mode,
	'wiki_suffix'			=> '@wikka',

	'action_path'			=> 'actions',
	'handler_path'			=> 'handlers',
	'gui_editor'				=> '1',
	'stylesheet'				=> 'css/wikka.css',

	// formatter and code highlighting paths
	'wikka_formatter_path' 	=> 'formatters',		# (location of Wikka formatter - REQUIRED)
	'wikka_highlighters_path'	=> 'formatters',		# (location of Wikka code highlighters - REQUIRED)
	'geshi_path' 			=> PATH_GESHI,				# (location of GeSHi package)
	'geshi_languages_path' 	=> PATH_GESHI.'/geshi',		# (location of GeSHi language highlighting files)

	'header_action'			=> 'header',
	'footer_action'			=> 'footer',

	'navigation_links'		=> '[[CategoryCategory Categories]] :: PageIndex ::  RecentChanges :: RecentlyCommented',
	'logged_in_navigation_links' => '[[CategoryCategory Categories]] :: PageIndex :: RecentChanges :: RecentlyCommented :: [[UserSettings Change settings]]',

	'referrers_purge_time'	=> '30',
	'pages_purge_time'		=> '0',
	'xml_recent_changes'		=> '10',
	'hide_comments'			=> '0',
	'require_edit_note'		=> '0',		# edit note optional (0, default), edit note required (1) edit note disabled (2)
	'anony_delete_own_comments'	=> '1',
	'public_sysinfo'			=> '0',		# enable or disable public display of system information in SysInfo
	'double_doublequote_html'	=> 'safe',
	'external_link_tail' 		=> '<span class="exttail">&#8734;</span>',
	'sql_debugging'			=> '0',
	'admin_users' 			=> '',
	'admin_email' 			=> '',
	'upload_path' 			=> 'uploads',
	'mime_types' 			=> 'mime_types.txt',

	// code hilighting with GeSHi
	'geshi_header'			=> 'div',				# 'div' (default) or 'pre' to surround code block
	'geshi_line_numbers'		=> '1',			# disable line numbers (0), or enable normal (1) or fancy line numbers (2)
	'geshi_tab_width'		=> '4',				# set tab width
	'grabcode_button'		=> '1',				# allow code block downloading

	'wikiping_server' 		=> '',

	'default_write_acl'		=> '+',
	'default_read_acl'		=> '*',
	'default_comment_acl'		=> '*');

// load config
$wakkaConfig = array();
if (file_exists("wakka.config.php")) rename("wakka.config.php", "wikka.config.php");
#if (!$configfile = GetEnv("WAKKA_CONFIG")) $configfile = "wikka.config.php";
if (defined('WAKKA_CONFIG'))	// use a define instead of GetEnv [SEC]
{
	$configfile = WAKKA_CONFIG;
}
else
{
	$configfile = 'wikka.config.php';
}
if (file_exists($configfile)) include($configfile);

$wakkaConfigLocation = $configfile;
$wakkaConfig = array_merge($wakkaDefaultConfig, $wakkaConfig);

/**
 * Start session.
 */
session_name(md5(BASIC_COOKIE_NAME.$wakkaConfig['wiki_suffix']));
session_start();

// fetch wakka location
/**
 * Fetch wakka location (requested page + parameters)
 *
 * @todo files action uses POST, everything else uses GET #312
 */
#$wakka = $_REQUEST["wakka"];
$wakka = $_GET['wakka']; #312

/**
 * Remove leading slash.
 */
$wakka = preg_replace("/^\//", "", $wakka);

/**
 * Split into page/method.
 *
 * Note this splits at the FIRST / so $method may contain one or more slashes;
 * this is not allowed, and ultimately handled in the Method() method. [SEC]
 */
if (preg_match("#^(.+?)/(.*)$#", $wakka, $matches)) list(, $page, $method) = $matches;
else if (preg_match("#^(.*)$#", $wakka, $matches)) list(, $page) = $matches;
//Fix lowercase mod_rewrite bug: URL rewriting makes pagename lowercase. #135
if ((strtolower($page) == $page) && (isset($_SERVER['REQUEST_URI']))) #38
{
 $pattern = preg_quote($page, '/');
 if (preg_match("/($pattern)/i", urldecode($_SERVER['REQUEST_URI']), $match_url))
 {
  $page = $match_url[1];
 }
}

/**
 * Create Wakka object
 */
$wakka = new Wakka($wakkaConfig);

/**
 * Check for database access.
 */
if (!$wakka->dblink)
{
	echo "<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish a connection to MySQL</b><br />".ERROR_NO_DB_ACCESS."</div>";
	require_once PATH_THEME."/theme.php";
}
else
{
	/**
	 * auto login if logged-in to ExiteCMS
	 */
	if (iMEMBER)
	{
		$userrec = $wakka->LoadSingle("select * from ".$wakka->config["table_prefix"]."users where name = '".$userdata['user_name']."' limit 1");
		if (!$userrec) {
			$wakka->Query("INSERT INTO ".$wakka->config['table_prefix']."users SET ".
				"signuptime = now(), ".
				"name = '".$userdata['user_name']."', ".
				"email = '".mysqli_real_escape_string($wakka->dblink, $userdata['user_email'])."'");
		}
		$userrec = $wakka->LoadSingle("select * from ".$wakka->config["table_prefix"]."users where name = '".$userdata['user_name']."' limit 1");
		$wakka->SetUser($userrec);
	} else {
		// make sure we're logged out
		$wakka->LogoutUser();
	}

	$headerparms = '	<link rel="stylesheet" type="text/css" href="'.$wakka->GetConfigValue("stylesheet").'" />'.
		'<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />';
	if ($wakka->GetMethod() != 'edit') {
		$headerparms .= "\n\t".'<link rel="alternate" type="application/rss+xml" title="'.$wakka->GetWakkaName().': revisions for '.$wakka->tag.' (RSS)" href="'.$wakka->Href('revisions.xml', $wakka->tag).'" />';
		$headerparms .= "\n\t".'<link rel="alternate" type="application/rss+xml" title="'.$wakka->GetWakkaName().': recently edited pages (RSS)" href="'.$wakka->Href('recentchanges.xml', $wakka->tag).'" />'."\n";
	}
	define('PAGETITLE', $wakkaConfig['wakka_name'].": ".$page);

	/**
	 * Run the engine.
	 */
	$wakka->Run($page, $method);
	if (!preg_match("/(xml|raw|mm|grabcode)$/", $method))
	{
		$tend = getmicrotime();
		//calculate the difference
		$totaltime = ($tend - $tstart);
		//output result
	//	print '<div class="smallprint">'.sprintf(PAGE_GENERATION_TIME, $totaltime)."</div>\n";
	}

	$content =  ob_get_contents();
	$page_output = $content;
	$page_length = strlen($page_output);

	// header("Cache-Control: pre-check=0");
	header("Cache-Control: no-cache");
	// header("Pragma: ");
	// header("Expires: ");

	$etag =  md5($content);
	header('ETag: '.$etag);

	// header('Content-Length: '.$page_length);
	ob_end_clean();

	/**
	 * Output the page.
	 */
	if (substr($_SERVER["QUERY_STRING"], -4) == ".xml") {

		echo $page_output;

	} else {

		$variables['html'] = $page_output;
		$template_panels[] = array('type' => 'body', 'title' => $wakkaConfig['wakka_name'], 'name' => 'wiki', 'template' => '_custom_html.tpl');
		$template_variables['wiki'] = $variables;

		require_once PATH_THEME."/theme.php";
	}
}
?>
