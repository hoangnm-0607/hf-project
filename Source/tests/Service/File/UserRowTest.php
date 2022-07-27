<?php

declare(strict_types=1);

namespace Tests\Service\File;

use App\Service\File\UserRow;
use PHPUnit\Framework\TestCase;

final class UserRowTest extends TestCase
{
    private UserRow $row;

    protected function setUp(): void
    {
        $headerArr = ['First Name', 'Last Name', 'Private Email', 'Business Email', 'Phone number', 'Gender', 'Date of Birth', 'New Name'];
        $data = ['name', ' last name ', 'pr@gmail.com', '', '+48111111111', 'male', '2000-04-02', 'Jack'];

        $header = new UserRow($headerArr);
        $this->row = new UserRow($data, $header);
    }

    protected function tearDown(): void
    {
        unset(
          $this->row,
        );
    }

    public function testData(): void
    {
        self::assertSame('name', $this->row->getFirstName());
        self::assertSame('last name', $this->row->getLastName());
        self::assertSame('pr@gmail.com', $this->row->getPrivateEmail());
        self::assertNull($this->row->getBusinessEmail());
        self::assertSame('+48111111111', $this->row->getPhoneNumber());
        self::assertSame('male', $this->row->getGender());
        self::assertInstanceOf(\DateTime::class, $this->row->getDateOfBirth());
        self::assertSame('2000-04-02', $this->row->getDateOfBirth()->format('Y-m-d'));
        self::assertSame('name last name 2000-04-02', $this->row->getFullNameWithBirthDate());
        self::assertSame(['New Name' => 'Jack'], $this->row->getCustomFields());
    }
}
