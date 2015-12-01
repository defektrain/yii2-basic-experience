<?php

use yii\db\Migration;

class m151201_200500_init_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date_create' => $this->integer()->notNull(),
            'date_update' => $this->integer()->notNull(),
            'preview' => $this->text()->notNull(),
            'date' => $this->date()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('author_id', '{{%books}}', 'author_id');
        $this->addForeignKey('books_ibfk_1', '{{%books}}', 'author_id', '{{%authors}}', 'id', 'CASCADE', 'CASCADE');

        $this->batchInsert('{{%authors}}', ['firstname', 'lastname'], [
                ['Герберт', 'Уэллс'],
                ['Сью', 'Таунсенд'],
                ['Лев', 'Толстой'],
                ['Александр', 'Пушкин'],
                ['Иван', 'Тургенев'],
                ['Николай', 'Гоголь'],
                ['Владимир', 'Даль'],
                ['Антон', 'Чехов'],
                ['Иван', 'Бунин'],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%authors}}');
        $this->dropTable('{{%books}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
