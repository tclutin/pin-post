# PIN-POST

участники:

Гирфанов Константин (pepenka)

Земцова Анастасия (Anastasiya Zemtsova и mibelmay)

Перникова Елизавета (lisa-veta)

Сусоев Дмитрий (tclutin)



### Install
```
docker compose up -d
docker exec -it <id_container> sh
composer instal
php artisan migrate
php artisan db:seed
php artisan octane:start --server=swoole --host=0.0.0.0 --port=1215 --workers=4 --task-workers=4
minio http://localhost:9001/ (логин пароль - minioadmin)
создать buckets -> "uploads" (установить доступ public)
```
