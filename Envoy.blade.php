@servers(['localhost' => '127.0.0.1'])

@task('deploy', ['on' => 'localhost'])
# git pull origin {{ $branch }}
php artisan migrate
npm install
npm run prod
cd Modules/GDProxy
npm install
npm run prod
cd Modules/NGProxy
npm install
npm run prod
cd ../..
php artisan assets:upload
@endtask
