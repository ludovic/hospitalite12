package proxy
{
	import mx.collections.ArrayCollection;
	import mx.controls.Alert;
	
	import common.helper.QueryHelper;
	
	import phi.framework.sql.SQLErrorEvent;
	import phi.framework.sql.SQLEvent;
	
	public class RemiseProxy
	{
		private static var _remiseHospitalier:ArrayCollection = new ArrayCollection;
		private static var _remiseMalade:ArrayCollection = new ArrayCollection;

		
		private static function queryError(evt:SQLErrorEvent):void
		{
			Alert.show(evt.error);
		}
		
		public static function loadRemise():void
		{
			QueryHelper.execute("Select * from remise where id_pele =" + index.peleActuel.id_pele, provideModuleDispo, queryError)
		}
		
		private static function provideModuleDispo(evt:SQLEvent):void
		{
			_remiseHospitalier = new ArrayCollection();
			_remiseMalade = new ArrayCollection();
			var result:ArrayCollection = new ArrayCollection(evt.result.data);
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