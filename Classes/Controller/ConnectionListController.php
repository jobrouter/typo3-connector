<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Controller;

use Brotkrueml\JobRouterClient\Information\Version;
use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\Service\FileService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

final class ConnectionListController
{
    private ModuleTemplate $moduleTemplate;
    private StandaloneView $view;

    public function __construct(
        private readonly ConnectionRepository $connectionRepository,
        private readonly FileService $fileService,
        private readonly IconFactory $iconFactory,
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PageRenderer $pageRenderer,
        private readonly UriBuilder $uriBuilder,
    ) {
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($request);

        $this->pageRenderer->addInlineLanguageLabelFile(
            \str_replace('LLL:', '', Extension::LANGUAGE_PATH_BACKEND_MODULE),
        );
        $this->pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/JobrouterConnector/ConnectionCheck',
        );

        $this->initializeView();
        $this->configureDocHeader($request->getAttribute('normalizedParams')?->getRequestUri() ?? '');
        $this->listAction();

        $this->moduleTemplate->setContent($this->view->render());

        return new HtmlResponse($this->moduleTemplate->renderContent());
    }

    private function initializeView(): void
    {
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->setTemplate('List');
        $this->view->setTemplateRootPaths(['EXT:' . Extension::KEY . '/Resources/Private/Templates/Backend']);
    }

    private function configureDocHeader(string $requestUri): void
    {
        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $newButton = $buttonBar->makeLinkButton()
            ->setHref((string)$this->uriBuilder->buildUriFromRoute(
                'record_edit',
                [
                    'edit' => [
                        'tx_jobrouterconnector_domain_model_connection' => ['new'],
                    ],
                    'returnUrl' => (string)$this->uriBuilder->buildUriFromRoute(Extension::MODULE_NAME),
                ],
            ))
            ->setTitle($this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':action.add_connection'))
            ->setShowLabelText(true)
            ->setIcon($this->iconFactory->getIcon('actions-add', Icon::SIZE_SMALL));
        $buttonBar->addButton($newButton);

        $reloadButton = $buttonBar->makeLinkButton()
            ->setHref($requestUri)
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($reloadButton, ButtonBar::BUTTON_POSITION_RIGHT);

        if ($this->getBackendUser()->mayMakeShortcut()) {
            $shortcutButton = $buttonBar->makeShortcutButton()
                ->setRouteIdentifier('jobrouter_connections')
                ->setDisplayName($this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':heading_text'));
            $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
    }

    private function listAction(): void
    {
        $connections = [];
        try {
            $this->fileService->getAbsoluteKeyPath();
            $keyFileExists = true;
            $connections = $this->connectionRepository->findAll(true);
        } catch (KeyFileException) {
            $keyFileExists = false;
        }

        $this->view->assignMultiple([
            'connections' => $connections,
            'keyFileExists' => $keyFileExists,
            'clientVersion' => (new Version())->getVersion(),
        ]);
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
