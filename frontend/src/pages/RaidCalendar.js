import React, { useState, useEffect } from 'react';
import FullCalendar from '@fullcalendar/react'; // Importer FullCalendar
import dayGridPlugin from '@fullcalendar/daygrid'; // Affichage mensuel
import timeGridPlugin from '@fullcalendar/timegrid'; // Affichage hebdomadaire
import frLocale from '@fullcalendar/core/locales/fr'; // Localisation française
import axios from '../services/axios';
import { useNavigate } from 'react-router-dom'; // Pour redirection
import tippy from 'tippy.js'; // Importer tippy.js pour les infobulles
import 'tippy.js/dist/tippy.css'; // Importer le style des infobulles
import '../styles/RaidCalendar.css'; // Importer le style

const RaidCalendar = () => {
    const [events, setEvents] = useState([]);
    const navigate = useNavigate(); // Utiliser pour rediriger l'utilisateur

    useEffect(() => {
        const fetchRaids = async () => {
            try {
                const response = await axios.get('/raids'); // Récupérer les raids depuis l'API
                const raidEvents = response.data.map((raid) => ({
                    id: raid.id, // Ajouter l'ID pour redirection
                    title: raid.title,
                    start: raid.date, // Assure-toi que "date" est au format ISO
                    description: raid.description,
                }));
                setEvents(raidEvents); // Ajouter les raids en tant qu'événements
            } catch (error) {
                console.error('Erreur lors de la récupération des raids:', error);
            }
        };
        fetchRaids();
    }, []);

    const handleEventClick = (info) => {
        // Redirection vers la page de détails du raid
        navigate(`/raid/${info.event.id}`);
    };

    return (
        <div className='raid-calendar-container'>

        <div className="raid-calendar">

            <h2>Calendrier des Raids</h2>
            <FullCalendar
  plugins={[dayGridPlugin, timeGridPlugin]}
  initialView="dayGridMonth"
  headerToolbar={{
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek',
  }}
  locale={frLocale}
  events={events}
  eventClick={handleEventClick}
  eventDidMount={(info) => {
    tippy(info.el, {
      content: `<strong>${info.event.title}</strong><br>${info.event.extendedProps.description}`,
      allowHTML: true,
    });
  }}
  buttonText={{
    today: "Aujourd'hui",
    month: 'Mois',
    week: 'Semaine',
  }}
  initialDate={new Date()}
/>

        </div>
        </div>

    );
};

export default RaidCalendar;
