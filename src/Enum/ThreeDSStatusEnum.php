<?php

namespace App\Infrastructure\ThreeDSIntegrator\Enum;

use OpenApi\Attributes as OA;

/**
 * 3DS Result status
 */
#[OA\Schema(type: 'string')]
enum ThreeDSStatusEnum: string
{
    /** Authentication Successful - The issuer has authenticated the cardholder by verifying the password or other identity info */
    case SUCCESSFUL = 'Y';
    /** Authentication was not available, but functionality was available - There will still be a liability shift */
    case NOT_AVAILABLE = 'A';
    /** Not Authenticated / Account Not Verified; Transaction denied */
    case NOT_AUTHENTICATED = 'N';
    /** Authentication / Account Verification Could Not Be Performed; Technical or other problem */
    case NOT_PERFORMED = 'U';
    /** Challenge Required; Additional authentication is required using */
    case CHALLENGE_REQUIRED = 'C';
    /** Authentication / Account Verification Rejected; Issuer is rejecting authentication / verification and request that authorisation not be attempted. */
    case REJECTED = 'R';
}
