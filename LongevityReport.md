# Difficult periods and Longevity Analysis #

We are interested in finding out specific years and months when a person might experience challenges, health problems, relationship issues, financial issues, threats and ultimately expected age of that person.

We will make use of several astrology techniques and each has been described below.

# Output #
An array with **YEARS** as key and value as another array which lists all techniques which indicate that year to be difficult. Years with most number of indicators is likely to be very difficult and challenging.

If needed, type and nature of the difficulty can be easily analyzed year-wise.

## Example ##
```
$array = array(
    2006 => array(
              'Transit' => $relatedTransitInfoArray1,
              'Transit' => $relatedTransitInfoArray2,
              'Vimshottari' => $relatedDashaInfoArray,
             );
    );
```
# Details #
## Application of Planetary Transits ##
### The Technique ###
  * Difficult Houses (in increasing order) - 4,6,12 and 8.
    * When slow moving planets(Saturn, Rahu, Ketu, Jupiter) transit through these houses wrt Asc., Moon, Sun and its own natal position unfavorable situations present themselves.
  * When slow malefic planets, namely Saturn, Rahu or Ketu aspect Ascendant, Moon or Sun, bad outcomes are indicated.

### TODO: ###
  * Make use of [AspectsGenerator](http://code.google.com/p/aheadzen/source/browse/AspectsGenerator.php) class as it handles the problem of retrograde motion of Saturn and Jupiter. Please find sample code in [testAspectsGenerator.php](http://code.google.com/p/aheadzen/source/browse/testAspectsGenerator.php)
  * Output will contain all difficult EXACT transits within that year.

## Application of Vimshottari Dasha ##
[Prediction Using the Vimshottari Dasha System](http://www.galacticcenter.org/main/articles-and-case-studies/prediction-using-the-vimshottari-dasha-system.html)
### The Technique ###
  * Lords of Difficult Houses or planets placed within difficult houses present challenges. Planets aspected or placed with naturally malefic planets also give poor results. Maaraka (or Lord of 2nd house and 7th House from Ascendant) also indicate difficulties. Same with weak planets.
    * All this have been taken into account when calculating planet potency in [AnalyzeChart](http://code.google.com/p/aheadzen/source/browse/AnalyzeChart.php) class.
  * For AntarDasha (or a sub period within MahaDasha) we will recalculate each planet's potency using Dasha lord's longitude position as Ascendant.
  * Same as above for Pratayantra Dasha (or a sub period within AntarDasha).
  * **RULES:**
    * Poor planet potency at all 3 levels - WORST
    * Poor planet potency in 2 levels - BAD

### TODO: ###
  * Make use of [AnalyzeChart](http://code.google.com/p/aheadzen/source/browse/AnalyzeChart.php) class for potency and [VimshottariDasha](http://code.google.com/p/aheadzen/source/browse/VimshottariDasha.php) class. Please find sample code in [testVimshottariDasha.php](http://code.google.com/p/aheadzen/source/browse/testVimshottariDasha.php).
  * Output will contain all difficult bad/worst dasha within that year.

## Application of Ashtakvarga ##
Find detailed explanation of this technique in this book [Dots Of Destiny: Applictions Of Ashtakvarga](http://books.google.co.in/books?id=1Uwi1LAZb7QC&printsec=frontcover)
### The Technique ###
  * Poor Houses are those which have less than 25 points after analyzing the chart. The lesser the points the more challenging the outcome.
  * When Jupiter and Saturn transit through houses that have poor number of points, bad outcomes are indicated.

### TODO: ###
  * Make use of [AshtakVarga](http://code.google.com/p/aheadzen/source/browse/ashtakvarg.php) class. Please find sample code in [testAshtakVarg.php](http://code.google.com/p/aheadzen/source/browse/testAshtakVarg.php).
  * Output will contain all difficult EXACT transits within that year.
## Application of Kota Chakra ##
Please find detailed explanation of this technique in [Kota Chakra](http://www.saptarishisastrology.com/filedownload.php?v=8&a=y&b=y&f=KotaChakraandProfessionalSetbackbyMImranBW.pdf).
### The Technique ###
  * In transit, when natural malefic planets transit through the nakshatras that lie in the Stambha (innermost) division of Kota Chakra, then native badly suffers physically (i.e. injury, illness or death).
  * If natural malefic planets transit through the nakshatras that are placed on the course of “entrance”, and at the same time, natural benefic planets transit on the path of “exit” then native face defeat in the battle (or any venture).
  * The **exception** of retrograde planets INVERTS the entrance and exit routes
### TODO: ###
    * Create classes to construct Kota Chakra and application of transits on them.
    * Output will contain all difficult EXACT transits within that year.
## Application of Bhrighu Chakra Paddhati ##
Please find detailed explanation of this method in [Bhrighu Chakra Paddhati – Part 1](http://www.articlesbase.com/astrology-articles/bhrighu-chakra-paddathi-1-part-582264.html)
### The Technique ###
    * Whenever BCP touches a person passes through a difficult phase:
      * Any malefic planet (Rahu, Ketu, Saturn and Mars),
      * or touches difficult houses 6,8,12
      * or exact point of aspect of difficult planet on any house.
      * or any house that has less than 25 ashtakvarga points

### TODO: ###
  * Create classes to construct BCP and application of transits on them.
  * Output will contain all difficult EXACT hits within that year.
## Longevity calculations ##
Many longevity techniques exists, but for this project we are trying one mentioned here [Bhrighu Chakra Padhatti, Sarvashtakvarga and Longevity](http://www.saptarishisastrology.com/filedownload.php?v=9&a=y&b=y&f=BhridhuChakraPaddathiAndLongevityBW.pdf)
### The Technique ###
  * Calculate probable life span as mentioned in article. Please simplify by making use of just Asc. and Moon, and short as 0 to 32, medium as 32 to 64, and long as 64+
  * Use BCP and Ashtakvarga to fix the probable years of death as mentioned in article.
  * Use the lord of the 8th house and the lord of the nakshatra in which the 8th lord is placed to pinpoint the year of death as mentioned in article.
### TODO: ###
  * Create code to make all calculations.
  * Output will contain possible life span of a person.