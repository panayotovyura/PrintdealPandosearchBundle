<?php

namespace Tests\Printdeal\PandosearchBundle\Service;

use PHPUnit\Framework\TestCase;
use Printdeal\PandosearchBundle\Builder\BuilderInterface;
use Printdeal\PandosearchBundle\Criteria\SerializableInterface;
use Printdeal\PandosearchBundle\Exception\BuilderNotFoundException;
use Printdeal\PandosearchBundle\Service\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    public function testBuild()
    {
        $builtOutput = ['full' => true];

        $objectToBuild = $this->createMock(SerializableInterface::class);

        $correctBuilder = $this->createMock(BuilderInterface::class);
        $incorrectBuilder = $this->createMock(BuilderInterface::class);

        $builderManager = new QueryBuilder();
        $builderManager->addBuilder($incorrectBuilder);
        $builderManager->addBuilder($correctBuilder);

        $incorrectBuilder->expects($this->once())
            ->method('supports')
            ->with($objectToBuild)
            ->willReturn(false);

        $correctBuilder->expects($this->once())
            ->method('supports')
            ->with($objectToBuild)
            ->willReturn(true);
        $correctBuilder->expects($this->once())
            ->method('build')
            ->with($objectToBuild)
            ->willReturn($builtOutput);

        $this->assertEquals($builtOutput, $builderManager->build($objectToBuild));
    }

    public function testBuilderNotFound()
    {
        $objectToBuild = $this->createMock(SerializableInterface::class);
        $builderManager = new QueryBuilder();

        $this->expectException(BuilderNotFoundException::class);
        $builderManager->build($objectToBuild);
    }
}
