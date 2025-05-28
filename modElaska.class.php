<?php
/* Copyright (C) 2025 Kylian65
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Date dernière modification: 2025-05-28 14:34:43
 * Par: Kylian65
 */

/**
 * Description and activation file for module eLaska
 */
include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

/**
 * Description and activation class for module eLaska
 */
class modElaska extends DolibarrModules
{
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;
        $this->db = $db;

        // Module ID
        $this->numero = 900000;
        // Module family
        $this->family = "crm";
        // Module position in the family
        $this->module_position = 500;
        // Module name
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description
        $this->description = "Module complet de gestion d'accompagnement administratif, financier et de conseil";
        // Keywords
        $this->keywords = array('crm', 'conseil', 'administratif', 'patrimoine', 'accompagnement', 'elaska');
        // Url
        $this->url = 'https://github.com/Kylian65/elaska';
        // Author information
        $this->editor_name = 'Kylian65';
        $this->editor_url = 'https://github.com/Kylian65';
        // Version
        $this->version = '1.0.0';
        // Key used in llx_const table to save module status
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        // Name of image file used for this module
        $this->picto = 'elaska@elaska';
        // Dependencies
        $this->depends = array('modProjet', 'modAgenda', 'modSociete');
        // Data directories to create when module is enabled
        $this->dirs = array(
            '/elaska/temp',
            '/elaska/documents',
            '/elaska/documents/dossiers',
            '/elaska/documents/patrimoine',
            '/elaska/documents/clients',
            '/elaska/documents/kb',
            '/elaska/documents/templates',
        );
        // Config pages
        $this->config_page_url = array("setup.php@elaska");
        
        // Constants
        $this->const = array(
            0 => array('ELASKA_USE_CUSTOM_MENU', 'chaine', '1', 'Utiliser le menu personnalisé eLaska', 0, 'current', 1),
            1 => array('ELASKA_MENU_ICONS_ENABLED', 'chaine', '1', 'Activer les icônes dans le menu', 0, 'current', 1),
            2 => array('ELASKA_MENU_ROLE_BASED', 'chaine', '1', 'Activer menu basé sur les rôles', 0, 'current', 1),
            3 => array('ELASKA_REAL_TIME_DATA', 'chaine', '1', 'Afficher les données en temps réel', 0, 'current', 1),
            4 => array('ELASKA_DASHBOARD_REFRESH_RATE', 'chaine', '60', 'Taux de rafraîchissement du tableau de bord (secondes)', 0, 'current', 1),
        );

        // Dictionaries
        $this->dictionaries = array(
            'langs' => 'elaska@elaska',
            'tabname' => array(
                MAIN_DB_PREFIX . "c_elaska_dossier_type",
                MAIN_DB_PREFIX . "c_elaska_prestations",
                MAIN_DB_PREFIX . "c_elaska_timeline_etapes",
                MAIN_DB_PREFIX . "c_elaska_intervenant_type",
                MAIN_DB_PREFIX . "c_elaska_notification_type",
                MAIN_DB_PREFIX . "c_elaska_opportunity_status",
                MAIN_DB_PREFIX . "c_elaska_satisfaction_question"
            ),
            'tablib' => array(
                "Types de dossier",
                "Prestations",
                "Étapes de timeline",
                "Types d'intervenants externes",
                "Types de notification",
                "Statuts d'opportunité",
                "Questions d'enquête de satisfaction"
            ),
            'tabsql' => array(
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_dossier_type',
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_prestations',
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_timeline_etapes',
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_intervenant_type',
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_notification_type',
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_opportunity_status',
                'SELECT rowid, code, label, active FROM ' . MAIN_DB_PREFIX . 'c_elaska_satisfaction_question'
            ),
            'tabsqlsort' => array('label ASC', 'label ASC', 'label ASC', 'label ASC', 'label ASC', 'label ASC', 'label ASC'),
            'tabfield' => array('code,label', 'code,label', 'code,label', 'code,label', 'code,label', 'code,label', 'code,label'),
            'tabfieldvalue' => array('code,label', 'code,label', 'code,label', 'code,label', 'code,label', 'code,label', 'code,label'),
            'tabfieldinsert' => array('code,label', 'code,label', 'code,label', 'code,label', 'code,label', 'code,label', 'code,label'),
            'tabrowid' => array('rowid', 'rowid', 'rowid', 'rowid', 'rowid', 'rowid', 'rowid'),
            'tabcond' => array('$conf->elaska->enabled', '$conf->elaska->enabled', '$conf->elaska->enabled', '$conf->elaska->enabled', '$conf->elaska->enabled', '$conf->elaska->enabled', '$conf->elaska->enabled')
        );

        // Boxes/Widgets
        $this->boxes = array(
            0 => array(
                'file' => 'box_elaska_dossiers.php@elaska',
                'note' => 'Widget affichant les dossiers actifs',
                'enabledbydefaulton' => 'Home'
            ),
            1 => array(
                'file' => 'box_elaska_tasks.php@elaska',
                'note' => 'Widget affichant les tâches du jour',
                'enabledbydefaulton' => 'Home'
            ),
            2 => array(
                'file' => 'box_elaska_rdv.php@elaska',
                'note' => 'Widget affichant les prochains rendez-vous',
                'enabledbydefaulton' => 'Home'
            ),
            3 => array(
                'file' => 'box_elaska_notifications.php@elaska',
                'note' => 'Widget affichant les notifications non lues',
                'enabledbydefaulton' => 'Home'
            ),
            4 => array(
                'file' => 'box_elaska_opportunities.php@elaska',
                'note' => 'Widget affichant les opportunités en cours',
                'enabledbydefaulton' => 'Home'
            )
        );

        // Permissions - toutes activées par défaut pour éviter les menus grisés
        $this->rights = array();
        $this->rights_class = 'elaska';
        $r = 0;

        // Module accessible par défaut
        $this->rights[$r][0] = 9000;
        $this->rights[$r][1] = 'Accès au module eLaska';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'lire';
        $this->rights[$r][5] = '';
        $r++;

        // Dashboard
        $this->rights[$r][0] = 9001;
        $this->rights[$r][1] = 'Tableau de bord eLaska';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'dashboard';
        $this->rights[$r][5] = 'read';
        $r++;

        // Prospection
        $this->rights[$r][0] = 9002;
        $this->rights[$r][1] = 'Consulter prospection & opportunités';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'prospection';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9003;
        $this->rights[$r][1] = 'Créer/modifier prospection & opportunités';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'prospection';
        $this->rights[$r][5] = 'write';
        $r++;

        // Tiers
        $this->rights[$r][0] = 9004;
        $this->rights[$r][1] = 'Consulter les tiers';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'tiers';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9005;
        $this->rights[$r][1] = 'Créer/modifier les tiers';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'tiers';
        $this->rights[$r][5] = 'write';
        $r++;

        // Prestations
        $this->rights[$r][0] = 9006;
        $this->rights[$r][1] = 'Consulter les prestations';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'prestations';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9007;
        $this->rights[$r][1] = 'Créer/modifier les prestations';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'prestations';
        $this->rights[$r][5] = 'write';
        $r++;

        // Dossiers
        $this->rights[$r][0] = 9008;
        $this->rights[$r][1] = 'Consulter les dossiers';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'dossiers';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9009;
        $this->rights[$r][1] = 'Créer/modifier les dossiers';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'dossiers';
        $this->rights[$r][5] = 'write';
        $r++;

        // Patrimoine
        $this->rights[$r][0] = 9010;
        $this->rights[$r][1] = 'Consulter patrimoine & finance';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'patrimoine';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9011;
        $this->rights[$r][1] = 'Créer/modifier patrimoine & finance';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'patrimoine';
        $this->rights[$r][5] = 'write';
        $r++;

        // Accompagnement
        $this->rights[$r][0] = 9012;
        $this->rights[$r][1] = 'Consulter accompagnement spécifique';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'accompagnement';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9013;
        $this->rights[$r][1] = 'Créer/modifier accompagnement spécifique';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'accompagnement';
        $this->rights[$r][5] = 'write';
        $r++;

        // Tâches
        $this->rights[$r][0] = 9014;
        $this->rights[$r][1] = 'Consulter tâches & suivi';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'tasks';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9015;
        $this->rights[$r][1] = 'Créer/modifier tâches & suivi';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'tasks';
        $this->rights[$r][5] = 'write';
        $r++;

        // Abonnements
        $this->rights[$r][0] = 9016;
        $this->rights[$r][1] = 'Consulter abonnements clients';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'abonnements';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9017;
        $this->rights[$r][1] = 'Créer/modifier abonnements clients';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'abonnements';
        $this->rights[$r][5] = 'write';
        $r++;

        // Communications
        $this->rights[$r][0] = 9018;
        $this->rights[$r][1] = 'Consulter communications';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'communications';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9019;
        $this->rights[$r][1] = 'Créer/modifier communications';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'communications';
        $this->rights[$r][5] = 'write';
        $r++;

        // Base de connaissances
        $this->rights[$r][0] = 9020;
        $this->rights[$r][1] = 'Consulter base de connaissances';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'kb';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9021;
        $this->rights[$r][1] = 'Créer/modifier base de connaissances';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'kb';
        $this->rights[$r][5] = 'write';
        $r++;

        // Partenariats
        $this->rights[$r][0] = 9022;
        $this->rights[$r][1] = 'Consulter partenariats';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'partenariats';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9023;
        $this->rights[$r][1] = 'Créer/modifier partenariats';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'partenariats';
        $this->rights[$r][5] = 'write';
        $r++;

        // Satisfaction client
        $this->rights[$r][0] = 9024;
        $this->rights[$r][1] = 'Consulter satisfaction client';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'satisfaction';
        $this->rights[$r][5] = 'read';
        $r++;

        $this->rights[$r][0] = 9025;
        $this->rights[$r][1] = 'Créer/modifier satisfaction client';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 1; // Activé par défaut
        $this->rights[$r][4] = 'satisfaction';
        $this->rights[$r][5] = 'write';
        $r++;

        // Administration - réservé aux admins
        $this->rights[$r][0] = 9099;
        $this->rights[$r][1] = 'Administrer le module eLaska';
        $this->rights[$r][2] = 'a';
        $this->rights[$r][3] = 0; // Désactivé par défaut (admin uniquement)
        $this->rights[$r][4] = 'admin';
        $this->rights[$r][5] = '';
        $r++;

        // Définition du menu complet
        $this->menu = array();
        $r = 0;

        // Menu principal 'elaska'
        $this->menu[$r] = array(
            'fk_menu' => 0, // Menu de premier niveau
            'type' => 'top',
            'titre' => 'eLaska',
            'prefix' => img_picto('', $this->picto, 'class="pictofixedwidth"'),
            'mainmenu' => 'elaska',
            'leftmenu' => '',
            'url' => '/custom/elaska/index.php', // Pointeur direct vers le dashboard
            'langs' => 'elaska@elaska',
            'position' => 100,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->lire',
            'target' => '',
            'user' => 2, // Pour tous les utilisateurs
        );
        $r++;

        // Tableau de bord
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Tableau de bord',
            'prefix' => '<i class="fas fa-chart-pie pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dashboard',
            'url' => '/custom/elaska/index.php',
            'langs' => 'elaska@elaska',
            'position' => 101,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dashboard->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dashboard',
            'type' => 'left',
            'titre' => 'Mes tâches du jour',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dashboard_tasks',
            'url' => '/custom/elaska/tasks/today.php',
            'langs' => 'elaska@elaska',
            'position' => 102,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dashboard',
            'type' => 'left',
            'titre' => 'Alertes & notifications',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dashboard_alerts',
            'url' => '/custom/elaska/alerts/index.php',
            'langs' => 'elaska@elaska',
            'position' => 103,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dashboard->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Prospection & Opportunités
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Prospection & Opportunités',
            'prefix' => '<i class="fas fa-search pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prospection',
            'url' => '/custom/elaska/crm/opportunites.php',
            'langs' => 'elaska@elaska',
            'position' => 104,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prospection->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=prospection',
            'type' => 'left',
            'titre' => 'Opportunités',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prospection_opportunities',
            'url' => '/custom/elaska/crm/opportunites.php',
            'langs' => 'elaska@elaska',
            'position' => 105,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prospection->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=prospection',
            'type' => 'left',
            'titre' => 'Partenariats',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prospection_partenariats',
            'url' => '/custom/elaska/crm/partenariats.php',
            'langs' => 'elaska@elaska',
            'position' => 106,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prospection->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=prospection',
            'type' => 'left',
            'titre' => 'Apporteurs d\'affaires',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prospection_apporteurs',
            'url' => '/custom/elaska/crm/apporteurs.php',
            'langs' => 'elaska@elaska',
            'position' => 107,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prospection->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Tiers
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Tiers',
            'prefix' => '<i class="fas fa-users pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers',
            'url' => '/custom/elaska/tiers/index.php',
            'langs' => 'elaska@elaska',
            'position' => 108,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tiers',
            'type' => 'left',
            'titre' => 'Particuliers',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers_particuliers',
            'url' => '/custom/elaska/tiers/particuliers.php',
            'langs' => 'elaska@elaska',
            'position' => 109,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tiers',
            'type' => 'left',
            'titre' => 'Associations',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers_associations',
            'url' => '/custom/elaska/tiers/associations.php',
            'langs' => 'elaska@elaska',
            'position' => 110,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tiers',
            'type' => 'left',
            'titre' => 'Créateurs d\'entreprises',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers_createurs',
            'url' => '/custom/elaska/tiers/createurs.php',
            'langs' => 'elaska@elaska',
            'position' => 111,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tiers',
            'type' => 'left',
            'titre' => 'TPE/PME',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers_entreprises',
            'url' => '/custom/elaska/tiers/entreprises.php',
            'langs' => 'elaska@elaska',
            'position' => 112,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tiers',
            'type' => 'left',
            'titre' => 'Intervenants externes',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers_intervenants',
            'url' => '/custom/elaska/tiers/intervenants.php',
            'langs' => 'elaska@elaska',
            'position' => 113,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tiers',
            'type' => 'left',
            'titre' => 'Organismes locaux',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tiers_organismes',
            'url' => '/custom/elaska/tiers/organismes.php',
            'langs' => 'elaska@elaska',
            'position' => 114,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Prestations
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Prestations',
            'prefix' => '<i class="fas fa-cogs pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prestations',
            'url' => '/custom/elaska/prestations/list.php',
            'langs' => 'elaska@elaska',
            'position' => 115,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prestations->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=prestations',
            'type' => 'left',
            'titre' => 'Liste des prestations',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prestations_list',
            'url' => '/custom/elaska/prestations/list.php',
            'langs' => 'elaska@elaska',
            'position' => 116,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prestations->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=prestations',
            'type' => 'left',
            'titre' => 'Nouvelle prestation',
            'mainmenu' => 'elaska',
            'leftmenu' => 'prestations_new',
            'url' => '/custom/elaska/prestations/card.php?action=create',
            'langs' => 'elaska@elaska',
            'position' => 117,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->prestations->write',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Dossiers
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Dossiers',
            'prefix' => '<i class="fas fa-folder-open pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers',
            'url' => '/custom/elaska/dossiers/list.php',
            'langs' => 'elaska@elaska',
            'position' => 118,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Tous les dossiers',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_all',
            'url' => '/custom/elaska/dossiers/list.php',
            'langs' => 'elaska@elaska',
            'position' => 119,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Particuliers',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_particuliers',
            'url' => '/custom/elaska/dossiers/list.php?type=particular',
            'langs' => 'elaska@elaska',
            'position' => 120,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Associations',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_associations',
            'url' => '/custom/elaska/dossiers/list.php?type=association',
            'langs' => 'elaska@elaska',
            'position' => 121,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Création d\'entreprise',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_creation',
            'url' => '/custom/elaska/dossiers/list.php?type=creation',
            'langs' => 'elaska@elaska',
            'position' => 122,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'TPE/PME',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_tpe_pme',
            'url' => '/custom/elaska/dossiers/list.php?type=tpe_pme',
            'langs' => 'elaska@elaska',
            'position' => 123,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Recouvrement',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_recouvrement',
            'url' => '/custom/elaska/dossiers/list.php?type=recovery',
            'langs' => 'elaska@elaska',
            'position' => 124,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Nouveau dossier',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_new',
            'url' => '/custom/elaska/dossiers/card.php?action=create',
            'langs' => 'elaska@elaska',
            'position' => 125,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->write',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=dossiers',
            'type' => 'left',
            'titre' => 'Timelines & workflows',
            'mainmenu' => 'elaska',
            'leftmenu' => 'dossiers_timelines',
            'url' => '/custom/elaska/timeline/list.php',
            'langs' => 'elaska@elaska',
            'position' => 126,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->dossiers->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Patrimoine & Finance
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Patrimoine & Finance',
            'prefix' => '<i class="fas fa-chart-line pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'patrimoine',
            'url' => '/custom/elaska/patrimoine/index.php',
            'langs' => 'elaska@elaska',
            'position' => 127,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->patrimoine->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=patrimoine',
            'type' => 'left',
            'titre' => 'Bilans patrimoniaux',
            'mainmenu' => 'elaska',
            'leftmenu' => 'patrimoine_bilans',
            'url' => '/custom/elaska/patrimoine/bilans.php',
            'langs' => 'elaska@elaska',
            'position' => 128,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->patrimoine->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=patrimoine',
            'type' => 'left',
            'titre' => 'Contrats d\'assurance',
            'mainmenu' => 'elaska',
            'leftmenu' => 'patrimoine_assurances',
            'url' => '/custom/elaska/patrimoine/assurances.php',
            'langs' => 'elaska@elaska',
            'position' => 129,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->patrimoine->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=patrimoine',
            'type' => 'left',
            'titre' => 'Dossiers de crédit',
            'mainmenu' => 'elaska',
            'leftmenu' => 'patrimoine_credits',
            'url' => '/custom/elaska/patrimoine/credits.php',
            'langs' => 'elaska@elaska',
            'position' => 130,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->patrimoine->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=patrimoine',
            'type' => 'left',
            'titre' => 'Gestion des sinistres',
            'mainmenu' => 'elaska',
            'leftmenu' => 'patrimoine_sinistres',
            'url' => '/custom/elaska/patrimoine/sinistres.php',
            'langs' => 'elaska@elaska',
            'position' => 131,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->patrimoine->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=patrimoine',
            'type' => 'left',
            'titre' => 'Suivi des investissements',
            'mainmenu' => 'elaska',
            'leftmenu' => 'patrimoine_investissements',
            'url' => '/custom/elaska/patrimoine/investissements.php',
            'langs' => 'elaska@elaska',
            'position' => 132,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->patrimoine->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Accompagnement spécifique
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Accompagnement Spécifique',
            'prefix' => '<i class="fas fa-hands-helping pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'accompagnement',
            'url' => '/custom/elaska/accompagnement/index.php',
            'langs' => 'elaska@elaska',
            'position' => 133,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->accompagnement->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=accompagnement',
            'type' => 'left',
            'titre' => 'Instances Associations',
            'mainmenu' => 'elaska',
            'leftmenu' => 'accompagnement_instances',
            'url' => '/custom/elaska/accompagnement/instances.php',
            'langs' => 'elaska@elaska',
            'position' => 134,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->accompagnement->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=accompagnement',
            'type' => 'left',
            'titre' => 'Financements',
            'mainmenu' => 'elaska',
            'leftmenu' => 'accompagnement_financements',
            'url' => '/custom/elaska/accompagnement/financements.php',
            'langs' => 'elaska@elaska',
            'position' => 135,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->accompagnement->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Tâches & Suivi
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Tâches & Suivi',
            'prefix' => '<i class="fas fa-tasks pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tasks',
            'url' => '/custom/elaska/tasks/personal.php',
            'langs' => 'elaska@elaska',
            'position' => 136,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tasks',
            'type' => 'left',
            'titre' => 'Mes tâches',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tasks_personal',
            'url' => '/custom/elaska/tasks/personal.php',
            'langs' => 'elaska@elaska',
            'position' => 137,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tasks',
            'type' => 'left',
            'titre' => 'Tâches équipe',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tasks_team',
            'url' => '/custom/elaska/tasks/team.php',
            'langs' => 'elaska@elaska',
            'position' => 138,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tasks',
            'type' => 'left',
            'titre' => 'Vue Kanban',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tasks_kanban',
            'url' => '/custom/elaska/tasks/kanban.php',
            'langs' => 'elaska@elaska',
            'position' => 139,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tasks',
            'type' => 'left',
            'titre' => 'Suivi du temps',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tasks_timetracking',
            'url' => '/custom/elaska/tasks/timetracking.php',
            'langs' => 'elaska@elaska',
            'position' => 140,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=tasks',
            'type' => 'left',
            'titre' => 'Consommations & frais',
            'mainmenu' => 'elaska',
            'leftmenu' => 'tasks_expenses',
            'url' => '/custom/elaska/tasks/expenses.php',
            'langs' => 'elaska@elaska',
            'position' => 141,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->tasks->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Abonnements Clients
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Abonnements Clients',
            'prefix' => '<i class="fas fa-sync-alt pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'abonnements',
            'url' => '/custom/elaska/abonnements/list.php',
            'langs' => 'elaska@elaska',
            'position' => 142,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->abonnements->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=abonnements',
            'type' => 'left',
            'titre' => 'Liste des abonnements',
            'mainmenu' => 'elaska',
            'leftmenu' => 'abonnements_list',
            'url' => '/custom/elaska/abonnements/list.php',
            'langs' => 'elaska@elaska',
            'position' => 143,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->abonnements->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=abonnements',
            'type' => 'left',
            'titre' => 'Nouvel abonnement',
            'mainmenu' => 'elaska',
            'leftmenu' => 'abonnements_new',
            'url' => '/custom/elaska/abonnements/card.php?action=create',
            'langs' => 'elaska@elaska',
            'position' => 144,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->abonnements->write',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Communications
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Communications',
            'prefix' => '<i class="fas fa-comments pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'communications',
            'url' => '/custom/elaska/communication/messages.php',
            'langs' => 'elaska@elaska',
            'position' => 145,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->communications->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=communications',
            'type' => 'left',
            'titre' => 'Messagerie interne',
            'mainmenu' => 'elaska',
            'leftmenu' => 'communications_messages',
            'url' => '/custom/elaska/communication/messages.php',
            'langs' => 'elaska@elaska',
            'position' => 146,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->communications->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=communications',
            'type' => 'left',
            'titre' => 'Historique',
            'mainmenu' => 'elaska',
            'leftmenu' => 'communications_history',
            'url' => '/custom/elaska/communication/history.php',
            'langs' => 'elaska@elaska',
            'position' => 147,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->communications->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=communications',
            'type' => 'left',
            'titre' => 'Rendez-vous',
            'mainmenu' => 'elaska',
            'leftmenu' => 'communications_rdv',
            'url' => '/custom/elaska/rdv/calendar.php',
            'langs' => 'elaska@elaska',
            'position' => 148,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->communications->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=communications',
            'type' => 'left',
            'titre' => 'Portail client',
            'mainmenu' => 'elaska',
            'leftmenu' => 'communications_portail',
            'url' => '/custom/elaska/portail/admin.php',
            'langs' => 'elaska@elaska',
            'position' => 149,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->communications->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Base de connaissances
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Base de connaissances',
            'prefix' => '<i class="fas fa-book pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'kb',
            'url' => '/custom/elaska/kb/articles.php',
            'langs' => 'elaska@elaska',
            'position' => 150,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->kb->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=kb',
            'type' => 'left',
            'titre' => 'Articles & fiches',
            'mainmenu' => 'elaska',
            'leftmenu' => 'kb_articles',
            'url' => '/custom/elaska/kb/articles.php',
            'langs' => 'elaska@elaska',
            'position' => 151,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->kb->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

$this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=kb',
            'type' => 'left',
            'titre' => 'Veille réglementaire',
            'mainmenu' => 'elaska',
            'leftmenu' => 'kb_veille',
            'url' => '/custom/elaska/kb/veille.php',
            'langs' => 'elaska@elaska',
            'position' => 152,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->kb->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=kb',
            'type' => 'left',
            'titre' => 'Dispositifs locaux',
            'mainmenu' => 'elaska',
            'leftmenu' => 'kb_dispositifs',
            'url' => '/custom/elaska/kb/dispositifs.php',
            'langs' => 'elaska@elaska',
            'position' => 153,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->kb->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=kb',
            'type' => 'left',
            'titre' => 'Modèles de documents',
            'mainmenu' => 'elaska',
            'leftmenu' => 'kb_templates',
            'url' => '/custom/elaska/kb/templates.php',
            'langs' => 'elaska@elaska',
            'position' => 154,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->kb->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Partenariats
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Partenariats',
            'prefix' => '<i class="fas fa-handshake pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'partenariats',
            'url' => '/custom/elaska/partenariats/partenaires.php',
            'langs' => 'elaska@elaska',
            'position' => 155,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->partenariats->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=partenariats',
            'type' => 'left',
            'titre' => 'Partenaires',
            'mainmenu' => 'elaska',
            'leftmenu' => 'partenariats_partners',
            'url' => '/custom/elaska/partenariats/partenaires.php',
            'langs' => 'elaska@elaska',
            'position' => 156,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->partenariats->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=partenariats',
            'type' => 'left',
            'titre' => 'Annuaire partenaires locaux',
            'mainmenu' => 'elaska',
            'leftmenu' => 'partenariats_annuaire',
            'url' => '/custom/elaska/partenariats/annuaire.php',
            'langs' => 'elaska@elaska',
            'position' => 157,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->partenariats->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Satisfaction client
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Satisfaction client',
            'prefix' => '<i class="fas fa-smile pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'satisfaction',
            'url' => '/custom/elaska/satisfaction/surveys.php',
            'langs' => 'elaska@elaska',
            'position' => 158,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->satisfaction->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=satisfaction',
            'type' => 'left',
            'titre' => 'Enquêtes',
            'mainmenu' => 'elaska',
            'leftmenu' => 'satisfaction_surveys',
            'url' => '/custom/elaska/satisfaction/surveys.php',
            'langs' => 'elaska@elaska',
            'position' => 159,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->satisfaction->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=satisfaction',
            'type' => 'left',
            'titre' => 'Analyses satisfaction',
            'mainmenu' => 'elaska',
            'leftmenu' => 'satisfaction_analytics',
            'url' => '/custom/elaska/satisfaction/analytics.php',
            'langs' => 'elaska@elaska',
            'position' => 160,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->satisfaction->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=satisfaction',
            'type' => 'left',
            'titre' => 'Retours clients',
            'mainmenu' => 'elaska',
            'leftmenu' => 'satisfaction_feedback',
            'url' => '/custom/elaska/satisfaction/feedback.php',
            'langs' => 'elaska@elaska',
            'position' => 161,
            'enabled' => '1', // Toujours activé
            'perms' => '$user->rights->elaska->satisfaction->read',
            'target' => '',
            'user' => 2,
        );
        $r++;

        // Configuration
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska',
            'type' => 'left',
            'titre' => 'Configuration',
            'prefix' => '<i class="fas fa-cog pictofixedwidth"></i>',
            'mainmenu' => 'elaska',
            'leftmenu' => 'setup',
            'url' => '/custom/elaska/admin/setup.php',
            'langs' => 'elaska@elaska',
            'position' => 162,
            'enabled' => '1', // Toujours visible mais accessible uniquement avec les droits
            'perms' => '$user->admin', // Accessible uniquement par les administrateurs
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=setup',
            'type' => 'left',
            'titre' => 'Paramètres généraux',
            'mainmenu' => 'elaska',
            'leftmenu' => 'setup_general',
            'url' => '/custom/elaska/admin/setup.php',
            'langs' => 'elaska@elaska',
            'position' => 163,
            'enabled' => '$user->admin',
            'perms' => '$user->admin',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=setup',
            'type' => 'left',
            'titre' => 'Gestion des rôles',
            'mainmenu' => 'elaska',
            'leftmenu' => 'setup_roles',
            'url' => '/custom/elaska/admin/roles.php',
            'langs' => 'elaska@elaska',
            'position' => 164,
            'enabled' => '$user->admin',
            'perms' => '$user->admin',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=setup',
            'type' => 'left',
            'titre' => 'Personnalisation',
            'mainmenu' => 'elaska',
            'leftmenu' => 'setup_customization',
            'url' => '/custom/elaska/admin/customization.php',
            'langs' => 'elaska@elaska',
            'position' => 165,
            'enabled' => '$user->admin',
            'perms' => '$user->admin',
            'target' => '',
            'user' => 2,
        );
        $r++;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=elaska,fk_leftmenu=setup',
            'type' => 'left',
            'titre' => 'Tags & étiquettes',
            'mainmenu' => 'elaska',
            'leftmenu' => 'setup_tags',
            'url' => '/custom/elaska/admin/tags.php',
            'langs' => 'elaska@elaska',
            'position' => 166,
            'enabled' => '$user->admin',
            'perms' => '$user->admin',
            'target' => '',
            'user' => 2,
        );
        $r++;
    }

     /**
     * Function called when module is enabled.
     * The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     * It also creates data directories.
     *
     * @param string $options       Options when enabling module ('', 'noboxes')
     * @return int                  1 if OK, 0 if KO
     */
    public function init($options = '')
    {
        global $conf, $langs;
        $sql_for_parent_init = array(); // Array for SQL commands, if any, to be executed by _init

        // 1. Create/Update module schema (tables)
        // This function loads SQL files from /elaska/sql/ to create/update tables.
        $result_load_tables = $this->_load_tables('/elaska/sql/');
        if ($result_load_tables < 0) {
            $this->error = "Failed to load module tables from /elaska/sql/. Ensure SQL files are correct and the database user has permissions. Error code: " . $result_load_tables;
            dol_syslog($this->error, LOG_ERR);
            return 0; // KO
        }

        // 2. Call the parent's _init function.
        // This handles:
        // - Creation of constants defined in $this->const
        // - Creation of directories defined in $this->dirs (e.g., DOL_DATA_ROOT/elaska/documents)
        // - Registration of boxes defined in $this->boxes
        // - Registration of permissions defined in $this->rights
        // - Creation of menu entries defined in $this->menu
        $result_parent_init = $this->_init($sql_for_parent_init, $options);
        if (! $result_parent_init) {
            // $this->error should be set by _init() in case of failure
            dol_syslog("Parent _init() failed for module " . $this->name . ". Error: " . (isset($this->error) ? $this->error : "Unknown error in _init()"), LOG_ERR);
            return 0; // KO
        }
        
        // 3. Initialize default data in dictionaries.
        // This should be done AFTER tables are created by _load_tables()
        // AND after constants are registered by _init() as $this->dictionaries['tabcond'] might use them.
        // $conf->elaska->enabled will be true if the MAIN_MODULE_ELASKA constant is set by _init().
        if (!empty($conf->global->MAIN_MODULE_ELASKA)) { // Check if module is considered enabled
             $this->initDictionaries();
        } else {
            dol_syslog("Module eLaska not fully enabled, skipping dictionary initialization. Check constants.", LOG_WARNING);
        }
        
        // 4. Set up any specific default permissions or other post-init tasks.
        // The setUpPermissions function ensures admin has all rights.
        // Standard user rights are handled by bydefault=1 in $this->rights and processed by _init().
        $this->setUpPermissions();
        
        // 5. Other custom initialization tasks for the module
        dolibarr_set_const($this->db, 'ELASKA_INSTALL_DATE', dol_now(), 'chaine', 0, '', $conf->entity);
        
        return 1; // OK
    }
    
    /**
     * Initialise les dictionnaires par défaut
     */
    private function initDictionaries() 
    {
        global $conf, $langs; // $langs might be needed if labels are translated
        
        // Initialisation des types de dossiers
        $types_dossier = array(
            array('code' => 'PARTICULAR', 'label' => 'Particulier'),
            array('code' => 'ASSOCIATION', 'label' => 'Association'),
            array('code' => 'CREATION', 'label' => 'Création d\'entreprise'),
            array('code' => 'TPE_PME', 'label' => 'TPE/PME'),
            array('code' => 'RECOVERY', 'label' => 'Recouvrement')
        );
        
        // Make sure the table exists before trying to insert
        if ($this->isTableExists(MAIN_DB_PREFIX . 'c_elaska_dossier_type')) {
            $this->insertDictionaryEntries('c_elaska_dossier_type', $types_dossier);
        } else {
            dol_syslog("Dictionary table " . MAIN_DB_PREFIX . "c_elaska_dossier_type does not exist. Skipping entries.", LOG_WARNING);
        }
        
        // D'autres initialisations de dictionnaires peuvent être ajoutées ici
        // Example for another dictionary if it exists:
        // $types_prestations = array( ... );
        // if ($this->isTableExists(MAIN_DB_PREFIX.'c_elaska_prestations')) {
        //     $this->insertDictionaryEntries('c_elaska_prestations', $types_prestations);
        // } else {
        //     dol_syslog("Dictionary table ".MAIN_DB_PREFIX."c_elaska_prestations does not exist. Skipping entries.", LOG_WARNING);
        // }
    }
    
    /**
     * Helper function to check if a table exists
     * @param string $tableName Full table name
     * @return bool
     */
    private function isTableExists($tableName)
    {
        $resql = $this->db->query("SHOW TABLES LIKE '".$this->db->escape($tableName)."'");
        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Insère des entrées dans une table de dictionnaire
     *
     * @param string $table Nom du dictionnaire sans le préfixe
     * @param array $entries Tableau des entrées à insérer
     */
    private function insertDictionaryEntries($table, $entries) 
    {
        global $conf; // $conf is needed for $conf->entity if you use it, but not directly here.
        
        $table_name = MAIN_DB_PREFIX . $table;
        
        foreach ($entries as $entry) {
            // Check if entry already exists
            $sql_check = "SELECT count(*) as nb FROM " . $table_name . " WHERE code = '" . $this->db->escape($entry['code']) . "'";
            $res_check = $this->db->query($sql_check);
            
            if ($res_check) {
                $obj_check = $this->db->fetch_object($res_check);
                if ($obj_check && $obj_check->nb == 0) {
                    // Entry does not exist, insert it
                    $sql_insert = "INSERT INTO " . $table_name . " (code, label, active) VALUES (";
                    $sql_insert .= "'" . $this->db->escape($entry['code']) . "',";
                    $sql_insert .= "'" . $this->db->escape($entry['label']) . "',";
                    $sql_insert .= "1)";
                    if (! $this->db->query($sql_insert)) {
                         dol_syslog("Failed to insert dictionary entry into " . $table_name . ": code=" . $entry['code'] . ". Error: " . $this->db->error(), LOG_ERR);
                    }
                }
            } else {
                 dol_syslog("Failed to check dictionary entry in " . $table_name . ": code=" . $entry['code'] . ". Error: " . $this->db->error(), LOG_ERR);
            }
        }
    }
    
    /**
     * Configure les permissions par défaut
     * Note: The parent _init() method already processes $this->rights array.
     * This method seems to grant all module rights to user ID 1 (admin) if MAIN_FEATURES_LEVEL is set.
     * This can be useful for ensuring the admin always has access regardless of 'bydefault' in $this->rights.
     */
    private function setUpPermissions() 
    {
        global $conf;
        
        // Grant all defined module rights to the admin user (ID 1) if not already set by default.
        // This is a common pattern to ensure superadmin has all rights for the module.
        if (! empty($conf->global->MAIN_FEATURES_LEVEL) && !empty($conf->user->admin)) { // Check if current user is admin
            foreach ($this->rights as $right) {
                $permission_id = $right[0];
                $sql_check_perm = "SELECT rowid FROM ".MAIN_DB_PREFIX."user_rights WHERE fk_user = 1 AND fk_id = ".$permission_id." AND entity = ".$conf->entity;
                $res_check_perm = $this->db->query($sql_check_perm);
                if ($res_check_perm && $this->db->num_rows($res_check_perm) == 0) {
                    $sql_grant = "INSERT INTO ".MAIN_DB_PREFIX."user_rights (fk_user, fk_id, entity) VALUES (1, ".$permission_id.", ".$conf->entity.")";
                    $this->db->query($sql_grant);
                }
            }
        }
    }

    /**
     * Function called when module is disabled.
     * Remove from database constants, boxes and permissions from Dolibarr database.
     * Data directories are not deleted.
     *
     * @param      string    $options    Options when enabling module ('', 'noboxes')
     * @return     int                   1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array(); // Array for SQL commands to be executed by _remove
        return $this->_remove($sql, $options);
    }
}