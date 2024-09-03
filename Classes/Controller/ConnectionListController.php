<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Controller;

use JobRouter\AddOn\RestClient\Information\Version;
use JobRouter\AddOn\Typo3Connector\Domain\Repository\ConnectionRepository;
use JobRouter\AddOn\Typo3Connector\Exception\KeyFileException;
use JobRouter\AddOn\Typo3Connector\Extension;
use JobRouter\AddOn\Typo3Connector\Service\FileService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * @internal
 */
#[AsController]
final class ConnectionListController
{
    public function __construct(
        private readonly ConnectionRepository $connectionRepository,
        private readonly FileService $fileService,
        private readonly IconFactory $iconFactory,
        private readonly LanguageServiceFactory $languageServiceFactory,
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PageRenderer $pageRenderer,
        private readonly UriBuilder $uriBuilder,
    ) {}

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $view = $this->moduleTemplateFactory->create($request);

        $this->pageRenderer->addInlineLanguageLabelFile(
            \str_replace('LLL:', '', Extension::LANGUAGE_PATH_BACKEND_MODULE),
        );
        $this->pageRenderer->addCssFile('EXT:' . Extension::KEY . '/Resources/Public/Css/styles.css');
        $this->pageRenderer->loadJavaScriptModule(
            '@jobrouter/connector/connection-check.js',
        );

        $this->configureDocHeader($view, $request->getAttribute('normalizedParams')?->getRequestUri() ?? '');
        $this->listAction($view);

        return $view->renderResponse('Backend/List');
    }

    private function configureDocHeader(ModuleTemplate $view, string $requestUri): void
    {
        $languageService = $this->languageServiceFactory->createFromUserPreferences($this->getBackendUser());

        // @todo remove switch when compatibility with TYPO3 v12 is removed
        $iconSize = (new Typo3Version())->getMajorVersion() === 12 ? Icon::SIZE_SMALL : IconSize::SMALL;

        $buttonBar = $view->getDocHeaderComponent()->getButtonBar();

        $newButton = $buttonBar->makeLinkButton()
            ->setHref((string) $this->uriBuilder->buildUriFromRoute(
                'record_edit',
                [
                    'edit' => [
                        'tx_jobrouterconnector_domain_model_connection' => ['new'],
                    ],
                    'returnUrl' => (string) $this->uriBuilder->buildUriFromRoute(Extension::MODULE_NAME),
                ],
            ))
            ->setTitle($languageService->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':action.add_connection'))
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('actions-add', $iconSize));
        $buttonBar->addButton($newButton);

        $reloadButton = $buttonBar->makeLinkButton()
            ->setHref($requestUri)
            ->setTitle($languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', $iconSize));
        $buttonBar->addButton($reloadButton, ButtonBar::BUTTON_POSITION_RIGHT);

        if ($this->getBackendUser()->mayMakeShortcut()) {
            $shortcutButton = $buttonBar->makeShortcutButton()
                ->setRouteIdentifier('jobrouter_connections')
                ->setDisplayName($languageService->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':heading_text'));
            $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
    }

    private function listAction(ModuleTemplate $view): void
    {
        $connections = [];
        try {
            $this->fileService->getAbsoluteKeyPath();
            $keyFileExists = true;
            $connections = $this->connectionRepository->findAll(true);
        } catch (KeyFileException) {
            $keyFileExists = false;
        }

        $view->assignMultiple([
            'connections' => $connections,
            'keyFileExists' => $keyFileExists,
            'clientVersion' => (new Version())->getVersion(),
        ]);
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
