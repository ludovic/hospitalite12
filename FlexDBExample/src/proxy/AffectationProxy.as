package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	import phi.interfaces.IQuery;

	public class AffectationProxy
	{
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _affectation:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		
		public static function loadAffectation():void
		{	
			QueryHelper.execute("Select id_affectation,Service from affectation",provideAffectation, queryError);
		}
		
		private static function provideAffectation(event:SQLEvent):void
		{
			_affectation = new ArrayCollection(event.result.data);
		}
		private static function queryError(event:SQLErrorEvent):void
		{
			Alert.show(event.error);
		}
		
		public static function get Affectation():ArrayCollection
		{
			return _affectation;
		}
	}
}