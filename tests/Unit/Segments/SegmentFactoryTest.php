<?php

declare(strict_types=1);

namespace EdifactParser\Tests\Unit\Segments;

use EdifactParser\Segments\BGMBeginningOfMessage;
use EdifactParser\Segments\CNTControl;
use EdifactParser\Segments\DTMDateTimePeriod;
use EdifactParser\Segments\MEADimensions;
use EdifactParser\Segments\NADNameAddress;
use EdifactParser\Segments\PCIPackageId;
use EdifactParser\Segments\SegmentFactory;
use EdifactParser\Segments\UNHMessageHeader;
use EdifactParser\Segments\UnknownSegment;
use EdifactParser\Segments\UNTMessageFooter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SegmentFactoryTest extends TestCase
{
    /** @test */
    public function withDefaultSegments(): void
    {
        $factory = SegmentFactory::withDefaultSegments();

        self::assertInstanceOf(UNHMessageHeader::class, $factory->segmentFromArray(['UNH']));
        self::assertInstanceOf(DTMDateTimePeriod::class, $factory->segmentFromArray(['DTM']));
        self::assertInstanceOf(NADNameAddress::class, $factory->segmentFromArray(['NAD']));
        self::assertInstanceOf(MEADimensions::class, $factory->segmentFromArray(['MEA']));
        self::assertInstanceOf(CNTControl::class, $factory->segmentFromArray(['CNT']));
        self::assertInstanceOf(PCIPackageId::class, $factory->segmentFromArray(['PCI']));
        self::assertInstanceOf(BGMBeginningOfMessage::class, $factory->segmentFromArray(['BGM']));
        self::assertInstanceOf(UNTMessageFooter::class, $factory->segmentFromArray(['UNT']));
        self::assertInstanceOf(UnknownSegment::class, $factory->segmentFromArray(['___']));
    }

    /** @test */
    public function withCustomSegments(): void
    {
        $factory = SegmentFactory::withSegments([
            'UNH' => UNHMessageHeader::class,
        ]);

        self::assertInstanceOf(UNHMessageHeader::class, $factory->segmentFromArray(['UNH']));
        self::assertInstanceOf(UnknownSegment::class, $factory->segmentFromArray(['DTM']));
    }

    /** @test */
    public function exceptionWhenTagTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SegmentFactory::withSegments(['TAG_TOO_LARGE' => UNHMessageHeader::class]);
    }

    /** @test */
    public function exceptionWhenCreatingNonValidTag(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SegmentFactory::withSegments(['NON' => NONFakeSegment::class]);
    }
}
