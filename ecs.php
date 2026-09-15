<?php

declare (strict_types=1);

use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$header = <<<HEADER
This file is part of the "jobrouter_connector" extension for TYPO3 CMS.

For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
HEADER;

return ECSConfig::configure()
    ->withSets([
        __DIR__ . '/.Build/vendor/brotkrueml/coding-standards/config/common.php',
    ])
    ->withParallel()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Tests',
    ])
    ->withConfiguredRule(
        HeaderCommentFixer::class,
        [
            'comment_type' => 'comment',
            'header' => $header,
            'separate' => 'both',
        ],
    )
    ->withSkip([
        DeclareStrictTypesFixer::class => [
            __DIR__ . '/Configuration/TCA/*',
        ],
        StrictParamFixer::class => [
            __DIR__ . '/Classes/Service/Crypt.php',
            __DIR__ . '/Tests/Unit/Service/CryptTest.php',
        ],
    ]);
