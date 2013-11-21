<?php

namespace SilverStripe\BehatExtension\Context\Initializer;

use Behat\Behat\Context\Initializer\InitializerInterface,
Behat\Behat\Context\ContextInterface;

use SilverStripe\BehatExtension\Context\SilverStripeAwareContextInterface;

/*
 * This file is part of the Behat/SilverStripeExtension
 *
 * (c) Michał Ochman <ochman.d.michal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * SilverStripe aware contexts initializer.
 * Sets SilverStripe instance to the SilverStripeAware contexts.
 *
 * @author Michał Ochman <ochman.d.michal@gmail.com>
 */
class SilverStripeAwareInitializer implements InitializerInterface
{
    
    private $databaseName;
    
    /**
     * @var Array
     */
    protected $ajaxSteps;

    /**
     * @var Int Timeout in milliseconds
     */
    protected $ajaxTimeout;

    /**
     * @var String {@link see SilverStripeContext}
     */
    protected $adminUrl;

    /**
     * @var String {@link see SilverStripeContext}
     */
    protected $loginUrl;

    /**
     * @var String {@link see SilverStripeContext}
     */
    protected $screenshotPath;

    /**
     * Initializes initializer.
     */
    public function __construct($frameworkPath)
    {
        $this->bootstrap($frameworkPath);
        $this->databaseName = $this->initializeTempDb();
    }

    public function __destruct()
    {
        $this->deleteTempDb();
    }

    /**
     * Checks if initializer supports provided context.
     *
     * @param ContextInterface $context
     *
     * @return Boolean
     */
    public function supports(ContextInterface $context)
    {
        return $context instanceof SilverStripeAwareContextInterface;
    }

    /**
     * Initializes provided context.
     *
     * @param ContextInterface $context
     */
    public function initialize(ContextInterface $context)
    {
        $context->setDatabase($this->databaseName);
        $context->setAjaxSteps($this->ajaxSteps);
        $context->setAjaxTimeout($this->ajaxTimeout);
        $context->setScreenshotPath($this->screenshotPath);
        $context->setAdminUrl($this->adminUrl);
        $context->setLoginUrl($this->loginUrl);
    }

    public function setAjaxSteps($ajaxSteps)
    {
        if($ajaxSteps) $this->ajaxSteps = $ajaxSteps;
    }

    public function getAjaxSteps()
    {
        return $this->ajaxSteps;
    }

    public function setAjaxTimeout($ajaxTimeout)
    {
        $this->ajaxTimeout = $ajaxTimeout;
    }

    public function getAjaxTimeout()
    {
        return $this->ajaxTimeout;
    }

    public function setAdminUrl($adminUrl)
    {
        $this->adminUrl = $adminUrl;
    }

    public function getAdminUrl()
    {
        return $this->adminUrl;
    }

    public function setLoginUrl($loginUrl)
    {
        $this->loginUrl = $loginUrl;
    }

    public function getLoginUrl()
    {
        return $this->loginUrl;
    }

    public function setScreenshotPath($screenshotPath)
    {
        $this->screenshotPath = $screenshotPath;
    }

    public function getScreenshotPath()
    {
        return $this->screenshotPath;
    }

    /**
     * @param String Absolute path to 'framework' module
     */
    protected function bootstrap($frameworkPath)
    {
        file_put_contents('php://stdout', 'Bootstrapping' . PHP_EOL);

        // Connect to database and build manifest
        $_GET['flush'] = 1;
        require_once $frameworkPath . '/core/Core.php';
        unset($_GET['flush']);

        // Remove the error handler so that PHPUnit can add its own
        restore_error_handler();
    }

    protected function initializeTempDb()
    {
        $dbname = \SapphireTest::create_temp_db();
        file_put_contents('php://stdout', "Creating temp DB $dbname" . PHP_EOL);
        \DB::set_alternative_database_name($dbname);

        return $dbname;
    }

    protected function deleteTempDb()
    {
        file_put_contents('php://stdout', "Killing temp DB" . PHP_EOL);
        \SapphireTest::kill_temp_db();
        \DB::set_alternative_database_name(null);
    }
}