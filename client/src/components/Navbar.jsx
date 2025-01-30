import React from 'react'
import { Link } from 'react-router-dom'

function Navbar() {
  return (
    <div>
        <h1>DistriApp</h1>

        <ul>
            <Link to="/login">Login</Link>
            <Link to="/register">Register</Link>
        </ul>
    </div>
  )
}

export default Navbar