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
        $this->object = new Parameters(array(
            'empty' => array(),
            'user'  => array(
                'name'     => 'Jon',
                'lastName' => 'Doe',
            )
        ));
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
}
