<?php

namespace Mocean\MoceanSmsLogin\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

/**
 * Class AuthenticateUser
 *
 * @package Mocean\MoceanSmsLogin\Service
 */
class AuthenticateService
{
	/**
     * Mocean api key
     *
     * @var string
     **/
    protected $apiKey = '';
	
	/**
     * Mocean api secret
     *
     * @var string
     **/
    protected $apiSecret = '';
	
	/**
     * Name of sender
     *
     * @var string
     **/
    protected $messageFrom = '';
	
	/**
     * Storage pid of users
     *
     * @var int
     **/
    protected $storagePid = 0;
	
	/**
     * Url of the verify form
     *
     * @var string
     **/
    protected $verifyPage = '';
	
	/**
     * Url to redirect after forceful logout
     *
     * @var string
     **/
    protected $logoutPage = '';
	
	/**
     * Url of 503 page
     *
     * @var string
     **/
    protected $unavailablePage = '';
	
	/**
     * Current user's username
     *
     * @var string
     **/
    protected $currentUsername = '';
	
	/**
     * Current user's telephone
     *
     * @var string
     **/
    protected $currentUserTelephone = '';
	
	/**
     * AuthenticateUser constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $messageFrom
     * @param int $storagePid
     * @param string $verifyPage
     * @param string $logoutPage
     * @param string $unavailablePage
     * @param string $currentUsername
     * @param string $currentUserTelephone
     */
    public function __construct($apiKey = '', $apiSecret = '', $messageFrom = '', $storagePid = 0, $verifyPage = '', $unavailablePage = '', $logoutPage = '', $currentUsername = '', $currentUserTelephone = '')
    {
		$this->extConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('mocean_sms_login');

        $this->apiKey = $this->extConfiguration['moceanApiKey'];
        $this->apiSecret = $this->extConfiguration['moceanApiSecret'];
        $this->messageFrom = $this->extConfiguration['moceanMessageFrom'];
        $this->storagePid = $this->extConfiguration['moceanStoragePid'];
        $this->verifyPage = $this->extConfiguration['moceanVerifyPage'];
        $this->logoutPage = $this->extConfiguration['moceanLogoutPage'];
        $this->unavailablePage = $this->extConfiguration['moceanUnavailablePage'];
		
		$uid = $GLOBALS['TSFE']->fe_user->user['uid'];
		
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
		
		$this->currentUsername = $queryBuilder
			->select('username')
			->from('fe_users')
			->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid)))
			->execute()
			->fetchColumn(0);
   
		$this->currentUserTelephone = $queryBuilder
			->select('telephone')
			->from('fe_users')
			->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid)))
			->execute()
			->fetchColumn(0);
	}
    
	
	/**
     * Gets storage pid
     *
     * @return int
     */
    public function getStoragePid()
    {
        return $this->storagePid;
    }
	
	/**
     * Gets url of the verify form
     *
     * @return string
     */
    public function getVerifyPage()
    {
        return $this->verifyPage;
    }
	
	/**
     * Gets url to redirect after forceful logout
     *
     * @return string
     */
    public function getLogoutPage()
    {
        return $this->logoutPage;
    }
	
	/**
     * Gets url of page 503
     *
     * @return string
     */
    public function getUnavailablePage()
    {
        return $this->unavailablePage;
    }
	
	/**
     * Gets current user's username
     *
     * @return string
     */
    public function getCurrentUsername()
    {
        return $this->currentUsername;
    }
	
	/**
     * Sets the currentUsername of the user
     *
     * @param string $currentUsername
     */
    public function setCurrentUsername(string $currentUsername)
    {
        $this->currentUsername = $currentUsername;
    }
	
	/**
     * Gets current user's telephone
     *
     * @return string
     */
    public function getCurrentUserTelephone()
    {
        return $this->currentUserTelephone;
    }
	
	public function sendCode() {
		$url = 'https://rest.moceanapi.com/rest/1/verify/req';
		$fields = array(
			'mocean-api-key'=>$this->apiKey,
			'mocean-api-secret'=>$this->apiSecret,
			'mocean-to'=>$this->currentUserTelephone,
			'mocean-brand'=>$this->messageFrom,
			'mocean-resp-format'=>'json'
		);

		$fields_string = '';
		foreach($fields as $key=>$value) {
			$fields_string .= $key.'='.$value.'&';
		}
		rtrim($fields_string, '&');

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		curl_close($ch);

		$json = json_decode($result, true);

		//returns an array
		return $json;
	}
	
	public function verifyCode($reqid, $code) {
		$url = 'https://rest.moceanapi.com/rest/1/verify/check';
		$fields = array(
			'mocean-api-key'=>$this->apiKey,
			'mocean-api-secret'=>$this->apiSecret,
			'mocean-reqid'=>$reqid,
			'mocean-code'=>$code,
			'mocean-resp-format'=>'json'
		);

		$fields_string = '';
		foreach($fields as $key=>$value) {
			$fields_string .= $key.'='.$value.'&';
		}
		rtrim($fields_string, '&');

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		curl_close($ch);

		$json = json_decode($result, true);

		//returns an array
		return $json;
	}
	
	public function statusCode($status) {
		if ($status == 16 || $status == 17) 
		{
			return 'Incorrect code or code expired.';
		}
		else if ($status == 1 || $status == 19) 
		{
			return 'Operation too frequent.';
		}
		else if ($status == 2 || $status == 18) 
		{
			return "Authorization failed or low on credit, report to the site's admin.";
		}
		else if ($status == 15) 
		{
			return 'Incorrect code was provided too many times. Maximum retry limit is 3.';
		}
		else if ($status == 5) 
		{
			return 'You have not configure your telephone number.';
		}
		else 
		{
			return 'An error has occured, please try again later.';
		}
	}

}