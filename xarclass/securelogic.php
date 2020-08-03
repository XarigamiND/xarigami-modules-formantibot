<?php
/**
 * Simple number sum for logic captcha
 *
 * @package Xarigami modules
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Formantibot
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/formantibot
 */

class securlogic
{

    public $testsum = '';
    public $code_entered;
    public $correct_code;
    public $hash_salt = "fg7hg3yg3fd90oi4i";
    public $code;

    function __construct()
    {
        $this->setSettings();
        $this->userCodeKey = 'code.' . md5($this->hash_salt . $this->getcurrentip());

    }

    function setSettings()
    {
        $settings = unserialize(xarModGetVar('formantibot', 'settings'));

        foreach ($settings as $name => $value) {
            $this->$name = $value;
        }
    }

    function display()
    {
        $this->createTest();
        return $this->testsum;

    }
   /*
    * @based on code by Rob Malon <robmalon.com>
    */
    function createTest()
    {
        $firstnum = rand(5,8);
        $secondnum = rand(1,4);
        $coinflip = rand(1,2) % 2;
        $operators = array();
        if($coinflip == 0) {
            $math = $firstnum + $secondnum;
            $operators = array("+","Added To","Plus");
            $operatorschoice = rand(1,3) % 3;
        } else {
            $math = $firstnum - $secondnum;
            $operators = array("-","Minus");
            $operatorschoice = rand(1,2) % 2;
        }
        $this->testsum =  $firstnum . " " . $operators[$operatorschoice] . " " . $secondnum. " = ";
        $this->code = $math;
        $this->saveData();
    }


    function saveData()
    {

        xarSessionSetVar('formantibot_code', md5($this->hash_salt . $this->code));
    }

    function validate($userCode)
    {
        $userInput = md5($this->hash_salt . $userCode);
        $original  = xarSessionGetVar('formantibot_code');
        if(isset($original)) {
          xarSessionDelVar('formantibot_code');
        }
        if($userInput == $original) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    function getcurrentip()
    {
        $userip = xarSessionGetIPAddress();
        return $userip;
    }

} //end class

?>