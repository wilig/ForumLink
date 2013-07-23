<?php
/**
 * @package SharedShelfLink
 * @subpackage Models
 * @copyright Copyright 2012 ARTstor, Inc.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Model class for a record.
 *
 * @package SharedShelfLink
 * @subpackage Models
 */
class SharedShelfTransferRecord extends Omeka_Record_AbstractRecord
{
    public $id;
    public $ss_id;
    public $collection_id;
    public $item_id;
    public $published;
}