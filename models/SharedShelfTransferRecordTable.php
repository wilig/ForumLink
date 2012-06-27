<?php
/**
 * @package SharedShelfLink
 * @subpackage Models
 * @copyright Copyright (c) 2012 ARTstor, Inc.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Model class for a record table.
 *
 * @package SharedShelfLink
 * @subpackage Models
 */
class SharedShelfTransferRecordTable extends Omeka_Db_Table
{
    /**
     * Return records by harvest ID.
     *
     * @param int $harvsetId
     * @return array An array of OaipmhHarvesterRecord objects.
     */
    public function findBySharedShelfIdAndCollectionId($ss_id, $collection_id)
    {
        $select = $this->getSelect();
        $select->where('ss_id = ? AND collection_id = ?');
        return $this->fetchObject($select, array($ss_id, $collection_id));
    }

    public function findByItemId($item_id)
    {
        $select = $this->getSelect();
        $select->where('item_id = ?');
        return $this->fetchObject($select, array($item_id));
    }

    public function findAllTransfers()
    {
        $select = $this->getSelect()->order('id');
        return $this->fetchObjects($select);
    }
}