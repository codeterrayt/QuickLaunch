const express = require("express");
const app = express();
const { createAndStartContainer } = require("./docker");

//application level middleware
app.use((req, res, next) => {
  console.log("Time:", Date.now());
  next();
});


app.use(express.json());

app.all("/start", async (req, res) => {
    const { ports, image } = req.body;

    if (!ports || !image) {
        return res.status(400).send("Ports and image are required.");
    }

    try {
        const container = await createAndStartContainer(image, ports);
        res.json(container);
    } catch (err) {
        console.error("Failed to start container:", err.message);
        res.status(500).send("Failed to start container");
    }
});


app.use("/app", async (req, res, next) => {
  console.log("middlware");
  next();
});

app.get("/app", async (req, res) => {
  res.send(
    `<iframe src='https://127.0.0.1:53426/' fulkl style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" />`
  );
});

app.listen(3000, () => console.log("app running on port 3000"));
