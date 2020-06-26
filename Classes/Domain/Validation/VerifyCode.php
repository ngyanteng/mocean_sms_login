<?php

namespace Mocean\MoceanSmsLogin\Domain\Validation;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Mocean\MoceanSmsLogin\Service\AuthenticateService;

class VerifyCode extends AbstractValidator
{
	protected function isValid($value)
	{
		$reqid = $GLOBALS['TSFE']->fe_user->getKey('ses', 'reqid');
		$moceanService = GeneralUtility::makeInstance(AuthenticateService::class);
		$jsonResponse = $moceanService->verifyCode($reqid, $value);
		
		$status = $jsonResponse['status'];

		if ($status != 0) 
		{
			$this->addError($reqid, 1591175982);
			return;
		}
		
		$GLOBALS["TSFE"]->fe_user->setKey('ses', 'reqid', NULL);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'unauthorized', NULL);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
	}
}