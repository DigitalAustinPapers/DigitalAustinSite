import java.util.HashMap;
import java.util.LinkedList;
import java.util.StringTokenizer;

public class Indexer {
	HashMap<String, LinkedList<String>> index = new HashMap<String, LinkedList<String>>();
	
	public void addText(String id, String text){
		StringTokenizer tokenizer = new StringTokenizer(text);
		
		while(tokenizer.hasMoreTokens()){
			String token = tokenizer.nextToken();
			token = token.toLowerCase();
			if(!token.equals(" ") && !token.contains("*person") && !token.contains("*location") && !token.contains("*date") && !token.contains("*p*") && !token.contains("*/p") && !token.contains("*/date") && !token.contains("*/person") && !token.contains("*/location")){
				token = token.replaceAll("[^\\p{L}]", "");
				if(!index.containsKey(token)){
					LinkedList<String> list = new LinkedList<String>();
					list.add(id);
					index.put(token, list);
				}
				else if(!index.get(token).contains(id)){
					index.get(token).add(id);
				}
			}
		}
	}
	
	public HashMap<String, LinkedList<String>> getIndex(){
		return index;
	}
}
