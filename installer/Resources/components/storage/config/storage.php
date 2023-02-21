<?php

declare(strict_types=1);

use Spiral\Storage\Storage;

return [
    /**
     * -------------------------------------------------------------------------
     *  Default Storage Bucket Name
     * -------------------------------------------------------------------------
     *
     * Here you can specify which of the buckets you want to use by default for
     * all work with storages.
     *
     */
    'default' => env('STORAGE_DEFAULT', Storage::DEFAULT_STORAGE),

    /**
     * -------------------------------------------------------------------------
     *  Storage Servers
     * -------------------------------------------------------------------------
     *
     * Here are each of the servers is configured for your application. Of
     * course, examples of customizing each available server supported by
     * Spiral are shown below to simplify development.
     *
     */
    'servers' => [
        'local' => [
            //
            // Server type name. For a local server, this value must be
            // a string value "local".
            //
            'adapter' => 'local',

            //
            // The required path to the local directory where your files will
            // be stored.
            //
            'directory' => directory('public') . 'uploads',

            //
            // Visibility mapping. Here you can set the default visibility for
            // files and the permissions for files and directories corresponding
            // to a certain type of visibility.
            //
            // The visibility value can only be "private" or "public".
            //
            'visibility' => [
                'public' => ['file' => 0644, 'dir' => 0755],
                'private' => ['file' => 0600, 'dir' => 0700],

                'default' => 'public',
            ],
        ],

        // Example S3 configuration
        /*'s3' => [
            //
            // Server type name. For a S3 server, this value must be a string
            // value "s3" or "s3-async".
            //
            'adapter' => 's3',

            //
            // Required string key of S3 region like "eu-north-1".
            //
            // Region can be found on "Amazon S3" page here:
            //  - https://s3.console.aws.amazon.com/s3/home
            //
            'region' => env('S3_REGION'),

            //
            // Optional key of S3 API version.
            //
            'version' => env('S3_VERSION', 'latest'),

            //
            // Required key of S3 bucket.
            //
            // Bucket name can be found on "Amazon S3" page here:
            //  - https://s3.console.aws.amazon.com/s3/home
            //
            'bucket' => env('S3_BUCKET'),

            //
            // Required key of S3 credentials key like "AAAABBBBCCCCDDDDEEEE".
            //
            // Credentials key can be found on "Security Credentials" page here:
            //  - https://console.aws.amazon.com/iam/home#/security_credentials
            //
            'key' => env('S3_KEY'),

            //
            // Required key of S3 credentials private key.
            // This must be a private key string value or a path to a private key file.
            //
            // Identifier can be also found on "Security Credentials" page here:
            //  - https://console.aws.amazon.com/iam/home#/security_credentials
            //
            'secret' => env('S3_SECRET'),

            //
            // Optional key of S3 credentials token.
            //
            'token' => env('S3_TOKEN', null),

            //
            // Optional key of S3 credentials expiration time.
            //
            'expires' => env('S3_EXPIRES', null),

            //
            // Optional key of S3 files visibility. Visibility is "public"
            // by default.
            //
            'visibility' => env('S3_VISIBILITY', 'public'),

            //
            // For buckets that use S3 servers, you can add a directory
            // prefix.
            //
            'prefix' => '',

            //
            // Optional key of S3 API endpoint URI. This value is required when
            // using a server other than Amazon.
            //
            'endpoint' => env('S3_ENDPOINT', null),

            //
            // Optional additional S3 options.
            // For example, option "use_path_style_endpoint" is required to work
            // with a Minio S3 Server.
            //
            // Note: This "options" section is available since framework >= 2.8.5
            // See also https://github.com/spiral/framework/issues/416
            //
            'options' => [
                'use_path_style_endpoint' => true,
            ]
        ], */
    ],

    /**
     * -------------------------------------------------------------------------
     *  Storage Buckets
     * -------------------------------------------------------------------------
     *
     * Here are a list of specific buckets (or storages) that use the above
     * server settings. Each "server" section in this list must refer to a
     * valid server name in the list above.
     *
     * The list of settings in this case is also an example of use. You can
     * freely change the number of buckets and the type of settings as you wish.
     *
     */
    'buckets' => [
        'default' => [
            'server' => 'local',
        ],
        /*
        'images' => [
            'server' => 's3',
        ],*/
    ],
];
