<?php 

namespace Wpjscc\Json\Tests\Cases;

use System\Tests\Bootstrap\TestCase;

class BaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        app()->singleton('json', fn () => new \Wpjscc\Json\Services\Json());
        $this->app->singleton('json.twig.loader', function ($app) {
            return new \Wpjscc\Twig\Classes\DatabaseTwigLoader();
        });
        app('json')->registerDatasource('wpjscc.twig', function ($json, $config) {
            $_data_option = $config['_data_option'] ?? [];

            $twig = new \Twig\Environment(app('json.twig.loader'));

            if ($_data_option['from_string'] ?? false) {
                $template = $twig->createTemplate($_data_option['template']);
                return $template->render($_data_option['context'] ?? []);
            }
            return $twig->render(
                        $_data_option['template'], 
                        $_data_option['context'] ?? []
                    );
        });
    }

    public function testBase()
    {
        $this->assertEquals('Hello World',
        app('json')->getAsyncJson([
            '_data_source' => 'wpjscc.twig',
            '_data_option' => [
                'template' => 'Hello {{ name }}',
                'context' => [
                    'name' => 'World',
                ],
                'from_string' => true,
            ],
        ]));
    }
}