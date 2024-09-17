import React, { useState, useRef, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';
import './DropdownMenu.css';

const DropdownMenu = () => {
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    const toggleDropdown = () => {
        setIsDropdownOpen(!isDropdownOpen);
    };

    const handleClickOutside = (event) => {
        if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
            setIsDropdownOpen(false);
        }
    };

    useEffect(() => {
        if (isDropdownOpen) {
            document.addEventListener('mousedown', handleClickOutside);
        } else {
            document.removeEventListener('mousedown', handleClickOutside);
        }

        // Nettoyage de l'événement lors du démontage du composant
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [isDropdownOpen]);

    const handleLinkClick = () => {
        setIsDropdownOpen(false);
    };

    return (
        <div className={`nav-dropdown ${isDropdownOpen ? 'open' : ''}`} ref={dropdownRef}>
            <button className="nav-dropdown-button" onClick={toggleDropdown}>
                Raids
                <FontAwesomeIcon
                    icon={faChevronDown}
                    className={`dropdown-icon ${isDropdownOpen ? 'open' : ''}`}
                />
            </button>
            <div className={`nav-dropdown-menu ${isDropdownOpen ? 'show' : ''}`}>
                <Link to="/raids" onClick={handleLinkClick}>Liste des Raids</Link>
                <Link to="/raids/history" onClick={handleLinkClick}>Historique des Raids</Link>
            </div>
        </div>
    );
};

export default DropdownMenu;
