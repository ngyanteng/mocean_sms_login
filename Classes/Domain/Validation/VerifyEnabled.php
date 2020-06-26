<?php

namespace Mocean\MoceanSmsLogin\Domain\Validation;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Mocean\MoceanSmsLogin\Service\AuthenticateService;
use Mocean\MoceanSmsLogin\Domain\Model\User;
use Mocean\MoceanSmsLogin\Domain\Repository\UserRepository;

class VerifyEnabled extends AbstractValidator
{
	protected function isValid($value)
	{
		if ($value == 1) 
		{
			$moceanService = GeneralUtility::makeInstance(AuthenticateService::class);
			$usernameValue = $moceanService->getCurrentUsername();
			
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class); 
			$this->userRepository = $objectManager->get(UserRepository::class);
			
			$user = $this->userRepository->findOneByUsername($usernameValue);
			$count = $this->userRepository->countByUsername($usernameValue);
			
			if ($count >= 1 && $user->getEnabled() == 1)
			{
				$this->addError('You have already opted-in.', 1591175983);
				return;
			}
			
			$jsonResponse = $moceanService->sendCode();
			
			$status = $jsonResponse['status'];
			
			if ($status != 0) 
			{
				$this->addError($moceanService->statusCode($status), 1591175482);
				return;
			}
			
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'reqid', $jsonResponse['reqid']);
			$GLOBALS['TSFE']->fe_user->storeSessionData();
		}
	}
}