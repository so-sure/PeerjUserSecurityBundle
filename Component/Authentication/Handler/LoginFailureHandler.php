<?php

/*
 * This file is part of the Peerj UserSecurityBundle
 *
 * (c) Peerj <https://peerj.com/>
 *
 * Available on github <http://www.github.com/Peerj/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Peerj\UserSecurityBundle\Component\Authentication\Handler;

use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Psr\Log\LoggerInterface;

use Peerj\UserSecurityBundle\Component\Authentication\Tracker\LoginFailureTracker;
	
/**
 *
 * @author Patrick McAndrew <patrick@peerj.com>
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class LoginFailureHandler extends DefaultAuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     *
     * @access protected
	 * @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    protected $router;
	
    /**
     *
     * @access protected
	 * @var \Peerj\UserSecurityBundle\Component\Authentication\Tracker\LoginFailureTracker $loginFailureTracker
     */
    protected $loginFailureTracker;
	
    /**
     *
     * @access protected
	 * @var bool $enabledd
     */
    protected $enabled;
	
    /**
     *
     * @access protected
	 * @var string $loginRoute
     */
    protected $loginRoute;
	
    /**
     *
     * @access protected
	 * @var array $loginRouteParams
     */
    protected $loginRouteParams;
	
    /**
     * Constructor.
     *
     * @param HttpKernelInterface $httpKernel
     * @param HttpUtils           $httpUtils
     * @param array               $options    Options for processing a failed authentication attempt.
     * @param LoggerInterface     $logger     Optional logger
	 * @param \Peerj\UserSecurityBundle\Component\Authentication\Tracker\LoginFailureTracker $loginFailureTracker
	 * @param bool $enabled
     */
    public function __construct(HttpKernelInterface $httpKernel, HttpUtils $httpUtils, array $options, LoggerInterface $logger = null, LoginFailureTracker $loginFailureTracker = null, $enabled = false)
    {
		$this->loginFailureTracker = $loginFailureTracker;
		$this->enabled = $enabled;
		
		parent::__construct($httpKernel, $httpUtils, $options, $logger);
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($this->enabled) {
            // Get the attempted username.
			if ($request->request->has('_username')) {
				$username = $request->request->get('_username');				
			} else {
				$username = '';
			}
			
            // Get our visitors IP address.
            $ipAddress = $request->getClientIp();

            // Make a note of the failed login.
            $this->loginFailureTracker->addAttempt($ipAddress, $username);
        }

		return parent::onAuthenticationFailure($request, $exception);		
    }
}