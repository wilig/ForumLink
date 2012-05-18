<?php
/**
 * Configuration form include.
 * 
 * @package OaipmhHarvester
 * @subpackage Views
 * @copyright Copyright (c) 2009 Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>
<div class="field">
    <label for="shared_shelf_link_token">Authorization Token</label>
    <div class="inputs">
        <?php echo __v()->formText('shared_shelf_link_token', $token, null);?>
        <p class="explanation">A authorization token/password that the Shared Shelf
        publisher will provide to authenticate each publishing request.</p>
    </div>
</div>
