import { Router } from 'express';
import { createProduct, getProducts, getProductsByCompany , deleteProduct } from '../controllers/products.controller.js';

const router = Router();

router.get('/', getProducts);
router.post('/', createProduct);
router.get('/:companyId', getProductsByCompany)
router.delete('/:productId', deleteProduct);

export default router;