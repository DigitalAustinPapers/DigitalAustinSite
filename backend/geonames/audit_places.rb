#!/usr/bin/env ruby
require 'nokogiri'
require 'csv'
require 'pry'
require 'bigdecimal'







DOCUMENT_PATH = "../../../AustinTranscripts/teip5_xml"
MYSQL_CREDENTIALS = "--user=dap --password=dap --database=dap"

def places_from_xml(doc_row)
  from = nil
  to = nil
  
  xml_file = File.join(DOCUMENT_PATH, "#{doc_row['id']}.xml")
  if File.exist? xml_file
	  doc = File.open(xml_file) { |f| Nokogiri::XML(f) }
	  # find place element with type attribute whose value is "origin"
	  from = doc.xpath('//*[@type="origin"]').text
	  # find place element with type attribute whose value is "destination"
	  to = doc.xpath('//*[@type="destination"]').text
  else
    from="NO XML FILE #{xml_file}!"
    to = from
  end
  
  [from, to]
end


#################
# main
#################


MYSQL_PLACES = "select * from NormalizedPlace;"
MYSQL_DOCUMENTS = "select d.id, d.title, f.name from_place_name, d.sentFromPlace, t.name to_place_name, d.sentToPlace from Document d left outer join NormalizedPlace f on d.sentFromPlace = f.id left outer join NormalizedPlace t on d.sentToPlace = t.id;"

places_raw = `mysql #{MYSQL_CREDENTIALS} -e \"#{MYSQL_PLACES}\" --batch`
documents_raw = `mysql #{MYSQL_CREDENTIALS} -e \"#{MYSQL_DOCUMENTS}\" --batch`

place_rows = CSV.new(places_raw, :col_sep => "\t", :headers => true)
document_rows = CSV.new(places_raw, :col_sep => "\t", :headers => true)

place_hash = {}
place_rows.each { |e| place_hash[e["id"]] = e }

document_rows = CSV.new(documents_raw, :col_sep => "\t", :headers => true)

output_csv = CSV.open("document_place_audit.csv", "wb") do |out|
  out << ['DOC ID', 'DOC TITLE', 'FROM PLACE (VERBATIM)', 'FROM PLACE NAME (ASSIGNED)', 'FROM PLACE ID (ASSIGNED)', 'TO PLACE (VERBATIM)', 'TO PLACE NAME (ASSIGNED)', 'TO PLACE ID (ASSIGNED)']
  document_rows.each do |doc|
    from_place_verbatim, to_place_verbatim = places_from_xml(doc)
    out << [
      doc["id"],
      doc["title"],
      from_place_verbatim,
      doc["from_place_name"],
      doc["sentFromPlace"],
      to_place_verbatim,
      doc["to_place_name"],
      doc["sentToPlace"]
    ]    
  end


end



exit


rows = CSV.read('places.tsv', :col_sep => "\t", :headers => true)

ATTRIBUTES= ['countryName', 'fcl', 'fcode', 'fcodeName', 'toponymName', 'adminName1', 'adminName2', 'lat', 'lng']
#ATTRIBUTES= ['lat', 'lng']

print "dap id\tdap name\tdap origin count\tdap dest count\tdap lat\tdap lng\tdap use\t"
print ATTRIBUTES.join("\t")
print "\n"
rows.each do |row|
	next if row['origin_count'] == '0' && row['destination_count'] == '0'
	raw_toponym = row["name"]
	placename = raw_toponym.split(',').first
	xml_files = Dir.glob("./*/#{placename}*")
  if xml_files.size == 0
		print "#{row['id']}\t#{raw_toponym}\t#{row['origin_count']}\t#{row['destination_count']}\t#{row['lat']}\t#{row['lng']}\t"
		print "\t\t\t\t\t\t\t\t\t\tError: #{raw_toponym} has no XML file and must be manually corrected\n"
	end
	row_lat = BigDecimal.new(row['lat']).round(5).to_s('F')
	row_lng = BigDecimal.new(row['lng']).round(5).to_s('F')
#	binding.pry
	xml_files.each do |xml_file|
		doc = File.open(xml_file) { |f| Nokogiri::XML(f) }
		doc.xpath('//geoname').each do |n| 
			print "#{row['id']}\t#{raw_toponym}\t#{row['origin_count']}\t#{row['destination_count']}\t#{row['lat']}\t#{row['lng']}\t"
#			print "#{row_lat}\t#{row_lng}\t"
			geo_lat = BigDecimal.new(n.xpath('lat').text).round(5).to_s('F')
			geo_lng = BigDecimal.new(n.xpath('lng').text).round(5).to_s('F')
			if row_lat == geo_lat &&  row_lng == geo_lng
				print "USED\t"
      else
				print "\t"
			end
			print(ATTRIBUTES.map{ |a| n.xpath(""+a).text}.join("\t") + "\n")  
		end				
	end	

end


