const Docker = require("dockerode");
const { json } = require("express");
const path = require("path");
const axios = require("axios");
const { env } = require("process");

// Load constants synchronously
const CONSTANTS = require(path.join(__dirname, "constants", "constants.json"));

let docker;

if (env.DOCKER_SOCKET.length == 0) {
  docker = new Docker();
} else {
  docker = new Docker({
    socketPath: env.DOCKER_SOCKET,
  });
}


// const docker = new Docker();

// Function to inspect container with retry mechanism
const inspectContainerWithRetry = async (container, ports) => {
  await new Promise((resolve) =>
    setTimeout(resolve, process.env.DOCKER_WAITING_TIME)
  );
  const containerInfo = await container.inspect();
  const portMappings = containerInfo.NetworkSettings.Ports;

  let newMap = {
    portMap: {},
    container_id: containerInfo.Id,
    container_state: containerInfo.State,
  };

  for (port of ports) {
    try {
      const mappedPort = portMappings[`${port}/tcp`][0].HostPort;
      newMap["portMap"][String(port)] = mappedPort;
    } catch (error) {
      console.log(error);
    }
  }
  // console.log(newMap);
  return newMap;
};

const startContainer = async (container_id, ports) => {
  try {
    const container = docker.getContainer(container_id);

    if (!container) {
      return json({ success: false, message: CONSTANTS.CONTAINER_NOT_FOUND });
    }

    // Inspect the container to check if it exists
    const inspectData = await new Promise((resolve, reject) => {
      container.inspect((err, data) => {
        if (err || !data) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_NOT_FOUND,
          });
        } else if (!data.State.Paused) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_ALREADY_RUNNING_STATE,
          });
        }
        resolve(data);
      });
    });

    // Restart the container
    await new Promise((resolve, reject) => {
      container.restart((err, data) => {
        if (err) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_NOT_FOUND,
          });
        }
        resolve(data);
      });
    });

    const icwr = await inspectContainerWithRetry(container, ports);
    return {
      success: true,
      message: CONSTANTS.CONTAINER_RESTARTED_SUCCESSFULLY,
      portMap: icwr.portMap,
    };
  } catch (error) {
    return { success: false, message: error.message };
  }
};

const pauseContainer = async (container_id, ports) => {
  try {
    const container = docker.getContainer(container_id);

    if (!container) {
      return json({ success: false, message: CONSTANTS.CONTAINER_NOT_FOUND });
    }

    // Inspect the container to check if it exists
    const inspectData = await new Promise((resolve, reject) => {
      container.inspect((err, data) => {
        if (err || !data) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_NOT_FOUND,
          });
        } else if (data.State.Paused) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_ALREADY_PAUSE_STATE,
          });
        }
        resolve(data);
      });
    });

    // Restart the container
    await new Promise((resolve, reject) => {
      container.pause((err, data) => {
        if (err) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_NOT_FOUND,
          });
        }
        resolve(data);
      });
    });

    const icwr = await inspectContainerWithRetry(container, ports);
    return {
      success: true,
      message: CONSTANTS.CONTAINER_PAUSED_SUCCESSFULLY,
      portMap: icwr.portMap,
    };
  } catch (error) {
    return { success: false, message: error.message };
  }
};

const stopContainer = async (container_id) => {
  try {
    const container = docker.getContainer(container_id);

    if (!container) {
      return json({ success: false, message: CONSTANTS.CONTAINER_NOT_FOUND });
    }

    // Inspect the container to check if it exists
    const inspectData = await new Promise((resolve, reject) => {
      container.inspect((err, data) => {
        if (err || !data) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_NOT_FOUND,
          });
        }
        resolve(data);
      });
    });

    // Restart the container
    await new Promise((resolve, reject) => {
      container.stop((err, data) => {
        if (err) {
          return reject({
            success: false,
            message: CONSTANTS.CONTAINER_NOT_FOUND,
          });
        }
        resolve(data);
      });
    });

    return { success: true, message: CONSTANTS.CONTAINER_STOPPED_SUCCESSFULLY };
  } catch (error) {
    return { success: false, message: error.message };
  }
};

const is_image_exists = async (image_repo_name) => {
  return new Promise((resolve) => {
    docker.getImage(image_repo_name).inspect((err, data) => {
      if (err && err.statusCode === 404) {
        resolve({
          exists: false,
          success: true,
        });
      } else if (err) {
        resolve({
          exists: false,
          success: false,
        });
      } else {
        resolve({
          exists: true,
          success: true,
        });
      }
    });
  });
};

// const pull_image_in_background = async (image_repo_name) => {
//   // Start pulling the image in the background
//   // console.log(image_repo_name)
//   docker.pull(image_repo_name, (err, stream) => {
//     if (err) {
//       console.error(`Error pulling image: ${err.message}`);
//       return;
//     }

//     // Handle the stream in the background
//     docker.modem.followProgress(stream, async (err, output) => {
//       if (err) {
//         console.error(`Error during image pull process: ${err.message}`);
//         return;
//       }

//       // Once the image is fully pulled, make the API call
//       try {
//         let res = await axios.post(`${env.LARAVEL_URL}/api/image/pulled`, {
//           image_repo_name: btoa(image_repo_name)
//         });
//         console.log(res.data)
//         console.log(`Image ${image_repo_name} pulled and API notified.`);
//       } catch (apiError) {
//         console.error(`Error calling API after image pull: ${apiError}`);
//       }
//     });
//   });

//   // Immediately return a response indicating the image is being pulled
//   return {
//     pulling: true,
//     message: `Image ${image_repo_name} is being pulled in the background.`
//   };
// };

const pull_image_in_background = async (image_repo_name) => {
  return new Promise((resolve, reject) => {
    console.log(`Pulling request sent for image: ${image_repo_name}`);

    // Start pulling the image in the background
    docker.pull(image_repo_name, (err, stream) => {
      if (err) {
        console.error(`Error pulling image: ${err.message}`);
        return reject(err);
      }

      // Log that the pulling process has started
      console.log(`Pulling image: ${image_repo_name}`);

      // Handle the stream in the background
      docker.modem.followProgress(stream, (err, output) => {
        if (err) {
          console.error(`Error during image pull process: ${err.message}`);
          return reject(err);
        }

        // Log success after the image is fully pulled
        console.log(`Image ${image_repo_name} pulled successfully.`);

        // Once the image is fully pulled, make the API call
        axios
          .post(`${env.LARAVEL_URL}/api/image/pulled`, {
            image_repo_name: btoa(image_repo_name),
          })
          .then((res) => {
            console.log(res.data);
            resolve(res.data);
          })
          .catch((apiError) => {
            console.error(`Error calling API after image pull: ${apiError}`);
            reject(apiError);
          });
      });
    });
  });
};

// Example usage in an API route
const pull_image = async (image_repo_name) => {
  try {
    const result = await pull_image_in_background(image_repo_name);
    // return result;  // Send pulling response immediately
    console.log("Pulling Request Sent to Background..");
  } catch (err) {
    console.log("Error starting image pull process");
  }
};

// Function to create and start the container
const createAndStartContainer = async (image, ports, password) => {
  // const options = {
  //   Image: "kasmweb/core-ubuntu-focal:1.14.0",
  //   Tty: true,
  //   Interactive: true,
  //   Env: ["VNC_PW=password"],
  //   HostConfig: {
  //     AutoRemove: true,
  //     ShmSize: 512 * 1024 * 1024, // 512 MB
  //     PortBindings: {
  //       "6901/tcp": [{ HostPort: "" }], // Docker will assign a random port
  //     },
  //   },
  //   User: "root", // Run as root
  // };

  let portBindings = {};
  ports.forEach((port) => {
    portBindings[`${port}/tcp`] = [{ HostPort: "" }];
  });

  console.log(portBindings);

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
      success: false,
      message: `Ivalid Image ${image} ${ports} `,
    });
  }
};

module.exports = {
  CONSTANTS,
  inspectContainerWithRetry,
  createAndStartContainer,
  startContainer,
  pauseContainer,
  stopContainer,
  is_image_exists,
  pull_image,
};
