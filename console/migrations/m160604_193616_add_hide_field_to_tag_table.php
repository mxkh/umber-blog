<?php

use yii\db\Migration;

/**
 * Handles adding hide_field to table `tag_table`.
 */
class m160604_193616_add_hide_field_to_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%tag}}', 'hide', 'smallint(6) NOT NULL DEFAULT 0 AFTER `frequency`');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%tag}}', 'hide');
    }
}
