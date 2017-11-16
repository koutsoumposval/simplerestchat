# Setup
Kickstart your development environment with this guide.

## Install Docker
The current Docker environment is based on Docker Toolbox. 
If you don't have Docker Toolbox installed, you can download it [here](https://www.docker.com/products/docker-toolbox).

### Creating the Docker Virtual Machine 
To generate a virtual machine, run the following command anywhere in your terminal:
``` bash 
docker-machine create default --driver=virtualbox
```

To prevent permission problems we leverage Docker-Machine-NFS to mount volumes as NFS.
First, [install docker-machine-nfs](https://github.com/adlogix/docker-machine-nfs) and then run the following command:
```bash
docker-machine-nfs default --nfs-config="-alldirs -maproot=0" --mount-opts="noacl,async,nolock,vers=3,udp,noatime,actimeo=1"
```

As a final step, load the docker-machine:
```bash
eval $(docker-machine env default)
```

## Register domain names in hosts-file
The development stack will expose the following domains:
* simplerestchat.dev - the URL of application

These domains need to be added to the /etc/hosts files. First look up the ip-address of your Docker VM:
```bash
docker-machine ip $(docker-machine active)
```
Next, open the hosts file
```bash
sudo nano /etc/hosts
```
Append the following line to the file (change the ip-address to your Docker VM's ip):
```bash
192.168.99.100  simplerestchat.dev
```

## Install vendor dependencies with Composer
To install all composer dependencies, run the following command:
```bash
docker run --rm -v $(PWD):/app -v $($HOME)/.composer:/composer --user $(id -u):$(id -g) composer install --optimize-autoloader --no-interaction --no-progress --no-scripts
```

## Setup Docker development environment
Create the required network in order to communicate with the reverse proxy:
```bash
docker network create traefik_bridge_gateway --driver bridge
```
From the root directory of the repository, create a Docker Registry which will host our images:
```bash
docker-compose -f docker-compose.local.yml up -d registry
```

Next, build the App image (Multistage build):
```bash
docker-compose -f docker-compose.local.yml build
```

After the image was build successfully, push it to the registry:
```bash
docker-compose -f docker-compose.local.yml push
```

Now the image has been pushed to the registry, we can start the development stack with:
```bash
docker-compose -f docker-compose.local.yml up -d
```
## Create the database
```bash
touch database/database.sqlite 
```

## Migrate the database
To migrate the database, run the following through the command-line:
```bash
php artisan migrate
```

## Seed the database
To seed the database, run the following through the command-line:
```bash
php artisan db:seed 
```

## Is it working?
Try:
```bash
open http://simplerestchat.dev
```
You should see the Laravel Lumen homepage with the version information

Get your [Postman](https://www.getpostman.com/) ready



