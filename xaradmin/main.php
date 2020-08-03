<?php
/**
 * formantibot API
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
 * Displays the overview menu
 *
 * @access public
 * @author Carl P. Corliss <carl.corliss@xaraya.com>
 * @returns mixed output array, or string containing formated output
 */
function formantibot_admin_main()
{
    if(!xarSecurityCheck('FormAntiBot-Admin')){
        return;
    }

    // we only really need to show the default view (overview in this case)
        xarResponseRedirect(xarModURL('formantibot', 'admin', 'modifyconfig'));
    // success
    return true;
}
?>
