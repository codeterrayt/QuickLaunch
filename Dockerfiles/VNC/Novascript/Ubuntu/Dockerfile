FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Kolkata
ENV USER=root

RUN apt-get update && apt-get install -y \
    xfce4 \
    xfce4-goodies \
    tightvncserver \
    wget \
    net-tools \
    curl \
    xterm \
    python3 \
    python3-minimal \
    && apt-get clean

RUN ln -s /usr/bin/python3 /usr/bin/python

RUN wget https://github.com/novnc/noVNC/archive/refs/tags/v1.3.0.tar.gz \
    && tar -xzf v1.3.0.tar.gz \
    && mv noVNC-1.3.0 /opt/noVNC \
    && wget https://github.com/novnc/websockify/archive/refs/tags/v0.9.0.tar.gz \
    && tar -xzf v0.9.0.tar.gz \
    && mv websockify-0.9.0 /opt/noVNC/utils/websockify \
    && rm v1.3.0.tar.gz v0.9.0.tar.gz

RUN mkdir ~/.vnc \
    && echo "password" | vncpasswd -f > ~/.vnc/passwd \
    && chmod 600 ~/.vnc/passwd

CMD ["bash", "-c", "vncserver :1 -geometry 1280x800 -depth 24 && /opt/noVNC/utils/novnc_proxy --vnc localhost:5901 --listen 8081"]
