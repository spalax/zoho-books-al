<?php
namespace ZohoBooksAL\Tests\Configuration\Reader;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use ZohoBooksAL\Configuration\Reader\ConfigFileReader;

class ConfigFileReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @expectedException \ZohoBooksAL\Configuration\Reader\InvalidConfigException
     * @expectedExceptionMessage Could not found configuration file
     */
    public function testForFileWhichNotExists()
    {
        new ConfigFileReader('');
    }

    /**
     * @expectedException \ZohoBooksAL\Configuration\Reader\InvalidConfigException
     * @expectedExceptionMessage Config is empty
     */
    public function testForExceptionOnEmptyConfig()
    {
        $this->root->addChild(vfsStream::newFile('config.inc.php'));
        new ConfigFileReader($this->root->getChild('config.inc.php')->url());
    }

    /**
     * @expectedException \ZohoBooksAL\Configuration\Reader\InvalidConfigException
     * @expectedExceptionMessage primary key in configuration array
     */
    public function testForMissingPrimaryKey()
    {
        $configFile = vfsStream::newFile('config.inc.php');
        $configFile->setContent('<?php return ["test_key"=>"test-value"];');
        $this->root->addChild($configFile);

        $configuration = new ConfigFileReader($this->root->getChild('config.inc.php')->url());
        $configuration->getConfiguration('primary-key');
    }

    public function testForReadingConfigFile()
    {
        $configFile = vfsStream::newFile('config.inc.php');
        $configFile->setContent('<?php return ["test_key"=>"test-value"];');
        $this->root->addChild($configFile);

        $configFileReading = new ConfigFileReader($this->root->getChild('config.inc.php')->url());
        $this->assertEquals(['test_key'=>'test-value'], $configFileReading->getConfiguration());
    }

    public function testForReadingWithOptionalKeyParameterConfigFile()
    {
        $configFile = vfsStream::newFile('config.inc.php');
        $configFile->setContent('<?php return ["primary-key"=>["test-key"=>"test-value"]];');
        $this->root->addChild($configFile);

        $configFileReading = new ConfigFileReader($this->root->getChild('config.inc.php')->url());
        $this->assertEquals(['test-key'=>'test-value'], $configFileReading->getConfiguration('primary-key'));
    }
}
