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
        'file',     // Make module a submodule of 'web'
        'metaedit',    // Submodule key
        '',                        // Position
        array(
            'Metafiles' => 'list,iglist,update',

        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_metaedit.xlf',
        )
    );

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'metaedit');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_metaedit_domain_model_dummy', 'EXT:metaedit/Resources/Private/Language/locallang_csh_tx_metaedit_domain_model_dummy.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_metaedit_domain_model_dummy');
