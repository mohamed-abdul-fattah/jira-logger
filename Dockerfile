FROM php:7.3-cli

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get -q update
# Install Ruby & Git
RUN apt-get install -yq --no-install-recommends \
        build-essential \
        procps \
        openssh-server \
        vim \
        ruby-full \
        git && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install overcommit
RUN gem install overcommit

# Install composer and enable it globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

COPY . /app
COPY entrypoint.sh /tmp/entrypoint.sh
RUN chmod +x /tmp/entrypoint.sh

WORKDIR /app

ENTRYPOINT ["/tmp/entrypoint.sh"]

CMD ["php", "-a"]
