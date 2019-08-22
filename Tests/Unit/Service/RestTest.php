<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Service;

use Brotkrueml\JobRouterConnector\Service\Rest;
use PHPUnit\Framework\TestCase;

class RestTest extends TestCase
{
    /** @var Rest */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new Rest();
    }

    /**
     * @test
     * @dataProvider dataProviderForGetReadableErrorMessage
     *
     * @param string $errorMessage
     * @param string $expected
     */
    public function getReadableErrorMessage(string $errorMessage, string $expected): void
    {
        $actual = $this->subject->getReadableErrorMessage($errorMessage);

        $this->assertSame($expected, $actual);
    }

    public function dataProviderForGetReadableErrorMessage(): array
    {
        return [
            'message is not JSON' => [
                'this is just a simple string',
                'this is just a simple string',
            ],
            'one error in JSON' => [
                \json_encode(['errors' => ['-' => ['some error']]]),
                'some error',
            ],
            'two errore in JSON' => [
                \json_encode(['errors' => ['-' => ['some error', 'another error']]]),
                'some error / another error',
            ],
        ];
    }
}
