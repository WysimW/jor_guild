import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import { useParams } from 'react-router-dom';
import tippy from 'tippy.js'; // Importer tippy.js pour les infobulles et popups
import 'tippy.js/dist/tippy.css'; // Importer les styles de tippy.js
import '../styles/RaidDetails.css';
import '../styles/Popup.css';

const RaidDetails = () => {
    const { id } = useParams(); // Récupérer l'ID du raid depuis l'URL
    const [raidDetails, setRaidDetails] = useState(null);
    const [showBuffs, setShowBuffs] = useState(false); // Gérer l'affichage du popup des buffs
    const [showTokenPopup, setShowTokenPopup] = useState(false); // Gérer l'état du popup des tokens
    const [showRegisterPopup, setShowRegisterPopup] = useState(false); // Gérer l'état du popup d'inscription
    const [characters, setCharacters] = useState([]); // Stocker les personnages de l'utilisateur
    const [specializations, setSpecializations] = useState([]); // Stocker les spécialisations du personnage
    const [selectedCharacter, setSelectedCharacter] = useState(""); // Stocker le personnage sélectionné
    const [selectedSpecialization, setSelectedSpecialization] = useState(""); // Stocker la spécialisation sélectionnée
    const [selectedCharacterDetails, setSelectedCharacterDetails] = useState(null);
    const [notification, setNotification] = useState(""); // Gérer les notifications
    const [isRegistered, setIsRegistered] = useState(false); // Pour suivre si un personnage est déjà inscrit
    const [userRegistration, setUserRegistration] = useState(null); // Stocker l'inscription de l'utilisateur


    // Fetch user's characters
    useEffect(() => {
        const fetchCharacters = async () => {
            try {
                const response = await axios.get('/characters/list');
                setCharacters(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des personnages:', error);
            }
        };
        fetchCharacters();
    }, []); // Appelé une seule fois au chargement du composant

    const fetchSpecializations = async (characterId) => {
        try {
            const response = await axios.get(`/characters/${characterId}/specializations`);
            setSpecializations(response.data);
        } catch (error) {
            console.error('Erreur lors de la récupération des spécialisations :', error);
        }
    };

    const handleCharacterChange = (characterId) => {
        const character = characters.find((char) => char.id === parseInt(characterId));
        setSelectedCharacterDetails(character);
        fetchSpecializations(characterId); // Récupérer les spécialisations pour ce personnage
    };

    const handleRegisterClick = () => {
        setShowRegisterPopup(true); // Ouvrir le popup directement
    };

    const handleRegister = async () => {
        if (!selectedSpecialization) {
            setNotification('Veuillez sélectionner une spécialisation pour vous inscrire.');
            return;
        }
    
        try {
            const requestData = {
                raid_id: id, // Utiliser l'ID du raid depuis l'URL
                character_id: selectedCharacterDetails.id,
                specialization_id: selectedSpecialization, // Envoyer l'ID de la spécialisation
            };
    
            const response = isRegistered
                ? await axios.put(`/raid/register/${userRegistration.id}`, requestData) // Modifier l'inscription
                : await axios.post('/raid/register', requestData); // Nouvelle inscription
    
            setNotification(isRegistered ? 'Inscription modifiée avec succès !' : 'Inscription réussie au raid !');
            setShowRegisterPopup(false); // Fermer le popup après l'inscription
        } catch (error) {
            console.error('Erreur lors de l\'inscription :', error.response);
            setNotification('Erreur lors de l\'inscription.');
        }
    };
    

    // Gérer la disparition des notifications après quelques secondes
    useEffect(() => {
        if (notification) {
            const timer = setTimeout(() => setNotification(''), 3000);
            return () => clearTimeout(timer);
        }
    }, [notification]); // Ce hook sera toujours appelé

    // Fetch raid details
    useEffect(() => {
        const fetchRaidDetails = async () => {
            try {
                const response = await axios.get(`/raid/${id}/details`); // Récupérer les détails du raid depuis l'API
                setRaidDetails(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des détails du raid:', error);
            }
        };
        fetchRaidDetails();
    }, [id]); // Toujours appelé lors du rendu si l'ID change

    // Initialiser tippy.js après le rendu des éléments de personnage
    useEffect(() => {
        if (raidDetails) {
            tippy('.character-badge', {
                content(reference) {
                    return reference.getAttribute('data-tippy-content');
                },
                theme: 'light',
            });

            tippy('.token-badge', {
                content(reference) {
                    return reference.getAttribute('data-tippy-content');
                },
                theme: 'light',
            });
        }
    }, [raidDetails]); // Ce hook est toujours appelé mais ne fait quelque chose que si raidDetails est défini

    // Réinitialiser tippy.js lorsque le popup des tokens apparaît
    useEffect(() => {
        tippy('.token-badge', {
            content(reference) {
                return reference.getAttribute('data-tippy-content');
            },
            theme: 'light',
        });
    }, [showTokenPopup]); // Toujours appelé, même si showTokenPopup change

    useEffect(() => {
        if (raidDetails) {
            const userCharacterIds = characters.map(character => character.id);
            const userRegistered = raidDetails.inscriptions.find(inscription =>
                userCharacterIds.includes(inscription.registredCharacter.id)
            );
            if (userRegistered) {
                setIsRegistered(true);
                setUserRegistration(userRegistered); // Stocker les détails de l'inscription de l'utilisateur
            }
        }
    }, [raidDetails, characters]);
    

    if (!raidDetails) {
        return <p>Chargement des détails du raid...</p>;
    }


    // Définir les priorités de rôle
    const rolePriority = {
        Tank: 1,
        Heal: 2,
        DPS: 3,
        'Aucun rôle': 4 // Pour les cas sans rôle attribué
    };

    // Mapping des buffs selon les classes et spécialisations
    const buffs = {
        "5% d'Intelligence": ['Mage'],
        "5% de Puissance d'Attaque": ['Guerrier'],
        "5% d'Endurance": ['Prêtre'],
        '3% de Réduction de dégâts': ['Paladin'],
        '5% de dégâts Physique': ['Moine'],
        '5% de dégâts magiques': ['Chasseur de démons'],
        '3% de Polyvalence': ['Druide'],
    };

    // Utilitaires de raid
    const utilities = {
        Bloodlust: ['Chaman', 'Mage', 'Évocateur', 'Chasseur'],
        'Battle Rez': ['Druide', 'Paladin', 'Chevalier de la mort', 'Démoniste'],
        'Skyfury Totem': ['Chaman'],
        'Burst of Movespeed': ['Chaman', 'Druide'],
        'Mass Dispel': ['Prêtre', 'Moine'],
        'AoE Damage Reduction': ['Guerrier', 'Chasseur de démons', 'Chevalier de la mort', 'Évocateur'],
        Immunity: ['Mage', 'Paladin', 'Chasseur', 'Voleur'],
    };

    const tokenGroups = {
        "Zenith": ["Evocateur", "Moine", "Guerrier", "Voleur"],
        "Dreadful": ["Chasseur de démons", "Chevalier de la mort", "Démoniste"],
        "Mystic": ["Mage", "Chasseur", "Druide"],
        "Venerated": ["Chaman", "Prêtre", "Paladin"]
    };

    // Compter les personnages pour chaque token
    const tokenCount = {
        "Zenith": 0,
        "Dreadful": 0,
        "Mystic": 0,
        "Venerated": 0
    };

    // Remplir le compteur des tokens en fonction des classes des personnages
    raidDetails.inscriptions.forEach(inscription => {
        const characterClass = inscription.registredCharacter.classe.name;
        for (let token in tokenGroups) {
            if (tokenGroups[token].includes(characterClass)) {
                tokenCount[token]++;
                break;
            }
        }
    });



    const countUtility = (utility) => {
        return raidDetails.inscriptions.filter((inscription) => {
            const { name: className } = inscription.registredCharacter.classe;
            return utilities[utility].includes(className);
        }).length;
    };

    // Fonction pour vérifier si un buff est actif
    const isBuffActive = (buff) => {
        return raidDetails.inscriptions.some((inscription) => {
            const { name: className } = inscription.registredCharacter.classe;
            return buffs[buff].includes(className);
        });
    };

    // Trier les inscriptions par rôle en utilisant la spécialisation et la priorité des rôles
    const sortedByRole = raidDetails.inscriptions.reduce((acc, inscription) => {
        const specialization = inscription.registredCharacter.specializations[0]; // Prendre la première spécialisation
        const role = specialization?.role?.name || 'Aucun rôle'; // Récupérer le nom du rôle de la spécialisation
        if (!acc[role]) {
            acc[role] = [];
        }
        acc[role].push({
            character: inscription.registredCharacter,
            specialization: specialization?.name || 'Aucune spécialisation',
        });
        return acc;
    }, {});

    const handleOutsideClick = (event) => {
        if (event.target.classList.contains('popup')) {
            setShowRegisterPopup(false); // Ferme le popup si le clic est en dehors du contenu
        }
    };


    // Classer les rôles selon leur priorité (Tank > Heal > DPS)
    const sortedRoles = Object.keys(sortedByRole).sort((a, b) => rolePriority[a] - rolePriority[b]);

    return (
        <div className="raid-details-container">
            <div className="overlay"></div> {/* Overlay devant l'image */}
            {/* Notification */}
            {notification &&
                <div className="notification">
                    {notification}
                </div>}
            <h2 className="raid-details__title">{raidDetails.title}</h2>

            {/* Badge pour afficher le mode du raid */}
            <div className={`raid-details__badge badge-mode-${raidDetails.mode.toLowerCase()}`}>
                Mode {raidDetails.mode} {/* Affiche le mode du raid */}
            </div>

            <div className="raid-details">
                <p className="raid-details__date">
                    <span className="raid-details__date--formatted">
                        {new Intl.DateTimeFormat("fr-FR", {
                            weekday: "long",
                            day: "numeric",
                            month: "long"
                        }).format(new Date(raidDetails.date))}
                    </span> à 21h
                </p>
                <p className="raid-details__description">{raidDetails.description}</p>

                <h3 className="raid-details__subheading">Liste des inscrits</h3>
                <div className="raid-details__grid">
                    {sortedRoles.map((role) => (
                        <div key={role} className="raid-details__role-column">
                            <h4 className="raid-details__role-title">
                                {role} ({sortedByRole[role].length}) {/* Afficher le nombre de personnages */}
                            </h4>
                            <ul>
                                {sortedByRole[role].map(({ character, specialization }) => (
                                    <li
                                        key={character.id}
                                        className={`character-badge character-${character.classe.name.toLowerCase()}`}
                                        data-tippy-content={`Classe: ${character.classe.name}, Spécialisation: ${specialization}`} // Utiliser l'attribut data-tippy-content pour afficher la classe et la spécialisation
                                    >
                                        <span className="character-badge__name">
                                            {character.name}
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>

                {/* Bouton pour afficher le popup des buffs */}
                <div className='raid-details__buttons'>
                    <button className="raid-details__buffs-button" onClick={() => setShowBuffs(true)}>
                        Afficher les Buffs
                    </button>
                    {/* <button
                        className="raid-details__tokens-button"
                        onClick={() => setShowTokenPopup(true)}
                    >
                        Voir les tokens de set
                    </button>*/}
                    <button
                        className="raid-details__register-button"
                        onClick={() => handleRegisterClick(isRegistered ? userRegistration.registredCharacter.id : selectedCharacter)}
                    >
                        {isRegistered ? "Modifier mon inscription" : "S'inscrire au raid"}
                    </button>

                </div>


            </div>
            {/* Popup des buffs */}
            {showBuffs && (
                <div className="popup-buffs" onClick={() => setShowBuffs(false)}>
                    <div className="popup-buffs__content">
                        <button className="popup-buffs__close-btn" onClick={() => setShowBuffs(false)}>
                            &times; {/* Utiliser un "×" pour la croix de fermeture */}
                        </button>
                        <h3 className="raid-details__buffs-title">Buffs & Utilitaires de Raids</h3>
                        <div className='raid-details__buffs-wrapper'>
                            <div className='raid-details__buffs-column'>
                                <h4>Raid buffs/debuffs</h4>
                                <ul className="raid-details__buffs">
                                    {Object.keys(buffs).map((buff) => (
                                        <li key={buff} className={`buff-${isBuffActive(buff) ? 'active' : 'inactive'}`}>
                                            {buff}: {isBuffActive(buff) ? 'Actif' : 'Inactif'}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                            <div className='raid-details__buffs-column'>
                                <h4>Utilitaires de raids</h4>
                                <ul className="raid-details__utilities">
                                    {Object.keys(utilities).map((utility) => (
                                        <li key={utility} className={`utility-${countUtility(utility) > 0 ? 'active' : 'inactive'}`}>
                                            {utility}: {countUtility(utility)} {countUtility(utility) > 0 ? 'Actif' : 'Inactif'}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Popup des tokens de set
            {showTokenPopup && (
                <div className="popup" onClick={(e) => e.target.classList.contains('popup') && setShowTokenPopup(false)}>
                    <div className="popup__content">
                        <button className="popup__close-btn" onClick={() => setShowTokenPopup(false)}>
                            &times;
                        </button>
                        <h3>Tokens de set</h3>
                        <ul className='token-list'>
                            {Object.keys(tokenCount).map(token => (
                                <li
                                    key={token}
                                    className="token-badge"
                                    data-tippy-content={`Classes : ${tokenGroups[token].join(', ')}`} // Utiliser tippy pour afficher la liste des classes liées à chaque token
                                >    Token {token}: {tokenCount[token]} inscrits
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
            )} */}
            {/* Popup d'inscription */}
            {showRegisterPopup && (
                <div className="popup" onClick={handleOutsideClick}>
                    <div className="popup__content">
                        <button
                            className="popup__close-btn"
                            onClick={() => setShowRegisterPopup(false)}
                        >
                            &times;
                        </button>
                        <h3>S'inscrire au raid</h3>

                        {/* Sélection du personnage */}
                        <label htmlFor="characterSelect">Personnage :</label>
                        <select
                            id="characterSelect"
                            value={selectedCharacter}
                            onChange={(e) => handleCharacterChange(e.target.value)}
                        >
                            <option value="">Sélectionnez un personnage</option>
                            {characters.map((character) => (
                                <option key={character.id} value={character.id}>
                                    {character.name} - {character.classe.name}
                                </option>
                            ))}
                        </select>

                        {/* Sélection de la spécialisation */}
                        {selectedCharacterDetails && (
                            <>
                                <label htmlFor="specializationSelect">Spécialisation :</label>
                                <select
                                    id="specializationSelect"
                                    value={selectedSpecialization}
                                    onChange={(e) => setSelectedSpecialization(e.target.value)}
                                >
                                    <option value="">Sélectionnez une spécialisation</option>
                                    {specializations.map((specialization) => (
                                        <option key={specialization.id} value={specialization.id}>
                                            {specialization.name}
                                        </option>
                                    ))}
                                </select>
                            </>
                        )}

                        {/* Bouton de confirmation */}
                        <button onClick={handleRegister} className="popup--confirm-btn">
                            Confirmer
                        </button>
                    </div>
                </div>
            )}

        </div>

    );
};

export default RaidDetails;
