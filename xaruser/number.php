<?php
/**
 * Generate a number logic
 *
 * @package Xarigami modules
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 */
function formantibot_user_number()
{

    sys::import('modules.formantibot.xarclass.securelogic');
    $secureLogic = new securlogic();

    $foo = $secureLogic->display();
    return $foo;
}
?>
