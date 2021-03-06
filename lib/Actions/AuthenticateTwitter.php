<?php

namespace Magium\Twitter\Actions;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Magium\AbstractTestCase;
use Magium\Commands\Open;
use Magium\Twitter\Themes\Twitter;
use Magium\Twitter\Identities\Twitter as TwitterIdentity;
use Magium\WebDriver\WebDriver;

class AuthenticateTwitter
{
    const ACTION = 'Magium\Twitter\Actions\AuthenticateTwitter';
    
    protected $webDriver;
    protected $theme;
    protected $identity;
    protected $open;
    
    public function __construct(
        WebDriver $webDriver,
        Open $open,
        Twitter $theme,
        TwitterIdentity $identity
    )
    {
        $this->open = $open;
        $this->webDriver    = $webDriver;
        $this->theme        = $theme;
        $this->identity     = $identity;
    }
    
    public function execute($remember = false)
    {
        $url = $this->webDriver->getCurrentURL();
        if ($url !== $this->theme->getTwitterUrl()) {
            $this->open->open($this->theme->getTwitterUrl());
        }

        try {
            $element = $this->webDriver->byXpath($this->theme->getLoginButtonXpath());
            $element->click();

            $element = $this->webDriver->byXpath($this->theme->getSiteUsernameXpath());
            $element->clear();
            $element->sendKeys($this->identity->getUsername());

            $element = $this->webDriver->byXpath($this->theme->getSitePasswordXpath());
            $element->clear();
            $element->sendKeys($this->identity->getPassword());

            if ($remember) {
                $element = $this->webDriver->byXpath($this->theme->getRememberXpath());
                $element->click();
            }

        } catch (NoSuchElementException $e) {
            // Presuming that we're logged in
        }
        $element = $this->webDriver->byXpath($this->theme->getSiteSignInXpath());
        $element->click();
    }

}