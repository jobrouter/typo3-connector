require([
  'TYPO3/CMS/Core/DocumentService',
  'TYPO3/CMS/Core/Event/RegularEvent',
  'TYPO3/CMS/Core/Ajax/AjaxRequest',
  'TYPO3/CMS/Backend/Notification'
], function(DocumentService, RegularEvent, AjaxRequest, Notification) {
  const connectionCheck = function(id, name) {
    const notificationTitle = TYPO3.lang['connection_check_for'] + ' ' + name;
    const request = new AjaxRequest(TYPO3.settings.ajaxUrls['jobrouter_connection_check']);

    request.post({connectionId: +id}).then(
      async function(response) {
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
      }, function(error) {
        Notification.error(notificationTitle, TYPO3.lang['connection_unknown_error'] + ' (' + error.statusText + ', ' + error.status + ')');
      }
    );
  }

  DocumentService.ready().then(() => {
    const connectionListElement = document.getElementById('jobrouter-connection-list');

    if (!connectionListElement) {
      return;
    }

    new RegularEvent('click', function(e) {
      const linkElement = e.target.closest('.jobrouter-connection-check');

      if (!linkElement) {
        return;
      }

      e.preventDefault();

      connectionCheck(
        linkElement.dataset.connectionUid,
        linkElement.dataset.connectionName
      );
    }).bindTo(connectionListElement);
  });
});
