function EditWorker(identifier)
{
	window.location.href = '#edit-worker-box';
	
	$('#edit-worker-identifier').val($('#worker' + identifier + ' > .worker-identifier').text());
	$('#edit-worker-name').val($('#worker' + identifier + ' > .worker-name').text());
	$('#edit-worker-job').val($('#worker' + identifier + ' > .worker-job').attr('jobIdentifier'));
	
	$('#edit-worker-name').removeAttr('disabled');
	$('#edit-worker-job').removeAttr('disabled');
}
function DeleteWorker(identifier)
{
	// Delete worker from database
	$.ajax({
		url: '/controllers/workers.php',
		method: 'delete',
		dataType: 'urlencoded',
		data: 'identifier=' + identifier,
		
		complete: function(response){
			
			console.log(response);
			
			if(response.status == 200)
			{
				var decodedResponse = jQuery.parseJSON(response.responseText);
				
				switch(decodedResponse['header']['status'])
				{
					case 'SUCCESS':
						
						DeleteWorkerFromLocal(identifier);
					
						break;
						
					case 'ERROR':
					
						alert(decodedResponse['header']['message']);
					
						break;
				}
			}
			else
			{
				alert('Houve um problema ao requisitar o servidor.');
			}
			
		}
	});
}

$(document).ready(function(){
	
	// Get jobs from database
	$.ajax({
		url: '/controllers/jobs.php',
		method: 'get',
		dataType: 'urlencoded',
		
		complete: function(response){
			
			if(response.status == 200)
			{
				var decodedResponse = jQuery.parseJSON(response.responseText);
				
				switch(decodedResponse['header']['status'])
				{
					case 'SUCCESS':
						
						for(var i = 0; i < decodedResponse['body'].length; i++){
							
							var job = '<option id="job' + decodedResponse['body'][i]['identifier'] + '" value="' + decodedResponse['body'][i]['identifier'] + '">' + decodedResponse['body'][i]['title'] + '</option>';
							
							$('#new-worker-job').append(job);
							$('#edit-worker-job').append(job);
							
						}
					
						break;
						
					case 'ERROR':
					
						alert(decodedResponse['header']['message']);
					
						break;
				}
			}
			else
			{
				alert('Houve um problema ao requisitar o servidor.');
			}
			
		}
	});
	
	// Get workers from database
	$.ajax({
		url: '/controllers/workers.php',
		method: 'get',
		dataType: 'urlencoded',
		
		complete: function(response){
			
			if(response.status == 200)
			{
				var decodedResponse = jQuery.parseJSON(response.responseText);
				
				switch(decodedResponse['header']['status'])
				{
					case 'SUCCESS':
						
						for(var i = 0; i < decodedResponse['body'].length; i++){
							
							AddWorkerInLocal(
								decodedResponse['body'][i]['identifier'],
								decodedResponse['body'][i]['name'],
								decodedResponse['body'][i]['job']
							);
							
						}
					
						break;
						
					case 'ERROR':
					
						alert(decodedResponse['header']['message']);
					
						break;
				}
			}
			else
			{
				alert('Houve um problema ao requisitar o servidor.');
			}
			
		}
	});
	
	// Search workers in database by name
	$('#new-worker-name').on('input', function(){
		
		DeleteAllWorkersFromLocal();
		
		$.ajax({
			url: '/controllers/workers.php',
			method: 'get',
			dataType: 'urlencoded',
			data: 'query=' + $('#new-worker-name').val(),
			
			complete: function(response){
				
				if(response.status == 200)
				{
					var decodedResponse = jQuery.parseJSON(response.responseText);
					
					switch(decodedResponse['header']['status'])
					{
						case 'SUCCESS':
							
							for(var i = 0; i < decodedResponse['body'].length; i++){
								
								AddWorkerInLocal(
									decodedResponse['body'][i]['identifier'],
									decodedResponse['body'][i]['name'],
									decodedResponse['body'][i]['job']
								);
								
							}
						
							break;
							
						case 'ERROR':
						
							alert(decodedResponse['header']['message']);
						
							break;
					}
				}
				else
				{
					alert('Houve um problema ao requisitar o servidor.');
				}
				
			}
		});
		
	});
	
	// Search workers in database by job
	$('#new-worker-job').on('input', function(){
		
		DeleteAllWorkersFromLocal();
		
		$.ajax({
			url: '/controllers/workers.php',
			method: 'get',
			dataType: 'urlencoded',
			data: 'query=' + $('#new-worker-job').children('option:selected').val(),
			
			complete: function(response){
				
				if(response.status == 200)
				{
					var decodedResponse = jQuery.parseJSON(response.responseText);
					
					switch(decodedResponse['header']['status'])
					{
						case 'SUCCESS':
							
							console.log(decodedResponse['body']);
							
							for(var i = 0; i < decodedResponse['body'].length; i++){
								
								AddWorkerInLocal(
									decodedResponse['body'][i]['identifier'],
									decodedResponse['body'][i]['name'],
									decodedResponse['body'][i]['job']
								);
								
							}
						
							break;
							
						case 'ERROR':
						
							alert(decodedResponse['header']['message']);
						
							break;
					}
				}
				else
				{
					alert('Houve um problema ao requisitar o servidor.');
				}
				
			}
		});
		
	});
	
	// Edit worker in database
	$('#edit-worker-save').click(function(){
				
		$('#edit-worker-save').attr('disabled', 'disabled');
		
		// Name
		$.ajax({
			url: '/controllers/workers.php',
			method: 'put',
			dataType: 'urlencoded',
			data: 'identifier=' + $('#edit-worker-identifier').val() + '&name=name&value=' + $('#edit-worker-name').val(),
			
			complete: function(response){
				
				if(response.status == 200)
				{
					var decodedResponse = jQuery.parseJSON(response.responseText);
					
					switch(decodedResponse['header']['status'])
					{
						case 'SUCCESS':
						
							EditWorkerInLocal(
								$('#edit-worker-identifier').val(),
								$('#edit-worker-name').val(),
								$('#edit-worker-job').children('option:selected').text()
							);
							
							$('#edit-worker-identifier').val('');
							$('#edit-worker-name').val('');
							$('#edit-worker-job').val('0');
							
							$('#edit-worker-name').attr('disabled', 'disabled');
							$('#edit-worker-job').attr('disabled', 'disabled');
							
							$('#edit-worker-console').text('Informações do funcionário alteradas com sucesso');
												
							setTimeout(function(){
								$('#edit-worker-console').text('');
							}, 1500);
						
							break;
							
						case 'ERROR':
						
							$('#edit-worker-console').text(decodedResponse['header']['message']);
												
							setTimeout(function(){
								$('#edit-worker-console').text('');
							}, 1500);
						
							break;
					}
				}
				else
				{
					alert('Houve um problema ao requisitar o servidor.');
				}
				
			}
		});
		
		// Job
		$.ajax({
			url: '/controllers/workers.php',
			method: 'put',
			dataType: 'urlencoded',
			data: 'identifier=' + $('#edit-worker-identifier').val() + '&name=job&value=' + $('#edit-worker-job').val(),
			
			complete: function(response){
				
				if(response.status == 200)
				{
					var decodedResponse = jQuery.parseJSON(response.responseText);
					
					switch(decodedResponse['header']['status'])
					{
						case 'SUCCESS':
						
							// Atualizar página ou retornar worker
						
							break;
							
						case 'ERROR':
						
							alert(decodedResponse['header']['message']);
						
							break;
					}
				}
				else
				{
					alert('Houve um problema ao requisitar o servidor.');
				}
				
			}
		});
		
		$('#edit-worker-save').removeAttr('disabled');
		
		return false;
	});

	// Add worker in database
	$('#add-worker').click(function(){
		
		$('#add-worker').attr('disabled', 'disabled');
		
		$.ajax({
			url: '/controllers/workers.php',
			method: 'post',
			dataType: 'urlencoded',
			data: 'name=' + $('#new-worker-name').val() + '&job=' + $('#new-worker-job').children('option:selected').val(),
			
			complete: function(response){
				
				if(response.status == 200)
				{
					var decodedResponse = jQuery.parseJSON(response.responseText);
					
					switch(decodedResponse['header']['status'])
					{
						case 'SUCCESS':
						
							// Get worker by name to add
							$.ajax({
								url: '/controllers/workers.php',
								method: 'get',
								dataType: 'urlencoded',
								data: 'name=' + $('#new-worker-name').val(),
								
								complete: function(response){
									
									if(response.status == 200)
									{
										var decodedResponse = jQuery.parseJSON(response.responseText);
										
										switch(decodedResponse['header']['status'])
										{
											case 'SUCCESS':
											
												AddWorkerInLocal(
													decodedResponse['body']['identifier'],
													decodedResponse['body']['name'],
													decodedResponse['body']['job']
												);
												
												$('#new-worker-console').text(decodedResponse['header']['message']);
												
												setTimeout(function(){
													$('#new-worker-console').text('');
												}, 1500);
											
												break;
												
											case 'ERROR':
											
												$('#new-worker-console').text(decodedResponse['header']['message']);
												
												setTimeout(function(){
													$('#new-worker-console').text('');
												}, 1500);
											
												break;
										}
									}
									else
									{
										alert('Houve um problema ao requisitar o servidor.');
									}
									
								}
							});
						
							break;
							
						case 'ERROR':
						
							$('#new-worker-console').text(decodedResponse['header']['message']);
							
							setTimeout(function(){
								$('#new-worker-console').text('');
							}, 1500);
						
							break;
					}
				}
				else
				{
					alert('Houve um problema ao requisitar o servidor.');
				}
				
			}
		});
		
		$('#add-worker').removeAttr('disabled');
		
		return false;
	});
	
});


function DeleteAllWorkersFromLocal()
{
	$('#workers-container').empty();
}
function DeleteWorkerFromLocal(identifier)
{
	$('#worker' + identifier).remove();
}
function AddWorkerInLocal(identifier, name, job)
{
	var jobIdentifier = job['identifier'];
	var jobTitle = job['title'];
	
	var worker = '<tr id="worker' + identifier + '"><td class="text-center worker-identifier table-row-value">' + identifier + '</td><td class="text-center worker-name table-row-value">' + name + '</td><td class="text-center worker-job table-row-value" jobIdentifier="' + jobIdentifier + '">' + jobTitle + '</td><td class="text-center table-row-value"><button class="actioner-button" onclick="EditWorker(\'' + identifier + '\')"><img src="/views/medias/svgs/baseline-edit.svg"></button><button class="actioner-button" onclick="DeleteWorker(\'' + identifier + '\')"><img src="/views/medias/svgs/baseline-delete-24px.svg"></button></td></tr>';
	$('#workers-container').append(worker);
}
function EditWorkerInLocal(identifier, name, job)
{	
	$('#worker' + identifier + ' > .worker-identifier').text(identifier);
	$('#worker' + identifier + ' > .worker-name').text(name);
	$('#worker' + identifier + ' > .worker-job').text(job);
}