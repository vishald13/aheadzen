<?php
// Business tier class that analyzes a birth chart for many things

$path = dirname( __FILE__ );

class AnalyzeChart
{
	private $_Page = array();
	private $_ChartInput;
	private $_ChartInfo = array();
	private $_Aspects = array();
	private $_AspectDetails = array();
	private $_SynastryAspects = array();
	private $_SynastryAspectDetails = array();
	private $_Lordship = array();
	private $_isCombust = array();
	private $_Potency = array();
	private $_SynastryPotency = array();
	const SHOWTIME_STRING = "g:i:s A";
	const DEGREE_STRING = "fulldegree";
	
	public function __construct($chart)
	{
		$this->_ChartInput = $chart;
		$this->_ChartInfo['house'] = $this->_ChartInput->getHouses();
		$this->_ChartInfo['planet'] = $this->_ChartInput->getPlanets();
		$planets = $this->_ChartInput->getPlanets();
		$houses = $this->_ChartInput->getHouses();
		$this->setLordship();

		//$this->referenceFrom( $planets['Sun']['fulldegree'] );

		$list = $this->getAuspicousPlanets( $houses['ASC']['sign'] );
		$list1 = $this->getAuspicousPlanets( $planets['Sun']['sign'] );
		$list2 = $this->getAuspicousPlanets( $planets['Moon']['sign'] );


		
		$position = array( 'GOOD' => array(), 'BAD' => array());
		$ascHouseDegree = $this->deltaDegrees( 15, $houses['ASC']['fulldegree'] );

		foreach( AstroData::$GOOD_PLANETS as $good )
		{
			
			if( in_array( $this->inHouseRelativeTo( $ascHouseDegree, $planets[$good]['fulldegree'] ), AstroData::$POSITION_GOOD_BAD['GOOD'] ) )
				$position['GOOD'][] = $good;
			else $position['BAD'][] = $good;
		}
		foreach( AstroData::$BAD_PLANETS as $bad )
		{
			if( in_array( $this->inHouseRelativeTo( $ascHouseDegree, $planets[$bad]['fulldegree'] ), AstroData::$POSITION_GOOD_BAD['BAD'] ) )
				$position['GOOD'][] = $bad;
			else $position['BAD'][] = $bad;

			if( $bad == 'Rahu' || $bad == 'Ketu' )
			{
				if( in_array( $this->inHouseRelativeTo( $ascHouseDegree, $planets[$bad]['fulldegree'] ), AstroData::$POSITION_GOOD_BAD['BAD'] ) )
					$position['GOOD'][] = $bad;
				else $position['BAD'][] = $bad;
			}

		}

		$grandlist = array_merge_recursive( $list, $position );
		$good = array_count_values( $grandlist['GOOD'] );
		$bad = array_count_values( $grandlist['BAD'] );
		$killer = array_count_values( $grandlist['KILLER'] );
		$yogakaraka = array_count_values( $grandlist['YOGAKARAKA'] );

		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS );

		$true = array();
		$this->referenceFrom( $planets, $houses['ASC'] );
		foreach( $all_planets as $p )
		{
			$true[$p] = $good[$p]*10 - $bad[$p]*10 - $killer[$p]*0 + $yogakaraka[$p]*10 + $this->calculateExaltationStrength($p, $planets[$p]['fulldegree'] ) + $this->calculateRashiStrength( $p, $planets[$p]['sign'] ) + $this->getAspectScore( $p );
		}
		arsort( $true );
		$this->_Potency = $true;
		//var_dump( $true );

	}
	private function getAspectScore( $planet )
	{
		$total = 0;
		if( !isset( $this->_Aspects[$planet] ) )
			return $total;

		foreach( $this->_Aspects[$planet] as $type )
		{
			if( $type == 'Sun' )
			{
				if( !empty( $this->_isCombust[$planet] ) )
					$total += $this->_isCombust[$planet] * 5;
			}
			else if( in_array( $type, AstroData::$GOOD_PLANETS ) )
				$total += 5;
			else $total -= 5;
		}
		return $total;
	}
	private function calculateSynastryAspectScore()
	{
		$total = 0;
		if( !isset( $this->_SynastryAspects ) )
			return $total;

		foreach( $this->_SynastryAspects as $base_planet => $type )
		{
			foreach( $type as $partner_planet )
			{
				if( $this->_SynastryAspectDetails[$base_planet][$partner_planet]['aspect_type'] == 'Problems' && in_array( $partner_planet, AstroData::$GOOD_PLANETS ) )
					$total = 0;
				else if( $this->_SynastryAspectDetails[$base_planet][$partner_planet]['aspect_type'] == 'Problems' && in_array( $partner_planet, AstroData::$BAD_PLANETS ) )
					$total = 0;
				else if( in_array( $partner_planet, AstroData::$GOOD_PLANETS ) || $partner_planet == 'ASC' || $this->_SynastryAspectDetails[$base_planet][$partner_planet]['aspect_type'] == 'trine' )
					$total = 5;
				else $total = 0;
				$this->_SynastryAspectDetails[$base_planet][$partner_planet]['score'] = $total;

			}
		}
	}
	public function getAspects( $planet )
	{
		return $this->_AspectDetails[$planet];
	}

	public function getPlanetInfo( $planet )
	{
		$info = array();
		$info['planet'] = $planet;
		$info['longitude'] = $this->getLongitude( $this->_ChartInfo['planet'][$planet] );
		$info['position'] = $this->_ChartInfo['planet'][$planet]['house'];
		$info['lordship'] = $this->getLordship( $planet );
		$info['potency'] = $this->showPlanetPotency( $planet );
		
		return $info;
	}
	private function getLongitude( $data )
	{
		$degree = (int)$data['degree'];
		$min = (int)(($data['degree'] - $degree) * 60);

		return $degree . ' ' . $data['sign'] . ' ' . $min;
	}
	private function getLordship( $planet )
	{
		$lord = array_keys( $this->_Lordship, $planet );
		
		if( $planet == 'Rahu' || $planet == 'Ketu' )
			$lord = array( $this->_ChartInfo['planet'][$planet]['house'] );

		return $lord;
	}
	private function setLordship()
	{
		for( $i = 1; $i < 13; $i++)
		{
			$sign = $this->_ChartInfo['house'][$i]['sign'];
			$this->_Lordship[$i] = AstroData::$ZODIAC_SIGNS_LORD[$sign];
		}
	}
	public function showPlanetPotency( $planet )
	{
		$potential = $this->_Potency[$planet];
		$text = '';
		if( $potential >= 10 && $potential < 20 )
			$text = 'Good and Auspicious';
		else if( $potential <= -10 && $potential > -20)
			$text = 'Poor, Inauspicious and Needs Attention';
		else if( $potential <= -20 )
			$text = 'Very Poor, Inauspicious and Needs Immediate Attention';
		else if( $potential >= 20 )
			$text = 'Excellent and Auspicious';
		else if( $potential > -10 && $potential < 10 )
			$text = 'Normal, Expect Mixed Results';

		return $text;

	}
	public function calculateSynastryPotency()
	{
		$this->_SynastryPotency['LIFE_LONG'] = 0;
		$this->_SynastryPotency['TUNING'] = 0;
		$this->_SynastryPotency['OTHERS'] = 0;

		foreach( $this->_SynastryAspectDetails as $partner_planet => $base_planets )
		{
			
			foreach( $base_planets as $base_planet_name => $base_planet )
			{
				if( $partner_planet == 'ASC' )
				{
					$multiplier = 1;
					if( $base_planet_name == 'Sun' || $base_planet_name == 'Moon' || $base_planet_name == 'ASC' )
						$multiplier = 2;

					$this->_SynastryPotency['LIFE_LONG'] += $base_planet['score']*$multiplier;
				} else if( $partner_planet == 'Sun' || $partner_planet == 'Moon' )
				{
					$this->_SynastryPotency['TUNING'] += $base_planet['score'];
				} else $this->_SynastryPotency['OTHERS'] += $base_planet['score'];
			}
		}

	}
	public function showAspectQuality( $planet, $aspectedBy )
	{
		$potential = $this->_Potency[$planet] + $this->_Potency[$aspectedBy];
		$text = '';
		if( $potential >= 10 && $potential < 25 )
			$text = 'Capable';
		else if( $potential <= -10 && $potential > -25)
			$text = 'Capable';
		else if( $potential <= -25 || $potential >= 25 )
			$text = 'Powerful';
		else if( $potential > -10 && $potential < 10 )
			$text = 'Weak and Ineffective';

		return $text;

	}
	// Following function calculates how partner is affected by you
	public function calculateSynastry( $partner_chart )
	{
		$planets = $partner_chart->getPlanets();
		$houses = $partner_chart->getHouses();
		$reverse_aspects = array_merge( AstroData::$REVERSE_DRISHTI, array ( 'Trines' => array(5,9), 'Problems' => array(6,8,12) ) );

		$planets['ASC'] = $houses['ASC'];

		$SynastryAspects = array();
		$SynastryAspectsDetails = array();
		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS, array( 'ASC' ) );

		foreach( $all_planets as $p )
		{
			$reference = $planets[$p]['fulldegree'];
			$pointHouseDegree = $this->deltaDegrees( 15, $reference );
			
			foreach( $all_planets as $pp )
			{
				if( $pp == 'ASC' )
				{
					$planet_name = 'Jupiter';
					$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree, $this->_ChartInfo['house'][$pp]['fulldegree'] );
				}
				else
				{
					$planet_name = $pp;
					$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree, $this->_ChartInfo['planet'][$pp]['fulldegree'] );
				}
			
				if( in_array($planetInHouse, $reverse_aspects[$planet_name] ) || in_array($planetInHouse, $reverse_aspects['Trines'] ) )
				{
					$houseAspectDegree = (12 - ($planetInHouse - 1)) * 30;
					if( !isset( $SynastryAspects[$p] ) )
					{
						$SynastryAspects[$p] = array();
						$SynastryAspectsDetails[$p] = array();
					}

					$aspect_type = AstroData::$ASPECT_NAME[$houseAspectDegree];

					$SynastryAspects[$p][] = $pp;
					$SynastryAspectsDetails[$p][$pp] = array( 'aspect_type' => $aspect_type );
				}
				if( in_array($planetInHouse, $reverse_aspects['Problems'] ) )
				{
					if( !isset( $SynastryAspects[$p] ) )
					{
						$SynastryAspects[$p] = array();
						$SynastryAspectsDetails[$p] = array();
					}
					$SynastryAspects[$p][] = $pp;
					$SynastryAspectsDetails[$p][$pp] = array( 'aspect_type' => 'Problems' );
				}


			}


		}
		$this->_SynastryAspects = $SynastryAspects;
		$this->_SynastryAspectDetails = $SynastryAspectsDetails;
		$this->calculateSynastryAspectScore();
		$this->calculateSynastryPotency();
		var_dump( $this->_SynastryAspects, $this->_SynastryAspectDetails, $this->_SynastryPotency );
	}
	private function referenceFrom( $planets, $asc )
	{
		$planets['ASC'] = $asc;

		$this->_Aspects = array();
		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS, array( 'ASC' ) );

		foreach( $all_planets as $p )
		{
			$reference = $planets[$p]['fulldegree'];
			$pointHouseDegree = $this->deltaDegrees( 15, $reference );
			
			foreach( $all_planets as $pp )
			{
				if( $p == $pp || $pp == 'ASC' )
					continue;

				$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree, $planets[$pp]['fulldegree']);
			
				if( in_array($planetInHouse, AstroData::$REVERSE_DRISHTI[$pp] ) )
				{
					$houseAspectDegree = (12 - ($planetInHouse - 1)) * 30;
					if( !isset( $this->_Aspects[$p] ) )
					{
						$this->_Aspects[$p] = array();
						$this->_AspectDetails[$p] = array();
					}

					$aspect_type = AstroData::$ASPECT_NAME[$houseAspectDegree];

					$this->_Aspects[$p][] = $pp;
					$this->_AspectDetails[$p][$pp] = array( 'aspect_type' => $aspect_type );

					if( $pp == 'Sun' )
					{
						if( $aspect_type == AstroData::$ASPECT_NAME[0] )
								$this->_isCombust[$p] = -1;
						else if( $aspect_type == AstroData::$ASPECT_NAME[180] )
								$this->_isCombust[$p] = 1;
						else $this->_isCombust[$p] = 0;
					}
				}

			}


		}
		//$Houses = $this->setupHouses( $pointHouseDegree );
		//var_dump( $this->_isCombust, $this->_AspectDetails );


	
	}
	private function setupHouses( $reference )
	{
		$house = array();
		for($i = 12; $i > 0; $i--)
		{
			$house[$i] = $this->deltaDegrees( (360 - 30*($i-1)), $reference );
		}
		return $house;
	}
	private function getZodiacSign( $degree )
	{
		$sign_number = floor( $degree/30 );
		return AstroData::$ZODIAC_SIGN_NAME[$sign_number];
	}
	private function calculateExaltationStrength( $planet, $fulldegree )
	{
		$debiliation = $this->modDegree( AstroData::$EXALTATION[$planet] - 180 );

		$step1 = $this->modDegree( $fulldegree - $debiliation );

		if( $step1 > 180 )
			$step1 = 360 - $step1;

		$step2 = $step1/18;

		return $step2;
	}	
	private function calculateRashiStrength( $planet, $sign )
	{
		if( AstroData::$MOOL_TRIKONA[$planet] == $sign )
			return 7;
	}
	private function calculatePlanetaryExchange()
	{
	}
	private function getAuspicousPlanets( $ASC )
	{
		return AstroData::$LAGNA_GOOD_BAD[$ASC];
	}
	private function getZodiacSignLord( $zodiac_sign )
	{
		return AstroData::$ZODIAC_SIGNS_LORD[$zodiac_sign];
	}
	private function inHouseRelativeTo( $ref, $transitPoint )
	{
		$deltaDegrees = $this->deltaDegrees( $ref, $transitPoint );
		$deltaHouse = (int)($deltaDegrees/30);
		$deltaHouse += 1;
		return $deltaHouse;
	}

	private function deltaDegrees( $ref, $transitPoint )
	{
		$deltaDegrees = $transitPoint - $ref;
		$deltaDegrees = $this->modDegree($deltaDegrees);
		return $deltaDegrees;
	}

	private function modDegree($degree)
	{
		if( $degree < 0 )
		{
			$degree += 360;
		}
		return $degree;
	}
}?>