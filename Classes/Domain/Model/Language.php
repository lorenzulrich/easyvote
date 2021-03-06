<?php
namespace Visol\Easyvote\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Language
 */
class Language extends AbstractEntity
{

    /**
     * Title
     *
     * @var \string
     */
    protected $title;

    /**
     * TYPO3 language uid
     *
     * @var \integer
     */
    protected $languageUid;

    /**
     * Returns the title
     *
     * @return \string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param \string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the TYPO3 language uid
     *
     * @return \integer $languageUid
     */
    public function getLanguageUid()
    {
        return $this->languageUid;
    }

    /**
     * Sets the TYPO3 language uid
     *
     * @param \integer $languageUid
     * @return void
     */
    public function setLanguageUid($languageUid)
    {
        $this->languageUid = $languageUid;
    }

}