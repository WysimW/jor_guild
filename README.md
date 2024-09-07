# Mon Projet

# Rebuild the Docker image
`docker-compose build`

# Recreate the containers
`docker-compose up -d`

# Restart docker
`docker restart $(docker ps -q)`

# Go on a container
`docker exec -it CONTAINER_NAME bash`

`docker exec -it jor_react_frontend bash`
`docker exec -it jor_symfony bash`

docker-compose down
docker-compose up --build


php bin/console make:entity

php bin/console make:migration
php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load

php bin/console cache:clear
