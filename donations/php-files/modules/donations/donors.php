<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

// defines
define('ITEMS_PER_PAGE', 10);

// load the locale for this module
locale_load("modules.donations");

// make sure rowstart has a valid value
if (!isset($rowstart1) || !isNum($rowstart1)) $rowstart1 = 0;
$variables['rowstart1'] = $rowstart1;

// get the list of donations and sponsors we want to report
$variables['donate1'] = array();
$result = dbquery("SELECT * FROM ".$db_prefix."donations WHERE donate_type != '2' ORDER BY donate_timestamp DESC");
if (dbrows($result)) {
	while ($data = dbarray($result)) {
		$variables['donate1'][] = $data;
	}
}

// get the list of investments and spending we want to report
$variables['donate2'] = array();
$result = dbquery("SELECT * FROM ".$db_prefix."donations WHERE donate_type = '2' ORDER BY donate_timestamp DESC");
if (dbrows($result)) {
	while ($data = dbarray($result)) {
		$variables['donate2'][] = $data;
	}
}

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'donations.donors', 'template' => 'modules.donations.donors.tpl', 'locale' => "modules.donations");
$template_variables['donations.donors'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>