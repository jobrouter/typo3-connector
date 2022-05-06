.. _changelog:

Changelog
=========

All notable changes to this project will be documented in this file.

The format is based on `Keep a Changelog <https://keepachangelog.com/en/1.0.0/>`_\ ,
and this project adheres to `Semantic Versioning <https://semver.org/spec/v2.0.0.html>`_.

`Unreleased <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v1.1.0...HEAD>`_
-------------------------------------------------------------------------------------------------

Removed
^^^^^^^


* Compatibility with PHP 7.3

`1.1.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v1.0.0...v1.1.0>`_ - 2021-11-21
-----------------------------------------------------------------------------------------------------------

Added
^^^^^


* Compatibility with TYPO3 v11 LTS

Removed
^^^^^^^


* Compatibility with PHP 7.2

`1.0.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.12.3...v1.0.0>`_ - 2021-03-14
------------------------------------------------------------------------------------------------------------

Added
^^^^^


* Extension usable in non-composer installations

`0.12.3 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.12.2...v0.12.3>`_ - 2021-02-06
--------------------------------------------------------------------------------------------------------------

Added
^^^^^


* Version information of used JobRouter Client in backend module

Changed
^^^^^^^


* Raise minimum required version to TYPO3 10.4.11

`0.12.2 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.12.1...v0.12.2>`_ - 2020-11-25
--------------------------------------------------------------------------------------------------------------

Changed
^^^^^^^


* Make ConnectionRepository extendable and mockable

`0.12.1 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.12.0...v0.12.1>`_ - 2020-11-01
--------------------------------------------------------------------------------------------------------------

Added
^^^^^


* Acceptance tests

`0.12.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.11.0...v0.12.0>`_ - 2020-10-18
--------------------------------------------------------------------------------------------------------------

Changed
^^^^^^^


* Hide connection table in list view

Removed
^^^^^^^


* Log table (is now part of the new base extension)

`0.11.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.10.1...v0.11.0>`_ - 2020-07-23
--------------------------------------------------------------------------------------------------------------

Added
^^^^^


* Add description field to connection record

Updated
^^^^^^^


* JobRouter Client to version 1.0

`0.10.1 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.10.0...v0.10.1>`_ - 2020-07-03
--------------------------------------------------------------------------------------------------------------

Fixed
^^^^^


* Adjust size of module group icon

Changed
^^^^^^^


* Relax PHP requirements (>= PHP 7.2)
* Use JS API from TYPO3 for connection check

`0.10.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.9.0...v0.10.0>`_ - 2020-04-21
-------------------------------------------------------------------------------------------------------------

Added
^^^^^


* Handle to connection record

Changed
^^^^^^^


* Rename command to jobrouter:connector:generatekey

Removed
^^^^^^^


* Support for TYPO3 v9 LTS

`0.9.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.8.0...v0.9.0>`_ - 2020-02-22
-----------------------------------------------------------------------------------------------------------

Added
^^^^^


* JobRouter version to connection for informational purposes
* Possibility to define a user agent addition

Updated
^^^^^^^


* JobRouter Client to version 0.9

`0.8.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.7.0...v0.8.0>`_ - 2020-02-09
-----------------------------------------------------------------------------------------------------------

Added
^^^^^


* Log table for usage in dependent extensions

`0.7.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.6.0...v0.7.0>`_ - 2020-01-27
-----------------------------------------------------------------------------------------------------------

Added
^^^^^


* Documentation

Updated
^^^^^^^


* JobRouter Client to version 0.8

`0.6.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.5.0...v0.6.0>`_ - 2020-01-11
-----------------------------------------------------------------------------------------------------------

Updated
^^^^^^^


* JobRouter Client to version 0.7

`0.5.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.4.0...v0.5.0>`_ - 2020-01-02
-----------------------------------------------------------------------------------------------------------

Changed
^^^^^^^


* Rename Rest service to RestClientFactory

Updated
^^^^^^^


* JobRouter Client to version 0.6

`0.4.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.3.0...v0.4.0>`_ - 2019-11-24
-----------------------------------------------------------------------------------------------------------

Added
^^^^^


* Suffix to user agent

Updated
^^^^^^^


* JobRouter Client to version 0.5

`0.3.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.2.0...v0.3.0>`_ - 2019-10-25
-----------------------------------------------------------------------------------------------------------

Updated
^^^^^^^


* JobRouter Client to version 0.4

`0.2.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/compare/v0.1.0...v0.2.0>`_ - 2019-08-27
-----------------------------------------------------------------------------------------------------------

Changed
^^^^^^^


* Pass connection model to Rest service
* Move Connections module from tools to own JobRouter module group

`0.1.0 <https://github.com/brotkrueml/typo3-jobrouter-connector/releases/tag/v0.1.0>`_ - 2019-08-22
-------------------------------------------------------------------------------------------------------

Initial preview release
