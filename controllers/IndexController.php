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

class SharedShelfLink_IndexController extends Omeka_Controller_Action
{
    /**
     * Show a list of recent transfers from Shared Shelf to Omeka.
     *
     * @return void
     */
    public function indexAction()
    {
        $transfers = $this->getTable('SharedShelfTransferRecord')->findAllTransfers();
        $this->view->transfers = $transfers;
    }

}