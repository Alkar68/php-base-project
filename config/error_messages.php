<?php

return [
    // Erreurs d'authentification
    'auth.invalid_credentials' => 'Email ou mot de passe incorrect',
    'auth.account_disabled' => 'Votre compte a été désactivé',
    'auth.email_not_verified' => 'Veuillez vérifier votre email avant de vous connecter',
    'auth.session_expired' => 'Votre session a expiré, veuillez vous reconnecter',
    'auth.unauthorized' => 'Vous n\'avez pas les permissions nécessaires',

    // Erreurs de validation
    'validation.required' => 'Le champ {field} est obligatoire',
    'validation.email' => 'L\'adresse email n\'est pas valide',
    'validation.min_length' => 'Le champ {field} doit contenir au moins {min} caractères',
    'validation.max_length' => 'Le champ {field} ne peut pas dépasser {max} caractères',
    'validation.password_mismatch' => 'Les mots de passe ne correspondent pas',
    'validation.email_exists' => 'Cet email est déjà utilisé',

    // Erreurs CSRF
    'csrf.invalid_token' => 'Token CSRF invalide. Veuillez réessayer',
    'csrf.missing_token' => 'Token CSRF manquant',

    // Erreurs générales
    'error.404' => 'Page non trouvée',
    'error.500' => 'Erreur serveur. Veuillez réessayer plus tard',
    'error.database' => 'Erreur de connexion à la base de données',
    'error.file_not_found' => 'Fichier introuvable',

    // Erreurs utilisateur
    'user.not_found' => 'Utilisateur introuvable',
    'user.delete_failed' => 'Impossible de supprimer cet utilisateur',
    'user.update_failed' => 'Erreur lors de la mise à jour de l\'utilisateur',

    // Messages de succès
    'success.login' => 'Connexion réussie',
    'success.register' => 'Inscription réussie',
    'success.logout' => 'Déconnexion réussie',
    'success.password_reset' => 'Mot de passe réinitialisé avec succès',
    'success.user_created' => 'Utilisateur créé avec succès',
    'success.user_updated' => 'Utilisateur mis à jour avec succès',
    'success.user_deleted' => 'Utilisateur supprimé avec succès',
];
