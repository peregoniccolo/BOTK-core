<?php
use PHPUnit\Framework\TestCase;

class FactsFactoryTest extends TestCase
{

	public function testMakeThing()
	{
		$profile = array(
			'datamapper' => function (array $rawdata) {
				$data = array();
				$data['identifier'] = $rawdata[0];
				$data['homepage'] = $rawdata[1];
				$data['alternateName'][] = $rawdata[2];
				$data['alternateName'][] = $rawdata[3];
				return $data;
			},
		);
		$rawdata = array(
			'1',
			'linkeddata.center',
			'LinkedData.Center',
			'LDC'
		);

		$factsFactory = new \BOTK\FactsFactory($profile);
		$facts = $factsFactory->factualize($rawdata);

		$structuredData = $facts->asArray();
		$this->assertInstanceOf('\BOTK\Model\SampleSchemaThing', $facts);
		$this->assertEquals($structuredData['identifier'], '1');
		// SampleSchemaThing imposes the FILTER_FORCE_ARRAY flag on the homepage field
		$this->assertEquals(count($structuredData['homepage']), 1); # there should be a single field
		$this->assertEquals($structuredData['homepage'][0], 'http://linkeddata.center'); # such field must contain the following
		$this->assertEquals(2, count($structuredData['alternateName']));
	}

}

