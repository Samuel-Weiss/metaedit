<?php
namespace Whitenote\Metaedit\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Samuel Weiss <samuel@whitenote.ch>, whitenote
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\FolderInterface;
use TYPO3\CMS\Backend\Configuration\TranslationConfigurationProvider;

/**
 * MetafilesController
 */
class MetafilesController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        /* Get Folder Id */
        $combinedIdentifier = GeneralUtility:: _GP('id');

        if ($combinedIdentifier) {

            /** @var $fileFactory \TYPO3\CMS\Core\Resource\ResourceFactory */
            $fileFactory = GeneralUtility:: makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
            $storage = $fileFactory->getStorageObjectFromCombinedIdentifier($combinedIdentifier);
            $identifier = substr($combinedIdentifier, strpos($combinedIdentifier, ':') + 1);
            if (!$storage->hasFolder($identifier)) {
                $identifier = $storage->getFolderIdentifierFromFileIdentifier($identifier);
            }

            $folderObject = $fileFactory->getFolderObjectFromCombinedIdentifier($storage->getUid() . ':' . $identifier);
            // Disallow the rendering of the processing folder (e.g. could be called
            // manually)
            // and all folders without any defined storage
            if ($folderObject && ($storage->getUid() === 0 || $storage->isProcessingFolder($folderObject))) {
                $folderObject = $storage->getRootLevelFolder();
            }

            $folderObject = $fileFactory->getFolderObjectFromCombinedIdentifier($storage->getUid() . ':' . $identifier);
            // Disallow the rendering of the processing folder (e.g. could be called
            // manually)
            // and all folders without any defined storage
            if ($folderObject && ($storage->getUid() === 0 || $storage->isProcessingFolder($folderObject))) {
                $folderObject = $storage->getRootLevelFolder();
            }

            $files = $folderObject->getFiles();


            $this->view->assign('files', $files);
            $this->view->assign('folderObject', $folderObject);

            /* Get Languages */
            // first two keys are "0" (default) and "-1" (multiple), after that comes the "other languages"
            $allSystemLanguages = GeneralUtility::makeInstance(TranslationConfigurationProvider::class)->getSystemLanguages();
            $systemLanguages = array_filter($allSystemLanguages, function($languageRecord) {
                if ($languageRecord[ 'uid' ] === -1) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            });

            $this->view->assign('systemLanguages', $systemLanguages);

        }

    }


    /**
     * action show
     *
     * @return void
     */
    public function showAction()
    {


    }

    /**
     * action edit
     *
     * @return void
     */
    public function editAction()
    {

    }

    /**
     * action update
     *
     * @return void
     */
    public function updateAction()
    {
        $arg = $this->request->getArguments();

        /** @var $storageRepository \TYPO3\CMS\Core\Ressources\StorageRepository */
        $storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility:: makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');

        echo "Storage:" . $arg['storage'] . "; <br/>";

        $storage = $storageRepository->findByUid((int)$arg['storage']);

        $file = $storage->getFile($arg['identifier']);
        $file->getProperties();

        var_dump($file);
        echo "<hr/>";

        $newproperties = (array)json_decode(stripslashes($arg["data"]));
        var_dump($newproperties);

        var_dump($newproperties);
        echo "<hr/>";

        $FileIndexRepository = \TYPO3\CMS\Core\Utility\GeneralUtility:: makeInstance('TYPO3\\CMS\\Core\\Resource\\Index\\MetaDataRepository');
        $FileIndexRepository->update($arg['id'], $newproperties);
        var_dump($FileIndexRepository);

        $this->view->assign('id', $arg['id']);
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility:: makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager')->persistAll();
    }

}