<?php
/**
 * Xarigami Formantibot
 *
 * @package Xarigami modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 */
/**
 * utility function pass individual menu items to the main menu
 *
 * @return array containing the menulinks for the main menu items.
 */
function formantibot_adminapi_getmenulinks()
{
    $menulinks = array();

    // Security Check

    if (xarSecurityCheck('FormAntiBot-Admin',0)) {

        $menulinks[] = Array('url'   => xarModURL('formantibot', 'admin',  'modifyconfig'),
                              'title' => xarML('Modify the Formantibot module configuration'),
                              'label' => xarML('Modify Config'),
                              'active' => array('modifyconfig'));

    }

    return $menulinks;
}

?>