<?php

use yii\db\Migration;

/**
 * Handles the creation of table `filesystem`.
 */
class m181206_104003_create_filesystem_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('filesystem', [
            'id'    => $this->primaryKey(),
            'name'  => $this->string(255),
            'size'  => $this->integer(11),
            'type'  => $this->string(255),
            'ctime' => $this->timestamp(),
            'path'  => $this->string(255),
            'is_dir'=> $this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('filesystem');
    }
}
