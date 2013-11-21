<?php

namespace SilverStripe\BehatExtension\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Loads SilverStripe core. Required to initialize autoloading.
 */
class CoreInitializationPass implements CompilerPassInterface
{
    /**
     * Loads kernel file.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // Connect to database and build manifest
        $frameworkPath = $container->getParameter('behat.silverstripe_extension.framework_path');
        $_GET['flush'] = 1;
        require_once $frameworkPath . '/core/Core.php';
        \TestRunner::use_test_manifest();
        unset($_GET['flush']);

        // Remove the error handler so that PHPUnit can add its own
        restore_error_handler();
    }
}