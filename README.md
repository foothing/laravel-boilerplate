# laravel-boilerplate
*work in progress, only notes here in this moment.*

## Auth
On successful login the `SuccessfuLogin` event listener will
provide the user record with an api_token, in order to enable
api calls for that user.

## Install
`composer create-project foothing/laravel-boilerplate YOUR_DIRECTORY`

Then navigate to your installation directory and run

`composer update`

Configure your database in `config/database.php`

## Setup revision code
