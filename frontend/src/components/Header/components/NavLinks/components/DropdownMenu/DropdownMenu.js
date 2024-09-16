import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons'; // Seule icône utilisée
import './DropdownMenu.css';

const DropdownMenu = () => {
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);

    const toggleDropdown = () => {
        setIsDropdownOpen(!isDropdownOpen);
    };

    return (
        <div className={`nav-dropdown ${isDropdownOpen ? 'open' : ''}`}>
            <button className="nav-dropdown-button" onClick={toggleDropdown}>
                Raids
                <FontAwesomeIcon
                    icon={faChevronDown}
                    className={`dropdown-icon ${isDropdownOpen ? 'open' : ''}`} // Applique la classe 'open' pour la rotation
                />
            </button>
            <div className={`nav-dropdown-menu ${isDropdownOpen ? 'show' : ''}`}>
                <Link to="/raids">Liste des Raids</Link>
                <Link to="/raids/history">Historique des Raids</Link>
            </div>
        </div>
    );
};

export default DropdownMenu;
