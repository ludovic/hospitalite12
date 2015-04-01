package proxy
{
	import flash.events.Event;
	
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import phi.interfaces.IQuery;
	
	public class HotelProxy
	{
		import phi.db.Database;
		import phi.db.Query;
		import phi.interfaces.IDatabase;
		import phi.interfaces.IQuery;
		
		private  static var db       :IDatabase;
		private static var _hotel:ArrayCollection 			 = new ArrayCollection;
		private static var _hotelPelerin:ArrayCollection	 = new ArrayCollection;
		private static var _hotelHospitalier:ArrayCollection = new ArrayCollection;
		private static var query    :IQuery;
		
		public static function loadHotel():void
		{
			db = Database.getInstance();
			query= new Query();
			query.connect("conn1", db);
			query.addEventListener(Query.QUERY_END, provideHotel);
			query.addEventListener(Query.QUERY_ERROR,queryError);
			query.execute("Select * from hebergement order by Libelle" )
		}
		
		private static function provideHotel(evt:Object):void
		{
			_hotelPelerin	 	= new ArrayCollection;
			_hotelHospitalier   = new ArrayCollection;
			
			_hotel = query.getRecords();
			for(var i:int=0;i<_hotel.length;++i)
			{
				if(_hotel[i].type == "pelerin")
					_hotelPelerin.addItem(_hotel[i]);
				else
					_hotelHospitalier.addItem(_hotel[i]);
			}
		}
		private static function queryError(evt:Event):void
		{
			Alert.show(query.getError());
		}
		
		public static function get Hotel():ArrayCollection
		{
			return _hotel;
		}
		
		public static function get HotelPelerin():ArrayCollection
		{
			return _hotelPelerin;
		}
		
		public static function get HotelHospitalier():ArrayCollection
		{
			return _hotelHospitalier;
		}
		
	}
}