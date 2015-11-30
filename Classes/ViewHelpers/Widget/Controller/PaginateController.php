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

use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 * Class Tx_Easyvote_ViewHelpers_Widget_Controller_PaginateController
 */
class Tx_Easyvote_ViewHelpers_Widget_Controller_PaginateController extends AbstractWidgetController
{

    /**
     * @var array
     */
    protected $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'maximumNumberOfLinks' => 99);

    /**
     * @var Tx_Extbase_Persistence_QueryResultInterface
     */
    protected $objects;

    /**
     * @var array
     */
    protected $additionalParams;

    /**
     * @var string
     */
    protected $additionalParamsPrefix;

    /**
     * @var integer
     */
    protected $currentPage = 1;

    /**
     * @var integer
     */
    protected $maximumNumberOfLinks = 99;

    /**
     * @var integer
     */
    protected $numberOfPages = 1;

    /**
     * @return void
     */
    public function initializeAction()
    {
        $this->objects = $this->widgetConfiguration['objects'];
        $this->additionalParams = $this->widgetConfiguration['additionalParams'];
        $this->additionalParamsPrefix = $this->widgetConfiguration['additionalParamsPrefix'];
        $this->configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::array_merge_recursive_overrule($this->configuration, $this->widgetConfiguration['configuration'], TRUE);
        $this->numberOfPages = ceil(count($this->objects) / (integer)$this->configuration['itemsPerPage']);
        $this->maximumNumberOfLinks = (integer)$this->configuration['maximumNumberOfLinks'];
    }

    /**
     * @param integer $currentPage
     * @return void
     */
    public function indexAction($currentPage = 1)
    {
        // set current page
        $this->currentPage = (integer)$currentPage;
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        } elseif ($this->currentPage > $this->numberOfPages) {
            $this->currentPage = $this->numberOfPages;
        }

        // modify query
        $itemsPerPage = (integer)$this->configuration['itemsPerPage'];
        $query = $this->objects->getQuery();
        $query->setLimit($itemsPerPage);
        if ($this->currentPage > 1) {
            $query->setOffset((integer)($itemsPerPage * ($this->currentPage - 1)));
        }
        $modifiedObjects = $query->execute();

        $this->view->assign('contentArguments', array(
            $this->widgetConfiguration['as'] => $modifiedObjects
        ));
        $this->view->assign('configuration', $this->configuration);
        $this->view->assign('pagination', $this->buildPagination());
        $this->view->assign('additionalParams', $this->additionalParams);
        $this->view->assign('additionalParamsPrefix', $this->additionalParamsPrefix);
    }

    /**
     * If a certain number of links should be displayed, adjust before and after
     * amounts accordingly.
     *
     * @return void
     */
    protected function calculateDisplayRange()
    {
        $maximumNumberOfLinks = $this->maximumNumberOfLinks;
        if ($maximumNumberOfLinks > $this->numberOfPages) {
            $maximumNumberOfLinks = $this->numberOfPages;
        }
        $delta = floor($maximumNumberOfLinks / 2);
        $this->displayRangeStart = $this->currentPage - $delta;
        $this->displayRangeEnd = $this->currentPage + $delta + ($maximumNumberOfLinks % 2 === 0 ? 1 : 0);
        if ($this->displayRangeStart < 1) {
            $this->displayRangeEnd -= $this->displayRangeStart - 1;
        }
        if ($this->displayRangeEnd > $this->numberOfPages) {
            $this->displayRangeStart -= ($this->displayRangeEnd - $this->numberOfPages);
        }
        $this->displayRangeStart = (integer)max($this->displayRangeStart, 1);
        $this->displayRangeEnd = (integer)min($this->displayRangeEnd, $this->numberOfPages);
    }

    /**
     * Returns an array with the keys "pages", "current", "numberOfPages", "nextPage" & "previousPage"
     *
     * @return array
     */
    protected function buildPagination()
    {
        $this->calculateDisplayRange();
        $pages = array();
        for ($i = $this->displayRangeStart; $i <= $this->displayRangeEnd; $i++) {
            $pages[] = array('number' => $i, 'isCurrent' => ($i === $this->currentPage));
        }
        $pagination = array(
            'pages' => $pages,
            'current' => $this->currentPage,
            'numberOfPages' => $this->numberOfPages,
            'displayRangeStart' => $this->displayRangeStart,
            'displayRangeEnd' => $this->displayRangeEnd,
            'hasLessPages' => $this->displayRangeStart > 2,
            'hasMorePages' => $this->displayRangeEnd + 1 < $this->numberOfPages
        );
        if ($this->currentPage < $this->numberOfPages) {
            $pagination['nextPage'] = $this->currentPage + 1;
        }
        if ($this->currentPage > 1) {
            $pagination['previousPage'] = $this->currentPage - 1;
        }
        return $pagination;
    }
}
