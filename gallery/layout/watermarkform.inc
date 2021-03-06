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
 * $Id: watermarkform.inc 15632 2007-01-02 06:01:08Z jenst $
 */

/**
 * @package Layout
 */

global $watermarkForm;
global $wmAlign;
global $wmAlignX;
global $wmAlignY;
global $previewFull;
global $wmName;
global $wmSelect;

$err = "";
if (!isset($wmAlignX) || ($wmAlignX <= 0)) {
    $wmAlignX = 0;
}

if (!isset($wmAlignY) || ($wmAlignY <= 0)) {
    $wmAlignY = 0;
}

if (!isset($wmName)) {
    $wmName = '';
}

if (!isset($wmAlign)) {
    $wmAlign = 9;
}

if (!isset($previewFull)) {
    $previewFull = 0;
}

if (!isset($wmSelect)) {
    $wmSelect = $gallery->app->watermarkSizes;
}

if (!isset($wmAlign)) {
    $wmAlign = 0;
}

echo "\n<p>";

// get a list of watermarks
if ($wmDir = opendir($gallery->app->watermarkDir)) {
    $found = 0;
    $extensions = array ("png", "gif", "tif");
    $extensions_string = "png, gif, tif";
    while ($wmFile = readdir($wmDir)) {
        $matches_ext = 0;
        foreach ($extensions as $ext) {
            if (eregi("\\.$ext$", $wmFile, $regs)) {
                $matches_ext = 1;
            }
        }
        if ($matches_ext) {
        	if(!empty($wmName) && $wmName == $wmFile) {
        		$options[] = array('value' => $wmFile, 'text' => $wmFile, 'selected' => true);
        	}
        	else {
        		$options[] = array('value' => $wmFile, 'text' => $wmFile);
        	}
        }
    }
    closedir($wmDir);
    
    if(sizeof($options) > 0) {
        echo "<b>". _("Watermark:") ."</b>&nbsp;";
        
        array_sort_by_fields($options, 'text', 'desc', false);
        if ($watermarkForm["allowNone"]) {
            $options[] = array('value' => '', 'text' => _("* No Watermark *"), 'selected' => true);
        }
        $options = array_reverse($options);
        echo drawSelect2('wmName', $options, array(), array('prettyPrinting' => true));
    }
    else {
        $err = sprintf(_("No files matching %s found in %s"),
            "<b>" . $extensions_string . "</b>",
            "<b>" . $gallery->app->watermarkDir . "</b>");
        echo gallery_error($err);
    } 
} else {
    echo gallery_error(sprintf (_("Could not open directory %s"), $gallery->app->watermarkDir));
}
?>
</p>

<b><?php echo _("Alignment:") ?></b>
<div align="<?php echo langLeft(); ?>">
<table border="1" width="100%">
<tr>
 <td><input <?php if ($wmAlign==1) echo " checked"; ?> type="radio" name="wmAlign" value="1">
     <?php echo _("Upper&nbsp;Left") ?></td>
 <td><input <?php if ($wmAlign==2) echo " checked"; ?> type="radio" name="wmAlign" value="2">
     <?php echo _("Top") ?></td>
 <td><input <?php if ($wmAlign==3) echo " checked"; ?> type="radio" name="wmAlign" value="3">
     <?php echo _("Upper&nbsp;Right") ?></td>
</tr>
<tr>
 <td><input <?php if ($wmAlign==4) echo " checked"; ?> type="radio" name="wmAlign" value="4">
 	<?php echo _("Left") ?></td>
 <td><input <?php if ($wmAlign==5) echo " checked"; ?> type="radio" name="wmAlign" value="5">
 	<?php echo _("Center") ?></td>
 <td><input <?php if ($wmAlign==6) echo " checked"; ?> type="radio" name="wmAlign" value="6">
     <?php echo _("Right") ?></td>
</tr>
<tr>
 <td><input <?php if ($wmAlign==7) echo " checked"; ?> type="radio" name="wmAlign" value="7">
     <?php echo _("Lower&nbsp;Left") ?></td>
 <td><input <?php if ($wmAlign==8) echo " checked"; ?> type="radio" name="wmAlign" value="8">
     <?php echo _("Bottom") ?></td>
 <td><input <?php if ($wmAlign==9) echo " checked"; ?> type="radio" name="wmAlign" value="9">
     <?php echo _("Lower&nbsp;Right") ?></td>
</tr>
<tr>
  <td colspan="3">
    <input <?php if ($wmAlign==10) echo " checked"; ?> type="radio" name="wmAlign" value="10">
    <?php echo _("Other") ?>
    X=<input type="text" name="wmAlignX" value="<?php echo $wmAlignX ?>">
    Y=<input type="text" name="wmAlignY" value="<?php echo $wmAlignY ?>">
  </td>
</tr>
</table>
</div>
<br>
<?php if ($watermarkForm["askPreview"] && $gallery->user->canViewFullImages($gallery->album)) { ?>
<b><?php echo _("Preview Size") ?>:</b><br>
<table border="0">
<tr>
  <td><input type="radio" name="previewFull" value="0"<?php if ($previewFull==0) echo " checked"; ?>><?php echo _("Resized"); ?></td>
</tr>
<tr>
  <td><input type="radio" name="previewFull" value="1"<?php if ($previewFull==1) echo " checked"; ?>><?php echo _("Full"); ?></td>
</tr>
</table>
<?php } // end if canViewFullImages
?>
<br>
<?php if ($watermarkForm["askRecursive"]) {
    global $recursive;
    print '<input type="checkbox" name="recursive"';
    if (isset($recursive)) {
        echo " checked";
    }
    print ">" . _("Watermark subalbums") . "<br><br>";
}
echo _("Apply watermark to") ?> <select name="wmSelect">
  <option value="0"<?php if ($wmSelect <  1) echo " selected"; ?>><?php echo _("All photos");?></option>
  <option value="1"<?php if ($wmSelect == 1) echo " selected"; ?>><?php echo _("Only sized photos");?></option>
  <option value="2"<?php if ($wmSelect >  1) echo " selected"; ?>><?php echo _("Only full photos");?></option>
</select>
<br>
