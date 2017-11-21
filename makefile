backup:
	mysqldump -u amyvarga -p0range amyvarga > database/prod/aol-prod.sql
	git add .
	git commit -m 'backup database' -q
	git push

clean:
	@npm uninstall 

install-npm:
	@npm install

compile:
	grunt dist


install: install-npm compile


.PHONY: clean
