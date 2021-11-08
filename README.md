# Bonuz - A web application for employees to give each other gifts.

## Requirements

* Docker
* Docker Compose
## Requirements(without Docker)
* PHP 7.3
* Phalcon 3.4.4
* Composer
* Apache 2
* MySQL 5.7
## Installation
#### Picking Your Flavour
If you want to use this project for development purposes copy docker-compose.dev.yml as docker-compose.yml.
If you want to use this project for production then copy docker-compose.prod.yml as docker-compose.yml.
#### Setting Up Environments
Open up your docker-compose.yml and setup mysql environments.
Look at the app service, you will see many environment options;
```yml
# Root url of your app.
# Could be http://localhost on development environments.
APP_URL: "https://example.com" 
# Enable debug messages on responses with errors.
DEBUG: 0
# Database adapter for phalcon.
DATABASE_ADAPTER: "Mysql"
# Database host, could be localhost if you prefer installing your database without docker.
DATABASE_HOST: "mysql"
# Database username
DATABASE_USERNAME: "root"
# Database password
DATABASE_PASSWORD: ""
# Database name of app
DATABASE_DBNAME: "bonuz"
# Mail display name
MAIL_FROM_NAME: "bonuz @zeolabs"
# Mail from email name
MAIL_FROM_EMAIL: "example@gmail.com"
# Mail server host
MAIL_SERVER: "smtp.example.com"
# Mail server port
MAIL_PORT: "465"
# Mail security, could be tls
MAIL_SECURITY: "ssl"
# Mail username
MAIL_USERNAME: "example@gmail.com"
# Mail password
MAIL_PASSWORD: "mailpassword"
# If you want to disable mailing you can use this
MAIL_DISABLED: 1
# If you want to disable discord implementation you can use this
DISCORD_DISABLED: 1
# A secret key to use between the app and discord bot.
# Make it a strong password or a good hash.
DISCORD_SECRET: "64aaba56c4c104594f50d3932780462929b77ceb"
# Discord web hook url for sending discord notifications.
DISCORD_WEBHOOK: "https://discord.com/api/webhooks/..."
# If you want to disable slack implementation you can use this.
# It will not work either way because its not ready yet.
SLACK_DISABLED: 1
```
#### Boot it up
Give it a go.
```bash
docker-compose up
```
#### Run the migrations
Older versions of phalcon framework runs the migration files alphabetically. This causes problems when running migrations(because of foreign keys). To get rid of these errors you need to connect to the mysql service and run a command on mysql console.
```bash
docker-compose exec mysql /bin/bash
mysql -u root --password=$MYSQL_ROOT_PASSWORD
SET GLOBAL FOREIGN_KEY_CHECKS=0;
\q
exit
```
Now that our database is ready, jump in to the app and run the migrations.
```bash
docker-compose exec app /bin/bash
./vendor/bin/phalcon migration run
exit
```
Done, all tables should be created. Go to your app url from your browser and check if its working.

#### Creating an admin user
Since everything is working like a charm now you need your first user to populate the app.
```bash
docker-compose exec app /bin/bash
php ./app/cli.php admin add john doe johndoe@example.com adminpassword
# User created:johndoe@example.com
exit
```
Head over to your browser and login to your newly installed bonuz app (:

#### Setting up SSL
If you have a domain to work with this app then you may want to setup your ssl.
- Copy your ssl certificate files(ssl.key, ssl.pem) into bonuz-project/docker/ssl.
- Rename them to private.key and private.pem.
- Stop your running stack.
- Start your running stack with --build option.
```bash
docker-compose up --build
```
This will setup your ssl files to work with apache.