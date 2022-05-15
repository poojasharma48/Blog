<?php

namespace Drupal\blog\Commands;

use Drupal\Core\Database\Connection;
use Drupal\Core\Mail\MailManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 */
class NotificationCommand extends DrushCommands {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $injected_database;

  /**
  * The mail manager.
  *
  * @var \Drupal\Core\Mail\MailManagerInterface
  */

  protected $mailManager;

  public function __construct(Connection $injected_database, MailManagerInterface $mail_manager) {
    $this->injected_database = $injected_database;
    $this->mailManager = $mail_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('plugin.manager.mail')
    );
  }

  /**
   * Echo msg if notification sent otherwise msg will display.
   *
   * Argument provided to the drush command.
   
   * @command drush9_example:notificationSend
   * @aliases d9-ns
   * @usage drush9_example:notificationSend 
   *
   */
  public function notificationSend() {
    /*get first five rows whose notification_status = 0*/
    $select = $this->injected_database->select('blog_info', 'b');
    $select->fields('b',['nid']);
    $select->innerJoin('node_field_data', 'n' ,'n.nid = b.nid');
    $select->innerJoin('users_field_data', 'u' ,'u.uid = n.uid');
    $select->fields('u',['mail']);
    $select->condition('notification_status', 0, "=");
    $select->range(0,5);
    $blog_entries = $select->execute()->fetchAll();
    /*update first five rows whose notification_status = 0 to 1 and updated = current time as well send notification */
    foreach ($blog_entries as $blog_key => $blog_val){
      /*start code to send mail  notification*/
      $module = 'blog_mail';
      $key = 'blog_notify'; 
      $params['subject'] = t('Blog notification');
      $params['message'] = t('New Blog Content Added Successfully With Nid '.$blog_val->nid);
      $to = $blog_val->mail;
      $langcode = 'en';
      $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, TRUE);
      /*start code to update blog info row after notify*/
      $update = $this->injected_database->update('blog_info');
      $update->fields(['notification_status' => 1, 'updated' => time()]);
      $update->condition('nid', $blog_val->nid, '=');
      $update->execute();
    }
    if (count($blog_entries) > 0) {
      $this->output()->writeln('Notification sent successfully.');
    }else {
      $this->output()->writeln('No Blog exist with notification status 0');
    }
  }
}