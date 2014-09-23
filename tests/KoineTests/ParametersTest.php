<?php

namespace KoineTests;

use Koine\Parameters;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ParametersTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        Parameters::$throwExceptions = true;

        $this->object = new Parameters(array(
            'empty' => array(),
            'user'  => array(
                'name'     => 'Jon',
                'lastName' => 'Doe',
            )
        ));
    }

    public function tearDown()
    {
        Parameters::$throwExceptions = true;
    }

    /**
     * @test
     */
    public function inheritsFromKoineHash()
    {
        $this->assertInstanceOf('Koine\Hash', $this->object);
    }

    /**
     * @test
     */
    public function throwExceptionsDefaultsToStaticConfiguration()
    {
        $this->assertTrue($this->object->getThrowExceptions());
        Parameters::$throwExceptions = false;

        $params = new Parameters();
        $this->assertFalse($params->getThrowExceptions());
    }

    /**
     * @test
     */
    public function setThrowExceptionsToFalseCascadesToNewParameters()
    {
        Parameters::$throwExceptions = false;
        $this->object = new Parameters($this->object->toArray());

        $user = $this->object->requireParam('user');

        $this->assertFalse($user->getThrowExceptions());

        $params = $user->permit(array('name', 'lastName'));
        $this->assertFalse($params->getThrowExceptions());
    }

    /**
     * @test
     * @expectedException \Koine\Parameters\ParameterMissingException
     * @expectedExceptionMessage Missing param 'person'
     */
    public function throwExceptionWhenRequiredParamDoesNotExist()
    {
        $this->object->requireParam('person');
    }

    /**
     * @test
     * @expectedException \Koine\Parameters\ParameterMissingException
     * @expectedExceptionMessage Missing param 'empty'
     */
    public function throwExceptionWhenRequiredParamIsEmpty()
    {
        $this->object->requireParam('empty');
    }

    /**
     * @test
     */
    public function requireParamReturnsOnlyTheRequiredParam()
    {
        $expected = array(
            'name'     => 'Jon',
            'lastName' => 'Doe',
        );

        $params = $this->object->requireParam('user');

        $this->assertEquals($expected, $params->toArray());

        $this->assertInstanceOf('Koine\Parameters', $params);
    }

    /**
     * @test
     * @expectedException Koine\Parameters\UnpermittedParameterException
     * @expectedExceptionMessage Parameter 'admin' is not allowed
     */
    public function permitThrowsExeptionWhenUnpermitedParamIsPassedIn()
    {
        $this->assertTrue($this->object->getThrowExceptions());
        $this->object['user']['admin'] = true;
        $this->object->requireParam('user')->permit(array('name', 'lastName'));
    }

    /**
     * @test
     */
    public function permitReturnsReturnsAllowedParameters()
    {
        $permitted = $this->object->requireParam('user')
            ->permit(array('name', 'lastName'));

        $this->assertInstanceOf(get_class($this->object), $permitted);

        $expected = array(
            'name'     => 'Jon',
            'lastName' => 'Doe',
        );

        $this->assertEquals($expected, $permitted->toArray());
    }

    /**
     * @test
     */
    public function permitFiltersOutParamsThatAreNotAllowed()
    {
        Parameters::$throwExceptions = false;
        $this->object = new Parameters($this->object->toArray());

        $this->assertFalse($this->object->getThrowExceptions());

        $permitted = $this->object->requireParam('user')->permit(array(
            'name',
        ));

        $this->assertInstanceOf(get_class($this->object), $permitted);

        $expected = array('name' => 'Jon');

        $this->assertEquals($expected, $permitted->toArray());
    }
}
