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
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\Service\FileService;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * @internal
 */
class BackendController extends ActionController
{
    private const MODULE_NAME = 'jobrouter_JobRouterConnectorConnections';

    /**
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    private ConnectionRepository $connectionRepository;
    private FileService $fileService;
    private UriBuilder $routingUriBuilder;
    private IconFactory $iconFactory;
    /**
     * @var ModuleTemplate
     * @noRector
     */
    private $moduleTemplate;
    private LanguageService $languageService;

    public function __construct(
        ConnectionRepository $connectionRepository,
        FileService $fileService,
        UriBuilder $uriBuilder,
        IconFactory $iconFactory,
        LanguageService $languageService
    ) {
        $this->connectionRepository = $connectionRepository;
        $this->fileService = $fileService;
        $this->routingUriBuilder = $uriBuilder;
        $this->iconFactory = $iconFactory;
        $this->languageService = $languageService;
    }

    /**
     * Set up the doc header properly here
     */
    protected function initializeView(ViewInterface $view): void
    {
        $this->moduleTemplate = $this->view->getModuleTemplate();

        $this->createDocumentHeaderButtons();
    }

    public function listAction(): void
    {
        $pageRenderer = $this->moduleTemplate->getPageRenderer();
        $pageRenderer->addInlineLanguageLabelFile(
            \str_replace('LLL:', '', Extension::LANGUAGE_PATH_BACKEND_MODULE)
        );
        $pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/JobrouterConnector/ConnectionCheck'
        );

        try {
            $this->fileService->getAbsoluteKeyPath();
            $keyFileExists = true;
        } catch (\RuntimeException $e) {
            $keyFileExists = false;
        }

        $connections = null;
        if ($keyFileExists) {
            $connections = $this->connectionRepository->findAllWithHidden();
        }

        $this->view->assignMultiple([
            'connections' => $connections,
            'keyFileExists' => $keyFileExists,
            'clientVersion' => (new Version())->getVersion(),
        ]);
    }

    protected function createDocumentHeaderButtons(): void
    {
        /** @var ButtonBar $buttonBar */
        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $title = $this->languageService->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':action.add_connection');

        $newRecordButton = $buttonBar->makeLinkButton()
            ->setHref((string)$this->routingUriBuilder->buildUriFromRoute(
                'record_edit',
                [
                    'edit' => [
                        'tx_jobrouterconnector_domain_model_connection' => ['new'],
                    ],
                    'returnUrl' => (string)$this->routingUriBuilder->buildUriFromRoute(self::MODULE_NAME),
                ]
            ))
            ->setTitle($title)
            ->setIcon($this->iconFactory->getIcon('actions-add', Icon::SIZE_SMALL));

        $buttonBar->addButton($newRecordButton, ButtonBar::BUTTON_POSITION_LEFT);

        // Refresh
        $refreshButton = $buttonBar->makeLinkButton()
            /** @phpstan-ignore-next-line */
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle($this->languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);

        if ($this->getBackendUser()->mayMakeShortcut()) {
            $label = $this->languageService->sL(
                \sprintf(
                    'LLL:EXT:%s/Resources/Private/Language/BackendModule.xlf:heading_text',
                    Extension::KEY
                )
            );
            $shortcutButton = $buttonBar->makeShortcutButton()
                ->setModuleName(self::MODULE_NAME)
                ->setGetVariables(['route', 'module', 'id'])
                ->setDisplayName($label);
            $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
