<?php
/**
 * Displays standard Overview page
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
 * Overview function that displays the standard Overview page
 *
 */
function formantibot_admin_overview()
{
   /* Security Check */
    if (!xarSecurityCheck('FormAntiBot-Admin',0)) return;

    $data=array();
   $data['menulinks'] = xarModAPIFunc('formantibot','admin','getmenulinks');
    return xarTplModule('formantibot', 'admin', 'main', $data,'main');
}

?>