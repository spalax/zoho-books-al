<?php
namespace ZohoBooksAL\Tests;

use ZohoBooksAL\EntityManager;
use ZohoBooksAL\Persister\PersisterInterface;
use ZohoBooksAL\Repository\BasicRepository;
use ZohoBooksAL\UnitOfWork;

class EntityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $configurationMock,
              $unitOfWorkMock,
              $metadataCollectionMock,
              $factoryMock;

    public function setUp()
    {
        $this->configurationMock = $this->getMockBuilder('ZohoBooksAL\Configuration\ConfigurationInterface')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->unitOfWorkMock = $this->getMockBuilder('ZohoBooksAL\UnitOfWork')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->metadataCollectionMock = $this->getMockBuilder('ZohoBooksAL\Metadata\MetadataCollection')
                                             ->disableOriginalConstructor()
                                             ->getMock();

        $this->factoryMock = $this->getMockBuilder('DI\FactoryInterface')
                                  ->disableOriginalConstructor()
                                  ->getMock();;

        $this->entityManager = new EntityManager($this->configurationMock,
                                                 $this->unitOfWorkMock,
                                                 $this->metadataCollectionMock,
                                                 $this->factoryMock);
    }

    public function testBasicRepositoryCreated()
    {
        $this->factoryMock->expects($this->once())->method('make')
                          ->with($this->equalTo(BasicRepository::class),
                                 $this->equalTo(['entityName'=>'TestEntityName',
                                                 'unitOfWork'=>$this->unitOfWorkMock]))
                          ->willReturn($this->getMockBuilder(BasicRepository::class)
                                            ->disableOriginalConstructor()->getMock());


        $this->assertInstanceOf(BasicRepository::class, $this->entityManager->getRepository('TestEntityName'));
    }

    public function testFindWillCallForLoadEntity()
    {
        $id = 123;
        $entityName = 'TestEntityName';

        $persisterMock = $this->getMockBuilder(PersisterInterface::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        $persisterMock->expects($this->once())
                      ->method('load')
                      ->with($this->equalTo($id))
                      ->willReturn(null);

        $this->unitOfWorkMock->expects($this->once())->method('getEntityPersister')
                             ->with($this->equalTo($entityName))
                             ->willReturn($persisterMock);

        $this->assertNull($this->entityManager->find($entityName, $id));
    }

    public function testPersistCall()
    {
        $entityMock = $this->getMockBuilder('ZohoBooksAL\Entity\EntityInterface')
                       ->disableOriginalConstructor()->getMock();

        $this->unitOfWorkMock->expects($this->once())->method('persist')
                             ->with($this->equalTo($entityMock));

        $this->assertNull($this->entityManager->persist($entityMock));
    }

    public function testFlush()
    {
        $this->unitOfWorkMock->expects($this->once())
             ->method('commit');

        $this->assertNull($this->entityManager->flush());
    }
}
