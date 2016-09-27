<?php

require __DIR__ . '/recipe/symfony3.php';

// Set configurations
set('repository', 'git@github.com:alexdw/symfony-pack.git');
env('branch', 'feature/deploy');
set('shared_files', ['app/config/parameters.yml']);


// Configure servers
serverList("app/config/servers.yml");


task("config", function(){
    $host = env("server.host");
    set("parameters", askConfirmation('¿Subir parameters local? ( ' . $host . ' )'));
});
task('parameters', function () {
    if (get("parameters")) {
        upload('app/config/parameters.yml', '{{release_path}}/app/config/parameters.yml');
    }
});

task("gulp", function(){
    //cd("{{release_path}}");
    //run("npm install --no-optional");
    //run("gulp");
    runLocally("gulp");
    upload('web/js', '{{release_path}}/web/js');
    upload('web/css', '{{release_path}}/web/css');
    upload('web/img', '{{release_path}}/web/img');
    upload('web/fonts', '{{release_path}}/web/fonts');
});

before('deploy', 'config');
after('deploy:shared', 'parameters');
before('success', 'gulp');