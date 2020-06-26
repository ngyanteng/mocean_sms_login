<?php

namespace Mocean\MoceanSmsLogin\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class User extends AbstractEntity
{

    /**
     * The pid of the user object
     *
     * @var int
     **/
    protected $pid = 0;
	
	/**
     * The username of the user
     *
     * @var string
     **/
    protected $username = '';

    /**
     * The phone of the user
     *
     * @var int
     **/
    protected $telephone = '';

    /**
     * The status for opt-in of the user
     *
     * @var int
     **/
    protected $enabled = 0;

    /**
     * User constructor.
     *
     * @param int $pid
     * @param string $username
     * @param int $telephone
     * @param int $enabled
     */
    public function __construct($username = '', $telephone = '', $enabled = 0)
    {
        $this->setUsername($username);
        $this->setTelephone($telephone);
        $this->setEnabled($enabled);
    }

    /**
     * Sets the pid of the user
     *
     * @param int $pid
     */
    public function setPid(int $pid): void
    {
        $this->pid = $pid;
    }

    /**
     * Gets the pid of the user
     *
     * @return int
     */
    public function getPid(): ?int
    {
        return $this->pid;
    }
	
    /**
     * Sets the username of the user
     *
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * Gets the username of the user
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the telephone of the user
     *
     * @param string $telephone
     */
    public function setTelephone(string $telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * Gets the telephone of the user
     *
     * @return telephone
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Sets the status for opt-in of the user
     *
     * @param int $enabled
     */
    public function setEnabled(int $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Gets the status for opt-in of the user
     *
     * @return int
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
	
}
