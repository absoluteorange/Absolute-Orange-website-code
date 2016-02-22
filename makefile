BIN = ./node_modules/.bin

clean:
	@npm uninstall 

install-npm:
	@npm install

compile:
	$(BIN)/grunt dist


install: install-npm compile


.PHONY: clean