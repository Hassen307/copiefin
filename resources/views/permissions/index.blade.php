@extends('layouts.app')
 
@section('content')
	<div class="row">
	    <div class="col-lg-12 margin-tb">
	        <div class="pull-left">
	            <h2>Permissions Management</h2>
                    
	        </div>
	        
	    </div>
        </div>
        @if ($message = Session::get('success'))
		<div class="alert alert-success">
			<p>{{ $message }}</p>
		</div>
	@endif
        <br><br>
        {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
         {!! Form::text('permiss', null, array('placeholder' => 'New Permission','class' => 'form-control')) !!}
        
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
				<button type="submit" class="btn btn-success">Submit</button>
        </div>
         {!! Form::close() !!}
         
        
	
	<table class="table table-bordered">
		<tr>
			
			<th><b>Name</b></th>
			<th width="280px"><b>Action</b></th>
			
		</tr>
                
	@foreach ($permissi as $key => $permission)
	<tr>
            
		
		<td><b>{{ $permission }}</b></td>
                <td>
			
			
			
			
			
			{!! Form::open(['method' => 'DELETE','route' => ['permissions.destroy', $permission],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        	{!! Form::close() !!}
        	
		</td>
		
		
	</tr>
	@endforeach
	</table>
	
        </br></br></br></br></br></br></br></br></br></br></br></br>
@endsection