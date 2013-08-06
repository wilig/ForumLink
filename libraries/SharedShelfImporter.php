<?php
/**
 * @package SharedShelfLink
 * @subpackage Libraries
 * @copyright Copyright (c) 2012 ARTstor, Inc
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Shared Shelf Record Importer
 *
 * @package SharedShelfLink
 * @subpackage Libraries
 */
class SharedShelfImporter
{

    public function createOrUpdateItem($data, $files)
    {
        $json = Zend_Json::decode($data['data']);
        $record = $this->_makeRecord($data['_collection_id'], $json, $files);
        $timestamp = date_parse($data['_publication_date']);
        if(($item = $this->_findLinkedItem($data['_ss_id'], $data['_collection_id']))) {
            $id = $item->id;
            release_object($item); // Not sure if this is required.  Possible resource leak if not called.
            $item = $this->_updateItem($id, $record['elementTexts']);
            $this->_updatePublicationDate($data['_ss_id'], $data['_collection_id'], $timestamp);
        } else {
            $item = $this->insertItem($record['itemMetadata'], $record['elementTexts'], $record['fileMetadata']);
            $this->_recordPublication($data['_ss_id'], $data['_collection_id'], $item->id, $timestamp);
        }
        release_object($item);
        release_object($record);
        return true;
    }

    private function _findLinkedItem($id, $collection_id)
    {
        $record = get_db()->getTable('SharedShelfTransferRecord')->findBySharedShelfIdAndCollectionId((int)$id, (int)$collection_id);
        if($record) {
            $item = get_db()->getTable('Item')->find($record->item_id);
            release_object($record);
            return $item;
        }
    }

    private function _updateItem($id, $elementTexts)
    {
        return update_item($id, array('overwriteElementTexts' => true), $elementTexts);
    }

    /**
     * Convenience method for inserting an item and its files.
     *
     * Method used by map writers that encapsulates item and file insertion.
     * Items are inserted first, then files are inserted individually. This is
     * done so Item and File objects can be released from memory, avoiding
     * memory allocation issues.
     *
     * @see insert_item()
     * @see insert_files_for_item()
     * @param mixed $metadata Item metadata
     * @param mixed $elementTexts The item's element texts
     * @param mixed $fileMetadata The item's file metadata
     * @return true
     */
    private function insertItem($metadata = array(), $elementTexts = array(), $fileMetadata = array())
    {
        // Insert the item.
        $item = insert_item($metadata, $elementTexts);

        // If there are files, insert one file at a time so the file objects can
        // be released individually.
        if (isset($fileMetadata['files'])) {
            // The default file transfer type is URL.
            $fileTransferType = isset($fileMetadata['file_transfer_type'])
                ? $fileMetadata['file_transfer_type']
                : 'Url';

            // The default option is ignore invalid files.
            $fileOptions = isset($fileMetadata['file_ingest_options'])
                ? $fileMetadata['file_ingest_options']
                : array('ignore_invalid_files' => true);

            // Prepare the files value for one-file-at-a-time iteration.
            $files = array($fileMetadata['files']);
            foreach ($files as $file) {
                $file = insert_files_for_item($item, $fileTransferType, $file, $fileOptions);
                // Release the File object from memory.
                release_object($file);
            }

        }
        return $item;
    }

    private function _recordPublication($ss_id, $collection_id, $item_id, $timestamp)
    {
        $record = new SharedShelfTransferRecord;

        $record->ss_id         = $ss_id;
        $record->collection_id = $collection_id;
        $record->item_id       = $item_id;
        $record->published     = (string) $timestamp;
        $record->save();

        release_object($record);
    }

    private function _updatePublicationDate($ss_id, $collection_id, $timestamp)
    {
        $record = get_db()->getTable('SharedShelfTransferRecord')->findBySharedShelfIdAndCollectionId((int)$ss_id, (int)$collection_id);
        $record->published = $timestamp;
        $record->save();

        release_object($record);
    }

    private function _findCollection($collection_id)
    {
        return get_db()->getTable('Collection')->find((int)$collection_id);
    }


    private function _makeRecord($collection_id, $data, $files)
    {
        $collection = $this->_findCollection($collection_id);
        $itemMetadata = array('collection_id' => $collection->id,
            'public'        => true,
            'featured'      => false);

        $fileMetadata = array();
        $elementTexts = array();
        $elements = array('contributor', 'coverage', 'creator',
            'date', 'description', 'format',
            'identifier', 'language', 'publisher',
            'relation', 'rights', 'source',
            'subject', 'title', 'type');
        foreach ($elements as $element) {
            if (array_key_exists($element, $data)) {
                foreach($data[$element] as $t) {
                    $elementTexts['Dublin Core'][ucwords($element)][] = array('text' => (string) $t, 'html' => false);
                }
            }
        }

        if (array_key_exists('_image_file', $files)) {
            $fileMetadata['file_transfer_type'] = 'Upload';
            $fileMetadata['files'] = '_image_file';
        }
        //release_object($collection);
        return array('itemMetadata' => $itemMetadata,
            'elementTexts' => $elementTexts,
            'fileMetadata' => $fileMetadata);
    }

}
