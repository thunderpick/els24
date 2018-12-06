<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/** @var $entry DirectoryIterator */
?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th colspan="3" style="border-right: 0px">Листинг директории <?php echo $_SERVER['DOCUMENT_ROOT']; ?></th>
        <th style="border-left: 0px;">
            <a href="<?php echo Url::to('/readdir/refresh') ?>" class="btn btn-xs btn-success pull-right"><span class="glyphicon glyphicon-refresh"></span> Обновить</a>
        </th>
    </tr>
    <tr>
        <th>Name</th>
        <th>Size</th>
        <th>Type</th>
        <th>Last modified</th>
    </tr>
    </thead>
    <?php foreach ($data as $entry): ?>
    <tbody>
        <tr>
            <td>
                <?php echo $entry['name'] ?>
            </td>
            <td>
                <?php $size = $entry['size']; ?>

                <?php if(!$entry['is_dir']): ?>
                    <?php printf('%.0f KB', $size); ?>
                <?php else: ?>
                    [DIR]
                <?php endif; ?>
            </td>
            <td>
                <?php if(!$entry['is_dir']): ?>
                    <?php echo $entry['type']; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $entry['ctime'] ?>
            </td>
        </tr>
    </tbody>
    <?php endforeach; ?>
</table>
