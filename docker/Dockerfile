FROM php:7.4-apache
MAINTAINER William White <wwhite@hubbardradio.com>

# install the PHP extensions we need
RUN set -ex; \
	\
	apt-get update; \
	apt-get install -y \
		gcc \
		g++ \
		git \
		vim \
		less \
        apt-utils \
        libzip-dev \
        libfreetype6-dev \
        libjpeg-dev \
        libpng-dev \
		libxml2-dev \
		libxslt-dev \
		libssl-dev \
		libbz2-dev \
		libgd-dev \
		libgmp-dev \
		libmcrypt-dev \
		libtidy-dev \
		libmagickwand-dev \
		libmemcached-dev \
	; \
	rm -rf /var/lib/apt/lists/*;

# Configure Apache Document Root for Slim Application
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow Apache rewrites
RUN a2enmod rewrite expires headers

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["apache2-foreground"]
