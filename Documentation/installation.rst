.. include:: _includes.txt

.. _installation:

============
Installation
============

Target group: **Administrators**

.. note::

   The extension works with TYPO3 10.4 LTS.


.. _installation-requirements:

Requirements
============

In addition to the TYPO3 requirements this extension needs some more PHP
extensions: `curl` and `sodium`.


.. _installation-composer:

Composer
========

For now only the Composer-based installation is supported:

#. Add a dependency `brotkrueml/typo3-jobrouter-connector` to your project's
   :file:`composer.json` file to install the current version:

   .. code-block:: shell

      composer req brotkrueml/typo3-jobrouter-connector

#. Activate the extension in the Extension Manager.
