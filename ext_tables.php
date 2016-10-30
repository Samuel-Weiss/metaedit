<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {

    /**
     * Registers a Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Whitenote.' . $_EXTKEY,
        'file',     // Make module a submodule of 'file'
        'metaedit',    // Submodule key
        '',                        // Position
        array(
            'Metafiles' => 'list,update',

        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:core/Resources/Public/Icons/T3Icons/apps/apps-filetree-folder-media.svg',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_metaedit.xlf',
        )
    );

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'metaedit');
