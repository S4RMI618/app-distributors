import jwt from 'jsonwebtoken';
import { TOKEN_SECRET } from '../config.js';

export const authenticateToken = (req, res, next) => {
    const token = req.headers['authorization']?.split(' ')[1];

    // Si no hay token, enviar un error de acceso denegado
    if (!token) return res.status(401).json({ message: 'Access denied, no token provided' });

    // Verificar el token
    jwt.verify(token, TOKEN_SECRET, (err, user) => {
        if (err) return res.status(403).json({ message: 'Invalid token' });
        req.user = user;
        next();
    });
};

export const authorizeDistributor = (req, res, next) => {
    if (req.user.role !== 2) {
        return res.status(403).json({ message: 'Forbidden: Only distributors can create orders' });
    }
    next();
};

export const authorizeAdmin = (req, res, next) => {
    if (req.user.role !== 1) {
        return res.status(403).json({ message: 'Forbidden: Only admins' });
    }
    next();
};

