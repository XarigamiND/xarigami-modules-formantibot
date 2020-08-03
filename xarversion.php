<?php
/**
 * Initialize the formantibot module
 *
 * @package Xarigami modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 * @author Carl P. Corliss <carl.corliss@xaraya.com>
 * @author Jo Dalle Nogare <icedlava@2skies.com>
 */

$modversion['name'] = 'formantibot';
$modversion['directory'] = 'formantibot';
$modversion['id'] = '761';
$modversion['version'] = '0.8.2';
$modversion['displayname']    = 'Formantibot';
$modversion['description'] = 'Captcha image input for securing forms';
$modversion['credits'] = 'xardocs/credits.txt';
$modversion['help'] = '';
$modversion['changelog'] = 'xardocs/changelog.txt';
$modversion['license'] = '';
$modversion['official'] = 1;
$modversion['author'] = 'Jo Dalle Nogare';
$modversion['contact'] = 'http://xarigami.com';
$modversion['admin'] = 1;
$modversion['user'] = 0;
$modversion['class'] = 'Utility';
$modversion['category'] = 'Security';
$modversion['requires'] = array();
$modversion['dependencyinfo']   = array(
                                    0 => array(
                                            'name' => 'core',
                                            'version_ge' => '1.4.0'
                                         )
                                );
if (false) { //Load and translate once
    xarML('Formantibot');
    xarML('Captcha image input for securing forms');
}
?>