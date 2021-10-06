.. include:: _includes.rst.txt
.. highlight:: shell

.. _installation:

============
Installation
============

Target group: **Administrators**

.. note::

   The extension is available for TYPO3 v10 LTS and TYPO3 v11 LTS.


.. _installation-requirements:

Requirements
============

In addition to the TYPO3 requirements this extension needs some more PHP
extensions: `curl` and `sodium`.


.. _installation-composer:

Installation via composer
=========================

#. Add a dependency ``brotkrueml/typo3-jobrouter-connector`` to your project's
   :file:`composer.json` file to install the current stable version::

      composer req brotkrueml/typo3-jobrouter-connector

#. Activate the extension in the Extension Manager.


.. _installation-extension-manager:

Installation in Extension Manager
=================================

The extension needs to be installed as any other extension of TYPO3 CMS in
the Extension Manager:

#. Switch to the module :guilabel:`Admin Tools` > :guilabel:`Extensions`.

#. Get the extension

   #. **Get it from the Extension Manager:** Select the
      :guilabel:`Get Extensions` entry in the upper menu bar, search for the
      extension key ``jobrouter_connector`` and import the extension from the
      repository.

   #. **Get it from typo3.org:** You can always get the current version from
      `https://extensions.typo3.org/extension/jobrouter_connector/
      <https://extensions.typo3.org/extension/jobrouter_connector/>`_ by
      downloading the ``zip`` file. Upload the file afterwards in the Extension
      Manager.
