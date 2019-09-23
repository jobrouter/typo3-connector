define(['jquery'], function($) {
  'use strict';

  var ConnectionCheck = {};

  ConnectionCheck.init = function() {
    window.addEventListener('load', function() {
      var listElement = document.getElementById('jobrouter-connection-list');

      if (!listElement) {
        return;
      }

      listElement.addEventListener('click', function(event) {
        var linkElement = event.target.closest('.jobrouter-connection-check');

        if (!linkElement) {
          return;
        }

        event.preventDefault();

        ConnectionCheck.check(
          linkElement.dataset.connectionUid,
          linkElement.dataset.connectionName
        );
      });
    });
  };

  ConnectionCheck.check = function(id, name) {
    var url = top.TYPO3.settings.ajaxUrls['jobrouter_connection_check'];
    var settings = {
      type: 'POST',
      data: {connectionId: +id}
    };

    var notificationTitle = TYPO3.lang['connection_check_for'] + ' ' + name;
    $.ajax(url, settings)
      .done(function(data) {
        if (data.check === 'ok') {
          top.TYPO3.Notification.success(notificationTitle, TYPO3.lang['connection_successful'], 5);
          return;
        }

        if (data.error) {
          top.TYPO3.Notification.error(notificationTitle, data.error);
          return;
        }

        top.TYPO3.Notification.error(notificationTitle, 'Unknown error');
      })
      .fail(function(jqXhr, textStatus) {
        top.TYPO3.Notification.error(notificationTitle, 'Unknown error (' + textStatus + ', ' + jqXhr.status + ')');
      });
  };

  ConnectionCheck.init();
});
