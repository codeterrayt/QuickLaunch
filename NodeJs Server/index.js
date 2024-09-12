const express = require("express");
const app = express();
const {
  createAndStartContainer,
  startContainer,
  pauseContainer,
  stopContainer,
  is_image_exists,
  pull_image,
  CONSTANTS,
} = require("./docker");

//application level middleware
app.use((req, res, next) => {
  console.log("Time:", Date.now());
  next();
});

app.use(express.json());

app.all("/spawn/container", async (req, res) => {
  const { ports, image, password } = req.body;

  if (!ports || !image || !password) {
    return res
      .status(400)
      .send({ success: false, message: CONSTANTS.IMAGE_PORT_REQUIRED });
  }

  try {
    console.log(image, ports, password);
    const container = await createAndStartContainer(image, ports, password);
    if (container.message == undefined || container.message === null) {
      return res.status(500).send(container);
    }
    console.log(message);
    return res.json(container);
  } catch (err) {
    console.error("Failed to start container:", err.message);
    return res
      .status(500)
      .send({ message: "Failed to start container, Image Not Found" });
  }
});

app.all("/start/container", async (req, res) => {
  const { container_id, ports } = req.body;

  const container = await startContainer(container_id, ports);
  return res.send(container);
});

app.all("/pause/container", async (req, res) => {
  const { container_id, ports } = req.body;
  const container = await pauseContainer(container_id, ports);
  return res.send(container);
});

app.all("/stop/container", async (req, res) => {
  const { container_id } = req.body;
  const container = await stopContainer(container_id);
  return res.send(container);
});

app.all("/image/exists", async (req, res) => {
  const { image_repo_name } = req.body;
  // console.log(image_repo_name)
  return res.send(await is_image_exists(image_repo_name));
});

app.all("/image/pull", async (req, res) => {
  const { image_repo_name } = req.body;
  let output = await pull_image(image_repo_name);
  console.log(output);
  return res.send(output);
});

app.use("/app", async (req, res, next) => {
  console.log("middlware");
  next();
});

app.get("/app", async (req, res) => {
  return res.send(
    `<iframe src='https://127.0.0.1:53426/' fulkl style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" />`
  );
});

app.listen(3000, () => console.log("app running on port 3000"));
