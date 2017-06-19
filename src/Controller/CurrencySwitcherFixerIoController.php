<?php
/**
 * @file
 * Contains \Drupal\currency_switcher_fixer_io\Controller\CurrencySwitcherFixerIoController.
 */

namespace Drupal\currency_switcher_fixer_io\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Html;

/**
 * Controller for Currency switcher fixer io.
 */
class CurrencySwitcherFixerIoController extends ControllerBase {

    /**
     * The form builder service.
     *
     * @var \Drupal\Core\Form\FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * The Drupal configuration factory.
     *
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     *
     * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
     *   The form builder service.
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The configuration factory holding resource settings.
     */
    public function __construct(FormBuilderInterface $form_builder, ConfigFactoryInterface $config_factory) {
        $this->formBuilder = $form_builder;
        $this->configFactory = $config_factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('form_builder'),
            $container->get('config.factory')
        );
    }

    /**
     * Constructs a list of result.
     */
    public function resultList() {
        $build['currency_switcher_fixer_io_form'] = $this->formBuilder
            ->getForm('Drupal\currency_switcher_fixer_io\Form\CurrencySwitcherFixerIoForm');
        $rows = array();
        $result_list = $this->configFactory->get('currency_switcher_fixer_io.currency')->get('currency');
        $caption = $this->t('The value of currencies is equivalent to ') . $result_list['base'] . $this->t(' at ') . $result_list['date'];
        $header = array(t('Currency'), t('Value'));
        if (!empty($result_list)) {
            foreach ($result_list['rates'] as $key => $value) {
                $data['currency'] = $key;
                $data['value'] = Html::escape($value);
                $rows[] = $data;
            }
        }
        $build['currency_switcher_fixer_io_table'] = array(
            '#type' => 'table',
            '#caption' => $caption,
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => $this->t('Not one currency is not selected.'),
        );
        return $build;
    }

}
