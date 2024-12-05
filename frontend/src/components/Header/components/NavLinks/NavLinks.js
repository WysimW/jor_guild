import React from 'react';
import { Link } from 'react-router-dom';
import DropdownMenu from './components/DropdownMenu/DropdownMenu';
import './NavLinks.css';

const NavLinks = ({ token, handleLogout }) => {
    return (
        <nav className="nav-links nav-links--left">
            {/* Liens côté gauche */}
                <Link className='nav-links--items' to="/">Accueil</Link>
                <DropdownMenu /> {/* Composant pour le menu déroulant des Raids */}
                <Link className='nav-links--items' to="/raid/calendar">Calendrier</Link>
        </nav>
    );
};

export default NavLinks;
