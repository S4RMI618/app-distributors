import pool from "../db.js";

const createOrder = async (distributorId, clientId, observations, products) => {
  try {
        // Crear la orden con distributor_id, client_id y observaciones
    const [orderResult] = await pool.query(
      "INSERT INTO orders (distributor_id, client_id, observations) VALUES (?, ?, ?)",
      [distributorId, clientId, observations]
    );
    
    // Obtener el ID de la orden creada
    const orderId = orderResult.insertId;

    // Insertar los productos en order_products
    for (const product of products) {
      await pool.query(
        "INSERT INTO order_products (order_id, product_id, quantity) VALUES (?, ?, ?)",
        [orderId, product.productId, product.quantity]
      );
    }

    return { orderId, message: "Order created successfully" };
  } catch (error) {
    throw new Error("Error creating order: " + error.message);
  }
};

const updateStatusOrder = async (orderId) => {
  try {
    const [order] = await pool.query(
      "UPDATE orders SET status = ? WHERE order_id = ?",
      [orderId]
    );
    return { message: "Order updated successfully", order: order };
  } catch (error) {
    throw new Error("Error updating order: " + error.message);
  }
};

const getOrdersByDistributor = async (distributorId) => {
  try {
    const [orders] = await pool.query(
      "SELECT * FROM orders WHERE distributor_id = ?",
      [distributorId]
    );
    return orders;
  } catch (error) {
    throw new Error("Error fetching orders: " + error.message);
  }
};

const getOrdersByClient = async (clientId) => {
  try {
    const [orders] = await pool.query(
      "SELECT * FROM orders WHERE client_id = ?",
      [clientId]
    );
    return orders;
  } catch (error) {
    throw new Error("Error fetching orders: " + error.message);
  }
};

export default {
  createOrder,
  getOrdersByDistributor,
  getOrdersByClient,
  updateStatusOrder,
};
