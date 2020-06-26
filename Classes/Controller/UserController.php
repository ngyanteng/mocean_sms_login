<?php

namespace Mocean\MoceanSmsLogin\Controller;

use Mocean\MoceanSmsLogin\Domain\Repository\UserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;

/**
 * Class StoreInventoryController
 *
 * @package Mocean\MoceanSmsLogin\Controller
 */
class UserController extends ActionController
{

    /**
     * @var \Mocean\MoceanSmsLogin\Domain\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @param \Mocean\MoceanSmsLogin\Domain\Repository\UserRepository
     */
    public function injectUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * List Action
     *
     * @return void
     */
    public function listAction()
    {
        $users = $this->userRepository->findAll();
        $this->view->assign('users', $users);
    }
}
