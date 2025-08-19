<?php

namespace Plugins\ExamplePlugin;

use App\Plugins\PluginInterface;

class Plugin implements PluginInterface
{
    public function getName(): string
    {
        return 'Example Plugin';
    }
    
    public function getVersion(): string
    {
        return '1.0.0';
    }
    
    public function getDescription(): string
    {
        return 'An example plugin demonstrating the plugin system';
    }
    
    public function activate()
    {
        // Add menu items
        PluginManager::getInstance()->addHook('admin_menu', function($menu) {
            $menu[] = [
                'title' => 'Example Plugin',
                'url' => '/admin/plugins/example',
                'icon' => 'fas fa-plug'
            ];
            return $menu;
        });
        
        // Add API endpoints
        PluginManager::getInstance()->addHook('api_routes', function($routes) {
            $routes['GET /api/example'] = [ExampleController::class, 'example'];
            return $routes;
        });
    }
    
    public function deactivate()
    {
        // Cleanup
    }
}