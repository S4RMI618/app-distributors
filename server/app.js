import express from "express";
import morgan from "morgan";
import bodyParser from "body-parser";
import dotenv from "dotenv";
import authRoutes from "./routes/auth.routes.js";
import companiesRoutes from "./routes/companies.routes.js";
import ordersRoutes from "./routes/orders.routes.js";
import cors from 'cors';
import productsRoutes from "./routes/products.routes.js";
dotenv.config();

const app = express();
app.use(express.json());
app.use(morgan('dev'));
app.use(bodyParser.json());
app.use("/api", authRoutes)
app.use("/api/companies", companiesRoutes)
app.use("/api/orders", ordersRoutes)
app.use("/api/products", productsRoutes)

app.use(cors({
    origin: process.env.FRONTEND_HOST
}));

export default app;
