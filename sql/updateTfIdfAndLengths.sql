
REPLACE INTO Idf (stem, idf)
SELECT stem, log(docCount/(0.01 + count(docId))) as idf
    FROM StemCount
    INNER JOIN (select count(id) as docCount FROM Document) as DocCount
    GROUP BY stem;


UPDATE StemCount, Idf
SET StemCount.tfIdf = StemCount.count * Idf.idf
WHERE StemCount.stem = Idf.stem;

UPDATE Document
INNER JOIN
(
    SELECT StemCount.docId, sqrt(sum(pow(StemCount.tfIdf,2))) as length
    FROM StemCount
    GROUP BY StemCount.docId
) as temp ON docId = Document.id
SET Document.vectorLength = temp.length
WHERE DocId = Document.Id;


SELECT Document.id as id, Document.summary as summary,
    Document.title as title, Document.creation as creation,
    sum(StemCount.tfIdf) / Document.vectorLength as similarity,
    Destination.lat as dstLat, Destination.lng as dstLng,
    Source.lat as srcLat, Source.lng as srcLng
FROM Document
INNER JOIN StemCount ON StemCount.docId = Document.id
INNER JOIN NormalizedPlace AS Destination
    ON Document.sentToPlace = Destination.id
INNER JOIN NormalizedPlace AS Source
    ON Document.sentFromPlace = Source.id
WHERE StemCount.stem in ('happy', 'sad', 'right', 'wrong')
GROUP BY Document.Id;

