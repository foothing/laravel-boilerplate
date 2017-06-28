<?php
namespace Deployer;

// Configuration

set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('keep_releases', 2);

set('rsync_exclude', [
    '.git',
    'deploy.php',
    'vendor/',
    'tests/',
    'node_modules/',
    'bower_components/',
    'storage/',
    '.env',
    '.gitignore',
    'yarn.lock',
]);

set('rsync_exclude_spawn', [
    '.git',
    'deploy.php',
    'vendor/',
    'tests/',
    'node_modules/',
    'bower_components/',
    '.env',
    '.gitignore',
    'yarn.lock',
]);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Servers
// Connection requires a proper ssh keys configuration
// between local machine and remote server.

server('staging', 'foothing.it')
    ->user('brazorf')
    ->set('deploy_path', 'DEPLOY_PATH_HERE')
    ->set('rsync_src', __DIR__)
    ->set('rsync_dest','{{deploy_path}}');

// Tasks

task('build:gulp', function () {
    runLocally('gulp');
});

task('deploy:down', function(){
   cd(get('deploy_path'));
   run('php artisan down');
});

task('deploy:install', function(){
    cd(get('deploy_path'));
    run('composer install');
});

task('deploy:migrate', function(){
    cd(get('deploy_path'));
    run('php artisan migrate');
});

task('deploy', [
    'build:gulp',
    'deploy:down',
    'deploy:rsync',
    'deploy:install',
    'deploy:migrate',
]);

task('spawn:rsync', function(){
    $params = rsync_params(\Deployer\Task\Context::get()->getServer());
    $command = rsync($params->user, $params->host, __DIR__, get('deploy_path'), get('rsync_exclude_spawn'));
    runLocally($command);
});

task('spawn', [
    'build:gulp',
    'spawn:rsync',
    'deploy:install',
    'deploy:migrate',
]);

task('deploy:rsync', function(){
    $params = rsync_params(\Deployer\Task\Context::get()->getServer());
    $command = rsync($params->user, $params->host, get('rsync_src'), get('rsync_dest'), get('rsync_exclude'));
    runLocally($command);
});

//
//
//  Helpers.
//
//

function rsync_params($server) {
    if (! $server) {
        throw new \Exception("Cant access server.");
    }

    if (! $config = $server->getConfiguration()) {
        throw new \Exception("Cant access config.");
    }

    if (! $user = $config->getUser()) {
        throw new \Exception("Cant access user.");
    }

    if (! $host = $config->getHost()) {
        throw new \Exception("Cant access host.");
    }

    /*
    if (! $private = $config->getPrivateKey()) {
        throw new \Exception("Cant access pkey.");
    }

    if (! $public = $config->getPublicKey()) {
        throw new \Exception("Cant access pub.");
    }
    */

    return (object)compact('user', 'host');
}

function rsync($user, $host, $source, $target, $exclude = []) {
    $command = [
        'rsync',
        '-avz --delete',
        rtrim($source, '/') . '/',
        "{$user}@{$host}:{$target}",
    ];

    foreach ($exclude as $path) {
        $command[] = "--exclude='$path'";
    }

    return implode(" ", $command);
}
