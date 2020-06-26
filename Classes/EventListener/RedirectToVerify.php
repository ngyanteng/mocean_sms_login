<?php

namespace Mocean\MoceanSmsLogin\EventListener;

use TYPO3\CMS\FrontendLogin\Event\LoginConfirmedEvent;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Mocean\MoceanSmsLogin\Service\AuthenticateService;
use Mocean\MoceanSmsLogin\Domain\Model\User;
use Mocean\MoceanSmsLogin\Domain\Repository\UserRepository;

class RedirectToVerify
{
    public function __invoke(LoginConfirmedEvent $event): void
    {
		$moceanService = GeneralUtility::makeInstance(AuthenticateService::class);
		$usernameValue = $moceanService->getCurrentUsername();
			
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class); 
		$this->userRepository = $objectManager->get(UserRepository::class);
			
		$user = $this->userRepository->findOneByUsername($usernameValue);
		$count = $this->userRepository->countByUsername($usernameValue);
		
		if ($count >= 1 && $user->getEnabled() == 1)
		{
			$jsonResponse = $moceanService->sendCode();
			
			$status = $jsonResponse['status'];
			
			
			if ($status == 0) 
			{
				$GLOBALS['TSFE']->fe_user->setKey('ses', 'reqid', $jsonResponse['reqid']);
				$GLOBALS['TSFE']->fe_user->storeSessionData();
				
				HttpUtility::redirect($moceanService->getVerifyPage());
				
				$GLOBALS['TSFE']->fe_user->setKey('ses', 'unauthorized', 1);
				$GLOBALS['TSFE']->fe_user->storeSessionData();
			}
			else 
			{				
				$url = $moceanService->getUnavailablePage().'?&logintype=logout';
				HttpUtility::redirect($url);
			}
		}
		
    }
}