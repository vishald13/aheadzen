# Introduction #
To find compatibility in two persons, following conditions apply –

  1. Good health – From Ascendant and Ascendant Lord.
  1. Healthy mind and positive attitude – From Moon
  1. Healthy Ego, Confidence and Surrender – From Sun
  1. Charm, Fun and Comforts – From Venus

Next we take a person’s personal planet and put it into other person’s chart, this give us what part of their personality is glorified and appreciated in the relationship.

If one’s moon goes into other person’s difficult area like 8th house or aspect from Saturn, it is almost certain that this person will feel disoriented, distressed and suffocated in this relationship.

# Details #

## Summary ##
We need to be able to calculate following things –
  1. Fruitful areas and planets of a chart
  1. Difficult areas and planets of a chart
## General Requirements – ##
  1. Calculate house cusps from any given point (usually Ascendant). See [private function setupHouses and function inHouseRelativeTo](http://code.google.com/p/aheadzen/source/browse/AnalyzeChart.php)
    * There are total 12 houses, each 30 degree in size.
    * House Center Calculation = `(house number - 1)*30 + reference point full degree`
    * Fruitful houses = 1, 5, 9
    * Difficult house = 6, 8, 12
  1. Calculate influence of planets by position or aspect. Please see [function referenceFrom](http://code.google.com/p/aheadzen/source/browse/AnalyzeChart.php)
    * Malefic planets like Saturn, Mars, Rahu and Ketu make life difficult
    * Benefic planets like Jupiter, Venus, Sun, Moon make life fruitful
    * Mercury is neutral but we will consider it as Malefic
  1. Planetary Strength – Already done.
  1. Calculate relative position between two points (generally two planets) = Pls see [function calculateSynastry](http://code.google.com/p/aheadzen/source/browse/AnalyzeChart.php)
    * Judging quality through position –
      1. If distance is of 1,5 or 9 house – Its fruitful
      1. If distance is of 3,11 house – its somewhat fruitful
      1. 2-12 is difficult
      1. 6-8 is difficult
      1. 4-10 is difficult
      1. 1-1 or 7-7 is active and fruitful.

# Exact Calculation of Compatibility #
**Version 1**
## Step 1 ##
  * Get both person’s birth charts, planetary positions and Asc. Fulldegree
## Step 2 ##
Analyze each birth chart and calculate following:
  * Fruitful and difficult areas (in fulldegree) of chart from Asc., Moon, Sun and Venus
  * Planetary Strength – already done.
  * Fruitful and difficult areas (in fulldegree) of chart due to planetary positions and aspects.
## Step 3 ##
For each birth chart calculate and analyze following:
  * 7th house from Ascendant, and its degree of fruitfulness as obtained from Step 2.
  * 7th house lord, and its planetary strength and relative position from 7th house.
### Notes ###
  1. A good 7th house and good 7th lord indicates hugely successful relationships and partnerships.
  1. A bad 7th house and good 7th lord indicates unresolved attachments and aversions but overall good relationship skills.
  1. A good 7th house and bad 7th lord indicates poor relationship skills but still able to maintain relationships.
  1. Both bad indicates either no relationship at all, or many broken relationships but no depth at all.
## Step 4 ##
Now take one person’s planets, mainly Asc, Sun, Moon and Venus and place them in other person’s chart.
If a point occupies fruitful area as seen from:
  * Asc., Moon, Sun and Venus
  * Position and aspect of Benefic planets
    * it indicates harmony, understanding and progress.
Similarly, if a point occupies difficult position as seen from:
  * Asc., Moon, Sun and Venus
  * Position and aspect of Malefic planets
    * it indicates issues and obstacles in success of relationship.