<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_post`.
 */
class m160603_061149_create_table_post extends Migration
{
    /**
     * @var string
     */
    protected $tableName = '{{%post}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'title' => $this->string(150)->notNull(),
            'slug' => $this->string(250)->notNull(),
            'text' => $this->text()->notNull(),
            'hide' => $this->smallInteger()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('post_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('pk1', '{{%post}}');
        $this->dropTable($this->tableName);
    }
}
