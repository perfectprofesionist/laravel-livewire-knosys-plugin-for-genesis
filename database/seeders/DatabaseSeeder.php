<?php
namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();
        Settings::truncate();
        $data = [
            [
                "name" => "LBL_NO_RES",
                "section" => "display",
                "label" => "No results label",
                "value" => "Nothing to show. ",
                "type" => "string"
            ],
            [
                "name" => "ERR_RESPONSE_TIMEOUT",
                "section" => "display",
                "label" => "Error: Response timed out error",
                "value" => "Session expired. Click to refresh.",
                "type" => "string"
            ],
            [
                "name" => "ERR_SAVE",
                "section" => "display",
                "label" => "Error: Unsuccessful save",
                "value" => "Unable to save.",
                "type" => "string"
            ],
            [
                "name" => "ERR_SNIPPET_COPY",
                "section" => "display",
                "label" => "Error: Unable to copy snipet",
                "value" => "Unable to copy snipet",
                "type" => "string"
            ],
            [
                "name" => "ERR_DOC_SEND",
                "section" => "display",
                "label" => "Error: Unable to send document",
                "value" => "Unable to send document",
                "type" => "string"
            ],
            [
                "name" => "ERR_INVALID_CONVO",
                "section" => "display",
                "label" => "Error: Invalid conversation",
                "value" => "Invalid conversation",
                "type" => "string"
            ],
            [
                "name" => "ERR_KNOSYS_CRED",
                "section" => "display",
                "label" => "Error: Wrong Knosys credentials",
                "value" => "Wrong Knosys credentials",
                "type" => "string"
            ],
            [
                "name" => "ERR_GENESYS_CRED",
                "section" => "display",
                "label" => "Error: Wrong Genesys credentials",
                "value" => "Wrong Genesys credentials",
                "type" => "string"
            ],
            [
                "name" => "SCCS_SAVED",
                "section" => "display",
                "label" => "Success: Saved",
                "value" => "Saved",
                "type" => "string"
            ],
            [
                "name" => "SCCS_SNIPPET_COPY",
                "section" => "display",
                "label" => "Success: Copied snipet",
                "value" => "Copied snipet",
                "type" => "string"
            ],
            [
                "name" => "SCCS_DOC_SEND",
                "section" => "display",
                "label" => "Success: Sent document",
                "value" => "Sent document",
                "type" => "string"
            ],
            [
                "name" => "SCCS_RVW_SAVED",
                "section" => "display",
                "label" => "Succes: Review Submitted",
                "value" => "Review Submitted",
                "type" => "string"
            ],
            [
                "name" => "SCCS_MSG_SENT",
                "section" => "display",
                "label" => "Success: Message Sent",
                "value" => "Message sent successfully",
                "type" => "string"
            ],
            [
                "name" => "SCCS_EMAIL_SENT",
                "section" => "display",
                "label" => "Success: Email Sent",
                "value" => "Email sent successfully",
                "type" => "string"
            ],
            [
                "name" => "ERR_NO_RES",
                "section" => "display",
                "label" => "INFO: No Results",
                "value" => "No Results Found",
                "type" => "string"
            ],
            [
                "name" => "ERR_FEEDBACK_EMPTY",
                "section" => "display",
                "label" => "Error: Empty Feedback",
                "value" => "Empty Feedback",
                "type" => "string"
            ],
            [
                "name" => "ERR_MSG_NOT_SENT",
                "section" => "display",
                "label" => "Error: Message not sent",
                "value" => "Unable to send message",
                "type" => "string"
            ],
            [
                "name" => "ERR_MAIL_NOT_SENT",
                "section" => "display",
                "label" => "Error: Email not sent",
                "value" => "Unable to send mail",
                "type" => "string"
            ],
            [
                "name" => "WEBHOOK_URL",
                "section" => "general",
                "label" => "Webhook URL",
                "value" => "https://kn07.dev.hpprojects.net",
                "type" => "string"
            ],
            [
                "name" => "CLIENT_IMP_ID",
                "section" => "general",
                "label" => "Client ID",
                "value" => "936f40e0-6b0a-48e6-bfca-78ef186eb4a9",
                "type" => "string"
            ],
            [
                "name" => "CLIENT_IMP_SECRET",
                "section" => "general",
                "label" => "Client Secret",
                "value" => "UmRh3PanTF4oiD07TZ-YiDw-4NCxfKZxBpJhmnvBmSY",
                "type" => "string"
            ],
            [
                "name" => "ENVIRONMENT",
                "section" => "general",
                "label" => "Environment",
                "value" => "mypurecloud.com.au",
                "type" => "string"
            ],
            [
                "name" => "customer_context",
                "section" => "user",
                "label" => "Get customer context from",
                "value" => "subject",
                "type" => "string"
            ],
            [
                "name" => "pageinate",
                "section" => "general",
                "label" => "Results per page",
                "value" => "15",
                "type" => "int"
            ],
            [
                "name" => "KNO_AUTH_ENV",
                "section" => "kiq",
                "label" => "Auth Environment",
                "value" => "https://rest.insurance.demo.kiq.cloud/api/v1",
                "type" => "string"
            ],
            [
                "name" => "KNO_AUTH_TOKEN",
                "section" => "kiq",
                "label" => "Auth Token",
                "value" => "9EF24365-7984-492A-A5D7-9D2630049290",
                "type" => "string"
            ],
            [
                "name" => "KNO_SITE_ID",
                "section" => "kiq",
                "label" => "Site ID",
                "value" => "53A47DA6-67C7-EB11-A839-000D3AE08DB5",
                "type" => "string"
            ],
            [
                "name" => "KNO_SECRET",
                "section" => "kiq",
                "label" => "Secret",
                "value" => "A36C9E3B301C43DB34A34B34DBB5DC3C5502BAB7048A61C1752C82944F8E5A1D024A1286",
                "type" => "string"
            ],
            [
                "name" => "KNO_USER_TYPE",
                "section" => "kiq",
                "label" => "User Type",
                "value" => "public",
                "type" => "string"
            ],
            [
                "name" => "KNO_ENV",
                "section" => "kiq",
                "label" => "Environment",
                "value" => "https://rest.insurance.demo.kiq.cloud/api/v2",
                "type" => "string"
            ],
            
            //SCCS-Showing Results;
            /*SCCS-Review Submitted:
            SCCS-Message sent successfully;
            SCCS-Email sent successfully;
            
            ERR-No Results found;
            ERR-Empty feedback;
            ERR-Unable to sent message;
            ERR-Unable to send email;*/
        ];
        Settings::insert($data);
    }
}
