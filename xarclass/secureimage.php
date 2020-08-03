<?php
/*********************************
Originally developed by:
---------------------
Securimage 0.3
Portable Security Image Script
Author: Drew Phillips
www.neoprogrammers.com
Copyright 2005 Drew Phillips
---------------------

Modified Extensively By:
------------------------
Carl P. Corliss
 the Digital Development Foundation
 www.ddfoundation.org
 www.xaraya.com
copyright 2006 the Digital Development Foundation
 Jo Dalle Nogare
 http://xarigami.com
 http://2skies.com
 Copyright 2008-2011 2skies.com
------------------------

If you found this script useful, please take a quick moment to
rate it.  http://www.hotscripts.com/rate/49400.html  Thanks.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

********************************/
/**
 * Output images in JPEG format
 */
if (!defined('SI_IMAGE_JPEG'))
  define('SI_IMAGE_JPEG', 1);
/**
 * Output images in PNG format
 */
if (!defined('SI_IMAGE_PNG'))
  define('SI_IMAGE_PNG',  2);
/**
 * Output images in GIF format (not recommended)
 * Must have GD >= 2.0.28!
 */
if (!defined('SI_IMAGE_GIF'))
  define('SI_IMAGE_GIF',  3);

class securimage
{
    //USER CONFIGURATION OF IMAGE
    //See included README.txt for detailed descriptions

    public $image_width = 230;
    //heigh of security image

    public $image_height = 40;
    //width of security image

    public $code_length = 7;
    //how many letters in the code

    public $ttf_file = "modules/formantibot/fonts/elephant.ttf";
    //path to ttf font to use

    public $font_size = 20;
    //size of the font

    public $text_angle_minimum = -20;
    //minimum angle in degress of letter. counter-clockwise direction

    public $text_angle_maximum = 20;
    //maximum angle in degrees of letter. clockwise direction

    public $text_x_start = 9;
    //position (in pixels) on the x axis where text starts

    public $text_minimum_distance = 30;
    //the shortest distance in pixels letters can be from eachother (a very small value will cause overlapping)

    public $text_maximum_distance = 33;
    //the longest distance in pixels letters can be from eachother

    public $image_bg_color = array("red" => 255, "green" => 255, "blue" => 255);
    //images background color.  set each red, green, and blue to a 0-255 value

    public $text_color = array("red" => 128, "green" => 128, "blue" => 255);
    //the color of the text

    public $shadow_text = true;
    //draw a shadow for the text (gives a 3d raised bolder effect)

    public $use_transparent_text = true;
    //true for the ability to use transparent text, false for normal text

    public $text_transparency_percentage = 25;
    //0 to 100, 0 being completely opaque, 100 being completely transparent

    public $draw_lines = TRUE;
    //set to true to draw horizontal and vertical lines on the image
    //the following 3 options will have no effect if this is set to false

    public $line_color = array("red" => 204, "green" =>204, "blue" => 255);
    //color of the horizontal and vertical lines through the image

    public $line_distance = 12;
    //distance in pixels the lines will be from eachother.
    //the smaller the value, the more "cramped" the lines will be, potentially making
    //the text harder to read for people

    public $draw_angled_lines = TRUE;
    //set to true to draw lines at 45 and -45 degree angles over the image  (makes x's)

    public $draw_lines_over_text = true;
    //set to true to draw the lines on top of the text, otherwise the text will be on the lines

    public $prune_minimum_age = 15;
    //age (in minutes) of files containing unused codes to be deleted

    public $hash_salt = "fg7hg3yg3fd90oi4i";
    //set this to a unique string, this prevents users guessing filenames and make data more secure

    public $removeambichars = FALSE;

     public $uppercaseonly = FALSE;
    //END USER CONFIGURATION
    //There should be no need to edit below unless you really know what you are doing

    public $im;
    public $bgimg;
    public $code;
    public $code_entered;
    public $correct_code;


    function __construct()
    {
        $this->setSettings();
        $this->userCodeKey = 'code.' . md5($this->hash_salt . $this->getcurrentip());
    }

    function setSettings()
    {
        $settings = unserialize(xarModGetVar('formantibot', 'settings'));

        foreach ($settings as $name => $value) {
            $this->$name = $value;
        }
    }

    function display($background_image = '')
    {
        if(!empty($background_image) && is_readable($background_image)) {
            $this->bgimg = $background_image;
        }

        $this->createImage();
        $this->sendHeaders();
        $this->sendImage();
    }

    function sendImage()
    {
        imagejpeg($this->im);
        imagedestroy($this->im);
    }

    function createImage()
    {
        if($this->use_transparent_text == TRUE || $this->bgimg != "") {
            $this->im = imagecreatetruecolor($this->image_width, $this->image_height);
            $bgcolor = imagecolorallocate($this->im, $this->image_bg_color['red'], $this->image_bg_color['green'], $this->image_bg_color['blue']);
            imagefilledrectangle($this->im, 0, 0, imagesx($this->im), imagesy($this->im), $bgcolor);
        } else { //no transparency
            $this->im = imagecreate($this->image_width, $this->image_height);
            $bgcolor = imagecolorallocate($this->im, $this->image_bg_color['red'], $this->image_bg_color['green'], $this->image_bg_color['blue']);
        }

        if($this->bgimg != "") { $this->setBackground(); }

        $this->code = $this->generateCode($this->code_length);

        if (!$this->draw_lines_over_text && $this->draw_lines) $this->drawLines();

        $this->drawWord();

        if ($this->draw_lines_over_text && $this->draw_lines) $this->drawLines();

        $this->saveData();
    }

    function setBackground()
    {
        $dat = @getimagesize($this->bgimg);
        if($dat == FALSE) { return; }

        switch($dat[2]) {
            case 1: $newim = @imagecreatefromgif($this->bgimg); break;
            case 2: $newim = @imagecreatefromjpeg($this->bgimg); break;
            case 3: $newim = @imagecreatefrompng($this->bgimg); break;
            case 15: $newim = @imagecreatefromwbmp($this->bgimg); break;
            case 16: $newim = @imagecreatefromxbm($this->bgimg); break;
            default: return;
        }

        if(!$newim) return;

        imagecopy($this->im, $newim, 0, 0, 0, 0, $this->image_width, $this->image_height);
    }

    function drawLines()
    {
        $linecolor = imagecolorallocate($this->im, $this->line_color['red'], $this->line_color['green'], $this->line_color['blue']);

        //vertical lines
        for($x = 1; $x < $this->image_width; $x += $this->line_distance) {
            imageline($this->im, $x, 0, $x, $this->image_height, $linecolor);
        }

        //horizontal lines
        for($y = 11; $y < $this->image_height; $y += $this->line_distance) {
            imageline($this->im, 0, $y, $this->image_width, $y, $linecolor);
        }

        if ($this->draw_angled_lines == TRUE) {
            for ($x = -($this->image_height); $x < $this->image_width; $x += $this->line_distance) {
                imageline($this->im, $x, 0, $x + $this->image_height, $this->image_height, $linecolor);
            }

            for ($x = $this->image_width + $this->image_height; $x > 0; $x -= $this->line_distance) {
                imageline($this->im, $x, 0, $x - $this->image_height, $this->image_height, $linecolor);
            }
        }
    }

    function drawWord()
    {
        if($this->use_transparent_text == TRUE) {
            $alpha = floor($this->text_transparency_percentage / 100 * 127);
            $font_color = imagecolorallocatealpha($this->im, $this->text_color['red'], $this->text_color['green'], $this->text_color['blue'], $alpha);
        } else { //no transparency
            $font_color = imagecolorallocate($this->im, $this->text_color['red'], $this->text_color['green'], $this->text_color['blue']);
        }

        $x = $this->text_x_start;
        $strlen = strlen($this->code);
        $y_min = ($this->image_height / 2) + ($this->font_size / 2) - 2;
        $y_max = ($this->image_height / 2) + ($this->font_size / 2) + 2;

        for($i = 0; $i < $strlen; ++$i) {
            $angle = rand($this->text_angle_minimum, $this->text_angle_maximum);
            $y = rand($y_min, $y_max);
            imagettftext($this->im, $this->font_size, $angle, $x, $y, $font_color, $this->ttf_file, $this->code{$i});
            if($this->shadow_text == TRUE) {
                imagettftext($this->im, $this->font_size, $angle, $x + 2, $y + 2, $font_color, $this->ttf_file, $this->code{$i});
            }
            $x += rand($this->text_minimum_distance, $this->text_maximum_distance);
        }
    }



    function generateCode($len = 6)
    {

        $code = '';
        $counter = 0;
        if ($this->removeambichars == TRUE) { //remove letters O,o, i and l, and numbers 0 and 1
             if (($this->uppercaseonly == TRUE) && ($this->caseinsensitive == TRUE)) {
                $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H','I',
                               'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
                               'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                               '2', '3', '4', '5', '6', '7', '8', '9');
                $counter =  32;
            } else {
                $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
                               'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
                               'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a',
                               'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j',
                               'k', 'm', 'n', 'p', 'q', 'r', 's',
                               't', 'u', 'v', 'w', 'x', 'y', 'z',
                               '2', '3', '4', '5', '6', '7', '8', '9');
                $counter =  55;
            }
        } else {
            if (($this->uppercaseonly == TRUE) && ($this->caseinsensitive == TRUE)) {
                $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
                               'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                               'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0',
                               '1', '2', '3', '4', '5', '6', '7', '8', '9');
                $counter = 35;
            } else {
               $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
                              'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                              'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a',
                              'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
                              'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
                              't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1',
                              '2', '3', '4', '5', '6', '7', '8', '9');
                $counter = 61;
              }
        }

        for($i = 1; $i <= $len; ++$i) {
          $code .= $chars[rand(0, $counter)];
        }

        return $code;
    }

    function sendHeaders()
    {
        header("Expires: Sun, 1 Jan 2000 12:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type: image/jpeg");
    }

    function saveData()
    {
        if ($this->caseinsensitive) {
            $code = strtolower($this->code);
        } else {
            $code = $this->code;
        }
         xarSessionSetVar('formantibot_code', md5($this->hash_salt . $code));
    }

    function validate($userCode)
    {
        if ($this->caseinsensitive) {
            $userCode  = strtolower($userCode);
        }

        $userInput = md5($this->hash_salt . $userCode);
        $original  =  xarSessionGetVar('formantibot_code');
        if (isset($original)) {
             xarSessionDelVar('formantibot_code');
        }
        if($userInput == $original) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getcurrentip()
    {
        $userip = xarSessionGetIPAddress();
        return $userip;
    }

} //end class

?>