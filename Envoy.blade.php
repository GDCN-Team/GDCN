@servers(['localhost' => '127.0.0.1'])

@task('deploy', ['on' => 'localhost'])
git pull origin {{ $branch }}
php artisan migrate
yarn prod
cd Modules/GDProxy
yarn prod
cd Modules/NGProxy
yarn prod
cd ../..
php artisan assets:upload
@endtask
