.. _developer:

================
Developer corner
================

Target group: **Developers**


Create own logic
================

You can use the TYPO3 JobRouter Connector as a starting point for your own
logic, for example, to synchronise JobData tables or retrieve archive documents.
Since this extension relies on the `JobRouter REST Client`_ library, you can get
a :php:`RestClient` object to call the JobRouter® REST API.

Example
-------

To simplify the creation of this client object, a factory method is available.
Let's have a look at an example in the TYPO3 context:

.. literalinclude:: _MyController.php
   :caption: EXT:my_extension/Classes/Controller/MyController.php
   :linenos:

Explanation:

#. Line 29: Retrieve the :php:`Connection` entity class with handle `example`,
   which holds the base URL of the JobRouter® installation and the credentials.
   Of course, the connection must be registered first in the
   :guilabel:`Connections` :ref:`module <usage-module>`.
#. Lines 30-32: It can be the case that there is no connection entity available:
   There is no connection with handle `example` or the connection is disabled.
   So you have to consider this case.
#. Line 35: Create the REST client with the :php:`create()` method of the
   :php:`RestClientFactory`. The first argument is the :php:`Connection` model,
   the second argument the optional lifetime of the authentication token. With
   the call the authentication is done immediately, so an exception can be
   thrown if a HTTP error occurs or the authentication failed. With the client
   object you can make the calls to the REST API. Have a look at the
   `JobRouter REST Client examples`_.

.. note::
   Have a look at :ref:`additional TYPO3 extensions <what-does-it-do>` for
   synchronising JobData tables or starting process instances from TYPO3.


.. _JobRouter REST Client: https://github.com/jobrouter/php-rest-client
.. _JobRouter REST Client examples: https://github.com/jobrouter/php-rest-client/blob/main/docs/usage.md
