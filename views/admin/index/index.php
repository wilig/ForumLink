<?php
/**
 * Admin index view.
 *
 * @package SharedShelfLink
 * @subpackage Views
 * @copyright Copyright (c) 2012 ARTstor, Inc.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

$head = array('body_class' => 'shared-shelf-link primary',
    'title'      => 'Shared Shelf Link');
head($head);
?>

<h1><?php echo $head['title']; ?></h1>

<div id="primary">

    <?php echo flash(); ?>

    <h2>Recent Published Items</h2>

    <?php if (empty($this->transfers)): ?>

    <p>There are no published items yet.</p>

    <?php else: ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Shared Shelf ID</th>
            <th>Timestamp</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($this->transfers as $transfer): ?>
        <tr>
            <td><?php echo $transfer->id; ?></td>
            <td><?php echo $transfer->ss_id; ?></td>
            <td><?php echo $$transfer->published; ?></td>
        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>

<?php foot(); ?>
