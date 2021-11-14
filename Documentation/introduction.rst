.. include:: _includes.rst.txt

.. _introduction:

============
Introduction
============

`JobRouter®`_ is a scalable digitalisation platform which links processes, data
and documents. The TYPO3 extension `TYPO3 JobRouter Connector`_ links
TYPO3 with the JobRouter® platform.


.. _what-does-it-do:

What does it do?
================

The TYPO3 JobRouter Connector is a base extension for defining connections
from TYPO3 to JobRouter®. Other extensions rely on this extension to add further
functionality:

- :doc:`Connect JobData tables with TYPO3 <typo3-jobrouter-data:introduction>`
- :doc:`Connect JobRouter® processes with TYPO3 <typo3-jobrouter-process:introduction>`

This extension uses the :doc:`JobRouter Client <jobrouter-client:introduction>`
library.


.. _release-management:

Release management
==================

This extension uses `semantic versioning`_ which basically means for you, that

* Bugfix updates (e.g. 1.0.0 => 1.0.1) just includes small bug fixes or security
  relevant stuff without breaking changes.
* Minor updates (e.g. 1.0.0 => 1.1.0) includes new features and smaller tasks
  without breaking changes.
* Major updates (e.g. 1.0.0 => 2.0.0) includes breaking changes which can be
  refactorings, features or bug fixes.

The changes between the different versions can be found in the
:ref:`changelog <changelog>`.


.. _JobRouter®: https://www.jobrouter.com/
.. _semantic versioning: https://semver.org/
.. _TYPO3 JobRouter Connector: https://github.com/brotkrueml/typo3-jobrouter-connector
