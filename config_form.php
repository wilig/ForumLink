<?php
/**
 * Configuration form include.
 * 
 * @package SharedShelfLink
 * @copyright Copyright (c) 2013 ARTstor, Inc
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>
<div class="field">
    <label for="shared_shelf_link_token">Authorization Token</label>
    <div class="inputs five columns omega">
        <?php echo get_view()->formText('shared_shelf_link_token', '<Your Token Here>', null);?>
        <p class="explanation">A authorization token/password that the Shared Shelf
        publisher will provide to authenticate each publishing request.  (Use password best practices.)</p>
    </div>
</div>
