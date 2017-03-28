<div class="row fullWidth">
	<div class="sixteen columns underline">
		<span class="link" id="back">&nbsp;&nbsp;Back to Gift Registry Main Page</span>
	</div>
</div>
<form id="searchForm">
	<div class="row">
						<div class=" sixteen  columns">
							<span class='sectHead'>Search our Gift Registries:</span>
						</div>
					</div>
		
					<div class="row">
						<div class="four columns field">
							First Name:<br>
								<input id="fname" name="fname" class="input req">
						</div>
			
						<div class="four columns field">
							Last Name:<br>
							<input id="lname" name="lname" class="input req">
						</div>
						
   		   		<div class="three columns field">
	   		   		Event Date:<br>
	   		   		<div class="picker">
   		   			<select name='sMonth' id='sMonth' class="req">
	   		   			<? include($_SERVER['DOCUMENT_ROOT']."/includes/month_select.php");?>
   		   			</select>
	   		   		</div>
   		   		</div>
						
				<div class="four columns field"><br>
					<div class="picker">
						<select name='sYear' id='sYear' class="req">
						<option value='0'>Year</option>"	
						<? include($_SERVER['DOCUMENT_ROOT']."/includes/year_select.php");?>
					</select>
					</div>
				</div>
			
			</div>
					
			<div class="row">
				<button class="three columns default medium pretty btn" id='search-registry'><a>Search</a></button>
			</div>

</form>
</div>
</div>
		

<div class="row">
	<div class='sixteen columns sectHead' style='height:20px;width:740px;' id='spanStatus'></div>
</div>
	
<div class="row" id="spanRegistryList"></div>
<div class="row" id="spanRegistryItems"></div>
	


