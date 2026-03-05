<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117013600 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ($schema->hasTable('dtb_mail_template')) {
            $templateId = $this->connection->fetchColumn('SELECT MAX(id) FROM dtb_mail_template');

            $templateId++;
            $this->addSql("INSERT INTO dtb_mail_template (
                id, creator_id, name, file_name, mail_subject, create_date, update_date, discriminator_type
            ) VALUES(
                $templateId, NULL, 'ご注文承諾メール', 'Mail/order-accept.twig', 'ご注文確定のお知らせ', '2022-01-17 01:15:03', '2022-01-17 01:15:03', 'mailtemplate'
            )");

        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        if ($schema->hasTable('dtb_mail_template')) {
            $this->addSql('DELETE FROM dtb_mail_template WHERE name = "ご注文承諾メール" AND file_name = "Mail/order-accept.twig"');
        }
    }
}
