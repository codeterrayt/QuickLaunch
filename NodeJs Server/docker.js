const Docker = require("dockerode");
const { json } = require("express");
const path = require('path');

// Load constants synchronously
const CONSTANTS = require(path.join(__dirname, 'constants', 'constants.json'));

// Create a Dockerode instance with default settings
const docker = new Docker();

// Function to inspect container with retry mechanism
const inspectContainerWithRetry = async (container, ports) => {
  await new Promise((resolve) =>
    setTimeout(resolve, process.env.DOCKER_WAITING_TIME)
  );
  const containerInfo = await container.inspect();
  const portMappings = containerInfo.NetworkSettings.Ports;

  let newMap = {"portMap":{},"container_id":containerInfo.Id, "container_state":containerInfo.State};

  for (port of ports) {
    try {
      const mappedPort = portMappings[`${port}/tcp`][0].HostPort;
      newMap['portMap'][String(port)] = mappedPort;
    } catch (error) {
      console.log(error);
    }
  }
  // console.log(newMap);
  return newMap;
};

const startContainer = async (container_id) => {
  try {
    const container = docker.getContainer(container_id);

    if (!container) {
      return json({ "success": false, "message": CONSTANTS.CONTAINER_NOT_FOUND });
    }

    // Inspect the container to check if it exists
    const inspectData = await new Promise((resolve, reject) => {
      container.inspect((err, data) => {
        if (err || !data) {
          return reject({ "success": false,"message": CONSTANTS.CONTAINER_NOT_FOUND });
        }
        else if(!data.State.Paused){
          return reject({ "success": false, "message": CONSTANTS.CONTAINER_ALREADY_RUNNING_STATE });
        }
        resolve(data);
      });
    });

    // Restart the container
    await new Promise((resolve, reject) => {
      container.restart((err, data) => {
        if (err) {
          return reject({ "success": false,"message": CONSTANTS.CONTAINER_NOT_FOUND });
        }
        resolve(data);
      });
    });

    return { "success": true, "message": CONSTANTS.CONTAINER_RESTARTED_SUCCESSFULLY};

  } catch (error) {
    return { "success": false, "message": error.message };
  }
};

const pauseContainer = async (container_id) => {
  try {
    const container = docker.getContainer(container_id);

    if (!container) {
      return json({ "success": false, "message": CONSTANTS.CONTAINER_NOT_FOUND });
    }

    // Inspect the container to check if it exists
    const inspectData = await new Promise((resolve, reject) => {
      container.inspect((err, data) => {
        if (err || !data) {
          return reject({ "success": false,"message": CONSTANTS.CONTAINER_NOT_FOUND });
        }
        else if(data.State.Paused){
          return reject({ "success": false, "message": CONSTANTS.CONTAINER_ALREADY_PAUSE_STATE });
        }
        resolve(data);
      });
    });

    // Restart the container
    await new Promise((resolve, reject) => {
      container.pause((err, data) => {
        if (err) {
          return reject({ "success": false,"message": CONSTANTS.CONTAINER_NOT_FOUND });
        }
        resolve(data);
      });
    });

    return { "success": true, "message": CONSTANTS.CONTAINER_PAUSED_SUCCESSFULLY };

  } catch (error) {
    return { "success": false, "message": error.message };
  }
};


const stopContainer = async (container_id) => {
  try {
    const container = docker.getContainer(container_id);

    if (!container) {
      return json({ "success": false, "message": CONSTANTS.CONTAINER_NOT_FOUND });
    }

    // Inspect the container to check if it exists
    const inspectData = await new Promise((resolve, reject) => {
      container.inspect((err, data) => {
        if (err || !data) {
          return reject({ "success": false,"message": CONSTANTS.CONTAINER_NOT_FOUND });
        }
        resolve(data);
      });
    });

    // Restart the container
    await new Promise((resolve, reject) => {
      container.stop((err, data) => {
        if (err) {
          return reject({ "success": false,"message": CONSTANTS.CONTAINER_NOT_FOUND });
        }
        resolve(data);
      });
    });

    return { "success": true, "message": CONSTANTS.CONTAINER_STOPPED_SUCCESSFULLY };

  } catch (error) {
    return { "success": false, "message": error.message };
  }
};


// Function to create and start the container
const createAndStartContainer = async (image, ports, password) => {
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
    Env: [`VNC_PW=${password}`],
    HostConfig: {
      AutoRemove: true,
      ShmSize: 512 * 1024 * 1024, // 512 MB
      PortBindings: portBindings,
    },
    User: "root", // Run as root
    // hostname: "QuickLaunch"
  };

  try {
    const container = await docker.createContainer(options);
    await container.start();
    return await inspectContainerWithRetry(container, ports);
  } catch (err) {
    // console.error("Error creating or starting container:", err);
    return json({
      "success":false,
      "message": `Ivalid Image ${image} ${ports} `,
    });
  }
};

module.exports = {CONSTANTS, inspectContainerWithRetry, createAndStartContainer, startContainer,pauseContainer, stopContainer };
