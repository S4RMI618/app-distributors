import { Router } from "express";
import { authenticateToken, authorizeAdmin } from "../middlewares/auth.js";
import {registerAdmin, registerUser, login } from "../controllers/auth.controller.js";

const router = Router();

router.post('/login', login);
router.post('/register-users', authenticateToken, authorizeAdmin, registerUser);
router.post('/register-admin', authenticateToken, authorizeAdmin, registerAdmin);


export default router;
