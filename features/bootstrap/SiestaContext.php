<?php

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

require("user.php");

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class SiestaContext extends BehatContext {

    /**
     * @Given /^I have a class that extends Siesta$/
     */
    public function iHaveAClass($argument)
    {
        $this->class = new User();
    }

    /**
     * @When /^I query the methods$/
     */
    public function iQueryTheMethods()
    {
        $f = new ReflectionClass('User');
        $this->output = [];
        foreach ($f->getMethods() as $method) {
            $this->output[] = $method->name;
        }
    }

    /**
     * @Then /^it should have these methods:$/
     */
     public function itShouldHaveAMethod(PyStringNode $methods)
     {
        $expected = explode("\n",$methods);

        foreach ($expected as $method) {

            if (!in_array($method,$this->output))
                throw new Exception("Class does not contain method: " + $method);

        }
     }

     /**
      * @When /^I call static method "([^"]*)"$/
      */
     public function iCallStatic($method)
     {
         $this->output = User::$method();
     }

     /**
      * @When /^I call instance method "([^"]*)"$/
      */
     public function iCallInstance($method)
     {
         $this->output = $this->class->$method();
     }

     /**
      * @Then /^the response should be a "([^"]*)"$/
      */
      public function theResponseShouldBeA($type)
      {
          if(!gettype($this->output) == $type)
              throw new Exception("Response is not of format: " + $type);
      }

     /**
      * @Then /^the length should be "([^"]*)"$/
      */
      public function theLengthShouldBe($length)
      {
          $length = (int)$length;
          assertEquals(count($this->output), $length);
      }

}