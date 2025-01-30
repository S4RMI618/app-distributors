import pool from "../db.js";

const createCompany = async (nit, name, phone, address, department, municipality) => {
    try {
        const [rows] = await pool.query('INSERT INTO companies (nit, name, phone, address, department, municipality) VALUES (?, ?, ?, ?, ?, ?)', 
        [nit, name, phone, address, department, municipality]);
        return rows;
    } catch (error) {
        throw new Error('Error creating company: ' + error.message);
    }
};

const getCompanies = async () => {
    try {
        const [rows] = await pool.query('SELECT * FROM companies');
        return rows;
    } catch (error) {
        throw new Error('Error fetching companies: ' + error.message);
    }
};

const getCompanyById = async (companyId) => {
    try {
        const [rows] = await pool.query('SELECT * FROM companies WHERE id = ?', [companyId]);
        return rows;
    } catch (error) {
        throw new Error('Error fetching company: ' + error.message);
    }
};
const getCompanyByNit = async (companyNit) => {
    try {
        const [rows] = await pool.query('SELECT * FROM companies WHERE nit = ?', [companyNit]);
        return rows;
    } catch (error) {
        throw new Error('Error fetching company: ' + error.message);
    }
};



const updateCompany = async (id, nit, name, phone, address, department, municipality) => {
    try {
        const [result] = await pool.query(
            `UPDATE companies 
            SET nit = ?, name = ?, phone = ?, address = ?, department = ?, municipality = ? 
            WHERE id = ?`, 
            [nit, name, phone, address, department, municipality, id]
        );
        return result;
    } catch (error) {
        throw new Error('Error updating company: ' + error.message);
    }
};

const deleteCompany = async (id) => {
    try {
        const [result] = await pool.query('DELETE FROM companies WHERE id = ?', [id]);
        return result;
    } catch (error) {
        throw new Error('Error deleting company: ' + error.message);
    }
};


export default { createCompany, getCompanies, getCompanyById, getCompanyByNit , updateCompany, deleteCompany };	