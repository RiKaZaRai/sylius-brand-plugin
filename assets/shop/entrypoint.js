// Import styles pour la boutique
import './styles/brand.scss';

// Import du JavaScript des filtres de marque
import './js/brand-filter.js';

// Import des controllers Stimulus pour la boutique
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Démarrage de l'application Stimulus pour le shop
const app = startStimulusApp();

// Configuration pour le développement  
if (process.env.NODE_ENV === 'development') {
    app.debug = true;
}

console.log('RikaSyliusBrandPlugin Shop assets loaded');