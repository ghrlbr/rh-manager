$(document).ready(function(){

	// Edit a job in database
	$('#edit-save-button').click(function(){
		
		if($('#e1502').val() !== '')
		{
			$.ajax({
				
				url: '/controllers/jobs.php',
				method: 'put',
				data: 'identifier=' + $('#new-job-identifier').val() + '&name=title&value=' + $('#new-job-title').val(),
				
				success: function(data){
					
					switch(data['header']['status'])
					{
						case 'SUCCESS':
						
							EditJob($('#new-job-identifier').val(), 'title', $('#new-job-title').val());
							$('#new-job-identifier').val('');
							$('#new-job-title').val('');
							
							$('#new-job-title').attr('disabled', 'disabled');
						
							break;
							
							case 'ERROR':
							
								$('#new-job-info-console').html(data['header']['message']);
											
									setTimeout(function(){
											
										$('#new-job-info-console').html('');
												
									}, 1500);
							
								break;
					}
					
				}
				
			});
		}
		else
		{
			
		}
		
		return false;
		
	});

	// Search a job in database
	$('#e0150').on('input', function(){
		
		$('#jobs-container').empty();
		
		if($('#e0150').val() !== '')
		{	
			$.ajax({
				
				url: '/controllers/jobs.php',
				method: 'get',
				data: 'query=' + $('#e0150').val(),
				
				success: function(data){
					
					switch(data['header']['status'])
					{
						case 'SUCCESS':
						
							for(var i = 0; i < data['body'].length; i++)
							{
								AddJob(data['body'][i]['identifier'], data['body'][i]['title']);
							}
						
							break;
							
							case 'ERROR':
							
								$('#e0152').html(data['header']['message']);
											
									setTimeout(function(){
											
										$('#e0152').html('');
												
									}, 1500);
							
								break;
					}
					
				}
				
			});
		}
		else
		{
			// Get jobs from database
			$.ajax({
				
				url: '/controllers/jobs.php',
				method: 'get',
				
				success: function(data){
					
					switch(data['header']['status'])
					{
						case 'SUCCESS':
						
							for(var i = 0; i < data['body'].length; i++)
							{
								AddJob(data['body'][i]['identifier'], data['body'][i]['title']);
							}
						
							break;
					}
					
				}
				
			});
		}
		
		return false;
		
	});

	// Create a job in database
	$('#e0149').click(function(){
		
		var title = $('#e0150').val();
		
		if(title !== '')
		{	
			$.ajax({
				
				url: '/controllers/jobs.php',
				method: 'post',
				data: $('#new-job-form').serialize(),
				
				success: function(data){
					
					switch(data['header']['status'])
					{
						case 'SUCCESS':
						
							$.ajax({
				
								url: '/controllers/jobs.php',
								method: 'get',
								data: 'title=' + title,
								
								success: function(data){
									
									switch(data['header']['status'])
									{
										case 'SUCCESS':
										
											$('#e0150').val('');
										
											AddJob(data['body']['identifier'], data['body']['title']);
										
											break;
											
										case 'ERROR':
										
											$('#e0152').html(data['header']['message']);
											
											setTimeout(function(){
												
												$('#e0152').html('');
												
											}, 1500);
										
											break;
									}
									
								}
								
							});
						
							break;
							
							case 'ERROR':
							
								$('#e0152').html(data['header']['message']);
											
									setTimeout(function(){
											
										$('#e0152').html('');
												
									}, 1500);
							
								break;
					}
					
				}
				
			});
		}
		else
		{
			$('#e0152').html('Você precisa definir um nome para o cargo');
											
				setTimeout(function(){
												
				$('#e0152').html('');
												
			}, 1500);
		}
		
		return false;
		
	});

	// Get jobs from database
	$.ajax({
		
		url: '/controllers/jobs.php',
		method: 'get',
		
		success: function(data){
			
			switch(data['header']['status'])
			{
				case 'SUCCESS':
				
					for(var i = 0; i < data['body'].length; i++)
					{
						AddJob(data['body'][i]['identifier'], data['body'][i]['title']);
					}
				
					break;
			}
			
		}
		
	});
	
	// Get workers from database
	$.ajax({
		
		url: '/controllers/workers.php',
		method: 'get',
		
		success: function(data){
			
			// Necessário a implementação
			
		}
		
	});
	
});


function AddJob(identifier, title)
{
	var job = '<tr id="job' + identifier + '"><td class="text-center job-identifier table-row-value">' + identifier + '</td><td class="text-center job-title table-row-value">' + title + '</td><td class="text-center"><button class="actioner-button" onclick="OpenJobEditionBox(\'' + identifier + '\')"><img src="/views/medias/svgs/baseline-edit.svg"></button><button class="actioner-button" onclick="DeleteJob(\'' + identifier + '\')"><img src="/views/medias/svgs/baseline-delete-24px.svg"></button></td></tr>';
	
	$('#jobs-container').append(job);
}
function DeleteJob(identifier)
{
	$.ajax({
		
		url: '/controllers/jobs.php',
		method: 'delete',
		dataType: 'urlencoded',
		data: 'identifier=' + identifier,
		
		complete: function(data){
			
			var response = jQuery.parseJSON(data.responseText);
			
			switch(response['header']['status'])
			{
				case 'SUCCESS':
				
					$('#job' + identifier).remove();
				
					break;
					
				case 'ERROR':
				
					alert(response['header']['message']);
				
					break;
			}
			
		}
		
	});
}
function DeleteWorker(identifier)
{
	$('#worker' + identifier).remove();
}
function EditJob(identifier, name, value)
{
	window.location.href = '#edit-job-form';
	
	$('#job' + identifier + ' > .job-' + name + '').text(value);
}

function OpenJobEditionBox(identifier)
{	
	$('#new-job-title').removeAttr('disabled');

	var selectedJobIdentifier = $('#job' + identifier + ' > .job-identifier').text();
	var selectedJobTitle = $('#job' + identifier + ' > .job-title').text()

	$('#new-job-identifier').val(selectedJobIdentifier);
	$('#new-job-title').val(selectedJobTitle);
}