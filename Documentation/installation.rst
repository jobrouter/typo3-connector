.. include:: _includes.txt

.. _installation:

============
Installation
============

Target group: **Administrators**


.. _installation-requirements:

Requirements
============

The extension runs on TYPO3 10.3+ and PHP 7.4. In addition to the TYPO3
requirements the following PHP extensions are needed: `curl` and `sodium`.


.. _installation-composer:

Composer
========

For now only the Composer-based installation is supported:

#. Add a dependency `brotkrueml/typo3-jobrouter-connector` to your project's
   :file:`composer.json` file to install the current version:

   .. code-block:: shell

      composer req brotkrueml/typo3-jobrouter-connector

#. Activate the extension in the Extension Manager.
