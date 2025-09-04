// Import styles pour l'administration
import './styles/brand.scss';

// Import des controllers Stimulus spécifiques
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Démarrage de l'application Stimulus pour l'admin
const app = startStimulusApp();

// Configuration pour le développement
if (process.env.NODE_ENV === 'development') {
    app.debug = true;
}

console.log('RikaSyliusBrandPlugin Admin assets loaded');