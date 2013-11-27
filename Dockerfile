# Digital Austin Papers Web Site
# for Docker 0.7 or newer
#
# This Dockerfile builds an image that runs a mysql-server with the necessary
# configuration for us to use as a test database for DAP.
#
# How to use (assuming Docker is installed):
# 
#   # This builds the Docker image. Wait for the command to finish.
#   sudo docker build -t dap/mysql .
#
#   # This runs the image in daemon mode.
#   sudo docker run -d -p 127.0.0.1:3306:3306 -name dapdb dap/mysql
#
# Now that the database container is running, launch a test PHP server:
#
#   php -S localhost:8888
#
# To kill (stop) the container once you are finished using it, use:
#
#   sudo docker kill dapdb
#
#  Note that any changes you made to the database will not persist the next
#  time you run this image, unless you create a new snapshot (see docker.io).
#
# VERSION               0.2

FROM ubuntu:12.04
MAINTAINER Digital Austin Papers
EXPOSE 3306

# Install packages
RUN apt-get update
RUN apt-get install -y mysql-server
RUN apt-get clean

# Configure mysql
RUN sed -i -e"s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/my.cnf

# Import the current test database dump
ADD sql/dev_db_dumpfile.sql.gz /root/

# Set up database
RUN /usr/sbin/mysqld & sleep 3 && mysqladmin create dap && echo "GRANT ALL ON *.* TO dap@'%' IDENTIFIED BY 'dap' WITH GRANT OPTION; FLUSH PRIVILEGES" | mysql dap && gunzip -c /root/dev_db_dumpfile.sql.gz | mysql dap

# Specify what to do when starting this image
CMD ["/usr/sbin/mysqld"]
