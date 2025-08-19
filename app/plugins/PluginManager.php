<?php

namespace App\Plugins;

class PluginManager
{
    private static $instance = null;
    private $plugins = [];
    private $hooks = [];
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function loadPlugins()
    {
        $pluginsDir = __DIR__ . '/../plugins';
        
        if (!is_dir($pluginsDir)) {
            return;
        }
        
        $directories = scandir($pluginsDir);
        
        foreach ($directories as $dir) {
            if ($dir === '.' || $dir === '..') continue;
            
            $pluginFile = "$pluginsDir/$dir/plugin.php";
            if (file_exists($pluginFile)) {
                require_once $pluginFile;
                
                $className = "\\Plugins\\$dir\\Plugin";
                if (class_exists($className)) {
                    $plugin = new $className();
                    $this->registerPlugin($plugin);
                }
            }
        }
    }
    
    public function registerPlugin(PluginInterface $plugin)
    {
        $this->plugins[$plugin->getName()] = $plugin;
        $plugin->activate();
    }
    
    public function addHook(string $hook, callable $callback, int $priority = 10)
    {
        if (!isset($this->hooks[$hook])) {
            $this->hooks[$hook] = [];
        }
        
        $this->hooks[$hook][] = [
            'callback' => $callback,
            'priority' => $priority
        ];
        
        usort($this->hooks[$hook], function($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }
    
    public function doHook(string $hook, ...$args)
    {
        if (isset($this->hooks[$hook])) {
            foreach ($this->hooks[$hook] as $hookData) {
                $result = call_user_func_array($hookData['callback'], $args);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        
        return null;
    }
    
    public function getPlugins(): array
    {
        return $this->plugins;
    }
}