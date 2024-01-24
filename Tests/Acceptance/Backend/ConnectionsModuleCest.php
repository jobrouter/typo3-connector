<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Acceptance\Backend;

use JobRouter\AddOn\Typo3Connector\Tests\Acceptance\Support\BackendTester;
use TYPO3\CMS\Core\Information\Typo3Version;

final class ConnectionsModuleCest
{
    private const CONNECTOR_MODULE_SELECTOR = 'jobrouter_connections';
    private const MOCKSERVER_BASE_URL = 'http://mockserver:1080/';

    public function _before(BackendTester $I): void
    {
        $I->loginAs('admin');
    }

    public function _after(BackendTester $I): void
    {
        $I->deleteJobRouterKey();
        $I->truncateConnectionTable();
    }

    public function onVeryFirstCallModuleComplainsAboutMissingKeyFile(BackendTester $I): void
    {
        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSee('Key file does not exist');
    }

    public function afterGenerationOfKeyModuleShowsInformativeBoxToCreateNewConnection(BackendTester $I): void
    {
        $I->createJobRouterKey();

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSee('No connections found');
    }

    public function whenNoConnectionIsAvailableClickOnCreateNewConnectionButtonShowsBackendFormForConnections(BackendTester $I): void
    {
        $I->createJobRouterKey();

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeLink('Create new connection');
        $I->click('Create new connection');
        $I->waitForText('Create new JobRouter Connection on root level');
    }

    public function whenConnectionIsAvailableItIsShownOnTheModulePage(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importConnectionFixture('https://example.org/', 'secretPwd');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->canSee('Some JobRouter Connection Name', '#jobrouter-connection-list-name-1');
        $I->canSee('some_connection_handle', '#jobrouter-connection-list-handle-1');
        $I->canSee('https://example.org/', '#jobrouter-connection-list-baseurl-1');
        $I->canSee('john.doe', '#jobrouter-connection-list-username-1');
        $I->canSeeInSource('actions.svg#actions-check', '#jobrouter-connection-list-verify-1');
    }

    public function whenConnectionIsAvailableWithVerifySetToFalseItIsShownOnTheModulePage(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importConnectionFixture('https://example.org/', 'secretPwd', 0);

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->canSee('Some JobRouter Connection Name', '#jobrouter-connection-list-name-1');
        $I->canSee('some_connection_handle', '#jobrouter-connection-list-handle-1');
        $I->canSee('https://example.org/', '#jobrouter-connection-list-baseurl-1');
        $I->canSee('john.doe', '#jobrouter-connection-list-username-1');
        $I->canSeeInSource('actions.svg#actions-close', '#jobrouter-connection-list-verify-1');
    }

    public function whenConnectionIsAvailableAClickOnTheNameLinkOpensToEditForm(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importConnectionFixture('https://example.org/', 'secretPwd');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->click('Some JobRouter Connection Name');
        $I->waitForText('Edit JobRouter Connection "Some JobRouter Connection Name" on root level');
    }

    public function whenConnectionIsAvailableAClickOnTheEditButtonOpensToEditForm(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importConnectionFixture('https://example.org/', 'secretPwd');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->click('#jobrouter-connection-list-edit-1');
        $I->waitForText('Edit JobRouter Connection "Some JobRouter Connection Name" on root level');
    }

    public function whenConnectionIsAvailableAClickOnTheCheckButtonShowsASuccessfulNotification(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importConnectionFixture(self::MOCKSERVER_BASE_URL, 'secretPwd');
        $I->createMockServerExpectation(self::MOCKSERVER_BASE_URL, 'john.doe', 'secretPwd');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->dontSee('5.1.5');
        $I->click('#jobrouter-connection-list-check-1');
        $I->switchToMainFrame();
        $I->waitForText('Connection established successfully');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('5.1.5');
    }

    public function whenConnectionIsAvailableAClickOnTheCheckButtonShowsAnErrorNotificationIfNoJobRouterConnection(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importConnectionFixture(self::MOCKSERVER_BASE_URL, 'secretPwd');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->click('#jobrouter-connection-list-check-1');
        $I->switchToMainFrame();
        $I->waitForText('Error fetching resource');
    }

    public function whenConnectionIsAvailableAClickOnTheOpenButtonOpensThatUrl(BackendTester $I): void
    {
        $url = self::MOCKSERVER_BASE_URL . 'mockserver/dashboard';

        $I->createJobRouterKey();
        $I->importConnectionFixture($url, 'secretPwd');

        $I->click($this->moduleIdentifier());
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->click('#jobrouter-connection-list-open-1');
        $I->amOnUrl($url);
    }

    private function moduleIdentifier(): string
    {
        if ((new Typo3Version())->getMajorVersion() < 12) {
            return '#' . self::CONNECTOR_MODULE_SELECTOR;
        }
        return \sprintf('[data-moduleroute-identifier="%s"]', self::CONNECTOR_MODULE_SELECTOR);
    }
}
