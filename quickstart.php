<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('G Suite Directory API PHP Quickstart');
    $client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY);
    $client->setAuthConfig('client_secret2.json');
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory('credentials.json');
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    var_dump($accessToken);
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        //$refreshToken = $client->getRefreshToken();
        //var_dump($refreshToken);
        //$client->fetchAccessTokenWithRefreshToken($refreshToken);
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        $newAccessToken = $client->getAccessToken();
        var_dump($newAccessToken);
        $accessToken = array_merge($accessToken, $newAccessToken);
        file_put_contents($credentialsPath, json_encode($accessToken));
        //file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Directory($client);

// Print the first 10 users in the domain.
$optParams = array(
  'customer' => 'my_customer',
  'maxResults' => 10,
  'orderBy' => 'email',
);
$results = $service->users->listUsers($optParams);

if (count($results->getUsers()) == 0) {
  print "No users found.\n";
} else {
  print "Users:\n";
  foreach ($results->getUsers() as $user) {
    printf("%s (%s)\n", $user->getPrimaryEmail(),
        $user->getName()->getFullName());
  }
}