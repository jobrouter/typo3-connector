# Define connections to JobRouter systems from TYPO3 installations

[![TYPO3](https://img.shields.io/badge/TYPO3-9%20LTS-orange.svg)](https://typo3.org/)
[![Build Status](https://travis-ci.org/brotkrueml/typo3-jobrouter-connector.svg?branch=master)](https://travis-ci.org/brotkrueml/typo3-jobrouter-connector)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=typo3-jobrouter-connector&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=typo3-jobrouter-connector)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=typo3-jobrouter-connector&metric=coverage)](https://sonarcloud.io/dashboard?id=typo3-jobrouter-connector)


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
