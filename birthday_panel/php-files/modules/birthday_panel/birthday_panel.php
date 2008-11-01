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
| $Id:: viewpage.php 1935 2008-10-29 23:42:42Z WanWizard              $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 1935                                         $|
+---------------------------------------------------------------------*/
if (eregi("birthday_panel.php", $_SERVER['PHP_SELF']) || !defined('INIT_CMS_OK')) die();

// load the locale for this panel
locale_load("modules.birthday_panel");

// array's to store the variables for this panel
$variables = array();

$localtime = time() + (isset($userdata['user_offset']) ? $userdata['user_offset'] : 0) * 60 * 60;
$result = dbquery("SELECT user_id, user_name, user_birthdate, YEAR(CURDATE())-YEAR(user_birthdate) as age FROM ".$db_prefix."users WHERE DAYOFMONTH(user_birthdate) = ".date('j' ,$localtime)." AND MONTH(user_birthdate) = ".date('m', $localtime)." ORDER BY user_name");

$variables['count'] = dbrows($result);

$variables['birthdays'] = array();
if ($variables['count']) {
	while ($data = dbarray($result)) {
		$variables['birthdays'][] = $data;
	}
}
$template_variables['modules.birthday_panel'] = $variables;
?>
