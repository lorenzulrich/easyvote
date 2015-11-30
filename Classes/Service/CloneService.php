<?php
namespace Visol\Easyvote\Service;

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

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Copies a DomainObject with treatment of relationship properties according to
 * source code annotations - @copy ignore|clone|reference. Returns a completely
 * fresh DomainObject with either copies of or references to the original
 * related values.
 *
 * @author Claus Due
 * @package Tool
 * @subpackage Service
 */
class CloneService implements SingletonInterface
{

    /**
     * RecursionHandler instance
     * @var \Visol\Easyvote\Service\RecursionService
     * @inject
     */
    public $recursionService;

    /**
     * ReflectionService instance
     * @var \TYPO3\CMS\Extbase\Reflection\ReflectionService
     * @inject
     */
    protected $reflectionService;

    /**
     * ObjectManager instance
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * Copy a single object based on field annotations about how to copy the object
     *
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $object The object to be copied
     * @return \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $copy
     * @api
     */
    public function copy($object)
    {
        $className = get_class($object);
        $this->recursionService->in();
        $this->recursionService->check($className);
        $copy = $this->objectManager->get($className);
        $properties = $this->reflectionService->getClassPropertyNames($className);
        foreach ($properties as $propertyName) {
            $tags = $this->reflectionService->getPropertyTagsValues($className, $propertyName);
            $getter = 'get' . ucfirst($propertyName);
            $setter = 'set' . ucfirst($propertyName);
            $copyMethod = $tags['copy'][0];
            $copiedValue = NULL;
            if ($copyMethod !== NULL && $copyMethod !== 'ignore') {
                $originalValue = $object->$getter();
                if ($originalValue instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
                    $originalValue = $originalValue->_loadRealInstance();
                }
                if ($copyMethod == 'reference') {
                    $copiedValue = $this->copyAsReference($originalValue);
                } elseif ($copyMethod == 'clone') {
                    $copiedValue = $this->copyAsClone($originalValue);
                }
                if ($copiedValue != NULL) {
                    $copy->$setter($copiedValue);
                }
            }
        }
        $this->recursionService->out();
        return $copy;
    }

    /**
     * Copies Domain Object as reference
     *
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $value
     * @return \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface
     */
    protected function copyAsReference($value)
    {
        if ($value instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
            // objectstorage; copy storage and attach items to this new storage
            // if 1:n mapping is used, items are detached from their old storage - this is
            // a limitation of this type of reference
            $newStorage = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\ObjectStorage');
            foreach ($value as $item) {
                $newStorage->attach($item);
            }
            return $newStorage;
        } elseif ($value instanceof \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface) {
            // 1:1 mapping as reference; return object itself
            return $value;
        } elseif (is_object($value)) {
            // fallback case for class copying - value objects and such
            return $value;
        } else {
            // this case is very unlikely: means someone wished to copy hard type as a reference - so return a copy instead
            return $value;
        }
    }

    /**
     * Copies Domain Object as clone
     *
     * @param \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $value
     * @return \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface
     * @api
     */
    protected function copyAsClone($value)
    {
        if ($value instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
            // objectstorage; copy storage and copy items, return new storage
            $newStorage = $this->objectManager->get('\TYPO3\CMS\Extbase\Persistence\ObjectStorage');
            foreach ($value as $item) {
                $newItem = $this->copy($item);
                $newStorage->attach($newItem);
            }
            return $newStorage;
        } elseif ($value instanceof \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface) {
            // DomainObject; copy and return
            /** @var $value \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface */
            return $this->copy($value);
        } elseif (is_object($value)) {
            // fallback case for class copying - value objects and such
            return clone $value;
        } else {
            // value is probably a string
            return $value;
        }
    }

}
