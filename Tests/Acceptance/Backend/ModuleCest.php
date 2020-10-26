<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Acceptance\Backend;

use Brotkrueml\JobRouterConnector\Tests\Acceptance\Support\BackendTester;

class ModuleCest
{
    private static $connectorModuleSelector = '#jobrouter_JobRouterConnectorJobrouterconnector';

    public function _before(BackendTester $I): void
    {
        $I->useExistingSession('admin');
    }

    public function _after(BackendTester $I): void
    {
        $I->deleteJobRouterKey();
        $I->truncateConnectionTable();
    }

    public function onFirstCallModuleComplainsAboutMissingKeyFile(BackendTester $I): void
    {
        $I->click(self::$connectorModuleSelector);
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSee('Key file does not exist');
    }

    public function afterGenerationOfKeyModuleShowsInformativeBoxToCreateNewConnection(BackendTester $I): void
    {
        $I->createJobRouterKey();

        $I->click(self::$connectorModuleSelector);
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSee('No connections found');
    }

    public function whenNoConnectionIsAvailableClickOnCreateNewConnectionButtonShowsBackendFormForConnections(BackendTester $I): void
    {
        $I->createJobRouterKey();

        $I->click(self::$connectorModuleSelector);
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeLink('Create new connection');
        $I->click('Create new connection');
        $I->waitForText('Create new JobRouter Connection on root level');
    }

    public function whenConnectionIsAvailableItIsShownOnTheModulePage(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importDatabaseFixture();

        $I->click(self::$connectorModuleSelector);
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->canSee('Some JobRouter Connection Name', '#jobrouter-connection-list-name-1');
        $I->canSee('some_connection_handle', '#jobrouter-connection-list-handle-1');
        $I->canSee('https://example.org/', '#jobrouter-connection-list-baseurl-1');
        $I->canSee('jobrouter_user', '#jobrouter-connection-list-username-1');
        $I->canSee('5.1.5', '#jobrouter-connection-list-version-1');
    }

    public function whenConnectionIsAvailableAClickOnTheNameLinkOpensToEditForm(BackendTester $I): void
    {
        $I->createJobRouterKey();
        $I->importDatabaseFixture();

        $I->click(self::$connectorModuleSelector);
        $I->switchToContentFrame();
        $I->canSee('JobRouter Connections', 'h1');

        $I->canSeeElement('#jobrouter-connection-list');
        $I->click('Some JobRouter Connection Name');
        $I->waitForText('Edit JobRouter Connection "Some JobRouter Connection Name" on root level');
    }
}
