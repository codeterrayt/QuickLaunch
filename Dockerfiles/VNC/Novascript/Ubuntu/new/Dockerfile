FROM ubuntu:24.04

EXPOSE 8080 5901
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Singapore

RUN apt-get update
RUN apt-get install -y xfce4 xfce4-terminal
RUN apt-get install -y novnc
RUN apt-get install -y tightvncserver websockify
RUN apt-get install -y wget net-tools wget curl chromium-browser firefox openssh-client git
ENV USER root

COPY start.sh /start.sh
RUN chmod a+x /start.sh

RUN useradd -ms /bin/bash user
RUN mkdir /.novnc
RUN chown user:user /.novnc

COPY config /home/user
RUN chown -R user:user /home/user

RUN apt-get -y install gedit vim
USER user

WORKDIR /.novnc
RUN wget -qO- https://github.com/novnc/noVNC/archive/v1.0.0.tar.gz | tar xz --strip 1 -C $PWD
RUN mkdir /.novnc/utils/websockify
RUN wget -qO- https://github.com/novnc/websockify/archive/v0.6.1.tar.gz | tar xz --strip 1 -C /.novnc/utils/websockify
RUN ln -s vnc.html index.html

WORKDIR /home/user

RUN openssl req -new -x509 -days 365 -nodes \
    -subj "/C=US/ST=CA/L=San Francisco/O=My Company/OU=Org Unit/CN=localhost" \
    -keyout /opt/noVNC/utils/websockify/self.pem \
    -out /opt/noVNC/utils/websockify/self.pem

CMD ["sh","/start.sh"]