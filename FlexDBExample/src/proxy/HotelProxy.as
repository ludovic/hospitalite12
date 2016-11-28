package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class HotelProxy
	{
		private static var _hotel:ArrayCollection 			 = new ArrayCollection;
		private static var _hotelPelerin:ArrayCollection	 = new ArrayCollection;
		private static var _hotelHospitalier:ArrayCollection = new ArrayCollection;
		
		public static function loadHotel():void
		{
			QueryHelper.execute("Select * from hebergement order by Libelle",provideHotel, queryError);
		}
		
		private static function provideHotel(evt:SQLEvent):void
		{
			_hotelPelerin	 	= new ArrayCollection;
			_hotelHospitalier   = new ArrayCollection;
			
			_hotel = new ArrayCollection(evt.result.data);
			for(var i:int=0;i<_hotel.length;++i)
			{
				if(_hotel[i].type == "pelerin")
					_hotelPelerin.addItem(_hotel[i]);
				else
					_hotelHospitalier.addItem(_hotel[i]);
			}
		}
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
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