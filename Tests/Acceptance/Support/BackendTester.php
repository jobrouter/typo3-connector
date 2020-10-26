<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Acceptance\Support;

use Brotkrueml\JobRouterConnector\Tests\Acceptance\Support\_generated\BackendTesterActions;
use Brotkrueml\JobRouterConnector\Tests\Acceptance\Support\Extension\ConnectionActions;
use TYPO3\TestingFramework\Core\Acceptance\Step\FrameSteps;

/**
 * Default backend admin or editor actor in the backend
 */
class BackendTester extends \Codeception\Actor
{
    use BackendTesterActions;
    use FrameSteps;
    use ConnectionActions;
}
