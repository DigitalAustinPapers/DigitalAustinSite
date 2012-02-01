import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.InputStreamReader;
import java.util.HashMap;
import java.util.LinkedList;

import org.w3c.dom.*;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;

import org.xml.sax.SAXParseException;

public class ReadXML{
	
	DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
    DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
    Document doc;
    
    HashMap<String, String> normalized = new HashMap<String, String>();
    
    Database database = null;
    
    File currFile;
    String filename;
    
    
    // constructor
    public ReadXML(String filename, Database database) throws Exception{
    	this.filename = filename;
    	
    	currFile = new File(filename);
    	
    	// create database object
    	this.database = database;
    	
    	// read file to string
        String fileAsString = readFileToString();
        
        // read normalized origins file
        readOrigins();
    	
        // fix some things and overwrite file
        writeFile(fileAsString);
        
        // parse/rename if parse fails
        parseFile(fileAsString);
        
    }

    
    // read normalized origins file
    public void readOrigins(){
    	try{
    		// Open the file
    		FileInputStream fstream = new FileInputStream("Normalized_Origins.txt");
    		// Get the object of DataInputStream
    		DataInputStream in = new DataInputStream(fstream);
    		BufferedReader br = new BufferedReader(new InputStreamReader(in));
    		String strLine;
    		//Read File Line By Line
    		String placeName = br.readLine();
    		String coordinateString = "";
    		while ((strLine = br.readLine()) != null){
    			if(strLine.length() == 0){
    				// get the normalized place name
    				placeName = br.readLine().trim();
    				// get the coordinates as string
    				coordinateString = br.readLine();
    				// separate coordinates by comma
    				String[] latLongString = coordinateString.split( ",\\s*" );
    				// add to database
    				database.insert("INSERT INTO coordinates (idCity, lat, lon) VALUES ('" + placeName + "', " + latLongString[0] + ", " + latLongString[1] + ")");
    				
    			}
    			else{
    				normalized.put(strLine.trim(), placeName);
    			}
    			// Print the content on the console
    			//System.out.println(strLine + ": " + placeName + ": " + coordinateString);
    		}
    		//Close the input stream
    		in.close();
    	}catch (Exception e){//Catch exception if any
    		//
    		System.err.println("Error: " + e.getMessage());
    	}
    }

    // get normalized place name
    public String getNormalized(String place){
    	return normalized.get(place.trim());
    }
    
    // get tag value by tag name
    public String getTag(String tagName) throws Exception{
    	NodeList listOfResults = doc.getElementsByTagName(tagName);
    	String result = "";
    	//try{
    		result = listOfResults.item(0).getFirstChild().getNodeValue();
    	//} catch(Exception e){
    	//	copyFile(readFileToString());
    	//	deleteFile();
    	//	System.exit(0);
    	//}
    	result = result.replace("\"", "\\\"");
    	result = result.replace("'", "\\'");
        return result;
    }
    
    // get author
    public String getAuthor(HashMap<String, Boolean> authorHash, Database database){
    	NodeList listOfResults = doc.getElementsByTagName("document_author");
    	String result = "";
    	//try{
    		result = listOfResults.item(0).getFirstChild().getNodeValue();
    	//} catch(Exception e){
    	//	copyFile(readFileToString());
    	//	deleteFile();
    	//	System.exit(0);
    	//}
    	result = result.replace("\"", "\\\"");
    	result = result.replace("'", "\\'");
    	result = result.replace(".", "");
    	
    	result = result.toLowerCase();
    	String authorString = result;
    	
    	result = result.replace(" and ", ",");
    	result = result.replace("/", ",");
    	result = result.replace("  ", " ");
    	
    	String[] authors = result.split(",");
    	
    	for(String author : authors){
    		author = author.trim();
    		// if previously unseen, add to database
    		if(!authorHash.containsKey(author) && author.length() > 1){
    			authorHash.put(author, true);
    			database.insert("INSERT INTO authors(author) VALUES('" + author + "')");
    		}
    	}
    	
    	return authorString;
    }
    
    // get recipient
    public String getRecipient(HashMap<String, Boolean> recipientHash, Database database){
    	NodeList listOfResults = doc.getElementsByTagName("document_recipient");
    	String result = "";
    	//try{
    		result = listOfResults.item(0).getFirstChild().getNodeValue();
    	//} catch(Exception e){
    	//	copyFile(readFileToString());
    	//	deleteFile();
    	//	System.exit(0);
    	//}
    	result = result.replace("\"", "\\\"");
    	result = result.replace("'", "\\'");
    	result = result.replace(".", "");
    	
    	result = result.toLowerCase();
    	
    	if(result.endsWith("(")){
    		result = result.substring(0, result.length() - 1);
    	}
    	
    	String recipientString = result;
    	
    	result = result.replace(" and ", ",");
    	result = result.replace("/", ",");
    	result = result.replace("  ", " ");
    	
    	String[] recipients = result.split(",");
    	
    	for(String recipient : recipients){
    		recipient = recipient.trim();
    		// if previously unseen, add to database
    		if(!recipientHash.containsKey(recipient) && recipient.length() > 1){
    			recipientHash.put(recipient, true);
    			database.insert("INSERT INTO recipients(recipient) VALUES('" + recipient + "')");
    		}
    	}
    	
    	return recipientString;
    }
    
    // get people mentioned
    public void getPeopleMentioned(String id, String text, HashMap<String, LinkedList<String>> people){
    	// make temp list to track duplicate entries within document
    	HashMap<String, Boolean> temp = new HashMap<String, Boolean>();
    	
    	int currIndex = 0;
    	int endIndex = 0;
    	
    	while(text.indexOf("*person", currIndex) > 0){
    		currIndex = text.indexOf("*person", currIndex) + 18;
    		endIndex = text.indexOf("*/person", currIndex);
    		
    		if(endIndex < 0){
    			endIndex = currIndex;
    		}
    		
    		// get content
    		String person = text.substring(currIndex, endIndex);
    		// make lower case/remove punctuation
    		person = person.toLowerCase();
    		person = person.replaceAll("[.',-\\[\\]]", "");
    		person = person.trim();
    		
    		//System.out.println(person);
    		if(!temp.containsKey(person)){
    			// add to temp list
    			temp.put(person, true);
    			// add to permanent list (person -> id)
    			if(!people.containsKey(person)){
    				LinkedList<String> list = new LinkedList<String>();
    				list.add(id);
    				people.put(person, list);
    			}
    			else{
    				people.get(person).add(id);
    			}
    			
    			// add to permanent list (id -> person)
    			if(!people.containsKey(id)){
    				LinkedList<String> list = new LinkedList<String>();
    				list.add(person);
    				people.put(id, list);
    			}
    			else{
    				people.get(id).add(person);
    			}
    		}
    	}
    }
    
    // get normalized sent_from location
    public String getNormalizedFrom(String sent_from){
    	
    	
    	return "";
    }
    
    // get normalized sent_to location
    public String getNormalizedTo(String sent_to){
    	return "";
    }
    
    // get locations mentioned
    public void getLocationsMentioned(String id, String text, HashMap<String, LinkedList<String>> places){
    	// make temp list to track duplicate entries within document
    	HashMap<String, Boolean> temp = new HashMap<String, Boolean>();
    	
    	int currIndex = 0;
    	int endIndex = 0;
    	
    	while(text.indexOf("*location", currIndex) > 0){
    		currIndex = text.indexOf("*location", currIndex) + 20;
    		endIndex = text.indexOf("*/location", currIndex);
    		
    		if(endIndex < 0){
    			endIndex = currIndex;
    		}
    		
    		// get content
    		String location = text.substring(currIndex, endIndex);
    		// make lower case/remove punctuation
    		location = location.toLowerCase();
    		location = location.replaceAll("[.',-\\[\\]]", "");
    		location = location.trim();
    		
    		//System.out.println(person);
    		if(!temp.containsKey(location)){
    			// add to temp list
    			temp.put(location, true);
    			// add to permanent list (location -> id)
    			if(!places.containsKey(location)){
    				LinkedList<String> list = new LinkedList<String>();
    				list.add(id);
    				places.put(location, list);
    			}
    			else{
    				places.get(location).add(id);
    			}
    			
    			// add to permanent list (id -> location)
    			if(!places.containsKey(id)){
    				LinkedList<String> list = new LinkedList<String>();
    				list.add(location);
    				places.put(id, list);
    			}
    			else{
    				places.get(id).add(location);
    			}
    		}
    	}
    }
    
    private String readFileToString() throws Exception{
    	// read file into string
    	FileInputStream currFileStream = new FileInputStream(filename);
    	String fileAsString;
    	
    	byte[] buffer = new byte[(int) currFile.length()];
        BufferedInputStream f = null;
        try {
            f = new BufferedInputStream(currFileStream);
            f.read(buffer);
            
            // get rid of unmatched tag '</titleStmt>'
            fileAsString = new String(buffer);
            fileAsString = fileAsString.replaceAll("</titleStmt>", "");
            fileAsString = fileAsString.replaceAll("</</person_mentioned>p>", "</person_mentioned></p>");
            fileAsString = fileAsString.replaceAll("</</date_mentioned>p>", "</date_mentioned></p>");
            fileAsString = fileAsString.replaceAll("</</location_mentioned>p>", "</location_mentioned></p>");
            fileAsString = fileAsString.replaceAll("</person_mentioned>p>", "</person_mentioned></p>");
            fileAsString = fileAsString.replaceAll("<p>", "*p*");
            fileAsString = fileAsString.replaceAll("</p>", "*/p*");
            fileAsString = fileAsString.replaceAll("<div1 type=\"body\">", "*div1 type=\"body\"*");
            fileAsString = fileAsString.replaceAll("<div1 type=\"summary\">", "*div1 type=\"summary\"*");
            fileAsString = fileAsString.replaceAll("</div1>", "*/div1*");
            fileAsString = fileAsString.replaceAll("<person_mentioned>", "*person_mentioned*");
            fileAsString = fileAsString.replaceAll("</person_mentioned>", "*/person_mentioned*");
            fileAsString = fileAsString.replaceAll("<location_mentioned>", "*location_mentioned*");
            fileAsString = fileAsString.replaceAll("</location_mentioned>", "*/location_mentioned*");
            fileAsString = fileAsString.replaceAll("<date_mentioned", "*date_mentioned");
            fileAsString = fileAsString.replaceAll("</date_mentioned>", "*/date_mentioned*");
            
            
        } finally {
            if (f != null) try {
            	f.close();
            	f = null;
            	currFileStream.close();
            	currFileStream = null;
            } catch (Exception e) {
            	System.err.println(e);
            }
        }
        
        return fileAsString;
    }
    
    private void writeFile(String fileAsString) throws Exception{
    	FileWriter fstream = null;
        BufferedWriter out = null;
        
        // write file
        try{
        	  // Create file 
        	  fstream = new FileWriter(filename);
        	  out = new BufferedWriter(fstream);
        	  out.write(fileAsString);
        } catch (Exception e){
        	System.err.println(e);
        } finally{
        	out.close();
        	out = null;
        	fstream.close();
        	fstream = null;
        }
    }
    
    // parse file
    private void parseFile(String fileAsString) throws Exception{
         try{
         	doc = docBuilder.parse(currFile);
         	doc.getDocumentElement().normalize();
         }catch(SAXParseException e){
         	System.err.println(e);
            
         	// Create new prepended file
         	copyFile(fileAsString);
       	    
         	// Delete original file
       	    deleteFile();
         }
    }
    
    // copy current file, prepend 'Z' to name
    private void copyFile(String fileAsString) throws Exception{
    	String newName = filename.substring(0, 7)+'Z'+filename.substring(7,18);
   	    FileWriter fstream = new FileWriter(newName);
   	    BufferedWriter out = new BufferedWriter(fstream);
   	    out.write(fileAsString);
   	    
   	    // Close the output stream
   	    out.close();
   	    out = null;
   	    fstream.close();
   	    fstream = null;
    }
    
    // delete file
    private void deleteFile() throws Exception{
    	
    	/*
    	 * There's some weird java bug where mixing
    	 * certain kinds of input readers doesn't release
    	 * the file handle properly and so it won't delete
    	 * the file
    	 * 
    	try{
    		System.gc();
    		currFile.delete();
    		System.exit(0);
    	}catch(Exception e){
    		System.err.println(e);
    	}
    	*/
    	
    	// workaround to delete file
    	String absolutePath = currFile.getAbsolutePath();
    	absolutePath = "cmd /c del " + absolutePath;
    	System.out.println(absolutePath);
    	Runtime run = Runtime.getRuntime();
    	run.exec(absolutePath);
    }
}