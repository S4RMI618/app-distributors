import pool from "../db.js";

const getProducts = async (req, res) => {
  try {
    const [rows] = await pool.query("SELECT * FROM products");
    return rows;
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
const createProduct = async (name_product, code_product, company_id) => {
  try {
    const [rows] = await pool.query(
      "INSERT INTO products (name_product, code_product, company_id) VALUES (?, ?, ?)",
      [name_product, code_product, company_id]
    );
    return rows;
  } catch (error) {
    throw new Error("Error creating product: " + error.message);
  }
};

const getProductsByCompany = async (companyId) => {
  try {
    const [rows] = await pool.query(
      "SELECT * FROM products WHERE company_id = ?",
      [companyId]
    );
    return rows;
  } catch (error) {
    throw new Error("Error fetching products: " + error.message);
  }
};

const deleteProduct = async (productId) => {
  const productFound = await pool.query("SELECT * FROM products WHERE id = ?", [
    productId,
  ]);

  if (productFound[0].length === 0) {
    throw new Error("Product not found");
  }
  try {
    const [result] = await pool.query("DELETE FROM products WHERE id = ?", [
      productId,
    ]);
    return result;
  } catch (error) {
    throw new Error("Error deleting product: " + error.message);
  }
};

const checkProductExists = async (productId) => {
  const [rows] = await pool.query("SELECT * FROM products WHERE id = ?", [
    productId,
  ]);
  return rows.length > 0;
};


export default {
  getProducts,
  createProduct,
  getProductsByCompany,
  deleteProduct,
  checkProductExists
};
