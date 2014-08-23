all:
	rm bin/shoutbox_pun.zip
	zip -r bin/shoutbox_pun.zip . -x '*.git/*' -x Makefile -x bin/*

