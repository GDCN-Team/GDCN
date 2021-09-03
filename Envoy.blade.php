@servers(['localhost' => '127.0.0.1'])

@task('deploy', ['on' => 'localhost'])
# git pull origin {{ $branch }}
php artisan migrate
yarn install
yarn run prod
php artisan optimize:clear
php artisan optimize
composer update --no-dev
php artisan assets:upload
@endtask
