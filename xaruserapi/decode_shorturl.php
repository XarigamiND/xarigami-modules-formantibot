<?php
/**
 * FormAntiBot module
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
 * extract function and arguments from short URLs for this module, and pass
 * them back to xarGetRequestInfo()
 *
 * @author Carl P. Corliss
 * @param Array $params array containing the different elements of the virtual path
 * @returns array
 * @return array containing func the function to be called and args the query
 *         string arguments, or empty if it failed
 */
function formantibot_userapi_decode_shorturl($params)
{
    // Initialise the argument list we will return
    $args = array();

    if (empty($params[1]) || $params[1] == 'secure.jpg') {
        return array('image', $args);
    }

    // default : return nothing -> no short URL decoded
}

?>