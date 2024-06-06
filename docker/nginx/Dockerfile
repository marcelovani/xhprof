FROM centos:centos7
# https://technology.riotgames.com/news/jenkins-docker-proxies-and-compose

# Install NGINX
RUN yum -y update; yum clean all
RUN yum -y install http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm; yum -y makecache
RUN yum -y install nginx-1.18.0

# Increment the version to force Docker to re-run the commands.
RUN export VERSION=1.0.0

# Clean up the default config
RUN rm /etc/nginx/conf.d/default.conf

# Add our config files
COPY nginx/xhprof.conf /etc/nginx/conf.d/xhprof.conf
COPY nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80
CMD ["nginx"]
