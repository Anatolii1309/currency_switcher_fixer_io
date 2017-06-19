<?php
/**
 * @file
 * Contains \Drupal\currency_switcher_fixer_io\Form\CurrencySwitcherFixerIoForm.
 */

namespace Drupal\currency_switcher_fixer_io\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\currency_switcher_fixer_io\CurrencySwitcherFixerIoInterface;

/**
 * Controller location for Currency Switcher Fixer Io Settings Form.
 */
class CurrencySwitcherFixerIoForm extends ConfigFormBase {

    /**
     * The Drupal configuration factory.
     *
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * The Currency Switcher Fixer Io control.
     *
     * @var Drupal\currency_switcher_fixer_io\CurrencySwitcherFixerIoInterface
     */
    protected $currencySwitcherFixerIo;

    /**
     * Constructs a Choose currency form object.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The configuration factory holding resource settings.
     * @param Drupal\currency_switcher_fixer_io\CurrencySwitcherFixerIoInterface $currency_switcher_fixer_io
     */
    public function __construct(ConfigFactoryInterface $config_factory, CurrencySwitcherFixerIoInterface $currency_switcher_fixer_io) {
        $this->configFactory = $config_factory;
        $this->currencySwitcherFixerIo = $currency_switcher_fixer_io;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('config.factory'),
            $container->get('currency_switcher_fixer_io.controller')
        );
    }

    /**
     * Implements \Drupal\Core\Form\FormInterface::getFormID().
     */
    public function getFormId() {
        return 'currency_switcher_fixer_io_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'currency_switcher_fixer_io.settings',
            'currency_switcher_fixer_io.currency',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $settings = $this->configFactory->get('currency_switcher_fixer_io.settings')->get('settings');
        $form['settings'] = array(
            '#type' => 'select',
            '#multiple' => TRUE,
            '#size' => 10,
            '#title' => 'Currency',
            '#options' =>
                array(
                    'AUD' => t('AUD'),
                    'BGN' => t('BGN'),
                    'BRL' => t('BRL'),
                    'CAD' => t('CAD'),
                    'CHF' => t('CHF'),
                    'CNY' => t('CNY'),
                    'CZK' => t('CZK'),
                    'DKK' => t('DKK'),
                    'GBP' => t('GBP'),
                    'HKD' => t('HKD'),
                    'HRK' => t('HRK'),
                    'HUF' => t('HUF'),
                    'IDR' => t('IDR'),
                    'ILS' => t('ILS'),
                    'INR' => t('INR'),
                    'JPY' => t('JPY'),
                    'KRW' => t('KRW'),
                    'MXN' => t('MXN'),
                    'MYR' => t('MYR'),
                    'NOK' => t('NOK'),
                    'NZD' => t('NZD'),
                    'PHP' => t('PHP'),
                    'PLN' => t('PLN'),
                    'RON' => t('RON'),
                    'RUB' => t('RUB'),
                    'SEK' => t('SEK'),
                    'SGD' => t('SGD'),
                    'THB' => t('THB'),
                    'TRY' => t('TRY'),
                    'USD' => t('USD'),
                    'ZAR' => t('ZAR'),
                ),
            '#default_value' => $settings,
            '#description' => t('Currency which will be used by default'),
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $form_value = $form_state->getValue('settings');
        foreach ($form_value as $values) {
            $currency_value [] = $values;
        }
        $options = implode(",", $currency_value);
        $output = $this->currencySwitcherFixerIo->currencyCheck($options);
        if (!empty($output)) {
            if (isset($output['base']) && !empty($output['base'])) {
                $base = $output;
                $this->config('currency_switcher_fixer_io.currency')
                    ->set('currency', $base)
                    ->save();
            }
         }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_value = $form_state->getValue('settings');
        foreach ($form_value as $values) {
            $currency_value [] = $values;
        }
        $this->config('currency_switcher_fixer_io.settings')
            ->set('settings', $currency_value)
            ->save();
        parent::submitForm($form, $form_state);
    }

}
