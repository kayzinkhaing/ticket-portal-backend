<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class configFiles
{
    /**
     * Update or add a new key-value pair in the custom_variables config file.
     */
    public function updateConfigFile($key, $value)
    {
        $configFilePath = config_path('custom_variables.php');

        // Check if the config file exists
        if (!File::exists($configFilePath)) {
            throw new \Exception("Config file 'custom_variables.php' does not exist.");
        }

        // Load the current configuration from the file
        $configData = include($configFilePath);

        // Update or add the key-value pair
        $configData[$key] = $value;

        // Write the updated configuration back to the file
        $this->writeConfigFile($configFilePath, $configData);

        // Re-load the updated config into Laravel's Config system
        $this->reloadConfig();
    }

    /**
     * Remove a key-value pair from the custom_variables config file.
     */
    public function removeConfigFileKey($key)
    {
        $configFilePath = config_path('custom_variables.php');

        // Check if the config file exists
        if (!File::exists($configFilePath)) {
            throw new \Exception("Config file 'custom_variables.php' does not exist.");
        }

        // Load the current configuration from the file
        $configData = include($configFilePath);

        // Check if the key exists before removing it
        if (isset($configData[$key])) {
            // Remove the key from the array
            unset($configData[$key]);

            // Write the updated configuration back to the file
            $this->writeConfigFile($configFilePath, $configData);

            // Re-load the updated config into Laravel's Config system
            $this->reloadConfig();
        } else {
            throw new \Exception("The key '$key' does not exist in the config file.");
        }
    }

    /**
     * Write the updated data back to the configuration file.
     */
    private function writeConfigFile($filePath, $data)
    {
        $fileContent = "<?php\n\nreturn " . var_export($data, true) . ";\n";

        // Write the updated content back to the config file
        File::put($filePath, $fileContent);
    }

    /**
     * Reload the configuration into Laravel's Config system.
     */
    private function reloadConfig()
    {
        // Reload the config
        Config::set('custom_variables', include(config_path('custom_variables.php')));
    }
}
