<?php

namespace Drupal\blog\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;


class AddBlogForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_blog_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Description'),
      '#rows' => 8,
      '#cols' => 46,
      '#format'=> 'full_html',
      '#required' => TRUE,
    ];

     $form['read_more'] = [
      '#type' => 'url',
      '#title' => $this->t('Read More'),
      '#required' => TRUE,
      '#description' => t('This must be an external URL such as http://example.com'),
    ];

    $form['blog_image'] = [        
      '#type' => 'managed_file',    
      '#title' => t('Upload Blog Image'),
      '#description' => t('One file only 250 KB limit and Images larger than 200x350 pixels will be resized.'),
      '#upload_validators' => [       
        'file_validate_size' => array(1024*250),   
        'file_validate_image_resolution' => array('200x350'),
      ],    
      '#upload_location' => 'public://',
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $url = $form_state->getValue('read_more');
    if (strpos($url, $_SERVER['HTTP_HOST']) > 0){
     $form_state->setErrorByName('read_more', t('Add External Domain Link '.$url.' is Internal Domain Link.'));
    }
  }  
 
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $description = $form_state->getValue('description');
    $blog_image = $form_state->getValue('blog_image');
    $read_more_link = $form_state->getValue('read_more');

    $node = Node::create([
      'type' => 'blog',
      'title' => $title,
      'body' => [
        'value'=> $description['value'],
        'format'=> $description['format']
      ],
      'field_blog_img' => [
        'target_id' => $blog_image[0]
      ],
      'field_read_more' => [
        'uri'=> $read_more_link
      ],
    ]);
    $node->save();

    drupal_set_message($this->t('Blog Content Added.'));
     
  }

}