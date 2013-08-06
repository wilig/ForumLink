<?php
/**
 * @package SharedShelfLink
 * @subpackage Controllers
 * @copyright Copyright (c) 2012 ARTstor, Inc.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Index controller
 *
 * @package SharedShelfLink
 * @subpackage Controllers
 */


class SharedShelfLink_PublishingController extends Omeka_Controller_AbstractActionController
{
    /**
     * Handle the publishing request from Shared Shelf.
     *
     * @return void
     */
    public function publishAction()
    {
        // Bypass ACL security on publication processing.
        //Omeka_Context::getInstance()->acl = null;

        $token = get_option('shared_shelf_link_token');
        if ($token == $_POST['__token']) {
            new SharedShelfImporter();
            $publication = new SharedShelfImporter;
            $publication->createOrUpdateItem($_POST, $_FILES);
            $this->_helper->json(array('success' => true));
        } else {
            $this->_helper->json(array('success' => false));
        }
    }

    public function collectionsAction()
    {
        // Bypass ACL security on collections listing.
        //Omeka_Context::getInstance()->acl = null;
        $db = $this->_helper->db->getDb();
        $token = get_option('shared_shelf_link_token');
        if ($token == $_GET['__token']) {
            $collections = $db->getTable('Collection')->findAll();
            $collections_json = array();
            foreach($collections as $collection) {
                $collections_json[] = array('id' => $collection->id,
                    'name' => strip_formatting(metadata($collection, array('Dublin Core', 'Title'))));
            }
            $this->_helper->json($collections_json);
        } else {
            throw new Omeka_Controller_Exception_403();
        }
    }


}