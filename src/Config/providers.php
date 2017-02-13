<?php

return [
    'msm' => [
        'url' => 'https://api.msm.az/sendsms',


        'response_codes' => [
            100 => "OK",
            0   => "Missing parameter/xml parse error",
            10  => "Configuration error",
            20  => "Invalid msisdn/no valid message to send",
            25  => "Blacklisted msisdn",
            30  => "Unauthorized destination network",
            40  => "Invalid username/password",
            50  => "Unauthorized sender name",
            60  => "Insufficient balance",
            80  => "Invalid validity period",
            85  => "Invalid delivery datetime",
            90  => "Exceeded message size limit",
            200 => "Server error"
        ]
    ],

    "mobis" => [
        'url' => 'https://sms.atatexnologiya.az/bulksms/api',



        'response_codes' => [
            000 => "Operation is successful",
            001 => "Processing, report is not ready",
            002 => "Duplicate <control_id> (it must be unique for each task)",
            100 => "Bad request",
            101 => "Operation type is empty",
            102 => "Invalid operation",
            103 => "Login is empty",
            104 => "Password is empty",
            105 => "Invalid authentification information",
            106 => "Title is empty",
            107 => "Invalid title",
            108 => "Task id is empty",
            109 => "Invalid task id",
            110 => "Task with supplied id is canceled",
            111 => "Scheduled date is empty",
            112 => "Invalid scheduled date",
            113 => "Old scheduled date",
            114 => "isbulk is empty",
            115 => "Invalid isbulk value, must “true” or “false”",
            116 => "Invalid bulk message",
            117 => "Invalid body",
            118 => "Not enough units",
            235 => "Invalid TITLE please contact Account Manager",
            300 => "Internal server error, report to administrator"
        ],

        "status_codes" => [
            1 => "Message is queued",
            2 => "Message was successfully delivered",
            3 => "Message delivery failed",
            4 => "Message was removed from list",
            5 => "System error"
        ]
    ]
];