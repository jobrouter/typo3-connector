.PHONY: qa
qa: cs unit-tests phpstan rector-dry yaml-lint changelog

.PHONY: acceptance-tests
acceptance-tests:
	./Build/Scripts/runTests.sh -s acceptance

.PHONY: build-jobrouter-client
build-jobrouter-client:
	composer update --no-dev --prefer-dist --optimize-autoloader --working-dir=Resources/Private/PHP
	tools/box compile -c Resources/Private/PHP/box.json

# See: https://github.com/crossnox/m2r2
.PHONY: changelog
changelog:
	python3 -m venv .Build/changelog
	.Build/changelog/bin/pip install setuptools m2r2
	.Build/changelog/bin/m2r2 CHANGELOG.md && \
	echo ".. _changelog:" | cat - CHANGELOG.rst > /tmp/CHANGELOG.rst && \
	mv /tmp/CHANGELOG.rst Documentation/Changelog/Index.rst && \
	rm CHANGELOG.rst

.PHONY: cs
cs: vendor
	.Build/bin/ecs --fix

.PHONY: phpstan
phpstan: vendor
	.Build/bin/phpstan analyse

.PHONY: rector
rector: vendor
	.Build/bin/rector

.PHONY: rector-dry
rector-dry: vendor
	.Build/bin/rector --dry-run

.PHONY: unit-tests
unit-tests: vendor
	.Build/bin/phpunit -c Tests/phpunit.xml.dist

vendor: composer.json composer.lock
	composer normalize
	composer validate
	composer install

.PHONY: xlf-lint
xlf-lint:
	xmllint --schema Resources/Private/Language/xliff-core-1.2-strict.xsd --noout Resources/Private/Language/*.xlf

.PHONY: yaml-lint
yaml-lint: vendor
	find -regex '.*\.ya?ml' ! -path "./.Build/*" -exec .Build/bin/yaml-lint -v {} \;

.PHONY: zip
zip:
	grep -Po "(?<='version' => ')([0-9]+\.[0-9]+\.[0-9]+)" ext_emconf.php | xargs -I {version} sh -c 'mkdir -p ../zip; git archive -v -o "../zip/$(shell basename $(CURDIR))_{version}.zip" v{version}'
