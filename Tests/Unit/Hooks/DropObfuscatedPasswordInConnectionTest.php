<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Unit\Hooks;

use JobRouter\AddOn\Typo3Connector\Evaluation\Password;
use JobRouter\AddOn\Typo3Connector\Hooks\DropObfuscatedPasswordInConnection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\DataHandling\DataHandler;

final class DropObfuscatedPasswordInConnectionTest extends TestCase
{
    private DropObfuscatedPasswordInConnection $subject;

    protected function setUp(): void
    {
        $this->subject = new DropObfuscatedPasswordInConnection();
    }

    #[Test]
    public function tableIsNotConnectionTableThenFieldsAreUntouched(): void
    {
        $incomingFieldArray = [
            'password' => 'some password',
        ];

        $this->subject->processDatamap_preProcessFieldArray($incomingFieldArray, 'some_table', 0, self::createStub(DataHandler::class));

        self::assertSame([
            'password' => 'some password',
        ], $incomingFieldArray);
    }

    #[Test]
    public function tableIsConnectionTabelThenPasswordFieldIsRemovedWhenObfuscated(): void
    {
        $incomingFieldArray = [
            'password' => Password::OBFUSCATED_VALUE,
            'another' => 'field',
        ];

        $this->subject->processDatamap_preProcessFieldArray($incomingFieldArray, 'tx_jobrouterconnector_domain_model_connection', 0, self::createStub(DataHandler::class));

        self::assertSame([
            'another' => 'field',
        ], $incomingFieldArray);
    }

    #[Test]
    public function tableIsConnectionTabelThenPasswordFieldIsRemovedWhenNotObfuscated(): void
    {
        $incomingFieldArray = [
            'password' => 'some password',
            'another' => 'field',
        ];

        $this->subject->processDatamap_preProcessFieldArray($incomingFieldArray, 'tx_jobrouterconnector_domain_model_connection', 0, self::createStub(DataHandler::class));

        self::assertSame([
            'password' => 'some password',
            'another' => 'field',
        ], $incomingFieldArray);
    }
}
