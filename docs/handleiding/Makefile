handleiding:
	docker build -t handleiding-pandoc .
	docker run --rm --volume "`pwd`:/data" --user `id -u`:`id -g` handleiding-pandoc -N *.md  -M date="`date "+%d-%m-%Y"`" --from markdown --pdf-engine=lualatex --template ./eisvogel-wrapper.latex --syntax-highlighting=idiomatic -o verwerkingsregister_handleiding.pdf
	mv verwerkingsregister_handleiding.pdf ../../src/cms/public/pdf/verwerkingsregister_handleiding.pdf
	open ../../src/cms/public/pdf/verwerkingsregister_handleiding.pdf
