<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.09.2017
 * Time: 20:57
 */
class AsuGoogleOAuthService extends GoogleOAuthService
{
    protected $scope = 'https://www.googleapis.com/auth/userinfo.email';

    protected function fetchAttributes() {
        $info = (array)$this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');

        $this->attributes['id'] = $info['id'];
        $this->attributes['name'] = $info['name'];

        if (!empty($info['link'])) {
            $this->attributes['url'] = $info['link'];
        }

        if (!empty($info['email'])) {
            $this->attributes['email'] = $info['email'];
        }

        /*if (!empty($info['gender']))
            $this->attributes['gender'] = $info['gender'] == 'male' ? 'M' : 'F';

        if (!empty($info['picture']))
            $this->attributes['photo'] = $info['picture'];

        $info['given_name']; // first name
        $info['family_name']; // last name
        $info['birthday']; // format: 0000-00-00
        $info['locale']; // format: en*/
    }
}