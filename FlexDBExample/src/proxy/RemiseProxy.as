package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;
	
	public class RemiseProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _remiseHospitalier:ArrayCollection = new ArrayCollection;
		private static var _remiseMalade:ArrayCollection = new ArrayCollection;

		private static var query   :IQuery;
		
		
		private static function queryError(evt:Event):void
		{
			Alert.show(query.getError());
		}
		
		public static function loadRemise():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provideModuleDispo);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select * from remise where id_pele =" + index.peleActuel.id_pele )
		}
		
		private static function provideModuleDispo(evt:Object):void
		{
			_remiseHospitalier = new ArrayCollection();
			_remiseMalade = new ArrayCollection();
			var result:ArrayCollection = query.getRecords();
			for(var i:int=0;i<result.length;i++)
			{
				if(result[i].type == "hospitalier")
					_remiseHospitalier.addItem(result[i]);
				else
					_remiseMalade.addItem(result[i]);
			}
		}
		
		public static function get RemiseHospitalier():ArrayCollection
		{
			return _remiseHospitalier;
		}
		
		public static function get RemiseMalade():ArrayCollection
		{
			return _remiseMalade;
		}
		
		public static function addRemise(remise:Object):void
		{
			if(remise.type == "hospitalier")
				_remiseHospitalier.addItem(remise);
			else
				_remiseMalade.addItem(remise);
		}
		
	}
}