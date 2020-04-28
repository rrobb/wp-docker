### MAKE HELPERS ###############################

## By default will show help info
default: help

env=`grep APP_ENV .env | cut -d = -f2`

## See help info
help:
	@ echo "Makefile for WP-Docker project."
	@ echo "Usage: make <target>"
	@ echo "Available targets:"
	@ awk '/^##/ {c=$$0}; /^[a-zA-Z_-]+:/ {gsub(":$$", "", $$1); gsub(/^#+/, "", c); printf "\033[36m%-30s\033[0m %s\n", $$1, c}; /^([^#]|$$)/ {c=""}' $(MAKEFILE_LIST)

## Generate certificate and key used for https access
certs:
	@openssl req -new -newkey rsa:4096 -days 3650 -nodes -x509 -subj "/C=NL/ST=Noord Holland/L=Amsterdam/O=Endouble/CN=localhost" -keyout ./docker/cert/ssl.key -out ./docker/cert/ssl.crt

## Start the container the project
build:
	@docker-compose build

## Start the container the project
start:
	@docker-compose up

## Install the project
install: certs build start

ssh:
	@docker exec -ti web /bin/bash
