FROM ubuntu:24.04

# Update package lists
RUN apt-get update


# Install OpenSSL
RUN apt-get install -y openssl

# Install sudo
RUN apt-get install -y sudo

# Install Git
RUN apt-get install -y git

# Install XFCE4 desktop environment
RUN apt-get install -y xfce4

# Install XFCE4 goodies
RUN apt-get install -y xfce4-goodies

# Install Faenza icon theme
RUN apt-get install -y faenza-icon-theme

# Install Bash
RUN apt-get install -y bash

# Install Python3
RUN apt-get install -y python3

# Install TigerVNC standalone server
RUN apt-get install -y tigervnc-standalone-server

# Install TigerVNC Xorg extension
RUN apt-get install -y tigervnc-xorg-extension

# Install XFCE4 terminal
RUN apt-get install -y xfce4-terminal

# Install Firefox
RUN apt-get install -y firefox

# Install CMake
RUN apt-get install -y cmake

# Install Wget
RUN apt-get install -y wget

# Install PulseAudio
RUN apt-get install -y pulseaudio

# Install PulseAudio utilities
RUN apt-get install -y pulseaudio-utils

# Install PulseAudio volume control
RUN apt-get install -y pavucontrol

# Install ALSA utilities
RUN apt-get install -y alsa-utils

# Install ALSA OSS
RUN apt-get install -y alsa-oss

# Install Node.js
RUN apt-get install -y nodejs

# Install npm
RUN apt-get install -y npm

# Install build-essential
RUN apt-get install -y build-essential


# # Add a new user 'ubuntu2'
# RUN adduser --home /home/ubuntu2 --shell /bin/bash --disabled-password --gecos "" ubuntu2

# # Set password for 'ubuntu2' user
# RUN echo "ubuntu:ubuntu" | chpasswd

# Add 'ubuntu2' user to sudoers group
RUN usermod -aG sudo ubuntu

# Allow 'ubuntu2' user to run sudo commands without a password
RUN echo "ubuntu ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers


# Clone noVNC repository
RUN git clone https://github.com/novnc/noVNC /opt/noVNC

# Clone websockify repository
RUN git clone https://github.com/novnc/websockify /opt/noVNC/utils/websockify

# Download custom script.js for noVNC
RUN wget https://raw.githubusercontent.com/novaspirit/Alpine_xfce4_noVNC/dev/script.js -O /opt/noVNC/script.js

# Download custom audify.js for noVNC
RUN wget https://raw.githubusercontent.com/novaspirit/Alpine_xfce4_noVNC/dev/audify.js -O /opt/noVNC/audify.js

# Download custom vnc.html for noVNC
RUN wget https://raw.githubusercontent.com/novaspirit/Alpine_xfce4_noVNC/dev/vnc.html -O /opt/noVNC/index.html

# Download custom pcm-player.js for noVNC
RUN wget https://raw.githubusercontent.com/novaspirit/Alpine_xfce4_noVNC/dev/pcm-player.js -O /opt/noVNC/pcm-player.js

# end

# Install npm packages
RUN npm install --prefix /opt/noVNC ws
RUN npm install --prefix /opt/noVNC audify

RUN apt-get update && \
    apt-get install -y xfce4 xfce4-goodies xorg dbus-x11 x11-xserver-utils xterm

# RUN echo -e "\n\n\n\n\n\n" | openssl req -new -x509 -days 365 -nodes -out self.pem -keyout /opt/noVNC/utils/websockify/self.pem

RUN openssl req -new -x509 -days 365 -nodes \
    -subj "/C=US/ST=CA/L=San Francisco/O=My Company/OU=Org Unit/CN=localhost" \
    -keyout /opt/noVNC/utils/websockify/self.pem \
    -out /opt/noVNC/utils/websockify/self.pem


# Create the entry.sh script
RUN echo '#!/bin/bash\n\
ls .X99-lock >> /dev/null 2>&1 && rm -rf /tmp/.X99-lock & \n\
sleep 1 \n\
mkdir -p /home/ubuntu/.vnc & \n\
sleep 1 \n\
echo "SecurityTypes=None" | tee -a /home/ubuntu/.vnc/config \n\
sleep 1 \n\
echo -e "#!/bin/bash\nstartxfce4 &" | tee -a /home/ubuntu/.vnc/xstartup \n\
sleep 1 \n\
/usr/bin/pulseaudio 2>&1 | sed "s/^/[pulseaudio] /" & \n\
sleep 1 \n\
/usr/bin/node /opt/noVNC/audify.js 2>&1 | sed "s/^/[audify    ] /" & \n\
/opt/noVNC/utils/novnc_proxy --vnc localhost:5999 2>&1 | sed "s/^/[noVNC     ] /"' > /entry.sh

# Make the script executable
RUN chmod +x /entry.sh

# RUN chown ubuntu:ubuntu /entry.sh

RUN echo "root ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

# Use root user (default)
USER root

# Set the entrypoint to the script
ENTRYPOINT [ "/bin/bash", "/entry.sh" ]
