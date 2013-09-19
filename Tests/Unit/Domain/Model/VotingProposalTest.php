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
 * Test case for class \Visol\Easyvote\Domain\Model\VotingProposal.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Lorenz Ulrich <lorenz.ulrich@visol.ch>
 */
class VotingProposalTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Visol\Easyvote\Domain\Model\VotingProposal
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Visol\Easyvote\Domain\Model\VotingProposal();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getShortTitleReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getShortTitle()
		);
	}

	/**
	 * @test
	 */
	public function setShortTitleForStringSetsShortTitle() {
		$this->fixture->setShortTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getShortTitle()
		);
	}
	/**
	 * @test
	 */
	public function getOfficialTitleReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getOfficialTitle()
		);
	}

	/**
	 * @test
	 */
	public function setOfficialTitleForStringSetsOfficialTitle() {
		$this->fixture->setOfficialTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getOfficialTitle()
		);
	}
	/**
	 * @test
	 */
	public function getYoutubeUrlReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getYoutubeUrl()
		);
	}

	/**
	 * @test
	 */
	public function setYoutubeUrlForStringSetsYoutubeUrl() {
		$this->fixture->setYoutubeUrl('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getYoutubeUrl()
		);
	}
	/**
	 * @test
	 */
	public function getGoalReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getGoal()
		);
	}

	/**
	 * @test
	 */
	public function setGoalForStringSetsGoal() {
		$this->fixture->setGoal('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getGoal()
		);
	}
	/**
	 * @test
	 */
	public function getInitialStatusReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getInitialStatus()
		);
	}

	/**
	 * @test
	 */
	public function setInitialStatusForStringSetsInitialStatus() {
		$this->fixture->setInitialStatus('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getInitialStatus()
		);
	}
	/**
	 * @test
	 */
	public function getConsequenceReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getConsequence()
		);
	}

	/**
	 * @test
	 */
	public function setConsequenceForStringSetsConsequence() {
		$this->fixture->setConsequence('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getConsequence()
		);
	}
	/**
	 * @test
	 */
	public function getProArgumentsReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getProArguments()
		);
	}

	/**
	 * @test
	 */
	public function setProArgumentsForStringSetsProArguments() {
		$this->fixture->setProArguments('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getProArguments()
		);
	}
	/**
	 * @test
	 */
	public function getContraArgumentsReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getContraArguments()
		);
	}

	/**
	 * @test
	 */
	public function setContraArgumentsForStringSetsContraArguments() {
		$this->fixture->setContraArguments('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getContraArguments()
		);
	}
	/**
	 * @test
	 */
	public function getGovernmentOpinionReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getGovernmentOpinion()
		);
	}

	/**
	 * @test
	 */
	public function setGovernmentOpinionForStringSetsGovernmentOpinion() {
		$this->fixture->setGovernmentOpinion('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getGovernmentOpinion()
		);
	}
	/**
	 * @test
	 */
	public function getLinksReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getLinks()
		);
	}

	/**
	 * @test
	 */
	public function setLinksForStringSetsLinks() {
		$this->fixture->setLinks('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getLinks()
		);
	}
	/**
	 * @test
	 */
	public function getProposalApprovalReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getProposalApproval()
		);
	}

	/**
	 * @test
	 */
	public function setProposalApprovalForStringSetsProposalApproval() {
		$this->fixture->setProposalApproval('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getProposalApproval()
		);
	}
}
?>