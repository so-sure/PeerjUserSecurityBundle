<?php

namespace Peerj\UserSecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpFoundation\Request;

/**
 * A console command for user security tasks
 */
class StatusCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('peerj:user-security:status')
            ->setDescription('Show the status of any blocked ips')
            ->addOption('ip', null, InputOption::VALUE_REQUIRED, 'IP Address')
            ->addOption('clear', null, InputOption::VALUE_NONE, 'Clear counts for ip')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'login_failed, reset or all', 'all')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input  input
     * @param \Symfony\Component\Console\Output\OutputInterface $output output
     *
     * @return string
     *
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clear = (true === $input->getOption('clear'));
        $ip = $input->getOption('ip');
        $type = $input->getOption('type');

        $sessionManager = $this->getContainer()->get('peerj_user_security.manager.session');

        if ($clear && $ip) {
            if ($type == 'login_failed' || $type == 'all') {
                print "Clearing login_failed counts for " . $ip . PHP_EOL;
                $sessionManager->clear('login_failed', $ip);
            }
            if ($type == 'reset' || $type == 'all') {
                print "Clearing reset counts for " . $ip . PHP_EOL;
                $sessionManager->clear('reset', $ip);
            }
        }
        
        $loginBlockMinutes = $this->getContainer()->getParameter("peerj_user_security.login_shield.block_for_minutes");
        $loginTimeLimit = new \DateTime('-' . $loginBlockMinutes . ' minutes');

        $resetBlockMinutes = $this->getContainer()->getParameter("peerj_user_security.reset_shield.block_for_minutes");
        $resetTimeLimit = new \DateTime('-' . $resetBlockMinutes . ' minutes');

        if ($type == 'login_failed' || $type == 'all') {
            print "Failed Login Attempts".PHP_EOL;
            print_r($sessionManager->getAllByType('login_failed', $loginTimeLimit));
        }
        
        if ($type == 'reset' || $type == 'all') {
            print "Reset Requests".PHP_EOL;
            print_r($sessionManager->getAllByType('reset', $resetTimeLimit));
        }
    }

}
