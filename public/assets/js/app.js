// Auto-fermeture des alertes
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');

    alerts.forEach(alert => {
        setTimeout(() => {
            // Vérifier que Bootstrap est chargé
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            } else {
                // Fallback si Bootstrap n'est pas disponible
                alert.remove();
            }
        }, 5000);
    });

    // Confirmation de suppression (déplacé dans DOMContentLoaded)
    document.querySelectorAll('form[data-confirm="true"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        });
    });
});
