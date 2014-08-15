<?php
/**
 * Module context class locator.
 * Provides module context class if found.
 */
namespace SilverStripe\BehatExtension\Specification;

#use SilverStripe\Behat\Suite\SymfonyBundleSuite;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Behat\Testwork\Specification\NoSpecificationsIterator;
use Behat\Testwork\Suite\Suite;


/**
 * @author Sriram Venkatesh <venksriram@gmail.com>
 */
final class ModuleContextClassLocator implements SpecificationLocator {

	/**
	* SpecificationLocator
	*/
	private $baseLocator;

	/**
	* Initializes locator.
	*
	* @param SpecificationLocator $baseLocator
	*/
	public function __construct(SpecificationLocator $baseLocator) {
		$this->baseLocator = $baseLocator;
	}

	/**
	* {@inheritdoc}
	*/
	public function getLocatorExamples() {
		return array(
			"a SilverStripe Module path <comment>(@BundleName/)</comment>"
		);
	}

	/**
	* {@inheritdoc}
	*/
	public function locateSpecifications(Suite $suite, $locator) {
		if (0 !== strpos($locator, '@' . $suite->getName())) {
			return new NoSpecificationsIterator($suite);
		}

		$locatorSuffix = substr($locator, strlen($suite->getName()) + 1);
		
		return $this->baseLocator->locateSpecifications($suite, 'SilverStripe\Cms\Test\Behaviour' . $locatorSuffix);
	}
}