import companiesService from "../services/companies.service.js";

export const getCompanies = async (req, res) => {
  try {
    const companies = await companiesService.getCompanies();
    res.status(200).json(companies);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

export const createCompany = async (req, res) => {
  const { nit, name, phone, address, department, municipality } = req.body;
  try {
    const result = await companiesService.createCompany(
      nit,
      name,
      phone,
      address,
      department,
      municipality
    );

    if (result.affectedRows === 0) {
      return res.status(400).json({ message: "Company was not created" });
    }
    
    const companyCreated = await companiesService.getCompanyByNit(nit);
    res.status(201).json({ message: "Company created", companyCreated: companyCreated });

  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

export const updateCompany = async (req, res) => {
  const { companyId } = req.params;
  const { nit, name, phone, address, department, municipality } = req.body;

  try {
    const result = await companiesService.updateCompany(
      companyId,
      nit,
      name,
      phone,
      address,
      department,
      municipality
    );
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Company not updated" });
    }
    const companyUpdated = await companiesService.getCompanyById(companyId);
    res.status(200).json({ message: "Company updated", company: companyUpdated });
  } catch (error) {
    res
      .status(500)
      .json({ message: "Error updating company: " + error.message });
  }
};

export const deleteCompany = async (req, res) => {
  const { companyId } = req.params;

  try {
    const result = await companiesService.deleteCompany(companyId);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Company not found" });
    }
    res.status(200).json({ message: "Company deleted successfully" });
  } catch (error) {
    res
      .status(500)
      .json({ message: "Company can't be eliminated: " + error.message });
  }
};
