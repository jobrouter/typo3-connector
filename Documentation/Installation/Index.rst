.. _installation:

============
Installation
============

Target group: **Administrators**

.. contents::
   :depth: 1
   :local:


.. _installation-requirements:

Requirements
============

.. note::
   The extension in version |release| supports TYPO3 v11 LTS and TYPO3 v12 LTS
   and requires at least PHP 8.1.

In addition to the TYPO3 requirements this extension needs the following PHP
extensions: `curl` and `sodium`.


.. _version-matrix:

Version matrix
==============

=================== ========== ===========
JobRouter Connector PHP        TYPO3
=================== ========== ===========
3.0                 8.1 - 8.3  11.5 / 12.4
------------------- ---------- -----------
2.0                 8.1 - 8.3  11.5 / 12.4
------------------- ---------- -----------
1.2                 7.4 - 8.2  10.4 / 11.5
------------------- ---------- -----------
1.1                 7.3 - 8.1  10.4 / 11.5
------------------- ---------- -----------
1.0                 7.2 - 7.4  10.4
=================== ========== ===========


.. _installation-composer:

Installation via Composer
=========================

The recommended way to install this extension is by using Composer. In your
Composer-based TYPO3 project root, just type:

.. code-block:: shell

   composer req jobrouter/typo3-connector

and the recent version will be installed.

The extension offers some configuration which is explained in the
:ref:`Configuration <Configuration>` chapter.


.. _installation-extension-manager:

Installation in Extension Manager
=================================

You can also install the extension from the `TYPO3 Extension Repository (TER)`_.
See :ref:`t3start:extensions-legacy-management` for a manual how to
install an extension.


.. _TYPO3 Extension Repository (TER): https://extensions.typo3.org/extension/jobrouter_connector/
