import React from 'react';
import { BrowserRouter as Router, Route, Routes, Navigate, useLocation } from 'react-router-dom';
import { useAuth } from './hooks/useAuth';
import './App.css';  // Import the reset CSS first
import './styles/Blizzard.css';  // Import the reset CSS first
import Home from './pages/Home';
import SignUp from './components/Auth/SignUp';
import Login from './components/Auth/Login';
import Dashboard from './components/Dashboard/Dashboard'; // Une page pour l'utilisateur connecté
import Header from './components/Core/Header';
import Footer from './components/Core/Footer';
import RaidList from './pages/RaidList'; // Page pour afficher la liste des raids
import RaidDetails from './pages/RaidDetails'; // Page pour afficher les détails d'un raid
import RaidCalendar from './pages/RaidCalendar'; // Page pour afficher le calendrier des raids
import { UserProvider } from './contexts/UserContext';
import { Helmet } from 'react-helmet';

const App = () => {
    const { token } = useAuth();

    // Route privée qui redirige vers /login si l'utilisateur n'est pas connecté
    const PrivateRoute = ({ children }) => {
        const token = localStorage.getItem('token');
        return token ? children : <Navigate to="/login" />;
    };

    // Composant qui gère l'affichage conditionnel du Header et du Footer
    const Layout = ({ children }) => {
        const location = useLocation(); // Utiliser useLocation après avoir rendu Router
        const hideHeaderFooter = ['/login', '/signup'].includes(location.pathname);

        return (
            <>
                {!hideHeaderFooter && <Header />} {/* Afficher le Header seulement si pas sur /login ou /signup */}
                {children} {/* Contenu principal (les routes) */}
                {!hideHeaderFooter && <Footer />} {/* Afficher le Footer seulement si pas sur /login ou /signup */}
            </>
        );
    };

    return (
        <Router>
            <Layout>
                <div className="App">
                    <Routes>
                        <Route path="/" element={<Home />} />
                        <Route path="/signup" element={<SignUp />} />
                        <Route path="/login" element={<Login />} />
                        <Route path="/raids" element={<PrivateRoute><RaidList /></PrivateRoute>} /> {/* Route vers la liste des raids */}
                        <Route path="/raid/:id" element={<PrivateRoute><RaidDetails /></PrivateRoute>} /> {/* Route vers les détails d'un raid */}
                        <Route path="/raid/calendar" element={<PrivateRoute><RaidCalendar /></PrivateRoute>} /> {/* Route vers le calendrier des raids */}
                        <Route path="/dashboard" element={<PrivateRoute><UserProvider><Dashboard /></UserProvider></PrivateRoute>} /> {/* Route protégée pour le Dashboard */}
                    </Routes>
                </div>
            </Layout>
        </Router>
    );
};

export default App;

