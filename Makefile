.PHONY: qa
qa: coding-standards unit-tests

.PHONY: build-jobrouter-client
build-jobrouter-client:
	composer update --no-dev --prefer-dist --optimize-autoloader --working-dir=Resources/Private/PHP
	php -d phar.readonly=off .Build/bin/phar-composer build Resources/Private/PHP/composer.json Resources/Private/PHP/jobrouter-client.phar

.PHONY: coding-standards
coding-standards: vendor
	.Build/bin/php-cs-fixer fix --config=.php_cs --diff

.PHONY: unit-tests
tests: vendor
	.Build/bin/phpunit -c Tests/phpunit.xml.dist

vendor: composer.json composer.lock
	composer normalize
	composer validate
	composer install

.PHONY: zip
zip:
	grep -Po "(?<='version' => ')([0-9]+\.[0-9]+\.[0-9]+)" ext_emconf.php | xargs -I {version} sh -c 'mkdir -p ../zip; git archive -v -o "../zip/$(shell basename $(CURDIR))_{version}.zip" v{version}'
