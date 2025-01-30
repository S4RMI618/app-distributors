import orderService from "../services/order.service.js";

export const createOrder = async (req, res) => {
  const { distributorId,  observations, clientId, products } = req.body;
  try {
    const result = await orderService.createOrder(
      distributorId,
      clientId,
      observations,
      products
    );
    res
      .status(201)
      .json({ message: "Order created successfully", order: result });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

export const getOrdersByClient = async (req, res) => {
    const { clientId } = req.params;

    try {
        const orders = await orderService.getOrdersByClient(clientId);
        res.status(200).json(orders);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

export const getOrdersByDistributor = async (req, res) => {
    const { distributorId } = req.params;

    try {
        const orders = await orderService.getOrdersByDistributor(distributorId);
        res.status(200).json(orders);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
}