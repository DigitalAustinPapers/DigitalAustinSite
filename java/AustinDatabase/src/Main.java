import java.io.File;
import java.util.HashMap;
import java.util.LinkedList;

// main class
public class Main {
	
	// main method
	public static void main(String [] args) throws Exception{
		
		// create database object:
		Database database = new Database();
		
		// connect to database server
		database.connect();
		
		// run SQL script
		database.runScript("create.sql");
		
		// select database
		database.insert("USE austincollection");
		
		// XML tag variables
		String currPath, day, month, year, creation, citation, status, title, digital_creation, author, recipient, summary, footnotes, barker_citation, type, sender, sent_from, sent_to, normalized_from, normalized_to, body, idAuthor, idRecipient, idPerson, name, idPlaces, place;
		
		// people and places mentioned
		HashMap<String, LinkedList<String>> peopleHash = new HashMap<String, LinkedList<String>>();
	    HashMap<String, LinkedList<String>> placesHash = new HashMap<String, LinkedList<String>>();
		HashMap<String, Boolean> authorHash = new HashMap<String, Boolean>();
		HashMap<String, Boolean> recipientHash = new HashMap<String, Boolean>();
	    
		// create indexer object
		Indexer indexer = new Indexer();
		
		// create a file object from the directory containing the XML documents
		File dir = new File("AP_XML");

		// get a list of all the XML documents
		String[] children = dir.list();

		// for each XML document
		for (int i=0; i < children.length; i++) {
		    // Get filename of file or directory
		    String currDocument = children[i];
		    
		    // skip the documents that start with 'Z'
		    // those documents have been renamed because they've been
		    // determined to contain invalid XML
		    if(!currDocument.startsWith("Z")){
		    
			    currPath = "AP_XML/" + currDocument;
			    
			    // create XML reader object using current document
			    ReadXML reader = new ReadXML(currPath, database);
				
			    // get the data from the relevant tags
				creation = reader.getTag("document_creation");
				citation = reader.getTag("document_citation");
				status = reader.getTag("document_status");
				digital_creation = reader.getTag("document_digital_creation");
				author = reader.getAuthor(authorHash, database);
				recipient = reader.getRecipient(recipientHash, database);
				title = reader.getTag("document_title");
				summary = reader.getTag("barker_summary");
				// some documents will not have barker_footnotes
				try{
					footnotes = reader.getTag("barker_footnotes");
				}catch(NullPointerException e){
					footnotes = "";
				}
				barker_citation = reader.getTag("barker_citation");
				type = reader.getTag("document_type");
				// some documents will not have document_sender
				try{
					sender = reader.getTag("document_sender");
				}catch(NullPointerException e){
					sender = "";
				}
				sent_from = reader.getTag("sent_from");
				sent_to = reader.getTag("sent_to");
				normalized_from = reader.getNormalizedFrom(sent_from);
				normalized_to = reader.getNormalizedTo(sent_to);
				body = reader.getTag("body");
				
				// get a list of the people mentioned in the current document
				reader.getPeopleMentioned(currDocument, body, peopleHash);
				// get a list of the places mentioned in the current document
				reader.getLocationsMentioned(currDocument, body, placesHash);
				
				// index the body text of the current document
				indexer.addText(currDocument, body);
				
				// format creation from DD-MM-YYYY to YYYY-MM-DD to match SQL format
				if(creation.length() == 10){
					// change unknown values from x to 0 to match SQL format
					creation = creation.replaceAll("x", "0");
					month = creation.substring(0,2);
					day = creation.substring(3,5);
					year = creation.substring(6,10);
					creation = year + '-' + month + '-' + day;
				}
				else{
					creation = "0000-00-00";
				}
				
				// format digital creation from DD-MM-YYYY to YYYY-MM-DD to match SQL format
				if(digital_creation.length() == 10){
					// change unknown values from x to 0 to match SQL format
					digital_creation = digital_creation.replaceAll("x", "0");
					month = digital_creation.substring(0,2);
					day = digital_creation.substring(3,5);
					year = digital_creation.substring(6,10);
					digital_creation = year + '-' + month + '-' + day;
				}
				else{
					digital_creation = "0000-00-00";
				}
				
				// insert data into database
				database.insert("INSERT INTO document(idDocument, creation, citation, language, status, type, sender, title, digital_creation, author, recipient) VALUES('" + currDocument + "', '" + creation + "', '"+ citation + "', " + "'English', " + "'" + status + "', '" + type + "', '" + sender + "', '" + title + "', '" + digital_creation + "', '" + author + "', '" + recipient + "')");
				database.insert("INSERT INTO text(idDocument, body, summary) VALUES('" + currDocument + "', '" + body + "', '" + summary + "')");
				database.insert("INSERT INTO place(idDocument, sent_from, sent_to, normalized_from, normalized_to) VALUES('" + currDocument + "', '" + sent_from + "', '" + sent_to + "', ' " + reader.getNormalized(sent_from) + "', '" + reader.getNormalized(sent_to) + "')");
		    }
		}
		
		// Populate people
		String people;
		
		for(String person : peopleHash.keySet()){
			people = "";
			for(String document : peopleHash.get(person)){
				people += document + ",";
			}
			people = people.substring(0, people.length()-1);
			database.insert("INSERT INTO people(person, peopleList) VALUES('" + person + "', '" + people + "')");
		}
		
		// Populate places
		String places;
		
		for(String location : placesHash.keySet()){
			places = "";
			for(String document : placesHash.get(location)){
				places += document + ",";
			}
			places = places.substring(0, places.length()-1);
			database.insert("INSERT INTO places(place, placesList) VALUES('" + location + "', '" + places + "')");
		}
		
		// Populate search index
		HashMap<String, LinkedList<String>> index = indexer.getIndex();
		String documents;
		
		for(String word : index.keySet()){
			documents = "";
			for(String document : index.get(word)){
				documents += document + ",";
			}
			documents = documents.substring(0, documents.length()-1);
			database.insert("INSERT INTO search_index(word, documentList) VALUES('" + word + "', '" + documents + "')");
		}
			
		// close connection
		database.close();
	}
}
