import { BACKEND_PORT } from "./config.js";
import app from "./app.js";

app.listen(BACKEND_PORT);
console.log("Server started on: " + BACKEND_PORT);