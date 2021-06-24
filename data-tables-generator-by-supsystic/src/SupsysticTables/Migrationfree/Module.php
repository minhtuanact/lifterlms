<?php

/**
 * Class SupsysticTables_Migrationfree_Module
 */
class SupsysticTables_Migrationfree_Module extends SupsysticTables_Core_BaseModule
{
    /**
     * {@inheritdoc}
     */
   public function onInit()
   {
        $this->handleMigrationRequest();
   }

	private function handleMigrationRequest()
	{
      if(current_user_can('administrator')) {
   		if (!$this->getRequest()->query->has('migration-supsystic-table')) {
   			return;
   		}
   		$config = $this->getEnvironment()->getConfig();
         $id = $this->getRequest()->query->get_esc_html('id');
         $ids = explode(';', $id);
   		if(!is_array($ids)) {
   			wp_die(sprintf($this->translate('The table IDs %s not found.'), $id));
   		}
         $core = $this->getEnvironment()->getModule('core');
         $tables = $core->getModelsFactory()->get('tables');
   		foreach($ids as $i => $id) {
   			$table = $tables->getById((int)$id);
   			if (null === $table) {
   				wp_die(sprintf($this->translate('The table ID %s not found.'), $id));
   			}
   		}
         $exporter = $core->getModelsFactory()->get('exporter', 'migrationfree');
         $exporter->export($ids);
         die();
      }
      return;
	}
}
