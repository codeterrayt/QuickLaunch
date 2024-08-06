# Use an official Python runtime as a parent image
FROM python:3.10-slim

# Set the working directory inside the container
WORKDIR /app

# Install necessary packages
RUN apt-get update && apt-get install -y \
    git \
    && rm -rf /var/lib/apt/lists/*

# Set the environment variable for the GitHub repository URL
ENV REPO_URL=""

# Clone the repository and set up the application at runtime
RUN echo '#!/bin/bash\n\
git clone $REPO_URL .\n\
pip install -r requirements.txt\n\
python manage.py migrate\n\
gunicorn myproject.wsgi:application --bind 0.0.0.0:8000' > /start.sh && chmod +x /start.sh

# Expose the port the app runs on (default for Django)
EXPOSE 8000

# Start the application using the script
CMD ["/start.sh"]
