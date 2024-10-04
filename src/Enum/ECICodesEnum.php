<?php

namespace App\Infrastructure\ThreeDSIntegrator\Enum;

use OpenApi\Attributes as OA;

/**
 * ECI Codes
 */
#[OA\Schema(type: 'string')]
enum ECICodesEnum: string
{
    /** Authentication failed (Mastercard) */
    case MASTERCARD_FAILED = '00';
    /** Authentication Attempted (Mastercard) */
    case MASTERCARD_ATTEMPTED = '01';
    /** Authentication Successful (Mastercard) */
    case MASTERCARD_SUCCESSFUL = '02';
    /** Authentication Failed (Visa) */
    case VISA_FAILED = '07';
    /** Authentication Attempted (Visa) */
    case VISA_ATTEMPTED = '06';
    /** Authentication Successful (Visa) */
    case VISA_SUCCESSFUL = '05';
}
