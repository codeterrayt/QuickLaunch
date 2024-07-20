const Docker = require("dockerode");

// Create a Dockerode instance with default settings
const docker = new Docker();

// Function to inspect container with retry mechanism
const inspectContainerWithRetry = async ( container, ports) => {
console.log(process.env.DOCKER_WAITING_TIME)
  await new Promise((resolve) => setTimeout(resolve, process.env.DOCKER_WAITING_TIME));
  const containerInfo = await container.inspect();
  const portMappings = containerInfo.NetworkSettings.Ports;

  let newMap={};

    for(port of ports){
        try {
            const mappedPort = portMappings[`${port}/tcp`][0].HostPort;
            newMap[String(port)] = mappedPort;
        } catch (error) {
            console.log(error)    
        }
    }   
    console.log(newMap)
    return newMap;
};

// Function to create and start the container
const createAndStartContainer = async (image, ports) => {
  //   const options = {
  //     Image: "kasmweb/core-ubuntu-focal:1.14.0",
  //     Tty: true,
  //     Interactive: true,
  //     Env: ["VNC_PW=password"],
  //     HostConfig: {
  //       AutoRemove: true,
  //       ShmSize: 512 * 1024 * 1024, // 512 MB
  //       PortBindings: {
  //         "6901/tcp": [{ HostPort: "" }], // Docker will assign a random port
  //       },
  //     },
  //     User: "root", // Run as root
  //   };




  let portBindings = {};
  ports.forEach((port) => {
    portBindings[`${port}/tcp`] = [{ HostPort: "" }];
  });


  const options = {
    Image: image,
    Tty: true,
    Interactive: true,
    Env: ["VNC_PW=password"],
    HostConfig: {
      AutoRemove: true,
      ShmSize: 512 * 1024 * 1024, // 512 MB
      PortBindings: portBindings,
    },
    User: "root", // Run as root
  };


  try {
    const container = await docker.createContainer(options);
    await container.start();
    // await new Promise((resolve) => setTimeout(resolve, 100));
    // let d = await inspectContainerWithRetry(container, portBindings);
    return await inspectContainerWithRetry(container,ports);
  } catch (err) {
    console.error("Error creating or starting container:", err);
    return `Ivalid Image ${image} ${ports} `;
  }
};

module.exports = { inspectContainerWithRetry, createAndStartContainer };
