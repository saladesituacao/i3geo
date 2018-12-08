FROM i3geo/software-base:latest
RUN mkdir /var/www/i3geo
RUN chgrp -R 0 /var/log/apache2
RUN chmod -R g+rwX /var/log/apache2
RUN chgrp -R 0 /var/lock/apache2
RUN chmod -R g+rwX /var/lock/apache2
RUN chgrp -R 0 /var/run/apache2
RUN chmod -R g+rwX /var/run/apache2
RUN echo -n > /var/www/index.php
RUN chmod -R 555 /var/www
RUN ln -sf /proc/self/fd/1 /var/log/apache2/error.log
COPY . /var/www/i3geo
CMD ["apachectl", "-D", "FOREGROUND"]
EXPOSE 8080
USER 1001