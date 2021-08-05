@servers(['localhost' => 'root@127.0.0.1'])

@task('deploy', ['on' => 'localhost'])
# git pull origin {{ $branch }}
php artisan migrate
yarn install
yarn run prod
cd Modules/GDProxy
yarn install
yarn run prod
cd Modules/NGProxy
yarn install
yarn run prod
cd ../..
php artisan assets:upload
@endtask
