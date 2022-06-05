FROM php:7.3-cli

ARG DEBIAN_FRONTEND=noninteractive
ARG APP_USER=jiralogger
ARG USER_HOME=/home/jiralogger
ARG APP_PATH=${USER_HOME}/jiralogger

RUN apt-get -q update && \
    apt-get install -yq --no-install-recommends \
        build-essential \
        procps \
        openssh-server \
        vim \
        ruby-full \
        wget \
        libzip-dev \
        unzip \
        git && \
    docker-php-ext-install zip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install overcommit
RUN gem install overcommit

# Install composer and enable it globally
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet && \
    mv composer.phar /usr/local/bin/composer

RUN useradd -md ${USER_HOME} -u 1000 -s /bin/bash ${APP_USER}

COPY --chown=${APP_USER}:${APP_USER} . ${APP_PATH}
COPY --chown=${APP_USER}:${APP_USER} entrypoint.sh /tmp/entrypoint.sh
RUN chmod +x /tmp/entrypoint.sh

WORKDIR ${APP_PATH}
USER ${APP_USER}

ENTRYPOINT [ "/tmp/entrypoint.sh" ]

CMD [ "php", "-a" ]
