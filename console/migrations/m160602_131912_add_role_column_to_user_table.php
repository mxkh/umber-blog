<?php

use yii\db\Migration;

/**
 * Handles adding role_column to table `user_table`.
 */
class m160602_131912_add_role_column_to_user_table extends Migration
{
    /**
     * @var string
     */
    protected $tableName = '{{%user}}';

    /**
     * @inheritdoc
     */
    public function up()
    {

        $this->addColumn($this->tableName, 'role', 'smallint(6) NOT NULL AFTER `status`');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->tableName, 'role');
    }
}
