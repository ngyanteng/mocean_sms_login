<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
		\TYPO3\CMS\Core\Imaging\IconRegistry::class
	);
	$iconRegistry->registerIcon(
		'mocean_sms_login_icon',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'mocean_sms_login/Resources/Public/Icons/Extension.svg']
	);
});

