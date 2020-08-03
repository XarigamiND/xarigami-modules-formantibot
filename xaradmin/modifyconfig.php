<?php
/**
 * Modify module's configuration
 *
 * @package Xarigami modules
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 */
/**
 * Modify module's configuration
 *
 * @return array
 */

function formantibot_admin_modifyconfig()
{
    $data = array();

    if (!xarSecurityCheck('FormAntiBot-Admin')) return;
   $data['menulinks'] = xarModAPIFunc('formantibot','admin','getmenulinks');
    /* Generate a one-time authorisation code for this operation */
    $data['authid'] = xarSecGenAuthKey();

    /* Specify some values for captcha display */
    //use for all users including registered (default is non-logged in only)
    $data['registered']      = xarModGetVar('formantibot', 'registered');
    $data['captchatype']      = xarModGetVar('formantibot', 'captchatype');
    $settings     = xarModGetVar('formantibot', 'settings');
    $settings = unserialize($settings);

    $linecolor = $settings['line_color']['red'].','.$settings['line_color']['green'].','.$settings['line_color']['blue'];
    $textcolor = $settings['text_color']['red'].','.$settings['text_color']['green'].','.$settings['text_color']['blue'];
    $imagebgcolor = $settings['image_bg_color']['red'].','.$settings['image_bg_color']['green'].','.$settings['image_bg_color']['blue'];

    $settings['linecolorhex'] = xarModAPIFunc('formantibot','user','rgb2hex2rgb',array('c'=>$linecolor));
    $settings['textcolorhex'] = xarModAPIFunc('formantibot','user','rgb2hex2rgb',array('c'=>$textcolor));
    $settings['imagebgcolorhex'] = xarModAPIFunc('formantibot','user','rgb2hex2rgb',array('c'=>$imagebgcolor));
    $settings['uppercaseonly'] = isset($settings['uppercaseonly'])?$settings['uppercaseonly']:false;
    $settings['captchapublic'] = isset($settings['captchapublic'])?$settings['captchapublic']:'';
    $settings['captchaprivate'] = isset($settings['captchaprivate'])?$settings['captchaprivate']:'';
    $data['settings'] = $settings;

    $caparray = array(0 => xarML('None'),
                      1 => xarML('Image captcha'),
                      2 => xarML('Number challenge'),
                      3 => xarML('Image reCAPTCHA')
                        );
    $data['caparray'] = $caparray;

    $data['fontpath'] = $settings['ttf_file_path'];
    $data['fileext'] = 'ttf';
    $hooks = xarModCallHooks('module', 'modifyconfig', 'formantibot',
                       array('module' => 'formantibot'));


    if (empty($hooks)) {
        $data['hookoutput'] ='';
    } else {
        $data['hookoutput'] = $hooks;
    }
    /* Return the template variables defined in this function */
    return $data;
}
?>
