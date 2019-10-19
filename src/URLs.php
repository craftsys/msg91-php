<?php

namespace Craftsys\MSG91Client;

class URLs
{
    /**
     * Base url for requests
     * @var string
     */
    const BASE_URL = "https://control.msg91.com/api";

    /**
     * URl for Send OTP Request
     * @var string
     */
    const OTP_URL = self::BASE_URL . "/sendotp.php";

    /**
     * URl for Verify OTP
     * @var string
     */
    const OTP_VERIFY_URL = self::BASE_URL . "/verifyRequestOTP.php";

    /**
     * URl for Resend OTP Request
     * @var string
     */
    const OTP_RESEND_URL = self::BASE_URL . "/retryotp.php";
}
