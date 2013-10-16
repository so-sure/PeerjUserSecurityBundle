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

namespace Peerj\UserSecurityBundle\Component\Listener;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Peerj\UserSecurityBundle\Component\Authentication\Tracker\ResetTracker;
use Psr\Log\LoggerInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class ResetShieldListener
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
	 * @var \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

  /**
     *
     * @access protected
	 * @var \Peerj\UserSecurityBundle\Component\Authentication\Tracker\ResetTracker $resetTracker
     */
    protected $resetTracker;
	
    /**
     *
     * @access protected
	 * @var bool $enabled
     */
    protected $enabled;
	
    /**
     *
     * @access protected
	 * @var array $blockRoutes
     */
    protected $blockRoutes;
	
    /**
     *
     * @access protected
	 * @var int $blockForMinutes
     */
    protected $blockForMinutes;
	
    /**
     *
     * @access protected
	 * @var int $limitAttempts
     */
    protected $limitAttempts;
	
    /**
     *
     * @access protected
	 * @var string $resetRoute
     */
    protected $resetRoute;
	
    /**
     *
     * @access protected
	 * @var array $resetRouteParams
     */
    protected $resetRouteParams;	

    /**
     *
     * @access protected
	 * @var string $redirectWhenDeniedRoute
     */
    protected $redirectWhenDeniedRoute;
	
    /**
     *
     * @access protected
	 * @var array $redirectWhenDeniedRouteParams
     */
    protected $redirectWhenDeniedRouteParams;	

    
    /**
     * @access public
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Peerj\UserSecurityBundle\Component\Authentication\Tracker\ResetTracker $resetTracker
	 * @param bool $enabled
	 * @param array $blockRoutes
	 * @param int $blockForMinutes
	 * @param int $limitAttempts
	 * @param string $resetRoute
	 * @param array $resetRouteParams
	 * @param string $redirectWhenDeniedRoute
	 * @param array $redirectWhenDeniedRouteParams
     */
    public function __construct(Router $router, LoggerInterface $logger, ResetTracker $resetTracker, $enabled, array $blockRoutes, $blockForMinutes, $limitAttempts, $resetRoute, array $resetRouteParams, $redirectWhenDeniedRoute, array $redirectWhenDeniedRouteParams)
    {
        $this->router = $router;
		$this->logger = $logger;
		$this->resetTracker = $resetTracker;
		$this->enabled = $enabled;
		$this->blockRoutes = $blockRoutes;
		$this->blockForMinutes = $blockForMinutes;
		$this->limitAttempts = $limitAttempts;
		$this->resetRoute = $resetRoute;		
		$this->resetRouteParams = $resetRouteParams;
        $this->redirectWhenDeniedRoute = $redirectWhenDeniedRoute;
        $this->redirectWhenDeniedRouteParams = $redirectWhenDeniedRouteParams;
    }

    /**
     * If you have failed to login too many times, a log of this will be present
     * in your session and the databse (incase session is dropped the record remains).
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->enabled) {
            // Abort if we are dealing with some symfony2 internal requests.
            if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
                return;
            }

            // Get the route from the request object.
			$request = $event->getRequest();

            $route = $request->get('_route');

            // Abort if the route is not a reset route.
            if ( ! in_array($route, $this->blockRoutes)) {
                return;
            }			
			
            $ipAddress = $request->getClientIp();

			// Unfortunately fos_user doesn't send an event if there is a failed reset request
			// so, we're just going to do a count by ip every time the reset route is accessed
			$this->resetTracker->addAttempt($ipAddress, '');

            // Get number of failed login attempts.
            $attempts = $this->resetTracker->getAttempts($ipAddress);

            if (count($attempts) > ($this->limitAttempts -1)) {
				$this->logger->warning(sprintf("Blocked access to %s from %s", $route, $ipAddress));
				if ($this->redirectWhenDeniedRoute) {
					return $event->setResponse(new RedirectResponse($this->router->generate($this->redirectWhenDeniedRoute, $this->redirectWhenDeniedRouteParams)));
				} else {
					throw new HttpException(503, 'flood control - reset blocked');
				}
            }
        }

        return;
    }
}