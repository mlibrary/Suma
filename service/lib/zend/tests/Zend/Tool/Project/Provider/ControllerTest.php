<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Tool
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ControllerTest.php 24593 2012-01-05 20:35:02Z matthew $
 */

require_once 'Zend/Tool/Project/Profile.php';
require_once 'Zend/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Framework/Provider/Pretendable.php';
require_once 'Zend/Tool/Project/Provider/Controller.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';
        
/**
 * @category   Zend
 * @package    Zend_Tool
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group Zend_Tool
 * @group Zend_Tool_Framework
 * @group Zend_Tool_Framework_Action
 */
class Zend_Tool_Project_Provider_ControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @group ZF-8305
     */   
    public function testHasResourceWithNonexistentModuleDiesFatalError()
    {
        $this->assertFalse(Zend_Tool_Project_Provider_Controller::hasResource(new Zend_Tool_Project_Profile(),
                'NewController', 'NonexistentModule'));
    }

}