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
 * $Id: popup.php 15632 2007-01-02 06:01:08Z jenst $
 */
?>
<?php
function build_popup_url($url, $url_is_complete=0) {
	/* Separate the target from the arguments */
	$result = explode('?', $url);
	$target = $result[0];
	if (isset($result[1])) {
		$arglist = $result[1];
	} else {
		$arglist = '';
	}

	/* Parse the query string arguments */
	$args = array();
	parse_str($arglist, $args);
	$args['gallery_popup'] = 'true';

	if (!$url_is_complete) {
		$url = makeGalleryUrl($target, $args);
	}

	return $url;
}

function popup($url, $url_is_complete=0, $height=500,$width=500) {
	// Force int data type
	$height = (int)$height;
	$width = (int)$width;

	$url = build_popup_url($url, $url_is_complete);
	return popup_js($url, "Edit","height=$height,width=$width,location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes");
}


function popup_js($url, $window, $attrs) {
	if (preg_match("~^http|^ftp|&amp;~", $url)) {
		$url = "'$url'";
	}

	return "nw=window.open($url,'$window','$attrs'); nw.opener=self; return false;";
}

function popup_status($url, $height=150, $width=350) {
	// Force int data type
	$height = (int)$height;
	$width = (int)$width;

	$attrs = "height=$height,width=$width,location=no,scrollbars=no,menubars=no,toolbars=no,resizable=yes";
	return "open('" . unhtmlentities(build_popup_url($url)) . "','Status','$attrs');";
}

function popup_link($title, $url, $url_is_complete=0, $online_only=true, $height=500,$width=500, $cssclass='', $extraJS='') {
	static $popup_counter = 0;
	global $gallery;

	// Force int data type
	$height = (int)$height;
	$width = (int)$width;

	if ( !empty($gallery->session->offline) && $online_only ) {
		return null;
	}

	$cssclass = empty($cssclass) ? '' : "class=\"$cssclass\"";

	$popup_counter++;

	$link_name = "popuplink_".$popup_counter;
	$url = build_popup_url($url, $url_is_complete);

	$a1 = "<a $cssclass style=\"white-space:nowrap;\" id=\"$link_name\" target=\"Edit\" href=\"$url\" onClick=\"javascript:".
		$extraJS .
		popup_js("document.getElementById('$link_name').href", "Edit",
		"height=$height,width=$width,location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes") .
		"\">$title</a>";

	return "$a1";
}
?>
