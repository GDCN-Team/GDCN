@servers(['localhost' => '127.0.0.1'])

@task('deploy', ['on' => 'localhost'])
git pull origin {{ $branch }}
php artisan migrate
yarn install
yarn prod
cd Modules/GDProxy
yarn install
yarn prod
cd Modules/NGProxy
yarn install
yarn prod
cd ../..
php artisan assets:upload
@endtask
