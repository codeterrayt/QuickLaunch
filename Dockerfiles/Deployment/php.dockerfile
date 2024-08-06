# Use an official PHP with Apache image as a parent image
FROM php:8.1-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Install Git
RUN apt-get update && apt-get install -y git

# Set the environment variable for the GitHub repository URL
ENV REPO_URL=""

# Clone the repository and set up the application at runtime
RUN echo '#!/bin/bash\n\
git clone $REPO_URL .\n\
composer install\n\
apache2-foreground' > /start.sh && chmod +x /start.sh

# Expose the port the app runs on (default for Apache)
EXPOSE 80

# Start the application using the script
CMD ["/start.sh"]
