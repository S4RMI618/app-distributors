import {
  loginUser,
  registerUser as user,
  registerAdmin as admin,
} from "../services/auth.service.js";

export const ping = async (req, res) => {
  const [rows] = await pool.query("SELECT * FROM roles");
  console.log(rows);
  res.json(rows);
};

export const registerUser = async (req, res) => {
  const { username, password, roleId, companyId, fullName, email, phone, address} = req.body;

  let rol;
  if (roleId === 2) {
    rol = 'Distributor';
  } else if (roleId === 3) {
    rol = 'Client';
    if (!fullName || !phone || !address) {
      return res.status(400).json({
        message: 'Client must provide full name, email, phone, and address.'
      });
    }
  } else {
    return res.status(400).json({ message: 'Invalid role ID' });
  }
  try {

    const clientData = roleId === 3 ? { fullName, email, phone, address } : null; // Only send client data if the role is Client
    const result = await user(username, password, roleId, companyId, clientData);
    res.status(201).json({ message: `${rol} registered`, user: result });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }

};

export const registerAdmin = async (req, res) => {
  const { username, password } = req.body;

  try {
    const result = await admin(username, password);
    res.status(201).json({ message: "Admin registered successfully", result });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

export const login = async (req, res) => {
  const { username, password } = req.body;
  try {
    const { token, user } = await loginUser(username, password);
    res.status(200).json({ token, user });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
