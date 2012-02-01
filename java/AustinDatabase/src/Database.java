import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

// the database class establishes/closes
// connections, performs inserts and runs
// SQL scripts
public class Database {
	
	Connection conn = null;

	public void connect(){
	    try
	    {
	        String userName = "root";
	        String password = "";
	        String url = "jdbc:mysql://127.0.0.1:3306/";
	        Class.forName ("com.mysql.jdbc.Driver").newInstance();
	        conn = DriverManager.getConnection(url, userName, password);
	        System.out.println ("Database connection established");
	    }
	    catch (Exception e)
	    {
	        System.err.println ("Cannot connect to database server");
	    }
	}
	    
	public void close(){
        if (conn != null)
        {
            try
            {
                conn.close ();
                System.out.println ("Database connection terminated");
            }
            catch (Exception e) { /* ignore close errors */ }
        }
	}
	
	public void insert(String query){
		if(conn != null){
			try{
				Statement prepareStatement = conn.prepareStatement(query);
				prepareStatement.executeUpdate(query);
			} catch (SQLException ex) {
				System.out.println(ex);
			}
		}
	}
	
	public void runScript(String filename) throws FileNotFoundException, IOException, SQLException{
		ScriptRunner runner = new ScriptRunner(conn, false, true);
		runner.runScript(new BufferedReader(new FileReader(filename)));
	}
}