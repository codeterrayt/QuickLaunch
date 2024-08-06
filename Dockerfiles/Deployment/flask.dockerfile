# Use an official Python runtime as a parent image
FROM python:3.10-slim

# Set the working directory inside the container
WORKDIR /app

# Install Git
RUN apt-get update && apt-get install -y git

# Set the environment variable for the GitHub repository URL
ENV REPO_URL=""

# Clone the repository, install dependencies, and start the application at runtime
RUN echo '#!/bin/bash\n\
git clone $REPO_URL .\n\
pip install -r requirements.txt\n\
gunicorn --bind 0.0.0.0:5000 wsgi:app' > /start.sh && chmod +x /start.sh

# Expose the port the app runs on (default for Flask)
EXPOSE 5000

# Start the application using the script
CMD ["/start.sh"]
