<?php
/**
 * Plugin hooks and filters.
 * 
 * @package SharedShelfLink
 * @copyright Copyright (c) 2012 ARTstor, Inc.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/** Plugin version number */
define('SHARED_SHELF_LINK_PLUGIN_VERSION', get_plugin_ini('SharedShelfLink', 'version'));

/** Path to plugin directory */
define('SHARED_SHELF_LINK_PLUGIN_DIRECTORY', dirname(__FILE__));


/** Plugin hooks */
add_plugin_hook('install', 'shared_shelf_link_install');
add_plugin_hook('uninstall', 'shared_shelf_link_uninstall');
add_plugin_hook('config_form', 'shared_shelf_link_config_form');
add_plugin_hook('config', 'shared_shelf_link_config');
add_plugin_hook('define_acl', 'shared_shelf_link_define_acl');

/** Plugin filters */
add_filter('admin_navigation_main', 'shared_shelf_link_admin_navigation_main');

/**
 * install callback
 * 
 * Sets options and creates tables.
 * 
 * @return void
 */
function shared_shelf_link_install()
{
    set_option('shared_shelf_link_plugin_version', SHARED_SHELF_LINK_PLUGIN_VERSION);
    set_option('shared_shelf_link_token', 'Yz91346GHZIGF');

    $db = get_db();

    /* Published records/items.
        id: primary key
        ss_id: the shared shelf id`
        item_id: the corresponding item id in `items`
        published: a timestamp of the publication date
    */
    $sql = "
    CREATE TABLE IF NOT EXISTS `{$db->prefix}shared_shelf_transfer_records` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `ss_id` int(10) unsigned NOT NULL,
        `item_id` int(10) unsigned default NULL,
        `published` datetime default NULL,
        PRIMARY KEY  (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $db->query($sql);
}

/**
 * uninstall callback.
 * 
 * Deletes options and drops tables.
 * 
 * @return void
 */
function shared_shelf_link_uninstall()
{
    delete_option('shared_shelf_link_plugin_version');

    $db = get_db();
    $sql = "DROP TABLE IF EXISTS `{$db->prefix}shared_shelf_transfer_records`;";
    $db->query($sql);
}

/**
 * config_form callback.
 * 
 * Prepares and renders the plugin's configuration form.
 * 
 * @return void
 */
function shared_shelf_link_config_form()
{
    $token = get_option('shared_shelf_link_token');

    include 'config_form.php';
}

/**
 * config callback.
 * 
 * Handles a submitted configuration form by setting options.
 * 
 * @return void
 */
function shared_shelf_link_config()
{
    set_option('shared_shelf_link_token', $_POST['shared_shelf_link_token']);
}

/**
 * define_acl callback.
 * 
 * Defines the plugin's access control list.
 * 
 * @param object $acl
 */
function shared_shelf_link_define_acl($acl)
{
    // Omeka_Acl_Resource is deprecated in 2.0.
    if (version_compare(OMEKA_VERSION, '2.0-dev', '<')) {
        $indexResource = new Omeka_Acl_Resource('SharedShelfLink_Index');
        $indexResource->add(array('index'));
        $publishingResource = new Omeka_Acl_Resource('SharedShelfLink_Publishing');
        $publishingResource->add(array('publish', 'collections'));
    } else {
        $indexResource = new Zend_Acl_Resource('SharedShelfLink_Index');
        $publishingResource = new Zend_Acl_Resource('SharedShelfLink_Publishing');
    }
    $acl->add($indexResource);
    $acl->add($publishingResource);
    $acl->allow(null, 'SharedShelfLink_Publishing', array('collections', 'publish'));
//    $acl->deny(null, 'SharedShelfLink_Index', array('show', 'add', 'edit', 'delete'));
}

/**
 * admin_navigation_main filter.
 * 
 * @param array $nav Array of main navigation tabs.
 * @return array Filtered array of main navigation tabs.
 */
function shared_shelf_link_admin_navigation_main($nav)
{
    if (has_permission('SharedShelfLink_Index', 'index')) {
        $nav['Shared Shelf Link'] = uri('shared-shelf-link');
    }
    return $nav;
}
