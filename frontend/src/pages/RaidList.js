import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import axios from "../services/axios";
import "../styles/RaidList.css";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus, faTimes } from "@fortawesome/free-solid-svg-icons";

const RaidList = () => {
  const [raids, setRaids] = useState([]);
  const [characters, setCharacters] = useState([]); // Stocker les personnages de l'utilisateur
  const [specializations, setSpecializations] = useState([]); // Stocker les spécialisations du personnage
  const [selectedCharacter, setSelectedCharacter] = useState(""); // Stocker le personnage sélectionné
  const [selectedSpecialization, setSelectedSpecialization] = useState(""); // Stocker la spécialisation sélectionnée
  const [selectedCharacterDetails, setSelectedCharacterDetails] = useState(null); // Stocker les détails du personnage
  const [message, setMessage] = useState("");
  const [showPopup, setShowPopup] = useState(false); // Gérer l'état du popup d'inscription
  const [notification, setNotification] = useState(""); // Gérer les notifications
  const [selectedRaid, setSelectedRaid] = useState(null); // Stocker l'ID du raid sélectionné

  const isUserRegistered = (raidId) => {
    return raids.some(
      (raid) =>
        raid.id === raidId &&
        raid.registeredCharacters?.some((character) =>
          characters.some((userCharacter) => userCharacter.id === character.id)
        )
    );
  };

  useEffect(() => {
    const fetchRaids = async () => {
      try {
        const response = await axios.get("/raids");
        setRaids(response.data);
      } catch (error) {
        console.error("Erreur lors de la récupération des raids:", error);
      }
    };
    fetchRaids();
  }, []);

  useEffect(() => {
    const fetchCharacters = async () => {
      try {
        const response = await axios.get("/characters/list");
        setCharacters(response.data);
      } catch (error) {
        console.error("Erreur lors de la récupération des personnages :", error);
      }
    };
    fetchCharacters();
  }, []);

  // Fetch specializations when a character is selected
  const fetchSpecializations = async (characterId) => {
    try {
      const response = await axios.get(`/characters/${characterId}/specializations`);
      setSpecializations(response.data);
    } catch (error) {
      console.error("Erreur lors de la récupération des spécialisations :", error);
    }
  };

  // Handle the register button click
  const handleRegisterClick = (raidId) => {
    setSelectedRaid(raidId); // Set the selected raid
    setShowPopup(true); // Show the popup
  };

  const handleRegister = async () => {
    if (!selectedSpecialization || !selectedCharacterDetails) {
      setNotification("Veuillez sélectionner un personnage et une spécialisation.");
      return;
    }

    try {
      const isRegistered = isUserRegistered(selectedRaid);
      const url = isRegistered
        ? `/raid/edit/${selectedRaid}/${selectedCharacterDetails.id}` // Endpoint for edit
        : "/raid/register"; // Endpoint for registering

      const method = isRegistered ? "put" : "post";

      const response = await axios[method](url, {
        raid_id: selectedRaid,
        character_id: selectedCharacterDetails.id,
        specialization_id: selectedSpecialization,
      });

      setNotification(isRegistered ? "Inscription modifiée avec succès !" : "Inscription réussie au raid !");
      setShowPopup(false); // Close the popup after registration
    } catch (error) {
      console.error("Erreur lors de l'inscription :", error.response);
      setNotification("Erreur lors de l'inscription.");
    }
  };

  // Handle clicking outside the popup to close it
  const handleOutsideClick = (event) => {
    if (event.target.classList.contains("popup")) {
      setShowPopup(false);
    }
  };

  // Clear notification after a few seconds
  useEffect(() => {
    if (notification) {
      const timer = setTimeout(() => setNotification(""), 3000);
      return () => clearTimeout(timer);
    }
  }, [notification]);

  return (
    <div className="raid-list-wrapper">
      <div className="raid-list-wrapper--overlay" /> {/* Overlay */}
      <div className="raid-list">
        <div className="raid-list__content">
          <h2 className="raid-list__title">Liste des Raids</h2>

          {/* Notification */}
          {notification && <div className="notification">{notification}</div>}

          {/* Raid List */}
          {raids.length > 0 ? (
            <ul className="raid-list__items">
              {raids.map((raid) => (
                <li className="raid-list__item" key={raid.id}>
                  <h3 className="raid-list__item-title">{raid.title}</h3>
                  <p className="raid-list__item-description">{raid.description}</p>
                  <p className="raid-list__item-date">
                    <span className="raid-list__item-date--dateformat">
                      {new Intl.DateTimeFormat("fr-FR", {
                        weekday: "long",
                        day: "numeric",
                        month: "long",
                      }).format(new Date(raid.date))}{" "}
                    </span>
                    à 21h
                  </p>
                  <div className="raid-list__item-links">
                    <button
                      onClick={() => handleRegisterClick(raid.id)}
                      className="raid-list__item-link"
                    >
                      {isUserRegistered(raid.id) ? "Modifier mon inscription" : "S'inscrire"}
                    </button>
                    <Link to={`/raid/${raid.id}`}>
                      <button className="raid-list__item-link">Voir les inscrits</button>
                    </Link>
                  </div>
                </li>
              ))}
            </ul>
          ) : (
            <p className="raid-list__no-raids">Aucun raid disponible pour le moment.</p>
          )}

          {/* Popup for selecting characters and specialization */}
          {showPopup && (
            <div className="popup" onClick={handleOutsideClick}>
              <div className="popup__content">
                <button className="popup__close-btn" onClick={() => setShowPopup(false)}>
                  <FontAwesomeIcon icon={faTimes} />
                </button>

                <h3>Inscription au raid</h3>

                {/* Character selection in popup */}
                <label htmlFor="characterSelectPopup">Personnage :</label>
                <select
                  id="characterSelectPopup"
                  value={selectedCharacter}
                  onChange={(e) => {
                    setSelectedCharacter(e.target.value);
                    const character = characters.find((char) => char.id === parseInt(e.target.value));
                    setSelectedCharacterDetails(character);
                    fetchSpecializations(e.target.value); // Update specializations
                  }}
                >
                  <option value="">Sélectionnez un personnage</option>
                  {characters.map((character) => (
                    <option key={character.id} value={character.id}>
                      {character.name} - {character.classe.name}
                    </option>
                  ))}
                </select>

                {/* Specialization selection */}
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

                <button onClick={handleRegister} className="popup--confirm-btn">
                  Confirmer
                </button>
              </div>
            </div>
          )}

          {message && <p className="raid-list__message">{message}</p>}
        </div>
      </div>
    </div>
  );
};

export default RaidList;
