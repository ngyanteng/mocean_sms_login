<?php

namespace Mocean\MoceanSmsLogin\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class UserRepository
 *
 * @package Mocean\MoceanSmsLogin\Domain\Repository
 */
class UserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
{
	public function initializeObject() {
		$querySettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
		$querySettings->setRespectStoragePage(FALSE);
		$this->setDefaultQuerySettings($querySettings);
	}
}
