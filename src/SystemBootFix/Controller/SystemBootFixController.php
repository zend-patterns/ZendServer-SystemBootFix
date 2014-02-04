<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/SystemBootFix for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SystemBootFix\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class SystemBootFixController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }
    
    public function patchAction()
    {
    	return $this->redirect()->toRoute('default',array('controller' => 'SystemBootFix', 'action' => 'index'));
    }

}
