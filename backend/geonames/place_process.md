# How we process places in DAP

## Source Data

Data sources for places in DAP

### TEI XML files (one per document)

Correspondence sources and destinations originate in XML files, in the 
`listPlace` element in the TEI Header.  These are verbatim toponyms from 
Barker's edition (metadata encoded from the print edition) and have not 
been geocoded (augmented with latitude/longitude pairs), standardized to 
modern place names, or deduplicated.

Example 1 from [APB0017.xml](https://github.com/DigitalAustinPapers/AustinTranscripts/blob/f9f769cedf659e8425d58c2ee3a23cd03ec07ca5/teip5_xml/APB0017.xml):
```xml
<listPlace>
    <place>
        <placeName type="origin">Austin Ville, VA</placeName>
    </place>
    <place>
        <placeName type="destination">Unknown</placeName>
    </place>
</listPlace>
```

Example 2 from [APB0568.xml](https://github.com/DigitalAustinPapers/AustinTranscripts/blob/f9f769cedf659e8425d58c2ee3a23cd03ec07ca5/teip5_xml/APB0568.xml#L15-L18)
```xml
<listPlace>
    <place>
        <placeName type="origin">Mexico City, MX</placeName>
    </place>
    <place>
        <placeName type="destination">Unknown</placeName>
    </place>
</listPlace>
```

Example 3 from [APB4781.xml](https://github.com/DigitalAustinPapers/AustinTranscripts/blob/f9f769cedf659e8425d58c2ee3a23cd03ec07ca5/teip5_xml/APB4781.xml)
```xml
<listPlace>
    <place>
        <placeName type="origin">Mexico</placeName>
    </place>
    <place>
        <placeName type="destination">Monclova, Coahuila</placeName>
    </place>
</listPlace>
```

### Database Tables
The DAP database contains a table `NormalizedPlace` that has been populated by
a combination of pre-2013 processes (likely extracted from all `placeName` 
elements in DAP transcripts, including correspondence metadata and 
`place_mentioned` elements within texts), a geocoding effort in 2013
(querying [GeoNames](https://www.geonames.org/) for matching toponyms in US or
MX), and a later pruning effort to reduce records to only those used for 
correspondence metadata. 

Example records from [unique_toponyms.sql](https://github.com/DigitalAustinPapers/DigitalAustinSite/blob/master/backend/geonames/normalized_places/unique_toponyms.sql) (cf. Example 1):
```sql
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Attoyac, TX", 31.55879, -94.35798); -- US	NA	fc=PPL
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Austin, TX", 30.26715, -97.74306); -- US	NA	fc=PPLA
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Austinville, VA", 36.85123, -80.91202); -- US	NA	fc=PPL
```

Example records from (origins_and_destinations.sql)[https://github.com/DigitalAustinPapers/DigitalAustinSite/blob/master/backend/geonames/normalized_places/origins_and_destinations.sql] (cf. Examples 2 and 3):
```
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Cincinnati, OH", 39.162, -84.45689); -- US	NA	fc=PPLA2
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("City of Mexico, MO", 39.16296, -91.87103); -- US	NA	fc=ADMD
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Clinton, LA", 30.86574, -91.01566); -- US	NA	fc=PPLA2
```
_..._
```
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Matagorda, TX", 28.69082, -95.96746); -- US	NA	fc=PPL
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Matamoros Banco Number 121, TX", 25.89369, -97.51498); -- US	NA
	fc=LEV
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Mexico City, The Federal District", 19.42847, -99.12766); -- MX	NA
	fc=PPLC
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Mexico, KY", 37.22505, -88.09669); -- US	NA	fc=PPL
INSERT INTO NormalizedPlace (name, lat, lng) VALUES ("Middletown, CT", 41.56232, -72.65065); -- US	NA	fc=PPL

```




## Target Data

### Search & Browse interface
The Search filters of "Sent from" and "Sent to" and the Browse categorys "Origin" and "Destination" are populated by the list of normalized places.  This means the UI is entirely dependent on the quality of the list of normalized places (which is not always accurate -- see below).

### Map visualization
The map visualization is, again, driven off of the list of normalized places.  We queried each place in that list against the Geonames API and stored the latitude and longitude of each place.  This means that any changes we make to the NormalizedPlaces table need to have latitude and longitude either manually entered or automatically queried against Geonames.  If Geonames is "off" -- if it returns the wrong place/latitude and longitude -- then we will have the wrong values.  I don't know if that is the case for any of our normalized names, but it seems possible given that Geonames is a database of modern places and Digital Austin Papers contains places from the Nineteenth Century.

### Word count visualization
Unlike the search, browse, and map visualizations the Named Location Counts under the "Word Count" tab in the search results is based on the locations tagged in the TEI-XML text.  There should be no false matches to the normalized places; any incorrect data here is a result of the tagging in the TEI and should be corrected there.  These can be found by looking for strings similar to the following across the TEI in Github:  `<placeName>Mexico </placeName>`. Or by clicking through the search results for a dubious placename like "settlement" and pulling the document ID from each document listed and editing each document's instance of the dubious placename in Github.  (This would be labor intensive.)

## Process

### XML Processing
Once the raw XML files have been cleaned and converted into TEI P5, each file 
is ingested into the DigitalAustinPapers system via 
[backend/batchSubmit.php](https://github.com/DigitalAustinPapers/DigitalAustinSite/blob/master/backend/batchSubmit.php)

This script attempts to merge the `origin` and `destination` placename strings 
against those places that already exist in the database table `NormalizedPlace`
(see above).  The toponyms in `NormalizedPlace` are considered canonical -- for
the purposes of this algorithm, no other toponyms exist, so each metadata placename
string is matched against every placename in `NormalizedPlace` and the record 
with the best the Jaccard Similarity is returned as the 'correct' place.

This process assumes the reliability of the data in `NormalizedPlace` -- a bad
assumption.  For Example 1, the process is successful: The non-standard
verbatim toponym `"Austin Ville, VA"` is matched against the normalized
toponym `"Austinville, VA"`, and the correct deduplication and standardization 
are made, so the (presumably) correct geocode can be used for mapping and the
modern/standardized name can be used in the browse interface and search filters.

For Examples 2 and 3 the algorithm fails.  `"Mexico City, MX"` has a highest
Jaccard Similarity to `"City of Mexico, MO"`, likely due to the additional 
length of `The Federal District` in the correct record in `NormalizedPlace`.
Simply eliminating the flytrap record `"City of Mexico, MO"` from the database
has a good chance to fix this association.  In the case of Example 3, `Mexico`
is most similar to `"Mexico, KY"` in `NormalizedPlace`, resulting in another
incorrect match.  Adding a record for the toponym `"Mexico"`--containing the 
lat/long for the centroid of the country of Mexico--would certainly fix this
misassociation.


