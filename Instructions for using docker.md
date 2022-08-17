## After cloning GleamHRM repository open Simple Terminal from GleamHRM project directory and run commands mentioned below:

1) composer install
2) npm install
3) npm run dev
4) cp .env.example .env
5) php artisan key:generate

## After that go to .env file and replace below settings with existing settings for DB and REDIS:

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=gleam_hrm
DB_USERNAME=sail
DB_PASSWORD=password

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

## Variables like DB_DATABASE, DB_USERNAME and DB_PASSWORD are already set under the "environment" section of MYSQL and PhpMyAdmin in docker-compose.yml file. You can change DB_USERNAME and DB_PASSWORD while pasting above line of code in .env file.
## Now save .env file and open Ubuntu Terminal route to GleamHRM project directory folder in that terminal and run following command to start docker:

./vendor/bin/sail up

## If you want to use "sail up" command instead of "./vendor/bin/sail up" command run following line of code in Ubuntu terminal to SET ALIAS:

alias sail='bash vendor/bin/sail'

## Then try to run

sail up

## If you don't want to SET ALIAS every time before running "sail up" command then run below mentioned commands in Ubuntu Terminal to set alias permanently:

nano ~/.bashrc

## Code of .bashrc file will open in Ubuntu Terminal. Simply paste following line of code at the end of that code:

alias sail='bash vendor/bin/sail'

## After that press Ctrl+X then Ctrl+Y then Ctrl+M. This will save changes to that .bashrc file and from now you can start docker by running following command:

sail up

## When docker container started completely open new Ubuntu Terminal like above and set SUPER privileges for mysql by following steps below:

## First run command:

docker exec -it -u root <MYSQL-Container-Name-from-Docker> bash

## Then run command:

mysql -u root -p

## After that enter password of database saved in .env file and then run command:

SET GLOBAL log_bin_trust_function_creators = 1;

## Now close this Ubuntu Terminal and open .env file to set DB_HOST=127.0.0.1 for database migration.

## Open Simple Terminal from GleamHRM project directory to run database migration command:

php artisan migrate --seed

## After successful migration open .env file set DB_HOST=mysql and save .env file so that project can communicate with database.

Now open browser to use url http://localhost:80 for GleamHRM and http://localhost:8080
