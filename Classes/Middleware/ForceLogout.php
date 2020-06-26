<?php
declare(strict_types=1);
namespace Mocean\MoceanSmsLogin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Core\Http\Message;
use Mocean\MoceanSmsLogin\Service\AuthenticateService;

/**
 * This middleware redirects the current frontend user to a configured page if the user must change the password
 */
class ForceLogout implements MiddlewareInterface
{
    /**
     * Check if the user must change the password and redirect to configured PID
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return RedirectResponse|ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$GLOBALS['TSFE']->fe_user->user['uid'])
		{
            return $handler->handle($request);
        }

		$response = $handler->handle($request);
		$unauthorized = $GLOBALS['TSFE']->fe_user->getKey('ses','unauthorized');

        if ($unauthorized == 1) 
		{
			$GLOBALS['TSFE']->fe_user->setKey('ses', 'unauthorized', NULL);
			$GLOBALS['TSFE']->fe_user->storeSessionData();
			
			$moceanService = GeneralUtility::makeInstance(AuthenticateService::class);
			
			$url = $moceanService->getLogoutPage().'?&logintype=logout';
			return new RedirectResponse($url, 302);
        }

        return $response;
    }
}
