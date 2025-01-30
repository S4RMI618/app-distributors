import dotenv from "dotenv";

dotenv.config();

export const BACKEND_PORT = process.env.BACKEND_PORT || 3000
export const FRONTEND_HOST = process.env.FRONTEND_HOST||'http://localhost:5173'
export const TOKEN_SECRET = process.env.TOKEN_SECRET || 'my-sarmientoken';