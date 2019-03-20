<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.09.2017
 * Time: 21:46
 */
class AsuGitHubOAuthService extends  GitHubOAuthService
{
    protected function fetchAttributes() {
        $info = (object)$this->makeSignedRequest('https://api.github.com/user');

        $this->attributes['id'] = $info->id;
        $this->attributes['name'] = $info->login;
        $this->attributes['url'] = $info->html_url;
        $this->attributes['email'] = $info->email;
    }
}