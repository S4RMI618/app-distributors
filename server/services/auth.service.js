import bcrypt from "bcryptjs";
import pool from "../db.js";
import jwt from "jsonwebtoken";
import { TOKEN_SECRET } from "../config.js";

const saltRounds = 10;

export const registerUser = async (username, password, roleId, companyId, clientData) => {
  const hashedPassword = await bcrypt.hash(password, saltRounds);

  const [rows] = await pool.query("SELECT * FROM users WHERE username = ?", [username]);
  if (rows.length > 0) {
    throw new Error("User already exists");
  }

  try {
    // Insert user into users table
    const [result] = await pool.query(
      "INSERT INTO users (username, password, role_id, company_id) VALUES (?, ?, ?, ?)",
      [username, hashedPassword, roleId, companyId]
    );
    const userId = result.insertId;

    // If the role is Client (roleId = 3), insert data into 'clients' table
    if (roleId === 3 && clientData) {
      const { fullName, email, phone, address } = clientData;
      await pool.query(
        "INSERT INTO customer_details (user_id, full_name, email, phone, address) VALUES (?, ?, ?, ?, ?)",
        [userId, fullName, email, phone, address]
      );
    }

    return { userId, message: "User registered successfully" };
  } catch (error) {
    throw new Error(`Error registering user : `+ error.message);
  }
};

//verify that only 2 admins can be created
/* const canAddAdmin = async () => {
  const [rows] = await pool.query(
    "SELECT COUNT(*) as adminCount FROM users WHERE role_id = 1"
  );
  return rows[0].adminCount < 2;
}; */

export const registerAdmin = async (username, password) => {
  const hashedPassword = await bcrypt.hash(password, saltRounds);
  const roleId = 1; // Admin role in db

  /* // Check if we can add more admins
  const canAdd = await canAddAdmin();
  if (!canAdd) {
    throw new Error("Cannot add more administrators");
  } */
  try {
    const [rows] = await pool.query(
      "INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)",
      [username, hashedPassword, roleId]
    );
    return rows;
  } catch (error) {
    throw new Error("Error registering admin: " + error.message);
  }
};

export const loginUser = async (username, password) => {
  try {
    const [rows] = await pool.query("SELECT * FROM users WHERE username = ?", [
      username,
    ]);

    if (rows.length === 0) {
      throw new Error("User not found");
    }
    const user = rows[0]; //set user result to current user
    const isValidPassword = await bcrypt.compare(password, user.password); //compare password

    //validate password
    if (!isValidPassword) {
      throw new Error("Invalid password");
    }

    const token = jwt.sign({ id: user.id, role: user.role_id }, TOKEN_SECRET, {
      expiresIn: "1h",
    });

    return { token, user };
  } catch (error) {
    throw new Error(error.message);
  }
};
