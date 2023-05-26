<?php
namespace Drupal\scfp_autofill\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
/**
 * Class ScfpAutofillDeleteForm.
 *
 * @package Drupal\scfp_autofill\Form
 */
class ScfpAutofillDeleteForm extends ConfirmFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'ScfpAutofillDeleteForm';
    }
    public $cid;
    public function getQuestion()
    { 
        return t('Are you sure you want to delete %cid?', array('%cid' => $this->cid));
    }
    public function getCancelUrl()
    {
        return new Url('scfp_autofill.openid_connect_auto_login');
    }
    public function getDescription()
    {
        return t('This action cannot be undone.');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return t('Delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return t('Cancel');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $id = null)
    {

        $this->id = $id;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
         $query = \Drupal::database();
         $query->delete('scfp_autofill')
             ->condition('id', $this->id)
             ->execute();
             \Drupal::messenger()->addStatus($this->t(' succesfully deleted'));
            $form_state->setRedirect('scfp_autofill.openid_connect_auto_login');
    }
}