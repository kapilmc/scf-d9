<?php
/**
 * @file
 * Contains \Drupal\cdh_page\Form\cdh_page_involved_form.
 */
namespace Drupal\cdh_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


/**
 * Class for cdh_page_involved_form Config Form.
 */
class cdh_page_involved_form extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'cdh_page_involved_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() 
    {
        return ['cdh_page_involved.settings'];
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cdh_page_involved.settings');
        // $form['items']['#tree'] = true;
        $form['cdh_page_involved_title'] = array(
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#default_value' => $config->get('cdh_page_involved_title'),
        '#weight' => -1,
        );

        // if (empty($form_state['num_rows'])) {
        //     $form_state['num_rows'] = count($data);
        // }
        // for ($i = 0; $i < $form_state['num_rows']; $i++) {
        //     $item = isset($data[$i]) ? $data[$i] : null;
        //     $item_id = isset($item) ? $item['id'] : 0;
        //     $attr = (count($data) > $i) ? ['readonly' => 'readonly'] : [];
        //     $remove_path = 'node/' . $item_id . '/edit';
        //     $remove_link = l('Edit', $remove_path, array('query' => array('destination' => $detstination)));
        //     $links = '';
        //     $links = '<div class="cdh-links">' . $remove_link . '</div>';

            $form['items'] = [
            'project' => [
              '#type' => 'textfield',
              '#default_value' => isset($item) ? $item['title'] : '',
              '#size' => 60,
              '#maxlength' => 255,
              '#required' => false,
              // '#attributes' => $attr,
            ],
            'status' => [
              '#type' => 'item',
              '#markup' => isset($item['status']) && $item['status'] == 1 ? 'Yes' : 'No',
              #markup' => '<h2>' . $node->get('field_price')->getString() . '$</h2>',
            ],
            'links' => [
              '#type' => 'item',
              // '#markup' => $links,
            ],
            'id' => [
              '#type' => 'hidden',
              // '#default_value' => $item['id'],
            ],
            'weight' => [
              '#type' => 'weight',
              '#title' => t('Weight'),
              // '#default_value' => $weight,
              '#delta' => 50,
              '#title_display' => 'invisible',
            ],
            ];
            // $weight++;

            // $form['actions'] = array('#type' => 'actions');
            $form['save'] = array(
            '#type' => 'submit',
            '#value' => t('Save Changes'),
            // '#submit' => array('_cdh_involved_form_save'),
            );
            $form['actions']['add_project'] = array(
            '#type' => 'submit',
            '#value' => t('Add Involved Video'),
            '#submit' => array([$this,'_add_involved_video']),
            );
            return $form;
    }      
 



/**
 *
 */
function _add_involved_video()
{

  dd('vsdvs');

  // return new RedirectResponse(\Drupal\Core\Url::fromRoute('user.page')->toString());
  drupal_goto('node/add/cdh-video', array('query' => array('destination' => 'admin/cdh-page/involved-videos')));
}








    public function validateForm(array&$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
      $val= $form_state->getValues();
      dd($val);
        // foreach ($form_state->getValues() as $key => $value) {
        //     $this->config('CdhPageInvolved.settings')
        //         ->set($key, $value)->save();

        //     parent::submitForm($form, $form_state);
        // }

        foreach ($form_state['values']['items'] as $id => $item) {
          $nid = isset($item['id']) ? $item['id'] : 0;
          if ($nid > 0) {
            db_merge('cdh_page_involved_ordering')
              ->key(array('nid' => $nid))
              ->fields(array(
                'nid' => $nid,
                'weight' => $item['weight'],
              ))
              ->execute();
          }
        }
        \Drupal::messenger()->addMessage(t('Changes have been saved successfully.'), $type = 'status');

    }
}

