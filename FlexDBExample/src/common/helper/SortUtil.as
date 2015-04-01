package common.helper
{
	import mx.collections.Sort;
	import mx.collections.SortField;

	public class SortUtil
	{
		public static function nomPrenomSort():Sort
		{
			var dataSortField:SortField = new SortField();
			dataSortField.name = "Nom";
			dataSortField.caseInsensitive = true;
			var dataSortField2:SortField = new SortField();
			dataSortField2.name = "Prenom";
			dataSortField2.caseInsensitive = true;
			
			var alphaDataSort:Sort = new Sort();
			alphaDataSort.fields = [dataSortField,dataSortField2];
			
			return alphaDataSort;
		}
		
		public static function minNomPrenomSort():Sort
		{
			var dataSortField:SortField = new SortField();
			dataSortField.name = "nom";
			dataSortField.caseInsensitive = true;
			var dataSortField2:SortField = new SortField();
			dataSortField2.name = "prenom";
			dataSortField2.caseInsensitive = true;
			
			var alphaDataSort:Sort = new Sort();
			alphaDataSort.fields = [dataSortField,dataSortField2];
			
			return alphaDataSort;
		}
	}
}