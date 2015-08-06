<?php
namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class DroitHubProvider extends AbstractProvider implements ProviderInterface
{

    protected $provider = 'droithub';
    /**
     * The fields that are included in the profile.
     *
     * @var array
     */
    protected $fields = [
        'id', 'name', 'email'
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('http://hub.local/oauth/authorize', $state);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'http://hub.local/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl  = 'http://hub.local/api/user?access_token='.$token;

        $response = $this->getHttpClient()->get($userUrl, [
            'headers' => [
                'HTTP_Authorization' => 'Bearer '.$token
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'         => $user['id'],
            'provider'   => $this->provider,
            'first_name' => array_get($user, 'first_name'),
            'last_name'  => array_get($user, 'last_name'),
            'email'      => array_get($user, 'email')
        ]);
    }
}