<?php

require_once 'missingoptionvalues.civix.php';
// phpcs:disable
use CRM_Missingoptionvalues_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function missingoptionvalues_civicrm_config(&$config) {
  _missingoptionvalues_civix_civicrm_config($config);
  if (defined('MISSINGOPTIONVALUES_INLCUDE_ALL_CIVI_TABLES')
    && MISSINGOPTIONVALUES_INLCUDE_ALL_CIVI_TABLES
  ) {
    _missingoptionvalues_civicrm_setEntitiesInCache($config);
  }
}

function _missingoptionvalues_civicrm_setEntitiesInCache($config) {
  if (empty(Civi::$statics[__FUNCTION__])) {
    $dbName = DB::parseDSN($config->dsn)['database'];
    $query = 'SELECT TABLE_NAME
      FROM information_schema.columns
      WHERE table_schema = %1 AND column_name = %2';

    $tables = CRM_Core_DAO::executeQuery($query, [
      1 => [$dbName, 'String'],
      2 => ['entity_table', 'String'],
    ])->fetchAll();

    $tables = array_column($tables, 'TABLE_NAME', 'TABLE_NAME');

    $entities = CRM_Core_DAO_AllCoreTables::daoToClass();
    $coreTables = array_flip(CRM_Core_DAO_AllCoreTables::getCoreTables());
    foreach ($entities as $entitiesType => &$value) {
      if (!empty($coreTables[$value])) {
        $value = $coreTables[$value];
      }
      else {
        unset($entities[$entitiesType]);
      }
    }
    Civi::$statics[__FUNCTION__]['entity'] = $entities;
    Civi::$statics[__FUNCTION__]['tables'] = array_intersect($entities, $tables);
  }
}

/**
 * Implements hook_civicrm_fieldOptions().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_fieldOptions
 */
function missingoptionvalues_civicrm_fieldOptions($entity, $field, &$options, $params) {
  if ($field == 'entity_table') {
    if (defined('MISSINGOPTIONVALUES_INLCUDE_ALL_CIVI_TABLES')
      && MISSINGOPTIONVALUES_INLCUDE_ALL_CIVI_TABLES
    ) {
      $fname = '_missingoptionvalues_civicrm_setEntitiesInCache';
      if (!empty(Civi::$statics[$fname]['tables'][$entity])) {
        $options += array_flip(Civi::$statics[$fname]['entity']);
      }
    }
    else if (in_array($entity, [
      'LineItem', 'EntityFinancialAccount', 'EntityBatch', 'FinancialItem',
      'EntityFile', 'EntityTag', 'EntityFinancialTrxn', 'RecurringEntity',
      'ACL', 'ACLEntityRole'
    ])) {
      $options += [
        'civicrm_case' => ts('Case'),
        'civicrm_activity' => ts('Activity'),
        'civicrm_grant' => ts('Grant'),
        'civicrm_contribution' => ts('Contribution'),
        'civicrm_membership' => ts('Membership'),
        'civicrm_option_value' => ts('OptionValue')
      ];
    }
  }

}
