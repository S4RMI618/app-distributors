import productService from "../services/products.service.js";

export const getProducts = async (req, res) => {
    try {
        const products = await productService.getProducts();
        res.status(200).json(products);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

export const createProduct = async (req, res) => {
    const { name_product , code_product, company_id} = req.body;

    if (!name_product || !code_product || !company_id) {
        return res.status(400).json({ message: 'Missing required fields' });
    }

    try {
        const result = await productService.createProduct(name_product, code_product, company_id);
        res.status(201).json({ message: 'Product created successfully', result });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

export const getProductsByCompany = async (req, res) => {
    const { companyId } = req.params;

    try {
        const products = await productService.getProductsByCompany(companyId);
        res.status(200).json(products);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

export const deleteProduct = async (req, res) => {
    const { productId } = req.params;
    const productExists = await productService.checkProductExists(productId); // return a boolean
    if (!productExists) {
        return res.status(404).json({ message: 'Product not found' });
    }
    try {
        await productService.deleteProduct(productId);
        res.status(200).json({ message: 'Product deleted successfully', productDeleted: productExists});
    } catch (error) {
        res.status(500).json({ message: error.message });   
    }
};