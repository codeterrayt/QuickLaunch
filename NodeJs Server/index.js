const express = require("express");
const app = express();
const path = require("path");
const Docker = require('dockerode');

// Create a Dockerode instance with default settings
const docker = new Docker();

const options = {
    Image: 'kasmweb/core-ubuntu-focal:1.14.0',
    Tty: true,
    Interactive: true,
    Env: ['VNC_PW=password'],
    HostConfig: {
        AutoRemove: true,
        ShmSize: 512 * 1024 * 1024, // 512 MB
        PortBindings: {
            '6901/tcp': [{ HostPort: '' }], // Docker will assign a random port
        }
    },
    User: 'root' // Run as root
};


// Function to inspect container with retry mechanism
const inspectContainerWithRetry = async (container, retries = 5) => {
    for (let attempt = 0; attempt < retries; attempt++) {
        try {
            const containerInfo = await container.inspect();
            const portMappings = containerInfo.NetworkSettings.Ports;

            if (portMappings && portMappings['6901/tcp'] && portMappings['6901/tcp'][0]) {
                const mappedPort = portMappings['6901/tcp'][0].HostPort;
                console.log(`Container started. Port 6901 is mapped to host port ${mappedPort} ${attempt} `);
                return mappedPort;
            } else {
                console.log('Port 6901 not found in port mappings, retrying...');
                await new Promise(resolve => setTimeout(resolve, 100)); // Wait for 100ms before retrying
            }
        } catch (err) {
            console.error('Error inspecting container:', err);
            return null;
        }
    }
    console.error('Port 6901 not found in port mappings or structure is unexpected');
    return null;
};

// Function to create and start the container
const createAndStartContainer = async () => {
    try {
        const container = await docker.createContainer(options);
        await container.start();
        return await inspectContainerWithRetry(container);
    } catch (err) {
        console.error('Error creating or starting container:', err);
        return null;
    }
};

//application level middleware
app.use((req, res, next) => {
    console.log('Time:', Date.now())
    next()
})

app.get("/", async (req, res) => {
    const port = await createAndStartContainer();
    if (port) {
        res.send(`Container started. Port 6901 is mapped to host port <a href="https://127.0.0.1:${port}">click here ${port}</a>`);
    } else {
        res.status(500).send('Failed to start container');
    }
});


app.use("/app",async (req,res,next)=>{
    console.log("middlware")
    next();
})

app.get("/app",async (req,res)=>{
    res.send(`<iframe src='https://127.0.0.1:53426/' fulkl style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" />`);
})







app.listen(3000,()=> console.log("app running on port 3000"));