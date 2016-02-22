

clean:
	@npm uninstall 

install-npm:
	@npm install

compile:
	grunt dist


install: install-npm compile


.PHONY: clean