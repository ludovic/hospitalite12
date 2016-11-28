package common.helper
{
	import phi.framework.sql.SQLConnectionManager;
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	import phi.framework.sql.SQLStatement;
	
	public class QueryHelper
	{
		public static function execute(query:String,result:Function, error:Function):void {
			var sqlStatement :SQLStatement = new SQLStatement();
			sqlStatement.sqlConnection = SQLConnectionManager.getInstance().getDefaultConnection();
			sqlStatement.text = query;
			sqlStatement.addEventListener( SQLEvent.SQL_RESULT, result );
			sqlStatement.addEventListener( SQLErrorEvent.SQL_ERROR, error );
			sqlStatement.execute();
		}
			
	}
}