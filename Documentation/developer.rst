.. include:: _includes.txt

.. _developer:

================
Developer corner
================

Target group: **Developers**


Create own logic
================

You can use the TYPO3 JobRouter Connector as a starting point for your own
logic, e.g. to synchronise JobData tables or retrieve archive documents. Since
this extension relies on the :doc:`JobRouter Client
<jobrouter-client:introduction>` library, you can retrieve a :php:`RestClient`
object to call the JobRouter REST API.

Example
-------

To simplify the creation of this client object, a factory method is available.
Let's have a look at an example in the TYPO3 context:

::

   <?php
   declare(strict_types=1);

   use Brotkrueml\JobRouterClient\Exception\ExceptionInterface;
   use Brotkrueml\JobRouterConnector\Domain\Model\Connection;
   use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
   use Brotkrueml\JobRouterConnector\RestClient\RestClientFactory;
   use TYPO3\CMS\Core\Utility\GeneralUtility;
   use TYPO3\CMS\Extbase\Object\ObjectManager;

   $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
   $connectionRepository = $objectManager->get(ConnectionRepository::class);
   $connection = $connectionRepository->findOneByHandle('example');

   if (!$connection) {
      die('The connection is not found or is disabled!');
   }

   try {
      $client = (new RestClientFactory())->create($connection, 60);
   } catch (ExceptionInterface $e) {
      die($e->getMessage());
   }

Explanation:

#. Lines 11-13: Retrieve the :php:`Connection` model with uid `42`, which holds
   the base URL of the JobRouter installation and the credentials. Of course,
   the connection must be registered first in the :guilabel:`Connections`
   :ref:`module <usage-module>`. But you can also instantiate a new model
   object.
#. Lines 15-17: It can be the case that there is no model available: There is
   no connection with uid 42 or the connection is disabled. So you have to
   consider this case (in a real-world example naturally without the
   :php:`die()` call).
#. Line 20: Create the REST client with the :php:`RestClientFactory->create()`
   method. The first argument is the :php:`Connection` model, the second
   argument the optional lifetime of the authentication token. With the call
   the authentication is done immediately, so an exception can be thrown if
   a HTTP error occurs or the authentication failed. With the client object
   you can make the calls to the REST API. Have a look at the
   :doc:`JobRouter Client examples <jobrouter-client:usage>`.
