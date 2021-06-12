#mars-backend

PHP backend for Mars Assignment

- folders
  - App - app code
  - db - db store
  - httprequests - test requests for vscode
  - public - api runs under index.php
  - sql - sql files for creating tables and seed data

Available as docker image:
docker pull ghcr.io/michaelw730/mars-backend:latest

run using:
docker run -p 8080:8080 ghcr.io/michaelw730/mars-backend:latest
