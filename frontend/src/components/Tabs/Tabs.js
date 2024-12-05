import React, { useState } from 'react';
import './Tabs.css'; // Style pour les onglets

const Tabs = ({ children }) => {
    const [activeTab, setActiveTab] = useState(0); // Gérer l'onglet actif

    return (
        <div className="tabs">
            <div className="tab-headers">
                {children.map((child, index) => (
                    <button
                        key={index}
                        className={activeTab === index ? 'active' : ''}
                        onClick={() => setActiveTab(index)}
                    >
                        {child.props.label}
                    </button>
                ))}
            </div>
            <div className="tab-content">
                {children[activeTab]}
            </div>
        </div>
    );
};

export default Tabs;
