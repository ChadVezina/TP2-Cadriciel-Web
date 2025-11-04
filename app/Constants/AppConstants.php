<?php

namespace App\Constants;

class AppConstants
{
    /**
     * Pagination constants.
     */
    public const PAGINATION_DEFAULT = 10;
    public const PAGINATION_MIN = 10;
    public const PAGINATION_MAX = 100;

    /**
     * File upload constants.
     */
    public const MAX_FILE_SIZE = 10240; // KB (10 MB)
    public const ALLOWED_FILE_TYPES = ['pdf', 'zip', 'doc', 'docx'];

    /**
     * Date format constants.
     */
    public const DATE_FORMAT = 'd/m/Y';
    public const DATETIME_FORMAT = 'd/m/Y H:i';
    public const INPUT_DATE_FORMAT = 'Y-m-d';

    /**
     * Language constants.
     */
    public const LOCALE_FR = 'fr';
    public const LOCALE_EN = 'en';
    public const AVAILABLE_LOCALES = [self::LOCALE_FR, self::LOCALE_EN];

    /**
     * Alert/Flash message types.
     */
    public const ALERT_SUCCESS = 'success';
    public const ALERT_ERROR = 'error';
    public const ALERT_WARNING = 'warning';
    public const ALERT_INFO = 'info';

    /**
     * User role constants (if implementing roles in the future).
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';
    public const ROLE_STUDENT = 'student';
}
