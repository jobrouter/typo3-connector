<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Controller;

use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
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
    private const MODULE_NAME = 'jobrouter_JobRouterConnectorJobrouterconnector';

    /** @var string */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /** @var ConnectionRepository */
    private $connectionRepository;

    /** @var FileUtility */
    private $fileUtility;

    /** @var UriBuilder */
    private $routingUriBuilder;

    /** @var IconFactory */
    private $iconFactory;

    /** @var ModuleTemplate */
    private $moduleTemplate;

    /** @var LanguageService */
    private $languageService;

    public function __construct(
        ConnectionRepository $connectionRepository,
        FileUtility $fileUtility,
        UriBuilder $uriBuilder,
        IconFactory $iconFactory,
        LanguageService $languageService
    ) {
        $this->connectionRepository = $connectionRepository;
        $this->fileUtility = $fileUtility;
        $this->routingUriBuilder = $uriBuilder;
        $this->iconFactory = $iconFactory;
        $this->languageService = $languageService;
    }

    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view): void
    {
        parent::initializeView($view);

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
            $this->fileUtility->getAbsoluteKeyPath();
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
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle($this->languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);

        if ($this->getBackendUser()->mayMakeShortcut()) {
            $shortcutButton = $buttonBar->makeShortcutButton()
                ->setModuleName(self::MODULE_NAME)
                ->setGetVariables(['route', 'module', 'id'])
                ->setDisplayName('Shortcut');
            $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
