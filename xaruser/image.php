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
 * Verifies whether a domain is formantiboted or not
 *
 * @author Carl P. Corliss <carl.corliss@xaraya.com>
 * @access public
 * @param string $domain Domain to verify for formantibot status
 * @returns bool True if formantiboted, false otherwise
 *
 */
function formantibot_user_image()
{
    sys::import('modules.formantibot.xarclass.secureimage');

    $secureImage = new securimage();
    $secureImage->display();
    exit();
}
?>