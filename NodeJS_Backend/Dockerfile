FROM node:20 

WORKDIR /app 


RUN apt-get update -y 
RUN apt-get upgrade -y 


COPY package.json . 
COPY package-lock.json . 

RUN npm i --force

COPY . .  

EXPOSE 5174

CMD npm run dev