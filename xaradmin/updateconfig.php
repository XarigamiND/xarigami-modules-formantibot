<?php
/**
 * @package Xarigami modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 */
/**
 * Standard function to update module configuration parameters
 *
 * This is a standard function to update the configuration parameters of the
 * module given the information passed back by the modification form
 */
function formantibot_admin_updateconfig()
{

    if (!xarVarFetch('registered',    'checkbox', $registered, FALSE, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('image_width',   'int',  $settings['image_width'], 230, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('image_height',  'int',  $settings['image_height'], 40, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('code_length',   'int',  $settings['code_length'], 7, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('ttf_file_name',      'str',      $settings['ttf_file_name'], "elephant.ttf", XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('ttf_file_path',      'str',      $settings['ttf_file_path'], "modules/formantibot/fonts", XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('font_size',     'int',  $settings['font_size'], 20, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('text_angle_minimum', 'int',  $settings['text_angle_minimum'], -20, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('text_angle_maximum', 'int',  $settings['text_angle_maximum'], 20, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('text_x_start',   'int',        $settings['text_x_start'], 9, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('text_minimum_distance', 'int', $settings['text_minimum_distance'], 30, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('text_maximum_distance', 'int', $settings['text_maximum_distance'], 33, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('shadow_text',    'checkbox',       $settings['shadow_text'], false, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('use_transparent_text', 'checkbox', $settings['use_transparent_text'], true, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('text_transparency_percentage','int',  $settings['text_transparency_percentage'], 25, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('draw_lines','checkbox',            $settings['draw_lines'], true, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('line_distance', 'int',         $settings['line_distance'], 12, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('draw_lines_over_text', 'checkbox', $settings['draw_lines_over_text'], true, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('draw_angled_lines','checkbox',     $settings['draw_angled_lines'], true, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('prune_minimum_age', 'int',     $settings['prune_minimum_age'], 15, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('hash_salt',      'str',    $settings['hash_salt'], "fg7hg3yg3fd90oi4i", XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('imagebgcolor', 'str',    $imagebgcolor, '#FFFFFF', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('textcolor',     'str',    $textcolor,'#8080FF', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('linecolor',     'str',    $linecolor, '#CCCCFF', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('removeambichars','checkbox',    $settings['removeambichars'], FALSE, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('caseinsensitive','checkbox',    $settings['caseinsensitive'], FALSE, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('uppercaseonly','checkbox',    $settings['uppercaseonly'], FALSE, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('captchatype',    'int', $captchatype, 1, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('captchapublic',  'str',      $settings['captchapublic'], '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('captchaprivate',  'str',      $settings['captchaprivate'], '', XARVAR_NOT_REQUIRED)) return;
    if (!xarSecConfirmAuthKey()) return;

    xarModSetVar('formantibot', 'registered', $registered);
    xarModSetVar('formantibot', 'captchatype', $captchatype);
    //handle booleans that are going to be serialized
    $booleans = array('shadow_text','use_transparent_text','draw_lines_over_text','draw_lines','draw_angled_lines','removeambichars','caseinsensitive');
    foreach ($booleans as $k) {
        $settings[$k] = isset($settings[$k])?$settings[$k] : 0;
    }
    $textcolor =xarModAPIFunc('formantibot','user','rgb2hex2rgb',array('c'=>$textcolor));
    $imagebgcolor =xarModAPIFunc('formantibot','user','rgb2hex2rgb',array('c'=>$imagebgcolor));
    $linecolor =xarModAPIFunc('formantibot','user','rgb2hex2rgb',array('c'=>$linecolor));

    $settings['text_color'] = array('red'=>$textcolor['red'],'green'=>$textcolor['green'],'blue'=>$textcolor['blue']);
    $settings['line_color'] = array('red'=>$linecolor['red'],'green'=>$linecolor['green'],'blue'=>$linecolor['blue']);
    $settings['image_bg_color'] = array('red'=>$imagebgcolor['red'],'green'=>$imagebgcolor['green'],'blue'=>$imagebgcolor['blue']);
    $settings['ttf_file'] = $settings['ttf_file_path'].'/'.$settings['ttf_file_name'];
    $settings = serialize($settings);
    xarModSetVar('formantibot','settings', $settings);
    xarModCallHooks('module','updateconfig','formantibot',
                   array('module' => 'formantibot'));
    $msg = xarML("Configuration settings successfully updated.");
    xarTplSetMessage($msg,'status');
    xarResponseRedirect(xarModURL('formantibot', 'admin', 'modifyconfig'));

    /* Return */
    return true;
}
?>