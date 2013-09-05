<?php
/**
 * Plugin hooks and filters.
 * 
 * @package SharedShelfLink
 * @copyright Copyright (c) 2012 ARTstor, Inc.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

class SharedShelfLinkPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array('install',
        'uninstall',
        'upgrade',
        //'initialize',
//        'define_acl',
        //'define_routes',
        'config_form',
        'config');
        //'html_purifier_form_submission');

    /**
     * @var array Filters for the plugin.
     */
//    protected $_filters = array('admin_navigation_main');
        //'public_navigation_main', 'search_record_types', 'page_caching_whitelist',
        //'page_caching_blacklist_for_record',
        //'api_resources');

    /**
     * @var array Options and their default values.
     */
//    protected $_options = array(
//        'shared_shelf_link_plugin_version', SHARED_SHELF_LINK_PLUGIN_VERSION);

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        // Create the table.
        $db = $this->_db;

        /* Published records/items.
            id: primary key
            ss_id: the shared shelf id`
            item_id: the corresponding item id in `items`
            published: a timestamp of the publication date
        */
        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->shared_shelf_transfer_records` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `ss_id` int(10) unsigned NOT NULL,
        `collection_id` int(10) unsigned NOT NULL,
        `item_id` int(10) unsigned default NULL,
        `published` datetime default NULL,
        PRIMARY KEY  (`id`)
        ) ENGINE=InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $db->query($sql);

        $this->_installOptions();
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        // Drop the table.
        $db = $this->_db;
        $sql = "DROP TABLE IF EXISTS `$db->shared_shelf_transfer_records`";
        $db->query($sql);

        $this->_uninstallOptions();
    }

    /**
     * Upgrade the plugin.
     *
     * @param array $args contains: 'old_version' and 'new_version'
     */
    public function hookUpgrade($args)
    {
    }

    /**
     * Define the ACL.
     *
     * @param Omeka_Acl
     */
//    public function hookDefineAcl($args)
//    {
//        $acl = $args['acl'];
//
//        // Omeka_Acl_Resource is deprecated in 2.0.
//        $indexResource = new Zend_Acl_Resource('SharedShelfLink_Index');
//        $publishingResource = new Zend_Acl_Resource('SharedShelfLink_Publishing');
//        $acl->add($indexResource);
//        $acl->add($publishingResource);
//        $acl->allow(null, 'SharedShelfLink_Publishing', array('collections', 'publish'));
//    }

    /**
     * Display the plugin config form.
     */
    public function hookConfigForm()
    {
        require dirname(__FILE__) . '/config_form.php';
    }

    /**
     * Set the options from the config form input.
     */
    public function hookConfig()
    {
        set_option('shared_shelf_link_token', $_POST['shared_shelf_link_token']);
    }

//    /**
//     * Filter the 'text' field of the simple-pages form, but only if the
//     * 'simple_pages_filter_page_content' setting has been enabled from within the
//     * configuration form.
//     *
//     * @param array $args Hook args, contains:
//     *  'request': Zend_Controller_Request_Http
//     *  'purifier': HTMLPurifier
//     */
//    public function hookHtmlPurifierFormSubmission($args)
//    {
//        $request = Zend_Controller_Front::getInstance()->getRequest();
//        $purifier = $args['purifier'];
//
//        // If we aren't editing or adding a page in SimplePages, don't do anything.
//        if ($request->getModuleName() != 'simple-pages' or !in_array($request->getActionName(), array('edit', 'add'))) {
//            return;
//        }
//
//        // Do not filter HTML for the request unless this configuration directive is on.
//        if (!get_option('simple_pages_filter_page_content')) {
//            return;
//        }
//
//        $post = $request->getPost();
//        $post['text'] = $purifier->purify($post['text']);
//        $request->setPost($post);
//    }

    /**
     * Add the Simple Pages link to the admin main navigation.
     *
     * @param array Navigation array.
     * @return array Filtered navigation array.
     */
//    public function filterAdminNavigationMain($nav)
//    {
//        $nav[] = array(
//            'label' => __('Shared Shelf Link'),
//            'uri' => url('shared-shelf-link'),
//            'resource' => 'SharedShelfLink_Index',
//            'privilege' => 'browse'
//        );
//        return $nav;
//    }

}










































/** Path to plugin directory */
//define('SHARED_SHELF_LINK_PLUGIN_DIRECTORY', dirname(__FILE__));


/** Plugin hooks
add_plugin_hook('install', 'shared_shelf_link_install');
add_plugin_hook('uninstall', 'shared_shelf_link_uninstall');
add_plugin_hook('config_form', 'shared_shelf_link_config_form');
add_plugin_hook('config', 'shared_shelf_link_config');
add_plugin_hook('define_acl', 'shared_shelf_link_define_acl');
*/

