<?php

namespace Craftsys\Msg91\Requests;

class URLs
{
    /**
     * URl for Send OTP Request
     * @var string
     */
    const OTP_URL = "https://api.msg91.com/api/v5/otp";

    /**
     * URl for Verify OTP
     * @var string
     */
    const OTP_VERIFY_URL = "https://api.msg91.com/api/v5/otp/verify";

    /**
     * URl for Resend OTP Request
     * @var string
     */
    const OTP_RESEND_URL = "https://api.msg91.com/api/v5/otp/resend";

    /**
     * URL for send sms
     * @var string
     */
    const SEND_SMS_URL = "https://api.msg91.com/api/sendhttp.php";
}
