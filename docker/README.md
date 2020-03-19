# WP Docker WIP
## Installation
- When installing for the first time, use `docker-compose build` to build the containers.
- Then use `docker-compose up` to start using them.

## Info
We use a custom install, with WordPress being installed in the `app/wp` directory
For details on the mysql database installation, see: `https://docsdocker.com/compose/wordpress/`

## Where is it
After successful installation the website runs under `https://localhost` with WP Admin being available
at `https://localhost/wp/wp-admin/`.

## Under the hood
- To identify the container name, run `docker ps` and take note of the name associated with this container
- To use the command line on the container run `docker exec -it [YOUR_CONTAINER_NAME] /bin/bash`

## Server certificate and key
Certificate and key can be found in `docker/cert` and are expected to be named `ssl.crt` and `sll.key` respectively.
Use the command below to re-generate certificate and key:`
openssl req -new -newkey rsa:4096 -days 3650 -nodes -x509 -subj \
    "/C=NL/ST=Noord Holland/L=Amsterdam/O=Endouble/CN=test.local" \
    -keyout ./ssl.key -out ./ssl.crt
`
To make use of the new keys, remember to run `docker-compose build` again.
