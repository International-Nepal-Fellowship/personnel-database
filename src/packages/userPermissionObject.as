package packages
{
	public class userPermissionObject
	{
		[Bindable]	public var tabName: String;		
		[Bindable]  public var addStatus: Boolean=false;		
		[Bindable]	public var deleteStatus: Boolean=false;
		[Bindable]  public var editStatus: Boolean=false;		
		[Bindable]	public var viewStatus: Boolean=false;
						
		public function userPermissionObject()
		{
		}
	


	}
}
