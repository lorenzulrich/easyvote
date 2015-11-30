<?php

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * A view helper for creating Links to extbase actions within widets.
 *
 * = Examples =
 *
 * <code title="URI to the show-action of the current controller">
 * <f:widget.link action="show">link</f:widget.link>
 * </code>
 * <output>
 * <a href="index.php?id=123&tx_myextension_plugin[widgetIdentifier][action]=show&tx_myextension_plugin[widgetIdentifier][controller]=Standard&cHash=xyz">link</a>
 * (depending on the current page, widget and your TS configuration)
 * </output>
 * @api
 */
class Tx_Easyvote_ViewHelpers_Widget_LinkViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * Initialize arguments
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
        $this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
        $this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
        $this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
    }

    /**
     * Render the link.
     *
     * @param string $action Target action
     * @param array $arguments Arguments
     * @param array $additionalParams
     * @param string $additionalParamsPrefix
     * @param string $section The anchor to be added to the URI
     * @param string $format The requested format, e.g. ".html"
     * @param boolean $ajax TRUE if the URI should be to an AJAX widget, FALSE otherwise.
     * @return string The rendered link
     * @api
     */
    public function render($action = NULL, $arguments = array(), $additionalParams = array(), $additionalParamsPrefix = '', $section = '', $format = '', $ajax = FALSE)
    {
        if ($ajax === TRUE) {
            $uri = $this->getAjaxUri();
        } else {
            $uri = $this->getWidgetUri();
        }
        $this->tag->addAttribute('href', $uri);
        $this->tag->setContent($this->renderChildren());

        return $this->tag->render();
    }

    /**
     * Get the URI for an AJAX Request.
     *
     * @return string the AJAX URI
     */
    protected function getAjaxUri()
    {
        $action = $this->arguments['action'];
        $arguments = $this->arguments['arguments'];
        $additionalParams = $this->arguments['additionalParams'];
        $additionalParamsPrefix = $this->arguments['additionalParamsPrefix'];

        $arguments[$additionalParamsPrefix] = $additionalParams;

        if ($action === NULL) {
            $action = $this->controllerContext->getRequest()->getControllerActionName();
        }
        $arguments['id'] = $GLOBALS['TSFE']->id;
        // TODO page type should be configurable
        $arguments['type'] = 7076;
        $arguments['fluid-widget-id'] = $this->controllerContext->getRequest()->getWidgetContext()->getAjaxWidgetIdentifier();
        $arguments['action'] = $action;

        return '?' . http_build_query($arguments, NULL, '&');
    }

    /**
     * Get the URI for a non-AJAX Request.
     *
     * @return string the Widget URI
     */
    protected function getWidgetUri()
    {
        $uriBuilder = $this->controllerContext->getUriBuilder();

        $argumentPrefix = $this->controllerContext->getRequest()->getArgumentPrefix();
        $arguments = $this->hasArgument('arguments') ? $this->arguments['arguments'] : array();
        $additionalParams = $this->hasArgument('additionalParams') ? $this->arguments['additionalParams'] : array();
        $additionalParamsPrefix = $this->hasArgument('additionalParamsPrefix') ? $this->arguments['additionalParamsPrefix'] : '';
        if ($this->hasArgument('action')) {
            $arguments['action'] = $this->arguments['action'];
        }
        if ($this->hasArgument('format') && $this->arguments['format'] !== '') {
            $arguments['format'] = $this->arguments['format'];
        }
        return $uriBuilder
            ->reset()
            ->setArguments(array($argumentPrefix => $arguments, $additionalParamsPrefix => $additionalParams))
            ->setSection($this->arguments['section'])
            ->setAddQueryString(TRUE)
            ->setArgumentsToBeExcludedFromQueryString(array($argumentPrefix, 'cHash'))
            ->setFormat($this->arguments['format'])
            ->build();
    }
}