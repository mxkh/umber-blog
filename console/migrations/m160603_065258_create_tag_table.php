<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tag_table`.
 */
class m160603_065258_create_tag_table extends Migration
{
    /**
     * @var string
     */
    protected $tableName = '{{%tag}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'frequency' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
