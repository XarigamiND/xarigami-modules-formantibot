<?php
/**
 * isformantiboted function
 *
 * @package Xarigami modules
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 */
/**
 * Verifies whether a domain is formantiboted or not
 *
 * @access public
 * @param string $domain Domain to verify for formantibot status
 * @returns bool True if formantiboted, false otherwise
 *
 */
function formantibot_userapi_validatenum($args)
{
    sys::import('modules.formantibot.xarclass.securelogic');

    extract($args);

    if (isset($userInput)) {
        $secureLogic = new securlogic();
        return $secureLogic->validate($userInput);
    }
    return FALSE;

}
?>