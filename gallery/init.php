<?php
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2007 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * $Id: init.php 15632 2007-01-02 06:01:08Z jenst $
*/
?>
<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/../../includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// Hack Prevention
$sensitiveList = array('gallery', 'GALLERY_EMBEDDED_INSIDE', 'GALLERY_EMBEDDED_INSIDE_TYPE', 'GLOBALS');
foreach ($sensitiveList as $sensitive) {
    if (!empty($_REQUEST[$sensitive])) {
        terminate("Security violation! Override attempt.");
    }
}

// check if this module is installed
if (!isset($settings['gallery_albumdir'])) {
	terminate('The Gallery module for ExiteCMS is not installed. Please do so before trying to use it.');
}

// start capturing all output
ob_start();

/*
*  Seed the randomization pool once, instead of doing it every place
*  that we use rand() or mt_rand()
*/
mt_srand((double) microtime() * 1000000);

global $gallery;
require(dirname(__FILE__) . "/Version.php");
require(dirname(__FILE__) . "/util.php");

/* Load bootstrap code */
if (getOS() == OS_WINDOWS) {
    include_once(dirname(__FILE__) . "/platform/fs_win32.php");
} else {
    include_once(dirname(__FILE__) . "/platform/fs_unix.php");
}

if (fs_file_exists(dirname(__FILE__) . "/config.php")) {
    include_once(dirname(__FILE__) . "/config.php");

    /* Here we set a default execution time limit for the entire Gallery script
     * the value is defined by the user during setup, so we want it inside the
     * 'if config.php' block.  If the user increases from the default, this will cover
     * potential execution issues on slow systems, or installs with gigantic galleries.
     * By calling set_time_limit() again further in the script (in locations we know can
     * take a long time) we reset the counter to 0 so that a longer execution can occur.
    */
    set_time_limit($gallery->app->timeLimit);
}

/*
 *  We TRY to make sure that register_globals is disabled.  If the user has not disabled
 *  register_globals in their php.ini, we try to emulate its functionality by unsetting all
 *  variables from $_REQUEST.  Some *Nuke systems apparently do not function well with this
 *  emulation, so we have given users a method to opt-out.
 *
 *  WE DO NOT OFFICIALLY SUPPORT THE USE OF skipRegisterGlobals BECAUSE IT COULD POTENTIALLY
 *  OPEN Gallery TO SECURITY RISKS!  This is for advanced users only.
*/
if (empty($gallery->app->skipRegisterGlobals) || $gallery->app->skipRegisterGlobals != "yes") {
    $register_globals = @ini_get('register_globals');
    if (!empty($register_globals) && !preg_match("^(no|off|false)$", $register_globals)) {
        foreach (array_keys($_REQUEST) as $key) {
            unset($$key);
        }
    }
}

// Optional developer hook - location to add useful
// functions such as code profiling modules
if (file_exists(dirname(__FILE__) . "/lib/devel.php")) {
    require_once(dirname(__FILE__) . "/lib/devel.php");
}

if (isset($gallery->app->devMode) && $gallery->app->devMode == 'yes') {
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
}

/*
 * Detect if we're running under SSL and adjust the URL accordingly.
*/
if(isset($gallery->app)) {
    if (isset($_SERVER["HTTPS"] ) && stristr($_SERVER["HTTPS"], "on")) {
        $gallery->app->photoAlbumURL =
            preg_replace("~^http:~i", "https:", $gallery->app->photoAlbumURL);
        $gallery->app->albumDirURL =
            preg_replace("~^http:~i", "https:", $gallery->app->albumDirURL);
    } else {
        $gallery->app->photoAlbumURL =
            preg_replace("~^https:~i", "http:", $gallery->app->photoAlbumURL);
        $gallery->app->albumDirURL =
            preg_replace("~^https:~i", "http:", $gallery->app->albumDirURL);
    }

    /*
     * We have a Coral (http://www.scs.cs.nyu.edu/coral/) request coming in, adjust outbound links
    */
    if(isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'CoralWebPrx')) {
        if (preg_match("~^(http://[^:]+):(\d+)(.*)$~", $gallery->app->photoAlbumURL)) {
            $gallery->app->photoAlbumURL = preg_replace("~^(http://[^:]+):(\d+)(.*)$~", "\1.\2\3", $galllery->app->photoAlbumURL);
        }

        $gallery->app->photoAlbumURL = preg_replace("~^(http://[^/]+)(.*)$~", '\1.nyud.net:8090\2',$gallery->app->photoAlbumURL);
        if (preg_match("~^(http://[^:]+):(\d+)(.*)$~", $gallery->app->albumDirURL)) {
            $gallery->app->albumDirURL = preg_replace("~^(http://[^:]+):(\d+)(.*)$~", "\1.\2\3", $galllery->app->albumDirURL);
        }
        $gallery->app->albumDirURL = preg_replace("~^(http://[^/]+)(.*)$~", '\1.nyud.net:8090\2',$gallery->app->albumDirURL);
    }
}

/*
 * Turn off magic quotes runtime as they interfere with saving and
 * restoring data from our file-based database files
*/
set_magic_quotes_runtime(0);

if (!isset($GALLERY_NO_SESSIONS)) {
    require(dirname(__FILE__) . "/session.php");
}

// We need to init the language before we include the files below, as they contain gettext calls.
initLanguage();

/* Load classes
 * Note: Some classes and libs are loaded in util.php
*/
require(dirname(__FILE__) . "/classes/Album.php");
require(dirname(__FILE__) . "/classes/Image.php");
require(dirname(__FILE__) . "/classes/AlbumItem.php");
require(dirname(__FILE__) . "/classes/AlbumDB.php");
require(dirname(__FILE__) . "/classes/User.php");
require(dirname(__FILE__) . "/classes/EverybodyUser.php");
require(dirname(__FILE__) . "/classes/NobodyUser.php");
require(dirname(__FILE__) . "/classes/LoggedInUser.php");
require(dirname(__FILE__) . "/classes/UserDB.php");
require(dirname(__FILE__) . "/classes/Comment.php");

$gallerySanity = gallerySanityCheck();

/* Make sure that Gallery is set up properly */
if ($gallerySanity != NULL) {
    include_once(dirname(__FILE__) . "/includes/errors/$gallerySanity");
    exit;
}

// ExiteCMS embedding
$GALLERY_EMBEDDED_INSIDE = 'ExiteCMS';
$GALLERY_EMBEDDED_INSIDE_TYPE = 'ExiteCMS';

include_once(dirname(__FILE__) . "/classes/Database.php");
include_once(dirname(__FILE__) . "/classes/database/mysql/Database.php");
include_once(dirname(__FILE__) . "/classes/ExiteCMS/UserDB.php");
include_once(dirname(__FILE__) . "/classes/ExiteCMS/User.php");

$dbhost = $GLOBALS['user_db_host'];
$dbuser = $GLOBALS['user_db_user'];
$dbpasswd = $GLOBALS['user_db_pass'];
$dbname = $GLOBALS['user_db_name'];

$gallery->database{"ExiteCMS"} = new MySQL_Database($dbhost, $dbuser, $dbpasswd, $dbname);
$gallery->database{"prefix"} = $GLOBALS['user_db_prefix'];

/* Load our user database (and user object) */
$gallery->userDB = new ExiteCMS_UserDB;
if (iMEMBER) {
	$gallery->session->username = $GLOBALS['userdata']['user_name'];
	$gallery->user = $gallery->userDB->getUserByUid($GLOBALS['userdata']['user_id']);
} else {
	$gallery->user = $gallery->userDB->getEverybody();
}

/* If there's no specific user, they are the special Everybody user */
if (!isset($gallery->user) || empty($gallery->user)) {
    if (empty($gallery->userDB)) {
        exit("Fatal error: UserDB failed to initialize!");
    }
    $gallery->user = $gallery->userDB->getEverybody();
    $gallery->session->username = "";
}

if (!isset($gallery->session->offline)) {
    $gallery->session->offline = FALSE;
}

/* Load the correct album object */
if (!empty($gallery->session->albumName)) {
    $gallery->album = new Album;
    $ret = $gallery->album->load($gallery->session->albumName);
    if (!$ret) {
        $gallery->session->albumName = "";
    } else {
        if ($gallery->album->versionOutOfDate()) {
            include_once(dirname(__FILE__) . "/upgrade_album.php");
            exit;
        }
    }
}
?>
