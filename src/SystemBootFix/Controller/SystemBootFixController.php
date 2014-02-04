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
use Snapshots\Db\Mapper;

class SystemBootFixController extends AbstractActionController
{
    public function indexAction() {
    	
    	$filePath = $filePath = FS::getGuiTempDir() . DIRECTORY_SEPARATOR . 'systemBoot.zip';
    	$archive = $this->getSystemBootArchive($filePath);
    	
    	if ($archive->locateName(WebAPIController::METADATA_FILE) !== false) {
	    	$snapshotProfile = $archive->getFromName(WebAPIController::METADATA_FILE);
    	} else {
    		$snapshotProfile = null;
    	}
    	
    	$archive->close();
    	
    	unlink($filePath);
    	
    	$currentProfile = $this->getCurrentProfile();
    	
        return array(
        	'snapshotProfile' => $snapshotProfile,
        	'currentProfile' => $currentProfile,
        );
    }
    
    public function patchAction() {
    	$filePath = FS::getGuiTempDir() . DIRECTORY_SEPARATOR . 'systemBoot.zip';
    	
    	$archive = $this->getSystemBootArchive($filePath);
    	
    	$currentProfile = $this->getCurrentProfile();
    	
    	if ($archive->locateName(WebAPIController::METADATA_FILE) !== false) {
    		$archive->deleteName(WebAPIController::METADATA_FILE);
    	}
    	
    	$archive->addFromString(WebAPIController::METADATA_FILE, implode(',', $currentProfile));
    	
    	$archive->close();
    	
    	$snapshotData = file_get_contents($filePath);
    	
    	$snapshotsMapper = $this->getServiceLocator()->get('Snapshots\Db\Mapper');
    	$snapshotsMapper->deleteKeysById(array($snapshotsMapper->findSystemSnapshot()->getId()));
    	$snapshotsMapper->addSnapshot('SystemBoot', $snapshotData, Mapper::SNAPSHOT_TYPE_SYSTEM);
    	
    	unlink($filePath);
    	return $this->redirect()->toRoute('default',array('controller' => 'SystemBootFix', 'action' => 'index'));
    }
    
    /**
     * @return array
     */
    private function getCurrentProfile() {
    	$profilesMapper = $this->getServiceLocator()->get('Zsd\Db\NodesProfileMapper');
    	$currentProfile = $profilesMapper->getProfile();
    	if(isset($currentProfile['NODE_ID'])) {
    		unset($currentProfile['NODE_ID']);
    	}
    	return $currentProfile;
    }
    
    /**
     * @throws \Exception
     * @return \ZendServer\FS\ZipArchive
     */
    private function getSystemBootArchive($filePath) {
    	$snapshotsMapper = $this->getServiceLocator()->get('Snapshots\Db\Mapper');
    	 
    	$snapshot = $snapshotsMapper->findSystemSnapshot();
    	$snapshotId = $snapshot->getId();
    	if (! $snapshotId) {
    		throw new \Exception("Failed retrieving the bootStrap snapshot");
    	}
    	
    	$data = $snapshot->getData();
    	
    	file_put_contents($filePath, $data);
    	 
    	return FS::getZipArchive($filePath, 0);
    }

}
