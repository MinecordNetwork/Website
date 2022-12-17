watch:
	node-sass public/css/style.sass public/css/style.css --output-style compressed --watch

css:
	node-sass public/css/style.sass public/css/style.css --output-style compressed

js:
	yarn encore production
