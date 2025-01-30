import { Router } from "express";
import {
  getCompanies,
  createCompany,
  updateCompany,
  deleteCompany,
} from "../controllers/companies.controller.js";

const router = Router();

router.post("/", createCompany);
router.get("/", getCompanies);
router.put("/:companyId", updateCompany);
router.delete("/:companyId", deleteCompany);

export default router;
