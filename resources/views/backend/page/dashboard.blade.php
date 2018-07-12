@extends('backend.layouts.master')

@section('content')
    <section class="content">
    	<div class="dashboard-rate-box">
    		<div class="row">

          <a href="">
		        <div class="col-md-4 col-sm-4 col-xs-12">
		          	<div class="info-box info-box1">
			            <div class="info-box-content">
			              	<span class="info-box-number">
                       
                      </span>
			              	<span class="info-box-text">Total User</span>
			            </div>
		          	</div>
		        </div>
          </a>
          <a href="">
	        	<div class="col-md-4 col-sm-4 col-xs-12">
	          		<div class="info-box info-box2">
            			<div class="info-box-content">
	              			<span class="info-box-number">
                      
                       </span>
	              			<span class="info-box-text">Reported Event</span>
	            		</div>
	          		</div>
		        </div>
            </a>
	        	<div class="clearfix visible-sm-block"></div>
            <a href="">
	        	<div class="col-md-4 col-sm-4 col-xs-12">
	          		<div class="info-box info-box3">
	            		<div class="info-box-content">
	              			<span class="info-box-number">
                       
                      </span>
	              			<span class="info-box-text">Register TR Users</span>
	            		</div>
	          		</div>
	        	</div>
            </a>
	      	</div>
      	</div>
      	<!--  -->
      	<div class="catagory-section box-shadow white-bg">
      		<ul>
      			<li>
             <a href="">
      				<div class="catagory-image">
      					<span><img src="{{ asset('/backend/dist/img/dashboard-catagory-icon01.png') }}" /></span>
      				</div>
              
      				<div class="catagory-text">
      					<p>Popular Location</p>
      					<span>
                  
                </span>
      				</div>
              </a>
      			</li>
      			<li>
             <a href="">
      				<div class="catagory-image">
      					<span><img src="{{ asset('/backend/dist/img/dashboard-catagory-icon02.png') }}" /></span>
      				</div>
      				<div class="catagory-text">
      					<p>No Of Reports Created</p>
      					<span>
                
                </span>
      				</div>
              </a>
      			</li>
      			<li>
      				<a href=""><div class="catagory-image">
      					<span><img src="{{ asset('/backend/dist/img/dashboard-catagory-icon04.png') }}" /></span>
      				</div>
      				<div class="catagory-text">
      					<p>Sub-Admins Created</p>
      					<span>
                
                Sub-Admin</span>
      				</div></a>
      			</li>
      		</ul>
      	</div>
        <div class="box-header people-involved-header">
          <h3 class="box-title">People Involved</h3>
    </div>   
    <div class="catagory-section box-shadow white-bg"> 
      <div class="people-involved-section">
      <a href="">
        <ul>
        
          
        </ul>
        </a>
      </div>
    </div>
      </section>
      
     <style type="text/css">
    .dataTables_filter {
    display: none; 
    }
    .sorting_asc {
    display: none; 
    }
    .dataTables_length
    {
    display: none; 
    }
    </style>
@endsection
@push('inlinescript')
@endpush
@section('requirejs')
 
@endsection
