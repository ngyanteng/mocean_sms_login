<?php

namespace Mocean\MoceanSmsLogin\Domain\Finishers;

use \TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Form\Domain\Model\FormDefinition;
use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Mocean\MoceanSmsLogin\Domain\Model\User;
use Mocean\MoceanSmsLogin\Domain\Repository\UserRepository;
use Mocean\MoceanSmsLogin\Service\AuthenticateService;

class SaveOptIn extends AbstractFinisher
{
	
    /**
     * Saves opt-in user into the database
     */
	protected function executeInternal()
	{
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'reqid', NULL);
		
		$moceanService = GeneralUtility::makeInstance(AuthenticateService::class);
		$usernameValue = $moceanService->getCurrentUsername();
		$telephoneValue = $moceanService->getCurrentUserTelephone();
		$pid = $moceanService->getStoragePid();
		
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class); 
		$this->userRepository = $objectManager->get(UserRepository::class);

		$enabledValue = $this->parseOption('enabledValue');
		if(!$enabledValue) 
		{
			$enabledValue = 0;	
		}
		
		if ($pid == 0 || !$pid) 
		{
		  $userList = $this->userRepository->findAll();
		  $pid = $userList[0]->getPid();
		}
		$user = $this->userRepository->findOneByUsername($usernameValue);
		$count = $this->userRepository->countByUsername($usernameValue);
		
		if ($count >= 1)
		{
			$user->setPid($pid);
			$user->setTelephone($telephoneValue);
			$user->setEnabled($enabledValue);
			$this->userRepository->update($user);
		}
		else
		{
			$user = new User();
			$user->setPid($pid);
			$user->setUsername($usernameValue);
			$user->setTelephone($telephoneValue);
			$user->setEnabled($enabledValue);
			$this->userRepository->add($user);
			$objectManager->get(PersistenceManagerInterface::class)->persistAll();
		}
		
	}
}