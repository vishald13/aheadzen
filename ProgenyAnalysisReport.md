# Progeny and Children Analysis v1 #

We are interested in finding out if a person will have kids, with ease or with difficulty and timing the birth of children.

Future versions of this report will contain exact count of children, their sex and other things like their well-being etc.

We will make use of several astrology techniques and each has been described below.

# Output #
Benefic or Malefic influences to various key parameters. This will indicate if children are promised or not.

An array with **most probably dates** as final output. Also we will need to see output of all techniques used for debugging purposes.

This project is more or less similar to an existing project that calculates possible dates during which a person is likely to get married. Please see [testMarriageGuru.php](http://code.google.com/p/aheadzen/source/browse/testMarriageGuru.php). It calculates possible dates a person might get married by making use of various techniques.

# Details #
## Finding promise of children in a birth chart ##
### The Technique ###
  * Examine influences on 5th house and the lord of 5th house.
  * Examine influences on the Ascendant and the Asc. lord.
  * Examine influences on Jupiter.
  * Examine influences to KshetraSphuta or BeejaSphuta.
  * **Condition for having children**:
    * Many Benefic influences on 5th house and its lord, and its lord is strongly placed in chart.
    * Other points mentioned above are favorable and not very weak.
  * **Cases where birth of children is either delayed, miscarriages or no children at all:**
    * When 5th house and 5th lord is aspected by malefics, or 5th lord is weakly placed in birth chart some delay is indicated.
    * When 5th house or 5th lord is in influence of Rahu/Ketu plus influence of atleast one other malefic, abortion or a miscarriage is a possibility.
    * When other points mentioned above are weak too, that is, under influence of malefics, it indicates difficulties to children and in rare cases no children at all.

## Timing the birth of children ##
### Application of Transits ###
  * For a particular year, Saturn should aspect the
    * 5th house, or
    * 5th lord, or
    * 9th house, or
    * 9th lord.
  * Similarly Jupiter should aspect any of the above points that year.
  * To time the month, Mars should aspect above points within 3 months of child delivery.
  * To further pinpoint to the day, the moon should aspect any of the above mentioned points within three days of the birth of child.

## Calculating Beeja Sphuta and Kshetra Sphuta ##
As mentioned on [Beeja & Kshetra Sphuta-The Astrological Sperm & Ovary](http://www.lightonvedicastrology.com/phpBB3_0/viewtopic.php?f=1&t=10859)
  * **Beeja Sphuta** is calculated for MALES by adding the full degree of Sun + Jupiter + Venus.
  * **Kshetra Sphuta** is calculated for FEMALES by adding similarly absolute longitudes of Moon+Mars+Jupiter
  * Remove multiples of 360 so that we can place it on the chart.