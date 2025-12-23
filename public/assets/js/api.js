/**
 * Classe utilitaire pour les appels API
 */
class API {
    constructor(baseURL = '') {
        this.baseURL = baseURL;
        this.csrfToken = this.getCSRFToken();
    }

    /**
     * Récupère le token CSRF depuis la meta
     */
    getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Requête GET
     */
    async get(endpoint, params = {}) {
        const url = new URL(this.baseURL + endpoint, window.location.origin);
        Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });

            return await this.handleResponse(response);
        } catch (error) {
            this.handleError(error);
        }
    }

    /**
     * Requête POST
     */
    async post(endpoint, data = {}) {
        try {
            const response = await fetch(this.baseURL + endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(data)
            });

            return await this.handleResponse(response);
        } catch (error) {
            this.handleError(error);
        }
    }

    /**
     * Requête PUT
     */
    async put(endpoint, data = {}) {
        try {
            const response = await fetch(this.baseURL + endpoint, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(data)
            });

            return await this.handleResponse(response);
        } catch (error) {
            this.handleError(error);
        }
    }

    /**
     * Requête DELETE
     */
    async delete(endpoint) {
        try {
            const response = await fetch(this.baseURL + endpoint, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            return await this.handleResponse(response);
        } catch (error) {
            this.handleError(error);
        }
    }

    /**
     * Gestion de la réponse
     */
    async handleResponse(response) {
        const contentType = response.headers.get('content-type');

        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Une erreur est survenue');
            }

            return data;
        }

        if (!response.ok) {
            throw new Error('Une erreur est survenue');
        }

        return await response.text();
    }

    /**
     * Gestion des erreurs
     */
    handleError(error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Instance globale
const api = new API();
