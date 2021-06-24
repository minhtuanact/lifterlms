<?php

namespace ExtendBuilder;

use function file_exists;
use function get_option;
use function str_replace;

class OptionThemeData
{
    const OPTION_NAME = 'extend_builder_theme';

    public function get()
    {
        return get_option(self::OPTION_NAME, []);
    }

    public function set($value)
    {
        update_option(self::OPTION_NAME, $value);
    }

    public function backup() {
        update_option(self::OPTION_NAME."_".time(), $this->get());
    }
}

class FileThemeData
{
    const FS_OPTION_NAME = 'colibri_page_builder_use_fs';
    const FS_OPTION_FS_ERROR = 'colibri_page_builder_fs_error';

    const PREFIX = '<?php //';
    private $files_dir = "colibri";

    public  function canWriteToFs()
    {
        $file_path = $this->file_path('test-'.time());
        $write = file_put_contents($file_path, 1);
        if ($write !== false){
            unlink($file_path);
            return true;
        }
        return false;
    }

    public  function enable()
    {
        $option_value = get_option(FileThemeData::FS_OPTION_NAME, false);
        if ($option_value === false) {
            $can_write = $this->canWriteToFs();
            update_option(FileThemeData::FS_OPTION_NAME, $can_write ? 'colibri-data-'.time() : self::FS_OPTION_FS_ERROR);
        }
    }

    public  function disable()
    {
        $option_value = get_option(FileThemeData::FS_OPTION_NAME, false);
        if ($option_value !== false) {
            delete_option(FileThemeData::FS_OPTION_NAME);
        }
    }

    function isMigrated() {
        return $this->file_name() !== false && file_exists($this->file_path());
    }

    function file_name()
    {
        return get_option(FileThemeData::FS_OPTION_NAME, false);
    }

    function file_path($name = "")
    {
        $dir_path = self::uploads_dir() . '/' . $this->files_dir;

        if (!is_dir($dir_path)) {
            wp_mkdir_p($dir_path);
        }

        if (!$name) {
            $name = $this->file_name();
        }

        return $dir_path . "/" . $name . ".php";
    }

    function uploads_dir()
    {
        $upload_data = wp_upload_dir(null, false);
        return $upload_data['basedir'];
    }

    public function get()
    {
        $content = file_get_contents($this->file_path());
        if ($content !== false) {
            $content = str_replace(self::PREFIX, '', $content);
            return json_decode($content, true);
        }
        return [];
    }

    public function set($value)
    {
        file_put_contents($this->file_path(), self::PREFIX . json_encode($value));
    }
}

class ThemeDataAccess
{
    public $option;
    public $fs;
    private static $instance;

    public static function getInstance()
    {
        if ( ! self::$instance ) {
            self::$instance = new ThemeDataAccess();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->option = new OptionThemeData();
        $this->fs = new FileThemeData();
    }

    public static function maybeEnableFs()
    {
      if (self::shouldTryToEnableFs()) {
          self::getInstance()->enableFS();
      }
    }

    public static function shouldTryToEnableFs()
    {
        $fs_option_value = get_option(FileThemeData::FS_OPTION_NAME, false);
        return $fs_option_value === false;
    }

    public static function canUseFs()
    {
        $fs_option_value = get_option(FileThemeData::FS_OPTION_NAME, false);
        return $fs_option_value !== false && $fs_option_value !== FileThemeData::FS_OPTION_FS_ERROR;
    }

    public function enableFS()
    {
        $this->fs->enable();
    }

    public function moveFromOptionToFile()
    {
        if (self::canUseFs()) {
            $value = $this->option->get();
            $this->fs->set($value);
        }
    }

    public function moveFromFileToOption()
    {
        if (self::canUseFs()) {
            $value = $this->fs->get();
            $this->option->set($value);
            $this->fs->disable();
        }
    }

    public function get()
    {
        if (self::canUseFs()) {
            if (!$this->fs->isMigrated()) {
                $value = $this->option->get();
                $this->fs->set($value);
                return $value;
            }
            return $this->fs->get();
        }
        return $this->option->get();
    }

    public function backup() {
        $this->option->backup();
    }

    public function set($value)
    {
        if (self::canUseFs()) {
            $this->fs->set($value);
        }
        // fallback //
        $this->option->set($value);
    }
}

