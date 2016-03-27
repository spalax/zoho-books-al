<?php
namespace ZohoBooksAL\Tests\Configuration;

use ZohoBooksAL\Configuration\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $readerMock;

    /**
     * @var Configuration
     */
    protected $configuration;

    public function setUp()
    {
        $this->readerMock = $this->getMockBuilder('ZohoBooksAL\Configuration\Reader\ReaderInterface')
                                 ->disableOriginalConstructor()->getMock();
    }

    /**
     * @expectedException \ZohoBooksAL\Configuration\InvalidOptionException
     * @expectedExceptionMessage authToken
     */
    public function testMissingMandatoryFields()
    {
        $this->readerMock->expects($this->once())->method('getConfiguration')->willReturn([]);

        new Configuration($this->readerMock);
    }

    /**
     * @expectedException \ZohoBooksAL\Configuration\InvalidOptionException
     * @expectedExceptionMessage serviceUri
     */
    public function testMissingAtLeastOneMandatoryFields()
    {
        $this->readerMock->expects($this->once())->method('getConfiguration')->willReturn(['authToken'=>'test']);

        new Configuration($this->readerMock);
    }

    public function testGettingAuthTokenAndServiceUri()
    {
        $authToken = 'test_value';
        $serviceUri = 'service';
        $this->readerMock->expects($this->once())
             ->method('getConfiguration')->willReturn(['authToken'=>$authToken,
                                                       'serviceUri'=>$serviceUri]);

        $configuration = new Configuration($this->readerMock);

        $this->assertEquals($authToken, $configuration->getAuthToken());
        $this->assertEquals($serviceUri, $configuration->getServiceUri());
    }
}
