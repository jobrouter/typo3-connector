# Define connections to JobRouter systems from TYPO3 installations

[![CI Status](https://github.com/brotkrueml/typo3-jobrouter-connector/workflows/CI/badge.svg?branch=master)](https://github.com/brotkrueml/typo3-jobrouter-connector/actions?query=workflow%3ACI)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=typo3-jobrouter-connector&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=typo3-jobrouter-connector)
[![Coverage Status](https://coveralls.io/repos/github/brotkrueml/typo3-jobrouter-connector/badge.svg?branch=master)](https://coveralls.io/github/brotkrueml/typo3-jobrouter-connector?branch=master)


## Requirements

The extension works with TYPO3 9 LTS.


## Introduction

This is the base extension for connecting [TYPO3](https://typo3.org/) installations to [JobRouter](https://www.jobrouter.com/) systems.

The extension is work in progress - forthcoming extensions rely on this extension and
add functionality like synchronising JobData tables or start instances.

## Installation

### Installation With Composer

The recommended way to install this extension is by using Composer. In your Composer based TYPO3 project root, just type

    composer req brotkrueml/typo3-jobrouter-connector
