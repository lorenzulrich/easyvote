<?php
namespace Visol\Easyvote\Controller;

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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller
 */
class KantonController extends ActionController
{

    /**
     * kantonRepository
     *
     * @var \Visol\Easyvote\Domain\Repository\KantonRepository
     * @inject
     */
    protected $kantonRepository;

    /**
     * action list
     *
     * @unused
     * @return void
     */
    public function listAction()
    {
        $kantons = $this->kantonRepository->findAll();
        $this->view->assign('kantons', $kantons);
    }

    /**
     * action list
     *
     * @return void
     */
    public function kantonNavigationAction()
    {
        $requestedKanton = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tx_easyvote_currentvotings')['kanton'];
        if (!empty($requestedKanton)) {
            $kantonObject = $this->kantonRepository->findByUid($requestedKanton);
            $this->view->assign('requestedKanton', $kantonObject);
        }
        $kantons = $this->kantonRepository->findAll();
        $this->view->assignMultiple(array(
            'kantons' => $kantons,
        ));
    }

    /**
     * action show
     *
     * @unused
     * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
     * @return void
     */
    public function showAction(\Visol\Easyvote\Domain\Model\Kanton $kanton)
    {
        $this->view->assign('kanton', $kanton);
    }

}