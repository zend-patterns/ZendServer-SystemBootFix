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
use ZendServer\FS\FS;
use Configuration\Controller\WebAPIController;

class SystemBootFixController extends AbstractActionController
{
    public function indexAction() {
    	$snapshotsMapper = $this->getServiceLocator()->get('Snapshots\Db\Mapper');
    	
    	$snapshot = $snapshotsMapper->findSystemSnapshot();
    	$snapshotId = $snapshot->getId();
    	if (! $snapshotId) {
    		throw new \Exception("Failed retrieving the bootStrap snapshot");
    	}
    		
    	$data = $snapshot->getData();
    	$filePath = FS::getGuiTempDir() . DIRECTORY_SEPARATOR . 'systemBoot.zip';
    	file_put_contents($filePath, $data);
    	
    	$archive = FS::getZipArchive($filePath, 0);
    	
    	if ($archive->locateName(WebAPIController::METADATA_FILE) !== false) {
	    	$snapshotProfile = $archive->getFromName(WebAPIController::METADATA_FILE);
    	} else {
    		$snapshotProfile = null;
    	}
    	
    	$archive->close();
    	
    	unlink($filePath);
    	
    	$profilesMapper = $this->getServiceLocator()->get('Zsd\Db\NodesProfileMapper');
    	$currentProfile = $profilesMapper->getProfile();
    	if(isset($currentProfile['NODE_ID'])) {
	    	unset($currentProfile['NODE_ID']);
    	}
    	
        return array(
        	'snapshotProfile' => $snapshotProfile,
        	'currentProfile' => $currentProfile,
        );
    }
    
    public function patchAction() {
    	return $this->redirect()->toRoute('default',array('controller' => 'SystemBootFix', 'action' => 'index'));
    }

}
