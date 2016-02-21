
	$('document').ready(function(){

		// highlite menu items in the processing section
		$("#contacts").siblings("nav").find("a:contains('Contacts')").parent().addClass("active");
		$("#dates").siblings("nav").find("a:contains('Dates')").parent().addClass("active");
		$("#reminders").siblings("nav").find("a:contains('Reminders')").parent().addClass("active");
		$("#checklist").siblings("nav").find("a:contains('Checklist')").parent().addClass("active");


		// change color when hovering over list itmes
		// $("ul li").hover(function(){
		// 	$(this).toggleClass("active");
		// });



	  	$(function() {
	    	$( "#datepicker" ).datepicker({
		      numberOfMonths: 3,
		      showButtonPanel: true
	   	 });
	  });

	  	// show individual signers on checklist
	  	$(".show_signers").click(function(e){
	  		console.log('show signers');
	  		$(this).parent().parent().next(".signers").toggle();
	  		e.preventDefault();
	  	});

  		// // first hide the rows 
  		// $("#item_search").click(function(){
  		// 	$("tr td").parent().hide();
  		// });

	  	$("#item_search").keyup(function(){

	  		// first hide 
  			$("tr td").parent().hide();

	  		// select upper or lower case text
	  		$.expr[':'].icontains = function(a, i, m) {
			  return $(a).text().toUpperCase()
			      .indexOf(m[3].toUpperCase()) >= 0;
			};

	  		var s = $("#item_search").val();
	  		var searchCriteria = s.toLowerCase();
	  		console.log(searchCriteria);

	  		// show the divs that contain the searchCriteria
	  		$(".search_name:icontains("+searchCriteria+")").parent().toggle();
	  	});

	  	// show form fields to add items in Proc windows
	  	$("#add_item").click(function(){
	  		console.log('toggle');
	  		$("#item").toggle();
	  	});

	  	// find existing forms based on form input
	      $("#description").keyup(function(e){
	      	console.log('keyup');
	      	$("#search_item_results").empty();
	        var itemName = $("#description").val();
	        // send ajax request to php and get results
	        $.get(
	          // "http://dev.tc-helper2/proc/form_list", 
	          "http://tc.srsample.us/proc/form_list", //production
	          {short_name: itemName}
	        )
	        .done(successFn)
	        .fail(errorFn)
	        .always(function(data, textStatus, jqXHR){
	          console.log('request complete');
	        });

	        function successFn(result){
	          console.log("success");
	          $.each(result, function(i,item){
	            console.log(item.heading);
	            $("#search_item_results").append('<li value="'+item.id+'"><a href="#">'+item.heading+' '+item.body+'</a></li>');
	          });

		      $("#search_item_results li a").click(function(){
	      		$("#search_item_results").empty();
		      	var id = $(this).parent().val();
		      	var item = $(this).text();
		      	console.log('item id ' +id);
		      	$("#item_id").val(id);
		      	$("#description").val(item);
		      	console.log(item);
		      });
	        }

	        function errorFn(xhr, status, strErr){
	          console.log('there was an error' + xhr + status + strErr);
	        }
	      });

			$("#add_list_item").click(function(){
				$("#add_item_form").toggle();
			});
	  	
	  	// submit checklist form
	  	$("#save").click(function(){
	  		$("#update_checklist_status").submit();
	  	});

	  	$(".del").click(function()
	  	{
	  		// first delete the item
	  		var hr  = '/admin/index/forms/del_item'; 
	  		console.log(hr);
	  		var c = confirm("Ok to delete this item?"); // confimr ok to delete
	  		if(c == true){
	  			var cId =$(this).parent().siblings(".id").text();  // get the id
	  			var d = {id: cId}; // set up the data array
	  			console.log('id' + cId);
	  			$.post(hr, d, function(result){
	  				console.log(result);
	  				console.log('done');
	  			})

					  .fail(function() {

					    alert( "error" );

					  })

					  .always(function() {

					    alert( "finished" );

					});
	  			// now remove the item from view
	  			$(this).parent().parent().remove();
	  		}
	  	});


	  	// $(".del_btn").click(function(evt)
	  	// {

  		// 	evt.preventDefault();

	  	// 	var c = confirm("Ok to delete this item?"); // confimr ok to delete
	  	// 	if(c == true){
	  	// 		$(this).siblings("a.del_link").trigger("click");
	  	// 		// $(this).css('background-color','red');
	  	// 	}
	  	// });

	  	// toggle "edit" form on table lists

		$('.edit').click(function(e){
		e.preventDefault();
			$(this).parent().siblings().find('.show_form').toggle();
		});

		// // select templates 

	 //    var itemsList= $("#item_list tr:last");

	 //    $(".dropdown li").click(function(){
	 //      var itemID = $(this).val();
	 //      var d = {
	 //        id: itemID
	 //      };

	 //      console.log(itemID);

	 //      $.ajax({
	 //        type: "POST",
	 //        url: "http://dev.tc-helper2/index.php/proc/select_template",
	 //        data: d,
	 //        success: function(data){              // data can be any name its a new variable
	 //          $.each(data, function(i,item){     //i is index ... standard
	 //            itemsList.after('<tr><td><input type="checkbox" name="items[]" checked value="'+item.item+'"></td><td>'+item.heading+'</td><td>'+item.body+'</td></tr>');
	 //         	console.log(item.item);
	 //          });
	 //        }
	 //      });
	 //    });

	 //    function alertSuccess(){
	 //      console.log("hello");
	 //    }


	  	// find existing contacts
	      $("#first_name").keyup(function(e){
	      	console.log("clicked first_name");
	      	$("#search_item_results").empty();
	        var itemName = $("#first_name").val();
	        // send ajax request to php and get results
	        $.get(
	          "http://dev.tc-helper2/proc/contact_list", 
	          // "http://tc.srsample.us/proc/contact_list", //production
	          {first_name: itemName}
	        )
	        .done(successFn)
	        .fail(errorFn)
	        .always(function(data, textStatus, jqXHR){
	          console.log('request complete');
	        });

	        function successFn(result){
	          console.log("success");
	          $.each(result, function(i,item){
	            console.log(item.first_name);
	            $("#search_item_results").append('<li value="'+item.id+'"><a href="#"><span id="first_name1"> '+item.first_name+' </span><span id ="last_name1"> '+item.last_name+' </span><span id= "email1">'+item.email+'</span></a></li>');
	          });

		      $("#search_item_results li a").click(function(){
	      		$("#search_item_results").empty();
		      	var id = $(this).parent().val();
		      	var first_name = $('#first_name1',this).text();
		      	var last_name = $('#last_name1',this).text();
		      	var email = $('#email1',this).text();
		      	console.log('first_name1' +first_name);
		      	$("#item_id").val(id);
		      	$("#first_name").val(first_name);
		      	$("#last_name").val(last_name);
		      	$("#email").val(email);
		      	console.log(item);
		      });
	        }

	        function errorFn(xhr, status, strErr){
	          console.log('there was an error' + xhr + status + strErr);
	        }
	      });


		$(".complete:contains(Incomplete)").css('background-color','yellow');

	});