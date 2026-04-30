<!-- Système de Notifications Flash

Ce fichier gère l'affichage des messages temporaires (confirmations, erreurs)
stockés en session via $_SESSION['mesgs'].

UTILISATION :
- Inclure ce fichier en haut de vos scripts : require_once 'flashMessages.php';
- Pour les pages sans header standard, appeler manuellement la fonction 
  displayFlashMessages() juste après l'ouverture de la balise <body>.

EN PHP : 
- $_SESSION['mesgs']['confirm'][] pour un message confirm 
- $_SESSION['mesgs']['errors'][] pour un message d'erreur 

EN JAVASCRIPT : 
- renderAlertJS("Message de succès", "confirm");
- renderAlertJS("Message d'erreur", "errors"); -->
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!-- on s'assure d'avoir le css :  -->
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


<!-- #############
     # PHP ET JS # 
     #############-->

<script>
    /**
     * Ferme une notification manuellement.
     * @param {HTMLElement} el L'élément (généralement un bouton) qui a déclenché la fermeture.
     */
    function closeNotif(el) {
        const notif = el.closest('.flashMessages');
        if (notif) {
            notif.classList.add('hide');
            setTimeout(() => notif.remove(), 400);
        }
    }
</script>

<?php
    /**
     * Affiche tous les messages flash stockés dans la session.
     * 
     * NOTE ARCHITECTURALE : 
     * Les balises <style> et <script> sont incluses à l'intérieur de cette fonction
     * pour garantir qu'elles ne soient injectées que lorsque c'est nécessaire et
     * impérativement à l'intérieur du <body>. Cela évite d'envoyer du contenu HTML
     * avant la déclaration du DOCTYPE ou du <head>, ce qui prend de l'espace dans le DOM et décale des chose 
     *
     * @return void
     */
    function displayFlashMessages() {
        if (isset($_SESSION['mesgs']) && !empty($_SESSION['mesgs'])) {
            ?>
            <style>
                /* Conteneur pour empiler les notifications en haut au centre */
                .flashMessages-container {
                    position: fixed;
                    top: 10px;
                    left: 0;
                    right: 0;
                    margin-left: auto;
                    margin-right: auto;
                    z-index: 9999;
                    width: 350px;
                    max-width: 90%;
                    pointer-events: none;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                /* Animation et ajustements pour w3.css */
                .flashMessages {
                    pointer-events: auto;
                    margin-bottom: 10px !important;
                    animation: slide-down 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                    transition: opacity 0.4s, transform 0.4s;
                    width: 100%;
                }

                .flashMessages.hide {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                @keyframes slide-down {
                    from { transform: translateY(-20px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }

                .flashMessages.w3-panel {
                    margin-top: 0;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 8px 16px;
                }
            </style>

            <script>
            document.addEventListener('DOMContentLoaded', setupAutoClose);
            setTimeout(setupAutoClose, 300);
            </script>

            <?php
            echo '<div class="flashMessages-container">';
            foreach ($_SESSION['mesgs'] as $type => $messages) {
                foreach ((array)$messages as $msg) {
                    renderAlertPHP($msg, $type);
                }
            }
            echo '</div>';
            
            // Nettoyage de la session après affichage
            unset($_SESSION['mesgs']); 
        }
    }
?>

<script>
    // Auto-suppression uniquement pour les message de type "confirm" après 2 secondes
    function setupAutoClose() {
        document.querySelectorAll('.flashMessages').forEach((notif, index) => {
            const type = notif.getAttribute('data-type');
            if (type === 'confirm') {
                setTimeout(() => {
                    if (notif && notif.parentNode) {
                        notif.classList.add('hide');
                        setTimeout(() => notif.remove(), 400);
                    }
                }, 2000 + (index * 400));
            }
        });
    }
</script>



<!-- #######
     # PHP #
     ####### -->
<?php
    /**
     * Génère le code HTML pour une alerte individuelle.
     *
     * @param string $message Le texte à afficher.
     * @param string $type Le type d'alerte : 'confirm' (succès) ou 'errors' (erreur).
     * @return void Affiche directement le HTML via un echo. 
     */
    function renderAlertPHP($message, $type = 'errors') {
        $icons = [
            'errors'  => '💥',
            'confirm' => '✅'
        ];

        $colors = [
            'errors'  => 'w3-red',
            'confirm' => 'w3-green'
        ];
        
        echo "
        <div class='flashMessages w3-panel {$colors[$type]} w3-card-4 w3-display-container w3-round' data-type='{$type}'>
            <div style='padding-right: 30px;'>
                <span style='font-size: 18px; margin-right: 8px;'>{$icons[$type]}</span>
                <span>" . htmlspecialchars($message) . "</span>
            </div>
            <span class='w3-button w3-display-topright w3-transparent w3-hover-none' 
                    onclick='closeNotif(this)' style='padding: 8px 12px;'>&times;</span>
        </div>";
    }
?>


<!-- ######
     # JS # 
     ###### -->


<script>
    /**
     * Affiche une notification flash depuis JavaScript.
     * 
     * @param {string} message - Le texte à afficher.
     * @param {('confirm'|'errors')} type - Le type d'alerte (succès ou erreur).
     */
    function renderAlertJS(message, type = 'errors') {
        const icons = { 
            'errors': '💥', 
            'confirm': '✅' 
        };
        const colors = { 
            'errors': 'w3-red', 
            'confirm': 'w3-green' 
        };

        const html = `
        <div class='flashMessages w3-panel ${colors[type]} w3-card-4 w3-display-container w3-round' data-type='${type}'>
            <div style='padding-right: 30px;'>
                <span style='font-size: 18px; margin-right: 8px;'>${icons[type]}</span>
                <span>${message}</span>
            </div>
            <span class='w3-button w3-display-topright w3-transparent w3-hover-none' 
                    onclick='closeNotif(this)' style='padding: 8px 12px;'>&times;</span>
        </div>`;

        // création du conteneur 
        let container = document.querySelector('.flashMessages-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'flashMessages-container';
            document.body.appendChild(container);
        }

        // équivalent d'un "echo" : 
        container.insertAdjacentHTML('beforeend', html);
        setupAutoClose();
    }
</script> 
