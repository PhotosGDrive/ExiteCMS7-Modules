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
| $Id:: file_downloads.php 2229 2009-06-23 13:41:33Z WanWizard        $|
+----------------------------------------------------------------------+
| Last modified by $Author:: WanWizard                                $|
| Revision number $Rev:: 2229                                         $|
+---------------------------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";

// make sure we have json_encode and json_decode available
require_once dirname(__FILE__)."/../../includes/json_include.php";

// function to fetch a directory
function fetchfiletree($root, $path, $id) {
	global $settings;

	// storage for the results
	$results = array();

	// read the directory
	if ($handle = opendir($root.$path)) {
		// loop through all entries in this directory
		while ( FALSE !== ( $file = readdir($handle) ) )
		{
			// skip hidden files and directory entries
			if (strncmp($file, '.', 1) == 0 OR ($file == '.' OR $file == '..')) {
				continue;
			}
			// is this a directory
			if (@is_dir($root.$path.$file)) {
				$results[] = array(
								"name" => $file,
								"date" => filemtime($root.$path.$file),
								"tree" => fetchfiletree($root, $path.$file."/", $id)
							);
			} else {
				$results[] = array(
								"name" => $file,
								"size" => filesize($root.$path.$file),
								"date" => filemtime($root.$path.$file),
								"link" => $settings["siteurl"]."modules/file_downloads/file_downloads.php?fd_id=".$id."&dir=".rtrim($path,"/")."&file=".$file
							);
			}
		}
	}
	// close the directory handle
	closedir($handle);

	// return the results
	return $results;
}

// temp storage for template variables
$variables = array();

// is this a json call?
if (is_ajax_call()) {

	// get the available categories
	$result = dbquery("SELECT * FROM ".$db_prefix."file_downloads WHERE ".($fd_id != 0 ? "fd_id = '$fd_id' AND " : "").groupaccess('fd_group')." ORDER BY fd_order");

	while ($data = dbarray($result)) {
		// fetch the tree for this category
		$data["tree"] = fetchfiletree($data["fd_path"], "/", $data["fd_id"]);
		// store this category
		$variables[] = array("id" => $data["fd_id"], "name" => $data["fd_name"], "tree" => $data["tree"]);
	}
}

// make sure the page isn't cached
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Type:application/json; charset=utf-8");
header("Pragma: no-cache");

echo json_encode($variables);

// flush any session info
session_clean_close();

// close the database connection
mysql_close();
?>
