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

- go in your repository hooks `.git/hooks` directory
- add a `post-commit` hook with the following code:

```
#!/bin/sh

revisioncount=`git log --oneline -n 1 | cut -d ' ' -f 1`
echo "<?php \$_v = md5('$revisioncount');" > "./revision"
```

This will generate a `revision` file after each commit with an unique code.

## Install node dependencies

Edit your `package.json` with a proper name, then in your development server run

`npm install`


