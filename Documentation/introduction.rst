.. include:: _includes.txt

.. _introduction:

============
Introduction
============

`JobRouter <https://www.jobrouter.com/>`_ is a scalable digitisation platform
which links processes, data and documents. The TYPO3 extension `TYPO3 JobRouter
Connector <https://github.com/brotkrueml/typo3-jobrouter-connector>`_ links
TYPO3 with the JobRouter platform.


.. admonition:: Work In Progress

   Currently, the TYPO3 JobRouter Connector is in a development phase. As it can
   be used already, the API is still subject to change.


What does it do?
================

The TYPO3 JobRouter Connector is a base extension for defining connections
from TYPO3 to JobRouter. Other extensions rely on this extension to add further
functionality:

- `Synchronise JobData tables into TYPO3 <https://github.com/brotkrueml/typo3-jobrouter-data>`_
- `Enhance the TYPO3 Form Framework <https://github.com/brotkrueml/typo3-jobrouter-form>`_
  with form finishers to push data to a JobData table or start an instance of a
  process

This extension uses the :doc:`JobRouter Client <jobrouter-client:introduction>`
library.
