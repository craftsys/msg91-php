<?php

namespace Craftsys\Msg91;

class URLs
{
    /**
     * URL for Send OTP Request
     * @var string
     */
    const OTP_URL = "https://api.msg91.com/api/v5/otp";

    /**
     * URL for Verify OTP
     * @var string
     */
    const OTP_VERIFY_URL = "https://api.msg91.com/api/v5/otp/verify";

    /**
     * URL for Resend OTP Request
     * @var string
     */
    const OTP_RESEND_URL = "https://api.msg91.com/api/v5/otp/retry";

    /**
     * URL for send sms
     * @var string
     */
    const SEND_SMS_URL = "https://api.msg91.com/api/v5/flow/";
}
