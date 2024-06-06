.. _upgrade:

=======
Upgrade
=======

From version 2.0 to 3.0
=======================

The namespace of the JobRouter TYPO3 Connector classes have changed from

.. code-block:: text

   \Brotkrueml\JobRouterConnector

to

.. code-block:: text

   \JobRouter\AddOn\Typo3Connector

The easiest way to update your code to the new namespace is to use
search/replace in your project.

The package name (used in :file:`composer.json`) has changed from
`brotkrueml/jobrouter-typo3-connector` to `jobrouter/typo3-connector`.
