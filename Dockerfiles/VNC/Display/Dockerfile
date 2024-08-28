FROM ubuntu:24.04
ENV USER=root
ENV PASSWORD=password1
ENV DEBIAN_FRONTEND=noninteractive 
ENV DEBCONF_NONINTERACTIVE_SEEN=true

# Set timezone data non-interactively
RUN apt-get update && \
    echo "tzdata tzdata/Areas select America" > ~/tx.txt && \
    echo "tzdata tzdata/Zones/America select New York" >> ~/tx.txt && \
    debconf-set-selections ~/tx.txt

# Install necessary packages
RUN apt-get install -y \
    abiword gnupg apt-transport-https wget software-properties-common \
    ratpoison novnc websockify libxv1 libglu1-mesa xauth x11-utils xorg \
    tightvncserver

# Add TurboVNC and VirtualGL repositories
RUN wget -q -O- https://packagecloud.io/dcommander/virtualgl/gpgkey | gpg --dearmor >/etc/apt/trusted.gpg.d/VirtualGL.gpg && \
    wget -q -O- https://packagecloud.io/dcommander/turbovnc/gpgkey |  gpg --dearmor >/etc/apt/trusted.gpg.d/TurboVNC.gpg && \
    wget https://raw.githubusercontent.com/VirtualGL/repo/main/VirtualGL.list && \
    wget https://raw.githubusercontent.com/TurboVNC/repo/main/TurboVNC.list && \
    mv TurboVNC.list /etc/apt/sources.list.d/TurboVNC.list && \
    mv VirtualGL.list /etc/apt/sources.list.d/VirtualGL.list

# Install VirtualGL and TurboVNC
RUN apt-get update && \
    apt-get install virtualgl -y && \
    apt-get install turbovnc -y

# Set up VNC password
RUN mkdir ~/.vnc/ && \
    mkdir ~/.dosbox && \
    echo $PASSWORD | vncpasswd -f > ~/.vnc/passwd && \
    chmod 0600 ~/.vnc/passwd

# Configure ratpoison
RUN echo "set border 1" > ~/.ratpoisonrc

# Generate a self-signed certificate for noVNC
RUN openssl req -x509 -nodes -newkey rsa:2048 -keyout ~/novnc.pem -out ~/novnc.pem -days 3650 -subj "/C=US/ST=NY/L=NY/O=NY/OU=NY/CN=NY emailAddress=email@example.com"

# Expose the port for noVNC
EXPOSE 80

# Start VNC server and websockify for noVNC
CMD /opt/TurboVNC/bin/vncserver :1 -geometry 1920x1080 -depth 24 && \
    websockify -D --web=/usr/share/novnc/ --cert=~/novnc.pem 80 localhost:5901 && \
    tail -f /dev/null
