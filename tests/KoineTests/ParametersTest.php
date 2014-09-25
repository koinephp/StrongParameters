<?php

namespace KoineTests;

use Koine\Parameters;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class ParametersTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Parameters::$throwExceptions = true;
    }

    public function getUserExample()
    {
        return new Parameters(array(
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
        $this->assertInstanceOf('Koine\Hash', $this->getUserExample());
    }

    /**
     * @test
     */
    public function throwExceptionsDefaultsToStaticConfiguration()
    {
        $params = $this->getUserExample();
        $this->assertTrue($params->getThrowExceptions());

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

        $params = $this->getUserExample();
        $params = new Parameters($params->toArray());

        $user = $params->requireParam('user');

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
        $params = $this->getUserExample();
        $params->requireParam('person');
    }

    /**
     * @test
     * @expectedException \Koine\Parameters\ParameterMissingException
     * @expectedExceptionMessage Missing param 'empty'
     */
    public function throwExceptionWhenRequiredParamIsEmpty()
    {
        $params = $this->getUserExample();
        $params['empty'] = new Parameters();
        $params->requireParam('empty');
    }

    /**
     * @test
     * @expectedException \Koine\Parameters\ParameterMissingException
     * @expectedExceptionMessage Missing param 'empty'
     */
    public function throwExceptionWhenRequiredParamIsEmptyArray()
    {
        $params = $this->getUserExample();
        $params['empty'] = array();
        $params->requireParam('empty');
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

        $params = $this->getUserExample()->requireParam('user');

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
        $params = $this->getUserExample();

        $this->assertTrue($this->getUserExample()->getThrowExceptions());
        $params['user']['admin'] = true;
        $params->requireParam('user')->permit(array('name', 'lastName'));
    }

    /**
     * @test
     */
    public function permitReturnsReturnsAllowedParameters()
    {
        $params = $this->getUserExample();

        $permitted = $params->requireParam('user')
            ->permit(array('name', 'lastName'));

        $this->assertInstanceOf(get_class($params), $permitted);

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

        $params = $this->getUserExample();

        $this->object = new Parameters($params->toArray());

        $this->assertFalse($params->getThrowExceptions());

        $permitted = $params->requireParam('user')->permit(array(
            'name',
        ));

        $this->assertInstanceOf(get_class($params), $permitted);

        $expected = array('name' => 'Jon');

        $this->assertEquals($expected, $permitted->toArray());
    }

    /**
     * @test
     */
    public function permitReturnsEmptyArrayIfArrayIsPassedInThePermitHash()
    {
        $dataCollection = array(
            array('id' => array()),
            array('id' => array('foo')),
            array('id' => array(1, 2, 3)),
        );

        foreach ($dataCollection as $data) {
            $params = new Parameters($data);

            $actual = $params->permit(array('id' => array()))->toArray();

            $this->assertEquals($data, $actual);
        }
    }

    /**
     * @test
     * @group focus
     */
    public function permitHandlesSimpleHashParams()
    {
        Parameters::$throwExceptions = false;

        $data = array(
            'authors' => array(
                array(
                    'name'     => 'Jon',
                    'birthday' => '1960-01-02',
                ),
                array(
                    'name'     => 'Daniel',
                    'birthday' => '1960-01-02',
                ),
            )
        );

        $params = new Parameters($data);

        $actual = $params->permit(array('authors' => array('name')))->toArray();

        $expected = array(
            'authors' => array(
                array('name' => 'Jon'),
                array('name' => 'Daniel'),
            )
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function permitHandlesNestedParams()
    {
        $this->markTestIncomplete();
        Parameters::$throwExceptions = false;

        $data = array(
            'book' => array(
                'title'   => 'Some Tilte',
                'edition' => '3',
                'authors' => array(
                    array(
                        'name'     => 'Jon',
                        'birthday' => '1960-01-02',
                    ),
                    array(
                        'name'     => 'Daniel',
                        'birthday' => '1960-01-02',
                    ),
                )
            ),
            'foo' => 'bar',
            'bar' => 'foo'
        );

        $params = new Parameters($data);

        $actual = $params->permit(array(
            'book' => array('authors' => array('name')),
            'foo'
        ))->toArray();

        $expected = array(
            'book' => array(
                'title'   => 'Some Tilte',
                'authors' => array(
                    array('name' => 'Jon'),
                    array('name' => 'Daniel'),
                )
            ),
            'foo' => 'bar'
        );

        $this->assertEquals($expected, $actual);
    }
}
