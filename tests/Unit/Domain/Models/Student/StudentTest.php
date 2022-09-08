<?php

namespace App\Tests\Unit\Domain\Models\Student;

use App\Domain\Models\Address\Address;
use App\Domain\Models\Email\Email;
use App\Domain\Models\Student\Name;
use App\Domain\Models\Student\Student;
use App\Domain\Models\Video\Video;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    private Student $student;

    protected function setUp(): void
    {
        $this->student = new Student(
            new Email('daniel@danielneto.dev.br'),
            new DateTimeImmutable('2000-07-17'),
            new Name('Daniel', 'Neto'),
            new Address(
                'Minha Rua',
                '314',
                'Meu Bairro',
                'Votuporanga',
                'São Paulo',
                'Brasil'
            )
        );
    }

    public function testNameMustBeRepresentedAsString(): void
    {
        self::assertEquals('Daniel Neto', $this->student->fullName());
    }


    public function testEmailMustBeRepresentedAsString(): void
    {
        self::assertEquals('daniel@danielneto.dev.br', $this->student->email());
    }

    public function testAddressMustBeRepresentedAsString(): void
    {
        self::assertEquals('Minha Rua, n 314 - Meu Bairro. Votuporanga / São Paulo - Brasil', $this->student->fullAddress());
    }

    public function testStudentWithoutWatchedVideosHasAccess(): void
    {
        self::assertTrue($this->student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoInLessThan90DaysHasAccess(): void
    {
        $date = new DateTimeImmutable('89 days');
        $this->student->watch(new Video(), $date);

        self::assertTrue($this->student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoInLessThan90DaysButOtherVideosWatchedHasAccess(): void
    {
        $this->student->watch(new Video(), new DateTimeImmutable('-89 days'));
        $this->student->watch(new Video(), new DateTimeImmutable('-60 days'));
        $this->student->watch(new Video(), new DateTimeImmutable('-30 days'));

        self::assertTrue($this->student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoIn90DaysDoesntHaveAccess(): void
    {
        $date = new DateTimeImmutable('-90 days');
        $this->student->watch(new Video(), $date);

        self::assertFalse($this->student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoIn90DaysButOtherVideosWatchedDoesntHaveAccess(): void
    {
        $this->student->watch(new Video(), new DateTimeImmutable('-90 days'));
        $this->student->watch(new Video(), new DateTimeImmutable('-60 days'));
        $this->student->watch(new Video(), new DateTimeImmutable('-30 days'));

        self::assertFalse($this->student->hasAccess());
    }
}
