<?php

namespace Humweb\Sociable\Tests;

use Humweb\Sociable\Models\SocialConnection;
use Humweb\Sociable\Tests\Stubs\User;
use Mockery as m;

class SocialTest extends TestCase
{

    /**
     * @test
     */
    public function it_can_link_oauth2_account()
    {
        $user = User::find(1);

        $user->attachProvider('github', $this->createOAuth2User());

        // Grab social link
        $link = $user->social->first();

        $this->assertEquals('github', $link->provider);
        $this->assertEquals('id-123', $link->social_id);
        $this->assertEquals('token-123', $link->data['token']);

        // Check autodetect oauth version
        $this->assertEquals(2, $link->oauth_version);
    }


    /**
     * @test
     */
    public function it_can_link_oauth1_account()
    {
        $user = User::find(1);

        $user->attachProvider('twitter', $this->createOAuth1User());

        // Grab social link
        $link = $user->social->first();

        $this->assertEquals('twitter', $link->provider);
        $this->assertEquals('id-123', $link->social_id);
        $this->assertEquals('token-123', $link->data['token']);

        // Check autodetect oauth version
        $this->assertEquals(1, $link->oauth_version);
    }

    /**
     * @test
     */
    public function it_can_get_provider_by_credentials()
    {
        $user = User::find(1);

        $user->attachProvider('twitter', $this->createOAuth1User());

        // Grab social link
        $provider = SocialConnection::ofCredentials('twitter', 'id-123')->first();

        $this->assertEquals(1, $provider->user_id);
        $this->assertEquals('id-123', $provider->social_id);

    }


    /**
     * @test
     */
    public function it_can_list_attached_providers()
    {
        $user = User::find(1);

        $user->attachProvider('twitter', $this->createOAuth1User());

        $this->assertEquals(['twitter'], $user->listProviders());
    }

    /**
     * @test
     */
    public function it_can_detach_provider_by_name()
    {
        $user = User::find(1);

        $user->attachProvider('github', $this->createOAuth2User());

        $this->assertEquals(1, $user->detachProviderByName('github'));
    }


    /**
     * @test
     */
    public function it_can_detach_provider_by_id()
    {
        $user = User::find(1);

        $user->attachProvider('github', $this->createOAuth2User());

        $this->assertEquals(1, $user->detachProviderById('id-123'));
    }


    /**
     * @test
     */
    public function it_has_a_user_relationship()
    {
        $link = new SocialConnection;

        $this->addMockConnection($link);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $link->user());
    }


    /**
     * @test
     */
    public function it_can_set_and_retrieve_the_user()
    {
        $user = m::mock('Humweb\Sociable\Tests\Stubs\User');

        $link = m::mock('Humweb\Sociable\Models\SocialConnection[user]');

        $this->addMockConnection($link);

        $link->getConnection()->getQueryGrammar()->shouldReceive('getDateFormat')->andReturn('Y-m-d H:i:s');

        $link->getConnection()->getPostProcessor()->shouldReceive('processInsertGetId');

        $link->getConnection()->shouldReceive('getQueryGrammar')->andReturn($grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar'));

        $link->getConnection()->getQueryGrammar()->shouldReceive('compileInsertGetId');

        $link->shouldReceive('user')->twice()->andReturn($relation = m::mock('Illuminate\Database\Eloquent\Relations\BelongsTo'));

        $relation->shouldReceive('associate')->with($user)->once();

        $relation->shouldReceive('getResults')->once()->andReturn($user);

        $link->setUser($user);

        $this->assertSame($user, $link->getUser());
    }


    /**
     * @test
     */
    public function it_can_set_and_retrieve_the_users_model()
    {
        SocialConnection::setUsersModel('foo');

        $this->assertEquals('foo', SocialConnection::getUsersModel());
    }


    /**
     * Adds a mock connection to the object.
     *
     * @param  mixed $model
     *
     * @return void
     */
    protected function addMockConnection($model)
    {
        $model->setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));

        $resolver->shouldReceive('connection')->andReturn(m::mock('Illuminate\Database\Connection'));

        $model->getConnection()->shouldReceive('getQueryGrammar')->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));

        $model->getConnection()->shouldReceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
    }


    protected function createOAuth1User()
    {
        return (new \Laravel\Socialite\One\User())->map([
            'id'          => 'id-123',
            'email'       => 'email-123@example.com',
            'token'       => 'token-123',
            'tokenSecret' => 'secret-123',
        ]);
    }


    protected function createOAuth2User()
    {
        return (new \Laravel\Socialite\Two\User())->map([
            'id'           => 'id-123',
            'email'        => 'email-123@example.com',
            'token'        => 'token-123',
            'refreshToken' => 'refresh-123',
            'expiresIn'    => 36000
        ]);
    }
}
