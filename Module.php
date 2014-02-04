<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/SystemBootFix for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SystemBootFix;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;

class Module implements AutoloaderProviderInterface, BootstrapListenerInterface
{
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
	/* (non-PHPdoc)
	 * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
	 */
	public function onBootstrap(\Zend\EventManager\EventInterface $e) {
		/// add ACL entries
		$serviceManager = $e->getApplication()->getServiceManager();
		$identityAcl = $serviceManager->get('ZendServerIdentityAcl');
		$identityAcl->addResource('route:SystemBootFix');
		$identityAcl->allow('administrator', 'route:SystemBootFix');
		$licenseAcl = $serviceManager->get('ZendServerLicenseAcl');
		$licenseAcl->addResource('route:SystemBootFix');
		$licenseAcl->allow(null, 'route:SystemBootFix');
	}


}
