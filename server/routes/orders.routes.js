import { Router } from 'express';
import { getOrdersByClient, createOrder } from '../controllers/orders.controller.js';
import { authenticateToken, authorizeDistributor } from '../middlewares/auth.js';

const router = Router();

router.post('/', authenticateToken, createOrder);
router.get('/:clientId', authenticateToken, authorizeDistributor , getOrdersByClient);

export default router;