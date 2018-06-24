FROM ubuntu:14.04
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install php5 php5-common php5-dev php5-json php5-mysql php5-cli php5-curl php5-imap php5-mcrypt php5-readline apache2 libapache2-mod-php5
RUN apt-get install -y vim git
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
RUN chown -R www-data:www-data /var/www/
RUN sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" /etc/php5/apache2/php.ini
ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf
ADD apache-config-ui.conf /etc/apache2/sites-available/ui.conf
ADD composer.sh /opt/composer.sh
RUN ln -s /etc/apache2/sites-available/ui.conf /etc/apache2/sites-enabled/ui.conf
RUN echo Listen 8000 >> /etc/apache2/ports.conf
EXPOSE 80
EXPOSE 8000
RUN a2enmod rewrite
RUN service apache2 restart
CMD /usr/sbin/apache2ctl -D FOREGROUND


