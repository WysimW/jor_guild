import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from '../services/axios';
import '../styles/RaidList.css';
import '../styles/Home.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlus, faTimes } from '@fortawesome/free-solid-svg-icons';

const RaidList = () => {
    const [raids, setRaids] = useState([]);
    const [characters, setCharacters] = useState([]); // Stocker les personnages de l'utilisateur
    const [classes, setClasses] = useState([]); // Stocker la liste des classes
    const [selectedCharacter, setSelectedCharacter] = useState(''); // Stocker le personnage sélectionné
    const [classeId, setClasseId] = useState(''); // Stocker l'ID de la classe sélectionnée
    const [message, setMessage] = useState('');
    const [showPopup, setShowPopup] = useState(false); // Gérer l'état du popup
    const [newCharacter, setNewCharacter] = useState({ name: '', classe_id: '' }); // Stocker les données du nouveau personnage
    const [notification, setNotification] = useState(''); // Gérer les notifications

    useEffect(() => {
        const fetchRaids = async () => {
            try {
                const response = await axios.get('/raids'); // Récupérer la liste des raids depuis l'API
                setRaids(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des raids:', error);
            }
        };
        fetchRaids();
    }, []);

    useEffect(() => {
        const fetchCharacters = async () => {
            try {
                const response = await axios.get('/characters/list'); // Endpoint pour récupérer les personnages
                setCharacters(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des personnages :', error);
            }
        };
        fetchCharacters();
    }, []);

    // Récupérer la liste des classes
    useEffect(() => {
        const fetchClasses = async () => {
            try {
                const response = await axios.get('/classes'); // Récupérer la liste des classes depuis l'API
                setClasses(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des classes :', error);
            }
        };
        fetchClasses();
    }, []);

    // Fermer le popup avec la touche Échap
    useEffect(() => {
        const handleEsc = (event) => {
            if (event.key === 'Escape') {
                setShowPopup(false);
            }
        };
        window.addEventListener('keydown', handleEsc);
        return () => window.removeEventListener('keydown', handleEsc);
    }, []);

    const handleRegister = async (raidId) => {
        if (!selectedCharacter) {
            setNotification('Veuillez sélectionner un personnage pour vous inscrire.');
            return;
        }

        try {
            const response = await axios.post('/raid/register', {
                raid_id: raidId,
                character_id: selectedCharacter,
            });
            setNotification('Inscription réussie au raid !');
        } catch (error) {
            console.error('Erreur lors de l\'inscription :', error.response);
            setNotification('Erreur lors de l\'inscription.');
        }
    };

    const handleCreateCharacter = async () => {
        // Envoyer la demande pour créer un nouveau personnage
        if (newCharacter.name && classeId) {
            try {
                const response = await axios.post('/character/create', {
                    name: newCharacter.name,
                    classe_id: classeId, // Envoyer l'ID de la classe sélectionnée
                });
                setNotification('Personnage créé avec succès !');
                setShowPopup(false); // Fermer le popup après la création
            } catch (error) {
                console.error('Erreur lors de la création du personnage :', error);
                setNotification('Erreur lors de la création du personnage.');
            }
        } else {
            setNotification('Veuillez remplir tous les champs.');
        }
    };

    // Fermer le popup en cliquant à l'extérieur
    const handleOutsideClick = (event) => {
        if (event.target.classList.contains('popup')) {
            setShowPopup(false);
        }
    };

    // Fonction pour effacer la notification après quelques secondes
    useEffect(() => {
        if (notification) {
            const timer = setTimeout(() => {
                setNotification('');
            }, 3000); // Délai de 3 secondes avant de masquer la notification
            return () => clearTimeout(timer);
        }
    }, [notification]);

    console.log(characters);

    return (
        <div className="raid-list-wrapper">
            <div className="raid-list-wrapper--overlay"></div> {/* Overlay devant l'image */}
    
            <div className="raid-list">
                <div className="raid-list__content">
                    <h2 className="raid-list__title">Liste des Raids</h2>
    
                    {/* Notification */}
                    {notification && <div className="notification">{notification}</div>}
    
                    <div className="raid-list__character-select">
                        <div>
                            <label htmlFor="characterSelect">Vous voulez vous inscrire à l'un de nos évènements ? Sélectionnez un personnage :</label>
                        </div>
                        <div className='raid-list__character-select--inputs'>
                            <select
                                id="characterSelect"
                                value={selectedCharacter}
                                onChange={(e) => setSelectedCharacter(e.target.value)}
                            >
                                <option value="">Sélectionnez un personnage </option>
                                {characters.map((character) => (
                                    <option key={character.id} value={character.id}>
                                        {character.name} - {character.classe.name}
                                    </option>
                                ))}
                            </select>
                            <span>OU</span>
                            {/* Bouton Ajouter un personnage */}
                            <button className="raid-list__add-character-btn" onClick={() => setShowPopup(true)}>
                                <span>Ajouter un personnage</span>
                            </button>
                        </div>
                    </div>
    
                    {/* Popup de création de personnage */}
                    {showPopup && (
                        <div className="popup" onClick={handleOutsideClick}>
                            <div className="popup__content">
                                <button className="popup__close-btn" onClick={() => setShowPopup(false)}>
                                    <FontAwesomeIcon icon={faTimes} />
                                </button>
                                <h3>Créer un personnage</h3>
                                <label htmlFor="characterName">Nom du personnage :</label>
                                <input
                                    id="characterName"
                                    type="text"
                                    value={newCharacter.name}
                                    onChange={(e) => setNewCharacter({ ...newCharacter, name: e.target.value })}
                                />
    
                                <label htmlFor="characterClasse">Classe :</label>
                                <select
                                    id="characterClasse"
                                    value={classeId}
                                    onChange={(e) => setClasseId(e.target.value)}
                                >
                                    <option value="">Sélectionnez une classe</option>
                                    {classes.length > 0 && classes.map((classe) => (
                                        <option key={classe.id} value={classe.id}>
                                            {classe.name}
                                        </option>
                                    ))}
                                </select>
    
                                <button onClick={handleCreateCharacter}>Créer</button>
                                <button onClick={() => setShowPopup(false)}>Annuler</button>
                            </div>
                        </div>
                    )}
    
                    {raids.length > 0 ? (
                        <ul className="raid-list__items">
                            {raids.map((raid) => (
                                <li className="raid-list__item" key={raid.id}>
                                    <h3 className="raid-list__item-title">{raid.title}</h3>
                                    <p className="raid-list__item-description">{raid.description}</p>
                                    <p className="raid-list__item-date">Date: {new Date(raid.date).toLocaleDateString()}</p>
                                    <div className='raid-list__item-links'>
                                        <Link to={/raid/${raid.id}}><button className="raid-list__item-link">Voir les inscrits</button></Link>
                                        <button onClick={() => handleRegister(raid.id)} className="raid-list__item-link">
                                            S'inscrire
                                        </button>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="raid-list__no-raids">Aucun raid disponible pour le moment.</p>
                    )}
    
                    {message && <p className="raid-list__message">{message}</p>}
                </div>
            </div>
        </div>
    );
    
    
};

export default RaidList;