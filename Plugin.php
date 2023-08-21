<?php namespace Wpjscc\Twig;

use Backend;
use Backend\Models\UserRole;
use System\Classes\PluginBase;

/**
 * twig Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'wpjscc.twig::lang.plugin.name',
            'description' => 'wpjscc.twig::lang.plugin.description',
            'author'      => 'wpjscc',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {
        $this->app->singleton('json.twig.loader', function ($app) {
            return new Classes\DatabaseTwigLoader();
        });
    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {
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

    /**
     * Registers any frontend components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return []; // Remove this line to activate

        return [
            'Wpjscc\Twig\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any backend permissions used by this plugin.
     */
    public function registerPermissions(): array
    {
        return [
            'wpjscc.twig.some_permission' => [
                'tab' => 'wpjscc.twig::lang.plugin.name',
                'label' => 'wpjscc.twig::lang.permissions.some_permission',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
        ];
    }

    /**
     * Registers backend navigation items for this plugin.
     */
    public function registerNavigation(): array
    {
        return [
            'twig' => [
                'label'       => 'wpjscc.twig::lang.plugin.name',
                'url'         => Backend::url('wpjscc/twig/twigs'),
                'icon'        => 'icon-leaf',
                'permissions' => ['wpjscc.twig.*'],
                'order'       => 500,
            ],
        ];
    }
}
