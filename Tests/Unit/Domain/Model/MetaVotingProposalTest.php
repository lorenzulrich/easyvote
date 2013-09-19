<?php

namespace Visol\Easyvote\Tests;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Lorenz Ulrich <lorenz.ulrich@visol.ch>, visol digitale Dienstleistungen GmbH
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \Visol\Easyvote\Domain\Model\MetaVotingProposal.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Lorenz Ulrich <lorenz.ulrich@visol.ch>
 */
class MetaVotingProposalTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Visol\Easyvote\Domain\Model\MetaVotingProposal
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Visol\Easyvote\Domain\Model\MetaVotingProposal();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getTypeReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->fixture->getType()
		);
	}

	/**
	 * @test
	 */
	public function setTypeForIntegerSetsType() {
		$this->fixture->setType(12);

		$this->assertSame(
			12,
			$this->fixture->getType()
		);
	}
	/**
	 * @test
	 */
	public function getScopeReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->fixture->getScope()
		);
	}

	/**
	 * @test
	 */
	public function setScopeForIntegerSetsScope() {
		$this->fixture->setScope(12);

		$this->assertSame(
			12,
			$this->fixture->getScope()
		);
	}
	/**
	 * @test
	 */
	public function getMainProposalApprovalReturnsInitialValueForFloat() {
		$this->assertSame(
			0.0,
			$this->fixture->getMainProposalApproval()
		);
	}

	/**
	 * @test
	 */
	public function setMainProposalApprovalForFloatSetsMainProposalApproval() {
		$this->fixture->setMainProposalApproval(3.14159265);

		$this->assertSame(
			3.14159265,
			$this->fixture->getMainProposalApproval()
		);
	}
	/**
	 * @test
	 */
	public function getVotingProposalsReturnsInitialValueForVotingProposal() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getVotingProposals()
		);
	}

	/**
	 * @test
	 */
	public function setVotingProposalsForObjectStorageContainingVotingProposalSetsVotingProposals() {
		$votingProposal = new \Visol\Easyvote\Domain\Model\VotingProposal();
		$objectStorageHoldingExactlyOneVotingProposals = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneVotingProposals->attach($votingProposal);
		$this->fixture->setVotingProposals($objectStorageHoldingExactlyOneVotingProposals);

		$this->assertSame(
			$objectStorageHoldingExactlyOneVotingProposals,
			$this->fixture->getVotingProposals()
		);
	}

	/**
	 * @test
	 */
	public function addVotingProposalToObjectStorageHoldingVotingProposals() {
		$votingProposal = new \Visol\Easyvote\Domain\Model\VotingProposal();
		$objectStorageHoldingExactlyOneVotingProposal = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneVotingProposal->attach($votingProposal);
		$this->fixture->addVotingProposal($votingProposal);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneVotingProposal,
			$this->fixture->getVotingProposals()
		);
	}

	/**
	 * @test
	 */
	public function removeVotingProposalFromObjectStorageHoldingVotingProposals() {
		$votingProposal = new \Visol\Easyvote\Domain\Model\VotingProposal();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($votingProposal);
		$localObjectStorage->detach($votingProposal);
		$this->fixture->addVotingProposal($votingProposal);
		$this->fixture->removeVotingProposal($votingProposal);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getVotingProposals()
		);
	}
	/**
	 * @test
	 */
	public function getKantonReturnsInitialValueForKanton() {	}

	/**
	 * @test
	 */
	public function setKantonForKantonSetsKanton() {	}
}
?>