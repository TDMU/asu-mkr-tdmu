<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.09.2017
 * Time: 23:04
 */
class AsuFacebookOAuthService extends FacebookOAuthService
{
    protected $scope = 'email';

    protected function fetchAttributes() {
        $info = (object)$this->makeSignedRequest('https://graph.facebook.com/v2.8/me', array(
            'query' => array(
                'fields' => join(',', array(
                    'id',
                    'name',
                    'link',
                    'email'
                ))
            )
        ));

        $this->attributes['id'] = $info->id;
        $this->attributes['name'] = $info->name;
        $this->attributes['url'] = $info->link;
        $this->attributes['email'] = $info->email;
    }
}