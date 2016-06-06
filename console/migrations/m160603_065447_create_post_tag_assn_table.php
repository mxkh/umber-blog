<?php

use yii\db\Migration;

/**
 * Handles the creation for table `post_tag_assn_table`.
 */
class m160603_065447_create_post_tag_assn_table extends Migration
{
    /**
     * @var string
     */
    protected $tableName = '{{%post_tag_assn}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('', $this->tableName, ['post_id', 'tag_id']);

        $this->addForeignKey('post_tag_assn_fk1', $this->tableName, 'post_id', '{{%post}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('post_tag_assn_fk2', $this->tableName, 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('post_tag_assn_fk1', $this->tableName);
        $this->dropForeignKey('post_tag_assn_fk1', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
