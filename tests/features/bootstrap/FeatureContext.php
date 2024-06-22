<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class FeatureContext implements Context
{

    /**
     * @var MinkContext
     */
    private $minkContext;

    /**
     * @var \Behat\Mink\Session
     */
    private $session;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope) {
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext(MinkContext::class);
        $this->session = $this->minkContext->getSession();
    }

    /**
     * @Then I wait :sec second
     * @Then I wait :secs seconds
     */
    public function iWaitSeconds($secs)
    {
        sleep($secs);
    }

    /**
     * @When I click the :locator element
     */
    public function iClickTheElement($locator)
    {
        if ($element = $this->session->getPage()->find('css', $locator)) {
            $element->click();
        }
    }

    /**
     * @When I jquery click the :locator element
     */
    public function iJqueryClickTheElement($locator)
    {
        $this->session->executeScript("document.querySelector('$locator').click();");
    }

    /**
     * @Then the query string :query should match :string
     */
    public function theQueryStringShouldMatch($query, $string)
    {
        // Get the current page URL
        $currentUrl = $this->session->getCurrentUrl();

        // Parse the URL to get its components
        $parsedUrl = parse_url($currentUrl);

        // Extract the query string
        $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

        $pattern = "/.*?$query=($string.*?)/";
        preg_match($pattern, $queryString, $matches);
        if (isset($matches[1]) && $matches[1] == $string) {
            return true;
        }

        var_dump($queryString, $matches);
        throw new \Exception("The query string $query does not match $string");
    }
}
