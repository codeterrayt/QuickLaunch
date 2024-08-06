# Use an official Node runtime as a parent image
FROM node:lts-slim

# Set the working directory inside the container
WORKDIR /app

# Install Git
RUN apt-get update && apt-get install -y git

# Set the environment variable for the GitHub repository URL
ENV REPO_URL=""

# Clone the repository, install dependencies, build the project, and start the server at runtime
RUN echo '#!/bin/bash\n\
git clone $REPO_URL .\n\
npm install\n\
npm run build\n\
npm start' > /start.sh && chmod +x /start.sh

# Expose the port the app runs on (default for React)
EXPOSE 3000

# Start the application using the script
CMD ["/start.sh"]