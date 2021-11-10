## Challenge Aivo

El proyecto esta desarrollado en PHP con el Framework Symfony 3.4

A continuación detallo los pasos para hacer funcionar el proyecto de manera local, para esto es necesario contar con Docker y Docker Compose

1 - Levantar docker y el proyecto

```bash
$ cd aivo/docker
$ docker-compose up -d
$ docker exec -ti aivo_apache composer install
$ docker exec -ti aivo_apache chmod -R 777 /var/www/html/var
$ docker exec -ti aivo_apache php bin/console cache:clear --env prod
```

2 - Correr dos pequeñas pruebas unitarias para comprobar que todo funcione bien

```bash 
$ docker exec -ti aivo_apache composer test test/
```

