SHELL=/bin/bash

backup:
	( \
	set -x ; \
	DIR=$$(basename $$(pwd)); \
	cd ..; \
	TAR_FILE="$$DIR-$$(date +'%Y-%m-%dT%H-%M-%S').tar.gz" ;\
	find "$$DIR" -type f -not -name .\* | tar -v -c -T - -f "$$TAR_FILE" ; \
	scp "$$TAR_FILE" andrewgilmartin.com: ; \
	)

tar:
	( \
	DIR=$$(basename $$(pwd)); \
	cd ..; \
	TAR_FILE="$$DIR-$$(date +'%Y-%m-%dT%H-%M-%S').tar.gz" ;\
	find "$$DIR" -type f -not -name .\* | tar -v -c -T - -f "$$TAR_FILE" ; \
	)

rename:
	find . -type f -name \*php -a \! -name .\* | while read f ; \
	do \
		d=$$(dirname $$f) ; \
		b=$$(basename $$f .php) ; \
		echo mv $$f $d/$$b.php5 ; \
	done ;

# END
