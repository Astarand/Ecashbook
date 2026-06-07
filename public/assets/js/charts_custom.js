

$(function() {
	var base_url = $("#base_url").val();
	$.ajaxSetup({
		headers: {
		  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	if(window.location.href == base_url+'/company-assignment'){
		getAssignRequestChart();	
	}

});

	function assign_chart(data1, data2 ,categories){

		data1 = data1.split(",");
		data2 = data2.split(",");
		categories = categories.split(",");
		
		//var columnCtx = document.getElementById("sales_chart"),
		var columnCtx = document.getElementById("assign_chart"),
			columnConfig = {
				colors: ['#28A745', '#DC3545'],
				series: [{
					name: "Accepted",
					type: "column",
					data: data1
				}, {
					name: "Rejected",
					type: "column",
					data: data2
				}],
				chart: {
					height: 270,
					type: "bar",
					toolbar: {
						show: false,
					},
				},
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: "75%",
						borderRadius: 2,
						borderRadiusApplication: "end",
					},
				},
				legend: {
					show: true,
					position: "bottom",
				},
				dataLabels: {
					enabled: false,
				},
				stroke: {
					show: true,
					width: 1,
					colors: ["transparent"],
				},
				fill: {
					type: "gradient",
					gradient: {
						type: "vertical",
						stops: [0, 100],
						shadeIntensity: 0.5,
						gradientToColors: ["#28C76F", "#F86D7D"],
					},
				},
				grid: {
					strokeDashArray: 4,
				},
				xaxis: {
					categories: categories, 
				},
				yaxis: {
					labels: {
						formatter: function (val) {
							return val; // Custom y-axis scaling
						},
					},
				},
				tooltip: {
					y: {
						formatter: function (val) {
							return " " + val + " customer"; // Tooltip formatting
						},
					},
				}
				
			};
		var columnChart = new ApexCharts(columnCtx, columnConfig);
		columnChart.render();
				
	}

	function getAssignRequestChart()
	{
		var base_url = $("#base_url").val();
		//var type = $(el).data('type');
		var type = $("#compAssignList option:selected").val();
		//alert(type);
		type = (type !=undefined)?type:'monthly';
		var active = "";
		var rejected = "";
		var categories = "";
		var separeted1 = "";
		var separeted2 = "";
		var sum1 = 0;
		var sum2 = 0;
		$("#assign_chart").html('');
		$.ajax({
			url: base_url + '/getAssignRequestChart',
			type:'POST',
			async: true,
			cache: false,
			data:{'type': type},
			success: function(res) {
				//console.log(res);
				 active = res.active;
				 rejected = res.rejected;
				 categories = res.categories;
				 separeted1 = active.split(",");
				 separeted2 = rejected.split(",");
				 sum1 = 0;
				 sum2 = 0;
				for (var i = 0; i < separeted1.length; i++) {
					sum1 += parseInt(separeted1[i].toString().match(/(\d+)/));
				}
				for (var j = 0; j < separeted2.length; j++) {
					sum2 += parseInt(separeted2[j].toString().match(/(\d+)/));
				}
				//$("#reqTot").html(Number(sum1) + Number(sum2));
				//$("#reqAce").html(sum1);
				//$("#reqRej").html(sum2);
				assign_chart(active,rejected,categories);
			}
		});
	}
	

 	
