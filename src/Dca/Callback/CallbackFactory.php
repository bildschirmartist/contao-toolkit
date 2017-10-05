<?php

/**
 * Contao toolkit.
 *
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2017 netzmacht David Molineus.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-toolkit/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Dca\Callback;

use Contao\Controller;
use Contao\Input;
use Contao\System;
use Netzmacht\Contao\Toolkit\Data\Alias\AliasGenerator;
use Netzmacht\Contao\Toolkit\Dca\Callback\Button\StateButtonCallback;
use Netzmacht\Contao\Toolkit\Dca\Callback\Save\GenerateAliasCallback;
use Netzmacht\Contao\Toolkit\Dca\Callback\Wizard\ColorPicker;
use Netzmacht\Contao\Toolkit\Dca\Callback\Wizard\FilePicker;
use Netzmacht\Contao\Toolkit\Dca\Callback\Wizard\PagePicker;
use Netzmacht\Contao\Toolkit\Dca\Callback\Wizard\PopupWizard;

/**
 * Class CallbackFactory.
 *
 * @package Netzmacht\Contao\Toolkit\Dca
 */
final class CallbackFactory
{
    /**
     * Create templates callback.
     *
     * @param string     $prefix  Template prefix to return only templates beginning with a filter.
     * @param array|null $exclude Exclude a set of template files.
     *
     * @return \Closure
     */
    public static function getTemplates(string $prefix = '', array $exclude = null): \Closure
    {
        return function () use ($prefix, $exclude) {
            $templates = Controller::getTemplateGroup($prefix);

            if (empty($exclude)) {
                return $templates;
            }

            return array_diff($templates, $exclude);
        };
    }

    /**
     * Create the state button toggle callback.
     *
     * @param string      $dataContainerName Data Contaienr name.
     * @param string      $column            State column.
     * @param string|null $disabledIcon      Optional disabled icon.
     * @param bool        $inverse           If true the state value gets inversed.
     *
     * @return StateButtonCallback
     */
    public static function stateButton(
        string $dataContainerName,
        string $column,
        ?string $disabledIcon = null,
        bool $inverse = false
    ): StateButtonCallback {
        $container = System::getContainer();

        return new StateButtonCallback(
            $container->get('contao.framework')->getAdapter(Input::class),
            $container->get('netzmacht.contao_toolkit.data.database_row_updater'),
            $dataContainerName,
            $column,
            $disabledIcon,
            $inverse
        );
    }

    /**
     * Create a callback by fetching a service.
     *
     * @param string $serviceName Name of the service.
     * @param string $methodName  Callback method name.
     *
     * @return \Closure
     */
    public static function service(string $serviceName, string $methodName): \Closure
    {
        return function () use ($serviceName, $methodName) {
            $service = System::getContainer()->get($serviceName);

            return call_user_func_array([$service, $methodName], func_get_args());
        };
    }

    /**
     * Create the color picker callback.
     *
     * @param bool        $replaceHex Replace hex char of rgb notation.
     * @param string|null $template   Template name.
     *
     * @return ColorPicker
     */
    public static function colorPicker(bool $replaceHex = false, string $template = null): ColorPicker
    {
        $container = System::getContainer();

        return new ColorPicker(
            $container->get('netzmacht.contao_toolkit.view.template_factory'),
            $container->get('translator'),
            $container->get('contao.framework')->getAdapter(Input::class),
            $replaceHex,
            $template
        );
    }

    /**
     * Create the file picker callback.
     *
     * @param string|null $template Template name.
     *
     * @return FilePicker
     */
    public static function filePicker(string $template = null)
    {
        $container = System::getContainer();

        return new FilePicker(
            $container->get('netzmacht.contao_toolkit.view.template_factory'),
            $container->get('translator'),
            $container->get('contao.framework')->getAdapter(Input::class),
            $template
        );
    }

    /**
     * Create the page picker callback.
     *
     * @param string|null $template Template name.
     *
     * @return PagePicker
     */
    public static function pagePicker(string $template = null): PagePicker
    {
        $container = System::getContainer();

        return new PagePicker(
            $container->get('netzmacht.contao_toolkit.view.template_factory'),
            $container->get('translator'),
            $container->get('contao.framework')->getAdapter(Input::class),
            $template
        );
    }

    /**
     * Create the popup wizard.
     *
     * @param string      $href        Link href snippet.
     * @param string      $label       Button label.
     * @param string      $title       Button title.
     * @param string      $icon        Button icon.
     * @param bool        $always      If true the button is generated always no matter if an value is given.
     * @param string      $linkPattern Link pattern.
     * @param string|null $template    Template name.
     *
     * @return PopupWizard
     */
    public static function popupWizard(
        string $href,
        string $label,
        string $title,
        string $icon,
        bool $always = false,
        string $linkPattern = null,
        ?string $template = null
    ): PopupWizard {
        $container = System::getContainer();

        return new PopupWizard(
            $container->get('netzmacht.contao_toolkit.view.template_factory'),
            $container->get('translator'),
            $container->get('security.csrf.token_manager'),
            $container->getParameter('contao.csrf_token_name'),
            $href,
            $label,
            $title,
            $icon,
            $always,
            $linkPattern,
            $template
        );
    }

    /**
     * Generate the alias generator callback.
     *
     * @param string      $dataContainerName Data Container name.
     * @param string      $aliasField        Alias field.
     * @param array       $fields            List of fields being combined as alias. If empty ['id'] is used.
     * @param string|null $factoryService    Custom alias generator factory service.
     *
     * @return GenerateAliasCallback
     */
    public static function aliasGenerator(
        string $dataContainerName,
        string $aliasField,
        array $fields = null,
        ?string $factoryService = null
    ): GenerateAliasCallback {
        $container      = Controller::getContainer();
        $factoryService = $factoryService ?: 'netzmacht.contao_toolkit.data.alias_generator.factory.default_factory';
        $factory        = $container->get($factoryService);
        $fields         = $fields ?: ['id'];

        /** @var AliasGenerator $aliasGenerator */
        $aliasGenerator = $factory($dataContainerName, $aliasField, $fields);

        return new GenerateAliasCallback($aliasGenerator);
    }
}
