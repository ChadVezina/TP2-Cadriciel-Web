<?php

namespace App\Constants;

/**
 * Classe AppConstants
 * 
 * Regroupe toutes les constantes de l'application pour centraliser
 * les valeurs utilisées à travers l'application et faciliter la maintenance.
 */
class AppConstants
{
    /**
     * Constantes de pagination.
     * 
     * @var int PAGINATION_DEFAULT Nombre d'éléments par page par défaut
     * @var int PAGINATION_MIN Nombre minimum d'éléments par page
     * @var int PAGINATION_MAX Nombre maximum d'éléments par page
     */
    public const PAGINATION_DEFAULT = 10;
    public const PAGINATION_MIN = 10;
    public const PAGINATION_MAX = 100;

    /**
     * Constantes de téléchargement de fichiers.
     * 
     * @var int MAX_FILE_SIZE Taille maximale de fichier en Ko (10 Mo)
     * @var array ALLOWED_FILE_TYPES Types de fichiers autorisés
     */
    public const MAX_FILE_SIZE = 10240; // KB (10 MB)
    public const ALLOWED_FILE_TYPES = ['pdf', 'zip', 'doc', 'docx'];

    /**
     * Constantes de format de date.
     * 
     * @var string DATE_FORMAT Format d'affichage des dates (jj/mm/aaaa)
     * @var string DATETIME_FORMAT Format d'affichage des dates avec heure
     * @var string INPUT_DATE_FORMAT Format de date pour les champs de formulaire
     */
    public const DATE_FORMAT = 'd/m/Y';
    public const DATETIME_FORMAT = 'd/m/Y H:i';
    public const INPUT_DATE_FORMAT = 'Y-m-d';

    /**
     * Constantes de langue.
     * 
     * @var string LOCALE_FR Code de langue française
     * @var string LOCALE_EN Code de langue anglaise
     * @var array AVAILABLE_LOCALES Langues disponibles dans l'application
     */
    public const LOCALE_FR = 'fr';
    public const LOCALE_EN = 'en';
    public const AVAILABLE_LOCALES = [self::LOCALE_FR, self::LOCALE_EN];

    /**
     * Types de messages d'alerte/flash.
     * 
     * @var string ALERT_SUCCESS Message de succès
     * @var string ALERT_ERROR Message d'erreur
     * @var string ALERT_WARNING Message d'avertissement
     * @var string ALERT_INFO Message d'information
     */
    public const ALERT_SUCCESS = 'success';
    public const ALERT_ERROR = 'error';
    public const ALERT_WARNING = 'warning';
    public const ALERT_INFO = 'info';

    /**
     * Constantes de rôles utilisateur (pour implémentation future).
     * 
     * @var string ROLE_ADMIN Rôle administrateur
     * @var string ROLE_USER Rôle utilisateur standard
     * @var string ROLE_STUDENT Rôle étudiant
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';
    public const ROLE_STUDENT = 'student';
}
