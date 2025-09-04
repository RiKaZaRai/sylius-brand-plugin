import { Controller } from '@hotwired/stimulus';

/**
 * Controller Stimulus pour la gestion des logos de marques
 * Utilisé dans l'admin pour prévisualiser les logos uploadés
 */
export default class extends Controller {
    static targets = ['input', 'preview', 'placeholder'];
    static values = { maxSize: Number };
    
    connect() {
        console.log('Brand Logo Controller connected');
        
        // Configuration par défaut
        this.maxSizeValue = this.maxSizeValue || 2000000; // 2MB par défaut
    }
    
    /**
     * Appelé quand un fichier est sélectionné
     */
    onFileSelect(event) {
        const file = event.target.files[0];
        
        if (!file) {
            this.resetPreview();
            return;
        }
        
        // Validation du fichier
        if (!this.validateFile(file)) {
            return;
        }
        
        // Prévisualisation
        this.previewFile(file);
    }
    
    /**
     * Validation du fichier uploadé
     */
    validateFile(file) {
        // Vérification du type
        if (!file.type.startsWith('image/')) {
            this.showError('Veuillez sélectionner un fichier image valide.');
            return false;
        }
        
        // Vérification de la taille
        if (file.size > this.maxSizeValue) {
            const maxSizeMB = (this.maxSizeValue / 1000000).toFixed(1);
            this.showError(`Le fichier est trop volumineux. Taille maximum : ${maxSizeMB}MB`);
            return false;
        }
        
        return true;
    }
    
    /**
     * Prévisualisation du fichier
     */
    previewFile(file) {
        const reader = new FileReader();
        
        reader.onload = (e) => {
            if (this.hasPreviewTarget) {
                this.previewTarget.src = e.target.result;
                this.previewTarget.style.display = 'block';
            }
            
            if (this.hasPlaceholderTarget) {
                this.placeholderTarget.style.display = 'none';
            }
        };
        
        reader.readAsDataURL(file);
    }
    
    /**
     * Reset de la prévisualisation
     */
    resetPreview() {
        if (this.hasPreviewTarget) {
            this.previewTarget.style.display = 'none';
            this.previewTarget.src = '';
        }
        
        if (this.hasPlaceholderTarget) {
            this.placeholderTarget.style.display = 'block';
        }
    }
    
    /**
     * Affichage des erreurs
     */
    showError(message) {
        // Création d'une notification d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'ui negative message';
        errorDiv.innerHTML = `<div class="header">Erreur</div><p>${message}</p>`;
        
        // Insertion près du contrôleur
        this.element.appendChild(errorDiv);
        
        // Suppression automatique après 5 secondes
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 5000);
        
        // Reset du champ file
        if (this.hasInputTarget) {
            this.inputTarget.value = '';
        }
    }
    
    /**
     * Suppression du logo
     */
    removeLogo() {
        this.resetPreview();
        
        if (this.hasInputTarget) {
            this.inputTarget.value = '';
        }
        
        // Déclenchement d'un événement personnalisé
        this.element.dispatchEvent(new CustomEvent('brand:logo-removed', {
            detail: { controller: this }
        }));
    }
}