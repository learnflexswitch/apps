# Apps
SnapRoute Applications; I.E. CLI etc. 

http://www.snaproute.com 

Product documentation is available at 
[Product Overview] (http://opensnaproute.github.io/docs/)

Complete system architecture can be found 
[Here](http://opensnaproute.github.io/docs/architecture.html) 

This project is clone from https://github.com/OpenSnaproute/apps.git 
This project is only for learing purpose.
This project is assumed to be changed by a program. 
DONOT CHECKOUT OR CLONE THIS PROJECT

(0)Install Docker:
cat /etc/lsb-release
apt-get update
apt-get upgrade
apt-get install -y docker.io
systemctl start docker
systemctl enable docker


(1) How to create docker image:
   1. cd /home/cli
   
   2. Copy all files and folders to current path

   3. Create Dockerfile:

FROM ubuntu:16.04
MAINTAINER york.chen

#update
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get -f install

RUN apt-get install -y net-tools apt-utils git openssh-server

##INSTALL CLI
ADD snaproute /opt/snaproute/
COPY cliprofile /etc/profile
COPY whistlerclisrc /etc/.whistlerclisrc

COPY python2.7 /usr/local/lib/python2.7/
COPY lib_python2.7 /usr/lib/python2.7
COPY python /usr/bin/python
ADD dist-packages /usr/local/lib/python2.7/dist-packages/

CMD ["chmod","666","/opt/snaproute/src/apps/cli2/models/cisco/*.json"]
CMD ["chmod","666","/opt/snaproute/src/apps/cli2/schema/*.json"]

#RUN /bin/bash -c "source /etc/profile"
#RUN /bin/bash --login -c "source /etc/profile"
RUN cat /etc/.whistlerclisrc >> /etc/bash.bashrc
#/etc/profile echo "source /ansible/hacking/env-setup" >> /tmp/setup

#Clean House
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN apt-get autoremove -y
RUN apt-get autoclean -y
RUN apt-get clean -y



Dockfile2:
FROM ubuntu:16.04
MAINTAINER whistler

RUN apt-get install -y net-tools apt-utils
##INSTALL CLI
ADD snaproute /opt/snaproute/
ENV PATH="/opt/snaproute/src/apps/cli2:/opt/snaproute/src/flexSdk/py:/opt/snaproute/src/flexSdk/py/flexswitchV2:${PATH}"
ENV PYTHONPATH=/opt/snaproute/src/flexSdk/py/flexswitchV2/:/opt/snaproute/src/apps/cli2/:/opt/snaproute/src/flexSdk/py/"

RUN cat clisrc >> /etc/bash.bashrc

(4). 
docker build -t "learnflexswitch/cli:1.0" .

(5). docker login

https://hub.docker.com/
learnflexswitch
learnflexswitch@gmail.com
<School>1982!


(6).
docker push learnflexswitch/cli:1.0

(7). Run a docker image:
(7.1.) Pull a image from docker hub:
docker pull learnflexswitch/whistlercli:1.0 
docker run -ti learnflexswitch/whistlercli:1.0 /bin/bash

docker ps -a #show all container, including stopped

docker ps  #show all that is running container


(8). Commands:

(8.1.). Delete all containers
docker rm $(docker ps -a -q)

(8.2.) Delete all images:
# Delete all images
docker rmi $(docker images -q)

(8.3.) Search in docker hub: 
docker version
docker search learnflex
docker pull ubuntu
docker images

(8.4.) Attache and detache a docker container
docker ps
docker exec -i -t 32ecbc1e6e70 bash
docker exec -it d_inst1 bash   #Enter into the docker shell for d_inst1
