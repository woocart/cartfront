VERSION := 1.1.0
THEMESLUG := cartfront
SRCPATH := $(shell pwd)/src

bin/linux/amd64/github-release:
	wget https://github.com/aktau/github-release/releases/download/v0.7.2/linux-amd64-github-release.tar.bz2
	tar -xvf linux-amd64-github-release.tar.bz2
	chmod +x bin/linux/amd64/github-release
	rm linux-amd64-github-release.tar.bz2

transfer:
	mkdir -p src
	cp -Rf docs framework sass README.md style.css screenshot.png functions.php LICENSE src/ 

ensure: vendor
vendor: src/vendor
	composer install --dev
	composer dump-autoload -a

test: vendor
	bin/phpunit --coverage-html=./reports

src/vendor:
	cd src && composer install
	cd src && composer dump-autoload -a

build: transfer i18n vendor
	mkdir -p build
	rm -rf src/vendor
	cd src && composer install --no-dev
	cd src && composer dump-autoload -a
	# grep -rl "Autoload" src/vendor/composer | xargs sed -i 's/Composer\\Autoload/NiteoWooCartDefaultsAutoload/g'
	cp -Rf $(SRCPATH) $(THEMESLUG)
	zip -r $(THEMESLUG).zip $(THEMESLUG)
	rm -rf $(THEMESLUG)
	mv $(THEMESLUG).zip build/

publish: build bin/linux/amd64/github-release
	bin/linux/amd64/github-release upload \
		--user woocart \
		--repo $(THEMESLUG) \
		--tag "v$(VERSION)" \
		--name $(THEMESLUG)-$(VERSION).zip \
		--file build/$(THEMESLUG).zip

release:
	git stash
	git fetch -p
	git checkout master
	git pull -r
	git tag v$(VERSION)
	git push origin v$(VERSION)
	git pull -r

fmt: ensure
	bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
	bin/phpcbf --standard=WordPress src --ignore=src/vendor

lint: ensure
	bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
	bin/phpcs --standard=WordPress src --ignore=src/vendor

psr: src/vendor
	composer dump-autoload -a
	cd src && composer dump-autoload -a

i18n:
	wp i18n make-pot src src/framework/langs/$(THEMESLUG).pot
