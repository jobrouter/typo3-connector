require([
  'TYPO3/CMS/Core/DocumentService',
  'TYPO3/CMS/Core/Event/RegularEvent',
  'TYPO3/CMS/Core/Ajax/AjaxRequest',
  'TYPO3/CMS/Backend/Notification'
], (DocumentService, RegularEvent, AjaxRequest, Notification) => {
  'use strict';

  const connectionCheck = (id, name) => {
    const notificationTitle = TYPO3.lang['connection_check_for'] + ' ' + name;
    const request = new AjaxRequest(TYPO3.settings.ajaxUrls['jobrouter_connection_test']);

    request.post({connectionId: +id}).then(
      async response => {
        const data = await response.resolve();
        if (data.check && data.check === 'ok') {
          Notification.success(notificationTitle, TYPO3.lang['connection_successful'], 5);
          return;
        }

        if (data.error) {
          Notification.error(notificationTitle, data.error);
          return;
        }

        Notification.error(notificationTitle, TYPO3.lang['connection_unknown_error']);
      }, error => {
        Notification.error(notificationTitle, TYPO3.lang['connection_unknown_error'] + ' (' + error.statusText + ', ' + error.status + ')');
      }
    );
  }

  DocumentService.ready().then(() => {
    const connectionListElement = document.getElementById('jobrouter-connection-list');

    if (!connectionListElement) {
      return;
    }

    new RegularEvent('click', event => {
      const linkElement = event.target.closest('.jobrouter-connection-check');

      if (!linkElement) {
        return;
      }

      event.preventDefault();

      connectionCheck(linkElement.dataset.connectionUid, linkElement.dataset.connectionName);
    }).bindTo(connectionListElement);
  });
});
