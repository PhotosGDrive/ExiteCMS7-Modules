<?php
/*---------------------------------------------------------------------+
| ExiteCMS Content Management System                                   |
+----------------------------------------------------------------------+
| Copyright 2006-2008 Exite BV, The Netherlands                        |
| for support, please visit http://www.exitecms.org                    |
+----------------------------------------------------------------------+
| Some code derived from PHP-Fusion, copyright 2002 - 2006 Nick Jones  |
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
if (!checkrights("I") || !defined("iAUTH") || $aid != iAUTH || !defined('INIT_CMS_OK')) fallback(BASEDIR."index.php");

/*---------------------------------------------------+
| Module identification                              |
+----------------------------------------------------*/
$mod_title = "Download Statistics";
$mod_description = "Gather and display download statistics from download mirror logs. includes a Google Map with downloaders per country";
$mod_version = "1.1.6";
$mod_developer = "WanWizard";
$mod_email = "wanwizard@exitecms.org";
$mod_weburl = "http://www.exitecms.org/";
$mod_type = "M";

/*---------------------------------------------------+
| Module administration panel installation details   |
+----------------------------------------------------*/

$mod_folder = "download_statistics";
$mod_admin_image = "dlstats.gif";
$mod_admin_panel = "admin.php";
$mod_admin_rights = "wS";
$mod_admin_page = 4;

/*---------------------------------------------------+
| Version and revision control                       |
+----------------------------------------------------*/

// check for a minumum version of the ExiteCMS engine
if (str_replace(".", "", $settings['version']) < 720) {
	$mod_errors .= sprintf($locale['mod001'], '7.20');
}
// check for a maximum version of the ExiteCMS engine
if (str_replace(".", "", $settings['version']) > 730) {
	$mod_errors .= sprintf($locale['mod002'], '7.30');
}
// check for a specific revision number range that is supported
if ($settings['revision'] < 0 || $settings['revision'] > 999999) {
	$mod_errors .= sprintf($locale['mod003'], 0, 999999);
}

/*---------------------------------------------------+
| Menu entries for this module                       |
+----------------------------------------------------*/

$mod_site_links = array();
$mod_site_links[] = array('name' => 'GeoMap', 'url' => 'geomap.php', 'panel' => '', 'visibility' => 101);

/*---------------------------------------------------+
| Report entries for this module                     |
+----------------------------------------------------*/

$mod_report_links = array();
$mod_report_links[] = array('name' => "topfiles", 'title' => "dls800", 'version' => "1.0.0", 'visibility' => 102);

/*---------------------------------------------------+
| Search entries for this module                     |
+----------------------------------------------------*/

$mod_search_links = array();
$mod_search_links[] = array('name' => "files", 'title' => "dls850", 'version' => "1.0.0", 'visibility' => 102);

/*---------------------------------------------------+
| locale strings for this module                     |
+----------------------------------------------------*/

$localestrings = array();
$localestrings['en'] = array();
$localestrings['en']['dls110'] = "The Download Statistics module is not installed. You have to do this before starting the download statistics processor";
// Geo Mapping using Google Maps panel
$localestrings['en']['dls400'] = "Geographical dispersion of users";
// Admin panel
$localestrings['en']['dls500'] = "Download Statistics Configuration";
$localestrings['en']['dls501'] = "GeoMap filename match regex:";
$localestrings['en']['dls502'] = "IP addresses of downloaded files that match the regex will be added to the GeoMap. Blank matches ALL files!";
$localestrings['en']['dls503'] = "Location of the log files:";
$localestrings['en']['dls504'] = "Path is relative to the docroot. Start with a / to use an absolute path";
$localestrings['en']['dls505'] = "Save";
$localestrings['en']['dls506'] = "No";
$localestrings['en']['dls507'] = "Yes";
$localestrings['en']['dls508'] = "External download counter updates:";
$localestrings['en']['dls509'] = "If No, downloads are counted by the download section, when the user clicks on a link. If Yes, the batch program like 'get_statistics.php' has to update the counters";
$localestrings['en']['dls510'] = "Access to download statistics for:";
$localestrings['en']['dls511'] = "Google Maps API key:";
$localestrings['en']['dls512'] = "Click <a href='http://code.google.com/apis/maps/signup.html' target='_blank'>here</a> to sign up for a key.";
$localestrings['en']['dls513'] = "Download Statistics Panel Title";
$localestrings['en']['dls514'] = "Used as title of the statistics panel. You can use %s as a placeholder for the total download count";
// Statistics counter panel
$localestrings['en']['dls600'] = "Download Statistics Panel";
$localestrings['en']['dls601'] = "Short Name";
$localestrings['en']['dls602'] = "Order";
$localestrings['en']['dls603'] = "Options";
$localestrings['en']['dls604'] = "There are no statistics counter entries defined at the moment";
$localestrings['en']['dls605'] = "Add";
$localestrings['en']['dls606'] = "Move this entry up";
$localestrings['en']['dls607'] = "Move this entry down";
$localestrings['en']['dls608'] = "Edit this statistics entry";
$localestrings['en']['dls609'] = "Delete this statistics entry";
$localestrings['en']['dls610'] = "Name:";
$localestrings['en']['dls611'] = "Description:";
$localestrings['en']['dls612'] = "Link to this download item:";
$localestrings['en']['dls613'] = "Use the counters from these download files:";
$localestrings['en']['dls614'] = "Save";
$localestrings['en']['dls615'] = "These are the files currently downloaded. Click on a filename to add it to the list:";
$localestrings['en']['dls616'] = "Download Statistics Counter";
$localestrings['en']['dls617'] = "Include the counter of this item in the count:";
// Messages: reports
$localestrings['en']['dls800'] = "Top files downloaded";
$localestrings['en']['dls801'] = "Filenames filter";
$localestrings['en']['dls802'] = "This filter is a <a href='http://www.google.com/support/googleanalytics/bin/answer.py?hl=en&answer=55582' target='_blank'>regexp</a>";
$localestrings['en']['dls803'] = "Show me";
$localestrings['en']['dls804'] = "the top";
$localestrings['en']['dls805'] = "All";
$localestrings['en']['dls806'] = "results in the report, and sort it";
$localestrings['en']['dls807'] = "Ascending";
$localestrings['en']['dls808'] = "Descending";
$localestrings['en']['dls809'] = "Error in regexp:";
$localestrings['en']['dls810'] = "Filename";
$localestrings['en']['dls811'] = "Download Count";
// Messages: searches
$localestrings['en']['dls850'] = "Downloaded files";
// Messages: geomap
$localestrings['en']['dls900'] = "<center>No valid Google Maps key found for the URL: %s!</center>";
$localestrings['en']['dls901'] = "A total of %u users could be mapped.";
$localestrings['en']['dls902'] = "To see more detailed information about a specific country, move your mouse over the marker";
$localestrings['en']['dls903'] = "Sorry, the Google Maps API is not compatible with this browser";
$localestrings['en']['dls904'] = "For %u user, no location could be determined";
$localestrings['en']['dls905'] = "For %u users, no location could be determined";
// Messages: admin
$localestrings['en']['dls910'] = "Group selection is invalid.";
$localestrings['en']['dls911'] = "Regular expression is invalid. Error is: ";
$localestrings['en']['dls912'] = "Specified log path does not exist.";
$localestrings['en']['dls913'] = "No write access to the specified log path.";
// Messages: Statistics counter panel
$localestrings['en']['dls920'] = "Statistics entry succesfully deleted.";
$localestrings['en']['dls921'] = "Statistics configuration is saved.";
$localestrings['en']['dls922'] = "The requested statistics ID does not exist.";
$localestrings['en']['dls923'] = "Result:";
$localestrings['en']['dls924'] = "Statistics counter entry succesfully added.";
$localestrings['en']['dls925'] = "Statistics counter entry succesfully updated.";
$localestrings['en']['dls926'] = "You have to supply a short name for this statistics counter";
$localestrings['en']['dls927'] = "You have to supply a description for this statistics counter";
$localestrings['en']['dls928'] = "You have to select either a download item or enter filename(s), or both";
$localestrings['en']['dls929'] = "Are you sure you want to delete this statistics counter?";
// Messages: Reports
$localestrings['en']['dls950'] = "The report could not be generated:";

$localestrings['nl'] = array();
$localestrings['nl']['dls110'] = "De Download Statistics module is niet geinstalleerd. Dit moet gebeuren voordat deze processor gebruikt kan worden.";
// Geo Mapping using Google Maps panel
$localestrings['nl']['dls400'] = "Geografische verdeling van de gebruikers";
// Admin panel
$localestrings['nl']['dls500'] = "Download statistieken configuratie";
$localestrings['nl']['dls501'] = "GeoMap bestandsnaam regex:";
$localestrings['nl']['dls502'] = "IP addressen van gedownloade bestanden die voldoen aan deze reguliere expressie zullen aan de GeoMap worden toegevoegd. Blanco betekent ALLE bestanden!";
$localestrings['nl']['dls503'] = "Locatie van de log bestanden:";
$localestrings['nl']['dls504'] = "Pad is relatief ten opzichte van de webroot. Start met een / om een absoluut pad te gebruiken";
$localestrings['nl']['dls505'] = "Bewaren";
$localestrings['nl']['dls506'] = "Nee";
$localestrings['nl']['dls507'] = "Ja";
$localestrings['nl']['dls508'] = "Download teller extern bijwerken:";
$localestrings['nl']['dls509'] = "Bij Nee worden downloads geteld als de gebruiker klikt op een link in de download sectie. Bij Ja moet er een extern programma als 'get_statistics.php' gebruikt worden.";
$localestrings['nl']['dls510'] = "Toegang tot de statistieken voor:";
$localestrings['nl']['dls511'] = "Google Maps API key:";
$localestrings['nl']['dls512'] = "Klik <a href='http://code.google.com/apis/maps/signup.html' target='_blank'>hier</a> om u in te schrijven bij Google Maps.";
$localestrings['nl']['dls513'] = "Titel download statistieken";
$localestrings['nl']['dls514'] = "Gebruikt als titel voor het statistieken paneel. U kunt %s gebruiken om het totaal aantal downloads in te voegen.";
// Statistics counter panel
$localestrings['nl']['dls600'] = "Download statistieken";
$localestrings['nl']['dls601'] = "Korte naam";
$localestrings['nl']['dls602'] = "Volgorde";
$localestrings['nl']['dls603'] = "Opties";
$localestrings['nl']['dls604'] = "Er zijn geen statistiek tellers gedefinieerd op dit moment";
$localestrings['nl']['dls605'] = "Toevoegen";
$localestrings['nl']['dls606'] = "Verplaats deze regel naar boven";
$localestrings['nl']['dls607'] = "Verplaats deze regel naar beneden";
$localestrings['nl']['dls608'] = "Wijzig deze definitie";
$localestrings['nl']['dls609'] = "Verwijder deze definitie";
$localestrings['nl']['dls610'] = "Naam:";
$localestrings['nl']['dls611'] = "Omschrijving:";
$localestrings['nl']['dls612'] = "Verbind met dit download item:";
$localestrings['nl']['dls613'] = "Gebruik de tellers van deze gedownloade bestanden:";
$localestrings['nl']['dls614'] = "Bewaren";
$localestrings['nl']['dls615'] = "Dit zijn de bestanden die tot op heden gedownload zijn. Klik op een bestandsnaam om deze aan de lijst toe te voegen:";
$localestrings['nl']['dls616'] = "Download statistieken teller";
$localestrings['nl']['dls617'] = "De teller van dit item meetellen in het totaal:";
// Messages: reports
$localestrings['nl']['dls800'] = "Top gedownloade bestanden";
$localestrings['nl']['dls801'] = "Bestandsnaam filter";
$localestrings['nl']['dls802'] = "Dit is een <a href='http://www.google.com/support/googleanalytics/bin/answer.py?hl=nl&answer=55582' target='_blank'>reguliere expressie</a>";
$localestrings['nl']['dls803'] = "Toon mij";
$localestrings['nl']['dls804'] = "de top";
$localestrings['nl']['dls805'] = "alle";
$localestrings['nl']['dls806'] = "resultaten in het rapport, en sorteer het";
$localestrings['nl']['dls807'] = "Oplopend";
$localestrings['nl']['dls808'] = "Aflopend";
$localestrings['nl']['dls809'] = "Fout in reguliere expressie:";
$localestrings['nl']['dls810'] = "Bestandsnaam";
$localestrings['nl']['dls811'] = "Download teller";
// Messages: searches
$localestrings['nl']['dls850'] = "Gedownloade bestanden";
// Messages: geomap
$localestrings['nl']['dls900'] = "<center>Geen valide Google Maps key gevonden voor de URL: %s!</center>";
$localestrings['nl']['dls901'] = "In totaal konden %u gebruikers worden gemapt.";
$localestrings['nl']['dls902'] = "Plaats uw muis op een markering om meer informatie te zien over een bepaald land";
$localestrings['nl']['dls903'] = "Sorry, de Google Maps API is niet compatibel met uw browser";
$localestrings['nl']['dls904'] = "Voor %u gebruiker kon geen locatie worden bepaald";
$localestrings['nl']['dls905'] = "Voor %u gebruikers kon geen locatie worden bepaald";
// Messages: admin
$localestrings['nl']['dls910'] = "Groep selectie is niet correct.";
$localestrings['nl']['dls911'] = "Reguliere expressie is foutief. De fout is: ";
$localestrings['nl']['dls912'] = "Opgegeven log pad naam bestaat niet.";
$localestrings['nl']['dls913'] = "Geen schrijftoegang tot het opgegeven pad.";
// Messages: Statistics counter panel
$localestrings['nl']['dls920'] = "Statistiek definitie is verwijderd.";
$localestrings['nl']['dls921'] = "Statistiek definitie is bewaard.";
$localestrings['nl']['dls922'] = "Het gevraagde statistiek ID bestaat niet.";
$localestrings['nl']['dls923'] = "Resultaat:";
$localestrings['nl']['dls924'] = "Statistiek definitie succesvol toegevoegd.";
$localestrings['nl']['dls925'] = "Statistiek definitie succesvol bijgewerkt.";
$localestrings['nl']['dls926'] = "U dient een korte naam in te geven voor deze definitie";
$localestrings['nl']['dls927'] = "U dient een omschrijving in te geven voor deze definitie";
$localestrings['nl']['dls928'] = "U moet of een download item, of bestandsnamen selecteren. Of beide.";
$localestrings['nl']['dls929'] = "Weet u zeker dat u deze definitie wilt verwijderen?";
// Messages: Reports
$localestrings['nl']['dls950'] = "Het rapport kon niet worden aangemaakt:";

/*---------------------------------------------------+
| commands to execute when installing this module    |
+----------------------------------------------------*/

$mod_install_cmds = array();							// commands to execute when installing this module

// add config entries for this module
$mod_install_cmds[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('dlstats_access', '103')");
$mod_install_cmds[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('dlstats_geomap_regex', '/software\.ver$/i')");
$mod_install_cmds[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('dlstats_logs', 'modules/download_statistics/batch/logs')");
$mod_install_cmds[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('dlstats_remote', '0')");
$mod_install_cmds[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('dlstats_google_api_key', '')");
$mod_install_cmds[] = array('type' => 'db', 'value' => "INSERT INTO ##PREFIX##configuration (cfg_name, cfg_value) VALUES ('dlstats_title', '')");

// Geomap information table
$mod_install_cmds[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##dlstats_ips (
  dlsi_id int(10) unsigned NOT NULL auto_increment,
  dlsi_ip char(15) NOT NULL default '0.0.0.0',
  dlsi_ccode char(2) NOT NULL default '',
  dlsi_onmap tinyint(1) unsigned NOT NULL default 0,
  dlsi_counter int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (dlsi_id),
  UNIQUE KEY dlsi_ip_cc(dlsi_ip, dlsi_ccode),
  KEY dlsi_onmap (dlsi_ccode, dlsi_onmap),
  KEY dlsi_ccode (dlsi_ccode)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

// statistics per file table
$mod_install_cmds[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##dlstats_files (
  dlsf_id int(10) unsigned NOT NULL auto_increment,
  dlsf_file varchar(255) NOT NULL default '',
  dlsf_success tinyint(1) unsigned NOT NULL default 0,
  dlsf_counter int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (dlsf_id),
  UNIQUE KEY dlsf_file (dlsf_file)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

// statistics per ip per file table
$mod_install_cmds[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##dlstats_file_ips (
  dlsf_id int(10) unsigned NOT NULL default 0,
  dlsi_id int(10) unsigned NOT NULL default 0,
  dlsfi_timestamp int(10) unsigned NOT NULL default 0,
  KEY dlsf_id (dlsf_id),
  KEY dlsi_id (dlsi_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

// Statistics counters table
$mod_install_cmds[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##dlstats_counters (
  dlsc_id SMALLINT(5) UNSIGNED NOT NULL auto_increment,
  dlsc_name VARCHAR(10) NOT NULL default '',
  dlsc_description VARCHAR(100) NOT NULL default '',
  dlsc_download_id SMALLINT(5) UNSIGNED NOT NULL default 0,
  dlsc_count_id TINYINT(1) UNSIGNED NOT NULL default 1,
  dlsc_files MEDIUMTEXT NOT NULL,
  dlsc_order SMALLINT(5) UNSIGNED NOT NULL default 0,
  PRIMARY KEY (dlsc_id)
) ENGINE = MYISAM DEFAULT CHARSET=utf8");

// statistics file cache table (to detect retries)
$mod_install_cmds[] = array('type' => 'db', 'value' => "CREATE TABLE ##PREFIX##dlstats_fcache (
  dlsfc_ip char(15) NOT NULL default '0.0.0.0',
  dlsfc_file varchar(255) NOT NULL default '',
  dlsfc_timeout int(10) unsigned NOT NULL default 0,
  UNIQUE KEY dlsfc_file (dlsfc_ip, dlsfc_file)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$mod_install_cmds[] = array('type' => 'function', 'value' => "install_dlstats");

/*---------------------------------------------------+
| commands to execute when uninstalling this module  |
+----------------------------------------------------*/

$mod_uninstall_cmds = array();							// commands to execute when uninstalling this module

// delete config entries
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_access'");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_geomap_regex'");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_logs'");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_remote'");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_google_api_key'");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_title'");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DELETE FROM ##PREFIX##configuration WHERE cfg_name = 'dlstats_history'");

// delete the tables
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##dlstats_ips");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##dlstats_files");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##dlstats_file_ips");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##dlstats_counters");
$mod_uninstall_cmds[] = array('type' => 'db', 'value' => "DROP TABLE ##PREFIX##dlstats_fcache");

$mod_uninstall_cmds[] = array('type' => 'function', 'value' => "uninstall_dlstats");

/*---------------------------------------------------+
| function for special installations                 |
+----------------------------------------------------*/
if (!function_exists('install_dlstats')) {
	function install_dlstats() {
	}
}
/*---------------------------------------------------+
| function for special de-installations              |
+----------------------------------------------------*/
if (!function_exists('uninstall_dlstats')) {
	function uninstall_dlstats() {
	}
}

/*---------------------------------------------------+
| function to upgrade from a previous revision       |
+----------------------------------------------------*/
if (!function_exists('module_upgrade')) {
	function module_upgrade($current_version) {
		global $db_prefix;

		switch ($current_version) {
			case "1.1.0":
			case "1.1.1":
			case "1.1.2":
			case "1.1.3":
				// add the dlsc_count_id if it doesn't exist
				$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."dlstats_counters LIMIT 1"));
				if (is_array($data) && !isset($data['dlsc_count_id'])) {
					$result = dbquery("ALTER TABLE ".$db_prefix."dlstats_counters ADD dlsc_count_id TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER dlsc_download_id");
				}
				break;
			case "1.1.4":			// upgrade to ExiteCMS v7.2
			case "1.1.5":			// upgrade to ExiteCMS v7.3
			case "1.1.6":			// current release version
				break;
			default:
				terminate("invalid current version number passed to module_upgrade()!");
		}
	}
}
?>
