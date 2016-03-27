<?php
namespace ZohoBooksAL\Tests;

use ZohoBooksAL\Metadata\ClassMetadata;
use ZohoBooksAL\Persister\BasicEntityPersister;
use ZohoBooksAL\Persister\PersisterInterface;
use ZohoBooksAL\UnitOfWork;

class UnitOfWorkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mapperMock,
              $metadataCollectionMock,
              $factoryMock,
              $basicEntityPersisterMock;

    /**
     * @var UnitOfWork
     */
    protected $unitOfWork;

    public function setUp()
    {

        $this->mapperMock = $this->getMockBuilder('ZohoBooksAL\Mapper\MapperInterface')
                                 ->disableOriginalConstructor()->getMock();
        $this->metadataCollectionMock = $this->getMockBuilder('ZohoBooksAL\Metadata\MetadataCollection')
                                             ->disableOriginalConstructor()->getMock();
        $this->factoryMock = $this->getMockBuilder('DI\FactoryInterface')
                                  ->disableOriginalConstructor()->getMock();

        $this->basicEntityPersisterMock = $this->getMockBuilder(BasicEntityPersister::class)
                                               ->disableOriginalConstructor()->getMock();

        $this->unitOfWork = new UnitOfWork($this->metadataCollectionMock,
                                           $this->mapperMock,
                                           $this->factoryMock);
    }

    public function testGetNewPersistableEntityPersister()
    {
        $entityName = 'TestEntityName';

        $metadataClassMock = $this->getMockBuilder(ClassMetadata::class)->disableOriginalConstructor()->getMock();
        $metadataClassMock->expects($this->once())->method('isPersistable')->willReturn(true);

        $this->metadataCollectionMock->expects($this->exactly(2))
             ->method('getClassMetadata')
             ->with($this->equalTo($entityName))
             ->willReturn($metadataClassMock);

        $this->factoryMock->expects($this->once())->method('make')
                          ->with($this->equalTo(BasicEntityPersister::class),
                                 $this->equalTo(['mapper'=>$this->mapperMock,
                                                 'classMetadata' => $metadataClassMock]))
                          ->willReturn($this->basicEntityPersisterMock);

        $this->assertEquals($this->basicEntityPersisterMock, $this->unitOfWork->getEntityPersister($entityName));
    }

    /**
     * @expectedException \ZohoBooksAL\Exception\NotPersistableEntityException
     */
    public function testExceptionForNotPersistableEntityPersister()
    {
        $entityName = 'TestEntityName';

        $metadataClassMock = $this->getMockBuilder(ClassMetadata::class)->disableOriginalConstructor()->getMock();
        $metadataClassMock->expects($this->once())->method('isPersistable')->willReturn(false);

        $this->metadataCollectionMock->expects($this->exactly(2))
                                     ->method('getClassMetadata')
                                     ->with($this->equalTo($entityName))
                                     ->willReturn($metadataClassMock);

        $this->factoryMock->expects($this->once())->method('make')
                          ->with($this->equalTo(BasicEntityPersister::class),
                              $this->equalTo(['mapper'=>$this->mapperMock,
                                              'classMetadata' => $metadataClassMock]))
                          ->willReturn($this->basicEntityPersisterMock);

        $this->unitOfWork->getEntityPersister($entityName);
    }

    public function testCreateEntityPersisterOnlyOnceThenCache()
    {
        $entityName = 'TestEntityName';

        $this->factoryMock->expects($this->once())->method('make')
                          ->willReturn($this->basicEntityPersisterMock);

        $metadataClassMock = $this->getMockBuilder(ClassMetadata::class)->disableOriginalConstructor()->getMock();
        $metadataClassMock->expects($this->exactly(2))->method('isPersistable')->willReturn(true);

        $this->metadataCollectionMock->expects($this->exactly(3))
                                     ->method('getClassMetadata')
                                     ->with($this->equalTo($entityName))
                                     ->willReturn($metadataClassMock);

        $resultEntityPersister = $this->unitOfWork->getEntityPersister($entityName);
        $this->assertEquals($resultEntityPersister, $this->unitOfWork->getEntityPersister($entityName));
    }

    public function testPersistCall()
    {
        $unitOfWork = $this->getMock('ZohoBooksAL\UnitOfWork',['getEntityPersister'],
                                     [$this->metadataCollectionMock,
                                      $this->mapperMock,
                                      $this->factoryMock]);


        $projectEntity = \Mockery::mock('ZohoBooksAL\Entity\EntityInterface');
        $projectEntity2 = \Mockery::mock('ZohoBooksAL\Entity\EntityInterface');

        $unitOfWork->persist($projectEntity);
        $unitOfWork->persist($projectEntity2);

        $reflectionMock = $this->getMockBuilder(\ReflectionObject::class)->disableOriginalConstructor()->getMock();
        $reflectionMock->expects($this->exactly(2))
                       ->method('getName')
                       ->willReturnSelf();

        $this->factoryMock->expects($this->exactly(2))
             ->method('make')
             ->with($this->equalTo(\ReflectionObject::class),
                    $this->equalTo(['argument'=>$projectEntity]))
             ->willReturn($reflectionMock);

        $persisterMock = $this->getMockBuilder(PersisterInterface::class)->disableOriginalConstructor()->getMock();
        $unitOfWork->expects($this->exactly(2))->method('getEntityPersister')
                   ->willReturn($persisterMock);

        $persisterMock->expects($this->exactly(2))->method('save')
                      ->withConsecutive([$this->equalTo($projectEntity)], [$this->equalTo($projectEntity2)]);

        $unitOfWork->commit();

        // And call second time to assert mock
        // call times
        $unitOfWork->commit();
    }
}
