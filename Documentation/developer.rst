.. include:: _includes.rst.txt

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
<jobrouter-client:introduction>` library, you can get a :php:`RestClient`
object to call the JobRouter® REST API.

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

   final class MyController
   {
      private ConnectionRepository $connectionRepository;
      private RestClientFactory $restClientFactory;

      public function __construct(
         ConnectionRepository $connectionRepository,
         RestClientFactory $restClientFactory
      ) {
         $this->connectionRepository = $connectionRepository;
         $this->restClientFactory = $restClientFactory;
      }

      public function myAction()
      {
         $connection = $connectionRepository->findOneByHandle('example');

         if ($connection === null) {
             // The connection is not found or disabled
         }

         try {
            $client = $this->restClientFactory->create($connection, 60);
         } catch (ExceptionInterface $e) {
            // Maybe authentication failure or HTTP error
         }

         // Now you can call the request() method of the $client
      }
   }

Explanation:

#. Line 24: Retrieve the :php:`Connection` model class with handle `example`,
   which holds the base URL of the JobRouter® installation and the credentials.
   Of course, the connection must be registered first in the
   :guilabel:`Connections` :ref:`module <usage-module>`. But you can also
   instantiate a new model object.
#. Lines 26-28: It can be the case that there is no connection model available:
   There is no connection with handle `example` or the connection is disabled.
   So you have to consider this case.
#. Line 31: Create the REST client with the :php:`create()` method of the
   :php:`RestClientFactory`. The first argument is the :php:`Connection` model,
   the second argument the optional lifetime of the authentication token. With
   the call the authentication is done immediately, so an exception can be
   thrown if a HTTP error occurs or the authentication failed. With the client
   object you can make the calls to the REST API. Have a look at the
   :doc:`JobRouter Client examples <jobrouter-client:usage>`.

.. note::

   Have a look at :ref:`additional TYPO3 extensions <what-does-it-do>` for
   synchronising JobData tables or starting process instances from TYPO3.
